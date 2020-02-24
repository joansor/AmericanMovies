<?php

class CommentsController extends Controller
{
	###################################################
	#### CONSTRUCTEUR #################################
	###################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Comments(); // Nouvel Object : Films
	}

	###################################################
	#### TRAITEMENT COMMENTAIRE #######################
	###################################################

	public function insert_commentaire() // Page : films/add
	{
		global $module, $idd, $commentaire, $userid, $admin, $user, $rating;

		if($admin || $user)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$insert_commentaire = $this->model->insert_commentaires_sql($idd, $module, $commentaire, $userid, $rating); // insert le commentaire dans la bdd

			$noteMoyenne = $this->model->calcul_moyenne($module, $idd); // Retourne la note moyenne du film/artiste => $noteMoyenne['AVG(note)']
			$updateNoteMoyenne = $this->model->updateNoteMoyenne($module, $idd, round($noteMoyenne['AVG(note)'], 1)); // Update la note (ex:9.1) dans la table films --> film #id

            if($module == "Films") 
            {
                $result = $this->model->getInfosByFilm($idd); // Retourne les infos du film
                $result['url'] = rewrite_url($result['titre_f']); // Retourne une url propre basée sur le titre du film
                $result["url"] = $result['url']; // Incrémente le tableau avec l'url
            }
            else if($module == "Artists")
            {
                $result = $this->model->getInfosByArtiste($idd); // Retourne les infos de l'artiste
                $result['url2'] = rewrite_url($result['nom_a'] ); // Retourne une composante pour une url propre basée sur le noms de l'artite
                $result['url'] = rewrite_url($result['prenom_a'] ); // Retourne une composante pour une url propre basée sur le noms de l'artite
    			$result["url"] = "". $result['url'] ."-". $result['url2'] .""; // Incrémente le tableau avec l'url
            }

			$message = "Votre commentaire a été publié"; // Message à afficher
			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../". strtolower($module) ."/show/". $idd ."/". $result["url"] ."", 0); // -> Redirection vers films/show/#id
		}
	}

	public function delete_commentaire($id) // Page : films/add
	{
		global $baseUrl, $admin, $user; // Superglobales

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

            $infoCommentaire = $this->model->getInfosByCommentaire($id);
            $module = $infoCommentaire['module'];
            $idd = $infoCommentaire['idd'];

            $noteMoyenne = $this->model->calcul_moyenne($module, $idd); // Retourne la note moyenne du film => $noteMoyenne['AVG(note)']
			$updateNoteMoyenne = $this->model->updateNoteMoyenne($module, $idd, round($noteMoyenne['AVG(note)'], 1)); // Update la note (ex:9.1) dans la table films ou artistes --> film or artiste #id

            if($module == "Films") 
            {
                $result = $this->model->getInfosByFilm($idd); // Retourne les infos du film
                $result['url'] = rewrite_url($result['titre_f']); // Retourne  une composante pour une url propre basée sur le titre du film
                $result["url"] = $result['url']; // Incrémente le tableau avec l'url
            }
            else if($module == "Artists")
            {
                $result = $this->model->getInfosByArtiste($idd); // Retourne les infos de l'artiste
                $result['url2'] = rewrite_url($result['nom_a']); // Retourne une composante pour une url propre basée sur le noms de l'artite
                $result['url'] = rewrite_url($result['prenom_a']); // Retourne une composante pour une url propre basée sur le noms de l'artite
    			$result["url"] = "". $result['url'] ."-". $result['url2'] .""; // Incrémente le tableau avec l'url
            }

			$delete_commentaire = $this->model->delete_commentaires_sql($id); // Supprime le commentaire #id

			$message = "Commentaire supprimé";
			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("". $baseUrl ."/". strtolower($module) ."/show/". $idd ."/". $result["url"] ."", 0); // -> Redirection vers films/show/#id
		}
    }

	###################################################
	#### VOTE SUR COMMENTAIRE - J'aime/J'aime pas #####
	###################################################

	public function updateVote($idcom,$iduser,$vote)
	{
		$aDejaVote = $this->model->getUserVoteThisCom($idcom, $iduser); // Retourne l'id du vote si l'utilisateur à déjà évalué ce commentaire

		if($aDejaVote['id_vote']) // Si il a déja évalué ce commentaire
		{
			$updateVote = $this->model->setUpdateVote($aDejaVote['id_vote'], $vote); // update le vote rxistant dans la bdd
		}
		else // Sinon, il n'a pas encore évalué ce commentaire
		{
			$insertVote = $this->model->setInsertVote($idcom, $iduser, $vote); // insert le vote dans la bdd
		}

		$nbVotesPositif = $this->model->getNbVotesByCom($idcom, "positif"); // Retourne le nombre de vote positif pour ce commentaire
		$nbVotesNegatif = $this->model->getNbVotesByCom($idcom, "negatif"); // Retourne le nombre de vote negatif pour ce commentaire

		$data = array('0' => $nbVotesNegatif['COUNT(*)'] , '1' => $nbVotesPositif['COUNT(*)']); // Tableau Json pour pour lecture avec ajax
        echo json_encode($data); // Affiche le résultat qui sera récuperer via ajax
	}
}