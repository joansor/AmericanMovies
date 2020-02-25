<?php

class CommentsController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR ####################################################
	######################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Comments(); // Nouvel Object : Films
	}

	###################################################
	#### TRAITEMENT COMMENTAIRE #######################
	###################################################

	public function insert() // Page : films/add
	{
		global $module, $idd, $commentaire, $userid, $admin, $user, $rating;

		if($admin || $user)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$insert_commentaire = $this->model->setInsertCommentaire($idd, $module, $commentaire, $userid, $rating); // insert le commentaire dans la bdd

			$noteMoyenne = $this->model->getNoteMoyenne($module, $idd); // Retourne la note moyenne du film/artiste => $noteMoyenne['AVG(note)']
			$updateNoteMoyenne = $this->model->setUpdateNoteMoyenne($module, $idd, round($noteMoyenne['AVG(note)'], 1)); // Update la note (ex:9.1) dans la table films --> film #id

            if($module == "Films") 
            {
				$instanceFilms = new Films();
                $result = $instanceFilms->getInfosByFilm($idd); // Retourne les infos du film
                $result['url'] = rewrite_url($result['titre_f']); // Retourne une url propre basée sur le titre du film
                $result["url"] = $result['url']; // Incrémente le tableau avec l'url
            }
            else if($module == "Artists")
            {
				$instanceArtists = new Artists();
                $result = $instanceArtists->getInfosByArtiste($idd); // Retourne les infos de l'artiste
                $result['url2'] = rewrite_url($result['nom_a'] ); // Retourne une composante pour une url propre basée sur le noms de l'artite
                $result['url'] = rewrite_url($result['prenom_a'] ); // Retourne une composante pour une url propre basée sur le noms de l'artite
    			$result["url"] = "". $result['url'] ."-". $result['url2'] .""; // Incrémente le tableau avec l'url
            }

			$message = "Votre commentaire a été publié"; // Message à afficher
			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../". strtolower($module) ."/show/". $idd ."/". $result["url"] ."", 0); // -> Redirection vers films/show/#id
		}
	}

	public function delete($id) // Page : films/add
	{
		global $baseUrl, $admin, $user; // Superglobales

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

            $infoCommentaire = $this->model->getInfosByCommentaire($id);
            $module = $infoCommentaire['module'];
            $idd = $infoCommentaire['idd'];

            if($module == "Films") 
            {
				$instanceFilms = new Films();
				$result = $instanceFilms->getInfosByFilm($idd); // Retourne les infos du film
                $result['url'] = rewrite_url($result['titre_f']); // Retourne  une composante pour une url propre basée sur le titre du film
                $result["url"] = $result['url']; // Incrémente le tableau avec l'url
            }
            else if($module == "Artists")
            {
				$instanceArtists = new Artists();
                $result = $instanceArtists->getInfosByArtiste($idd); // Retourne les infos de l'artiste
                $result['url2'] = rewrite_url($result['nom_a']); // Retourne une composante pour une url propre basée sur le noms de l'artite
                $result['url'] = rewrite_url($result['prenom_a']); // Retourne une composante pour une url propre basée sur le noms de l'artite
    			$result["url"] = "". $result['url'] ."-". $result['url2'] .""; // Incrémente le tableau avec l'url
            }

			$instanceCommentsVotes = new CommentsVotes();
			$deleteVotesCommentaire = $instanceCommentsVotes->setDeleteVotesByCom($id); // Supprime tous les votes j'aime/j'aime pas de commentaire #id

			$deleteCommentaire = $this->model->setDeleteCommentaire($id); // Supprime le commentaire #id

			$noteMoyenne = $this->model->getNoteMoyenne($module, $idd); // Retourne la note moyenne du film => $noteMoyenne['AVG(note)']
			$updateNoteMoyenne = $this->model->setUpdateNoteMoyenne($module, $idd, round($noteMoyenne['AVG(note)'], 1)); // Update la note (ex:9.1) dans la table films ou artistes --> film or artiste #id

			$message = "Commentaire supprimé";

			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("". $baseUrl ."/". strtolower($module) ."/show/". $idd ."/". $result["url"] ."", 0); // -> Redirection vers films/show/#id
		}
    }
}