<?php

class FilmsController extends Controller
{
	###################################################
	#### CONSTRUCTEUR #################################
	###################################################

	public function __construct()
	{
		parent::__construct();
		$this->model = new Films();
	}

	###################################################
	#### PAGE DE LISTING DE TOUS LES FILMS ############
	###################################################

	public function listing ()
	{
		global $admin, $user; // SuperGlobales

		$films = $this->model->getAllFilms(); // Appelle la fonction qui retourne la liste de tous les films
		$genres = $this->model->getAllGenres();
		$pageTwig = 'films/index.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		echo $template->render(["films" => $films, "admin" => $admin, "user" => $user, "genres" => $genres]); // Envoi les données à la View
	}
	
	###################################################
	#### PAGE DE PRESENTATION D'UN FILM BY #ID ########
	###################################################

	public function show(int $id) // Page : films/show/#id
	{
		global $admin, $user; // SuperGlobales
		
		$repertoireImagesFilms = "assets/images/films";
		$pageTwig = 'films/show.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View
		$result = $this->model->getInfosByFilm($id); // Appelle la fonction setters qui retourne les infos du film

		$result['genres'] = $this->model->getGenresByFilm($id); // Retourne tous les genres du film
		$result['acteurs'] = $this->model->getActeursByFilm($id); // Retourne tous les acteurs du film
		$result['realisateurs'] = $this->model->getRealisateursByFilm($id); // Retourne tous les réalisateurs du film
		$result['commentaires'] = $this->model->getCommentairesByFilm($id); // Retourne tous les commentaires du film

		if(!$result['poster_f'] || !file_exists("". $repertoireImagesFilms ."/". $result['poster_f'] ."")) $result['poster_f'] = "default.jpg";
		if(!$result['resume_f']) $result['resume_f'] = "Information à complêter"; // Si pas de résumé, alors on affiche le message : Information à complêter

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Envoi les données à la View
	}

	###################################################
	#### FORMULAIRE D'AJOUT D'UN NOUVEAU FILM #########
	###################################################

	public function add()  // Page : films/add
	{  
		global $admin;
		$pageTwig = 'films/add.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$result['allgenres'] = $this->model->getAllGenres(); // Retourne la liste de tous les genres
		$result['allacteurs'] = $this->model->getAllActeurs(); // Retourne la liste de tous les acteurs du site
		$result['allrealisateurs'] = $this->model->getAllRealisateurs(); // Retourne la liste de tous les réalisateurs du site

		echo $template->render(["result" => $result, "admin" => $admin]); // Envoi les données à la View
	}

	###################################################
	#### TRAITEMENT DES DONNEES - INSERTION DU FILM ###
	###################################################

	public function insert() // Page : films/insert
	{  
		global $admin, $titre, $poster, $annee, $resume, $video, $realisateurs, $acteurs, $genres; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		// Traitement des données
		// -> ajout du film dans la table film
	
        if(!$annee) $annee = "1970"; // Si pas d'annee, 1970 par defaut car obligé de remplir ce chanmps sql pour insert

		$repertoirePhotosFilms = "assets/images/films";
		$fichier = $_FILES['poster']['name'];

		if($fichier) // Si une image a été sélectionné dans le formulaire
		{
			$img_name = $fichier; // Variable intermédiare pour le traitement de l'image
			$ext = get_extension($img_name); // Fonction qui vérifie l'extention de l'image (fonction dans index.php)

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Contrôle sur l'extention de l'image
			{
				$fichier = renome_image("". $repertoirePhotosFilms ."", "". strtolower($titre) ."", $ext); // Fonction qui renomme l'image d'apres le titre du film (fonction dans index.php)

				move_uploaded_file($_FILES['poster']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplacement de l'image ou echec
				@chmod ($fichier, 0644); // Redéfinition du CHMOD

				$poster = redimentionne_image("". $repertoirePhotosFilms ."", $fichier); // Fonction qui redimmentionne l'image (fonction dans index.php)
			}
			else // Sinon, le format de l'image n'est pas correct
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n"; // Message à afficher
                redirect("javascript:history.back()", 5); // Redirection sur la page d'edition du film
			}
		}
        else
        {
            $poster = "";
        }

		$poster = str_replace("". $repertoirePhotosFilms ."/", "", $poster);

		$result = $this->model->insertFilm($titre, $poster, $annee,$resume, $video); // Insertion des données
		$id = $result; // Retourne l'#id du film inséré

		if(is_array($realisateurs)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
		{
			foreach ($realisateurs as $key => $realisateur) { $insertRealisateur = $this->model->setInsertRealisateurByFilm($id, $realisateur); } // Insertion dans la table realiser des réalisateurs qui ont réalisé dans le film
		}

		if(is_array($acteurs)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
		{
			foreach ($acteurs as $key => $acteur) { $insertActeur = $this->model->setInsertActeurByFilm($id, $acteur); } // Insertion dans le film dans la table jouer des acteurs qui ont joué
		}

		if(is_array($genres)) // Si la variable genres est un tableau, des genres ont été sélectionné
		{
			foreach ($genres as $key => $genre) { $insertGenre = $this->model->setInsertGenreByFilm($id, $genre); } // Insertion dans la table appartient des genres sélectionnés pour le film
		}

		$message = "Film inséré avec succès"; // Message à afficher

		echo $template->render(["result" => $result, "admin" => $admin, "message" => $message]);  // Envoi les données à la View
		redirect("../films/show/". $id ."", 2); // redirection vers films/show/#id
	}

	###################################################
	#### FORMULAIRE DE REEDITION D'UN FILM ############
	###################################################

	public function edition(int $id) // Page : films/edition/#id
	{
		global $admin, $user;

		$pageTwig = 'films/edition.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la view
		$result = $this->model->getInfosByFilm($id); // Appelle de la fonction

		if(!$result['poster_f'] || !file_exists("". $repertoireImagesFilms ."/". $result['poster_f'] ."")) $result['poster_f'] = "default.jpg";

		$result['allgenres'] = $this->model->getAllGenres(); // Retourne la liste de tous les genres
		$result['genres'] = $this->model->getGenresByFilm($id); // Retourne tous les id des genres du film
		$newtableaugenres = []; // Initialisation d'un nouveau tableau pour les genres 
		foreach ($result['genres'] as $key => $genre) { array_push($newtableaugenres, $genre['id_g']); } // Push l'id dans le tableau
		$result['genres'] = $newtableaugenres; // Retourne un tableau avec les id des genres du film

		$result['allacteurs'] = $this->model->getAllActeurs(); // Retourne la liste de tous les acteurs du site
		$result['acteurs'] = $this->model->getActeursByFilm($id); // Retourne tous les id des acteur du film
		$newtableauacteurs = []; // Initialisation d'un nouveau tableau pour les acteurs 
		foreach ($result['acteurs'] as $key => $acteur) { array_push($newtableauacteurs, $acteur['id_a']); } // Push l'id dans le tableau
		$result['acteurs'] = $newtableauacteurs; // Retourne un tableau avec les id des acteurs du film

		$result['allrealisateurs'] = $this->model->getAllRealisateurs(); // Retourne la liste de tous les réalisateurs du site
		$result['realisateurs'] = $this->model->getRealisateursByFilm($id); // Retourne tous les id des réalisateurs du films
		$newtableaurealisateurs = []; // Initialisation d'un nouveau tableau pour les réalisateurs 
		foreach ($result['realisateurs'] as $key => $realisateur) { array_push($newtableaurealisateurs, $realisateur['id_a']); } // Push l'id dans le tableau
		$result['realisateurs'] = $newtableaurealisateurs; // Retourne un tableau avec les id des realisateurs du film

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Envoi des données à la View
	}

	#########################################################
	#### TRAITEMENT DES DONNEES - MODIFICATIONS DU FILM #####
	#########################################################

	public function update(int $id) // Page : films/update/#id
	{
		global $titre, $poster, $newposter, $annee, $realisateurs, $acteurs, $genres, $video, $resume; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
		$fichier = $_FILES['newposter']['name']; // Definition du fichier

		if($fichier) // Si un fichier est envoyé (image)
		{
			$img_name = $fichier; // Variable intermédiare
			$ext = get_extension($img_name); // Retourne l'extention de l'image envoyée

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Verifi l'extention 
			{
				if($poster && file_exists($poster)) unlink($poster); // Supprime la photo existante

				$fichier = renome_image("". $repertoirePhotosFilms ."", "". strtolower($titre) ."", $ext); // / Retourne un nouveau nom d'image d'après le titre du film

				move_uploaded_file($_FILES['newposter']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplacement de l'image dans dossier de destination ou erreur
				@chmod ($fichier, 0644); // change le CHMOD du fichier

				$poster = redimentionne_image("". $repertoirePhotosFilms ."", $fichier); // Redimentionne l'image (fonction dans index.php)
			}
			else
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n"; // Erreur upload image
                redirect("javascript:history.back()", 5); // Redirige vers l'edition de film
			}
		}
        else if($poster) // si pas d'upload d'une nouvelle image, alors on travail sur l'image existante
        {
            $ext = get_extension($poster); // Retourne l'extention de l'image
			$newposter = renome_image("". $repertoirePhotosFilms ."", "". strtolower($titre) ."", $ext); // Retourne un nouveau nom d'image d'après le titre du film
            rename ("". $repertoirePhotosFilms ."/". $poster ."", $newposter); // Renome l'image existante avec le nouveau titre
            $poster = $newposter; // Redéfinition de la variable poster (image)
        }

		$poster = str_replace("". $repertoirePhotosFilms ."/", "", $poster);

		$update = $this->model->setUpdateFilms($id, $titre, $poster, $annee, $video, $resume); // -> update du film dans la table film (titre, poster, annee, resume, video)

		$message = "Film modifié avec succès"; // Message à afficher

		if(is_array($realisateurs)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
		{
			$deleteRealisateurs = $this->model->setDeleteRealisateursByFilms($id); // Supprime tous les réalisateurs du film
			foreach ($realisateurs as $key => $realisateur) { $insertRealisateur = $this->model->setInsertRealisateurByFilm($id, $realisateur); } // Insertion des réalisateurs qui ont joué dans le film
		}

		if(is_array($acteurs)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
		{
			$deleteActeurs = $this->model->setDeleteAllActeursByFilms($id); // Supprime tous les acteurs du film
			foreach ($acteurs as $key => $acteur) { $insertActeur = $this->model->setInsertActeurByFilm($id, $acteur); } // Insertion des acteurs qui ont joué dans le film
		}

		if(is_array($genres)) // Si la variable genres est un tableau, des genres ont été sélectionné
		{
			$deleteGenres = $this->model->setDeleteAllGenresByFilms($id); // Supprime tous les genre du film
			foreach ($genres as $key => $genre) { $insertGenre = $this->model->setInsertGenreByFilm($id, $genre); } // Insertion des genres qui ont joué dans le film
		}

		echo $template->render(["message" => $message]); // Envoi des données à la view
		redirect("../../films/show/". $id ."", 0); // -> Redirection vers films/show/#id
	}

	###################################################
	#### SUPPRESSION D'UN FILM ########################
	###################################################

	public function suppression($id) // Page : films/suppression/#id
	{ 
		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$result = $this->model->getInfosByFilm($id); // Appelle de la fonction qui retourne les infos du film (besoin du chemin de l'image pour la supprimer)
		$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
		$poster = "". $repertoirePhotosFilms ."/". $result['poster_f'] .""; // Chemin complet de l'image
		if($poster && file_exists($poster)) unlink($poster); // Supprime la photo existante

		$deleteRealisateurs = $this->model->setDeleteRealisateursByFilms($id); // Supprime tous les réalisateurs du film
		$deleteActeurs = $this->model->setDeleteAllActeursByFilms($id); // Supprime tous les acteurs du film
		$deleteGenres = $this->model->setDeleteAllGenresByFilms($id); // Supprime tous les genre du film
		$deleteCommentaires = $this->model->setDeleteAllCommentairesByFilms($id); // Supprime tous les commentaires du film
		$deleteFilm = $this->model->setDeleteFilm($id); // Suppression du film table films

		$message = "Film supprimé avec succès"; // Mesage à afficher

		echo $template->render(["message" => $message]);
		redirect("../../films", 2); // -> Redirection vers films
	}

	###################################################
	#### TRAITEMENT COMMENTAIRE #######################
	###################################################

	public function insert_commentaire()  // Page : films/add
	{  
		global $film, $commentaire, $userid;
		$pageTwig = 'traitement.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View
		$insert_commentaire = $this->model->insert_commentaires_sql($film, $commentaire, $userid); // Supprime tous les réalisateurs du film

		$message = "Votre commentaire a été publié";
		echo $template->render(["message" => $message]); // Envoi les données à la View
		redirect("../films/show/". $film ."", 0); // -> Redirection vers films/show/#id
	}

	public function delete_commentaire($id)  // Page : films/add
	{
		global $film;

		$pageTwig = 'traitement.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$films = $this->model->getFilmByCommentaire($id); // Récupère l'id du film pour la redirection à la fin du traitement

		if(is_array($films)) // Si la variable films est un tableau, l'id d'un film est retourné
		{
			foreach ($films as $key => $film) { }  // Parcours le tableau et retourne l'id du film
		}

		$delete_commentaire = $this->model->delete_commentaires_sql($id); // Supprime tous les réalisateurs du film

		$message = "Commentaire supprimé";
		echo $template->render(["message" => $message]); // Envoi les données à la View
		redirect("../../films/show/". $film ."", 0); // -> Redirection vers films/show/#id
	}


}