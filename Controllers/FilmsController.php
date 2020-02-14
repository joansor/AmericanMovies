<?php

class FilmsController extends Controller
{
	###################################################
	#### CONSTRUCTEUR #################################
	###################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Films(); // Nouvel Object : Films
	}

	###################################################
	#### PAGE DE LISTING DE TOUS LES FILMS ############
	###################################################

	public function listing ()
	{
		global $admin, $user; // SuperGlobales

		$pageTwig = 'films/index.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$films = $this->model->getAllFilms(); // Retourne la liste de tous les films
		$genres = $this->model->getAllGenres(); // Retourne la liste de tous les genres

		echo $template->render(["films" => $films, "admin" => $admin, "user" => $user, "genres" => $genres]); // Affiche la view et passe les données en paramêtres
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
		$result = $this->model->getInfosByFilm($id); // Retourne les infos du film

		$result['genres'] = $this->model->getGenresByFilm($id); // Retourne tous les genres du film
		$result['realisateurs'] = $this->model->getRealisateursByFilm($id); // Retourne tous les réalisateurs du film
		$result['acteurs'] = $this->model->getActeursByFilm($id); // Retourne tous les acteurs du film
		$result['commentaires'] = $this->model->getCommentairesByFilm($id); // Retourne tous les commentaires du film

		if(!$result['poster_f'] || !file_exists("". $repertoireImagesFilms ."/". $result['poster_f'] ."")) $result['poster_f'] = "default.jpg"; // Si pas d'image ou erreur image alors image par défaut !
		if(!$result['resume_f']) $result['resume_f'] = "Information à complêter"; // Si pas de résumé, alors on affiche le message : Information à complêter

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
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

		echo $template->render(["result" => $result, "admin" => $admin]); // Affiche la view et passe les données en paramêtres
	}

	###################################################
	#### TRAITEMENT DES DONNEES - INSERTION DU FILM ###
	###################################################

	public function insert() // Page : films/insert
	{  
		global $admin, $titre, $poster, $annee, $resume, $video, $realisateurs, $acteurs, $genres; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page
	
        if(!$annee) $annee = "1970"; // Si pas d'annee, 1970 par defaut car obligé de remplir ce chanmps sql pour insert

		$repertoirePhotosFilms = "assets/images/films"; // Répertoire de destination des images de films
		$fichier = $_FILES['poster']['name']; // Image uploadée via le formulaire !

		if($fichier) // Si une image a été sélectionné dans le formulaire
		{
			$img_name = $fichier; // Variable intermédiare pour le traitement de l'image
			$ext = get_extension($img_name); // Fonction qui vérifie l'extention de l'image. Fonction placée dans racine->functions.php

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Verifie la conformité de l'extention de l'image
			{
				$fichier = renome_image("". $repertoirePhotosFilms ."", "". strtolower($titre) ."", $ext); // Fonction qui renomme l'image d'apres le titre du film. Fonction placée dans racine->functions.php

				move_uploaded_file($_FILES['poster']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplacement de l'image ou echec
				@chmod ($fichier, 0644); // Redéfinition du CHMOD de l'image (droits d'accès => seul le script peut modifier le fichier)

				$poster = redimentionne_image("". $repertoirePhotosFilms ."", $fichier); // Redimentionne l'image à 250px max width/height. Fonction placée dans racine->functions.php
			}
			else // Sinon, le format de l'image n'est pas correct
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n"; // Message à afficher
                redirect("javascript:history.back()", 5); // Redirection sur la page d'edition du film
			}
		}
        else
        {
            $poster = ""; // Pas d'image, donc variable image = rien!
        }

		$poster = str_replace("". $repertoirePhotosFilms ."/", "", $poster); // On enleve le chemin du repertoire pour ne stocker que le nom de fichier final dans la bdd

		$result = $this->model->insertFilm($titre, $poster, $annee,$resume, $video); // Insertion des données
		$id = $result; // Retourne l'#id du film inséré

		if(is_array($realisateurs)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
		{
			foreach ($realisateurs as $key => $realisateur) { $insertRealisateur = $this->model->setInsertRealisateurByFilm($id, $realisateur); } // Insertion dans la table realiser des réalisateurs du film
		}

		if(is_array($acteurs)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
		{
			foreach ($acteurs as $key => $acteur) { $insertActeur = $this->model->setInsertActeurByFilm($id, $acteur); } // Insertion dans la table jouer des acteurs qui ont joué dans le film
		}

		if(is_array($genres)) // Si la variable genres est un tableau, des genres ont été sélectionné
		{
			foreach ($genres as $key => $genre) { $insertGenre = $this->model->setInsertGenreByFilm($id, $genre); } // Insertion dans la table appartient des genres sélectionnés pour le film
		}

		$message = "Film inséré avec succès"; // Message à afficher

		echo $template->render(["result" => $result, "admin" => $admin, "message" => $message]); // Affiche la view et passe les données en paramêtres
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

		$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
		if(!$result['poster_f'] || !file_exists("". $repertoirePhotosFilms ."/". $result['poster_f'] ."")) $result['poster_f'] = "default.jpg"; // Si pas d'image ou erreur image alors image par défaut!

		$result['allgenres'] = $this->model->getAllGenres(); // Retourne la liste de tous les genres
		$result['genres'] = $this->model->getGenresByFilm($id); // Retourne un tableau associatif avec tous les id des genres du film
		$newtableaugenres = []; // Initialisation d'un nouveau tableau non associatif pour les genres 
		foreach ($result['genres'] as $key => $genre) { array_push($newtableaugenres, $genre['id_g']); } // Push l'id dans le tableau
		$result['genres'] = $newtableaugenres; // Retourne un tableau non associatif avec les id des genres du film -> pour comparaison avec les #id du listing de tous les genres

		$result['allacteurs'] = $this->model->getAllActeurs(); // Retourne la liste de tous les acteurs du site
		$result['acteurs'] = $this->model->getActeursByFilm($id); // Retourne un tableau associatif avec tous les id des acteur du film
		$newtableauacteurs = []; // Initialisation d'un nouveau tableau non associatif pour les acteurs 
		foreach ($result['acteurs'] as $key => $acteur) { array_push($newtableauacteurs, $acteur['id_a']); } // Push l'id dans le tableau
		$result['acteurs'] = $newtableauacteurs; // Retourne un tableau non associatif avec les id des acteurs du film -> pour comparaison avec les #id du listing de tous les acteurs

		$result['allrealisateurs'] = $this->model->getAllRealisateurs(); // Retourne la liste de tous les réalisateurs du site
		$result['realisateurs'] = $this->model->getRealisateursByFilm($id); // Retourne un tableau associatif avec tous les id des réalisateurs du films
		$newtableaurealisateurs = []; // Initialisation d'un nouveau tableau non associatif pour les réalisateurs 
		foreach ($result['realisateurs'] as $key => $realisateur) { array_push($newtableaurealisateurs, $realisateur['id_a']); } // Push l'id dans le tableau
		$result['realisateurs'] = $newtableaurealisateurs; // Retourne un tableau non associatif avec les id des realisateurs du film -> pour comparaison avec les #id du listing de tous les réalisateurs

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
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

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Verifie la conformité de l'extention de l'image
			{
				if($poster && file_exists($poster)) unlink($poster); // Supprime la photo existante

				$fichier = renome_image("". $repertoirePhotosFilms ."", "". strtolower($titre) ."", $ext); // Retourne un nouveau nom d'image d'après le titre du film

				move_uploaded_file($_FILES['newposter']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplacement de l'image dans dossier de destination ou erreur
				@chmod ($fichier, 0644); // Redéfinition du CHMOD de l'image (droits d'accès => seul le script peut modifier le fichier)

				$poster = redimentionne_image("". $repertoirePhotosFilms ."", $fichier); // Redimentionne l'image à 250px max width/height. Fonction placée dans racine->functions.php
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

		$poster = str_replace("". $repertoirePhotosFilms ."/", "", $poster); // On enleve le chemin du repertoire pour ne stocker que le nom de fichier final dans la bdd

		$update = $this->model->setUpdateFilms($id, $titre, $poster, $annee, $video, $resume); // -> update du film dans la table film (titre, poster, annee, resume, video)

		$message = "Film modifié avec succès"; // Message à afficher

		$deleteRealisateurs = $this->model->setDeleteRealisateursByFilms($id); // Supprime tous les réalisateurs du film
		if(is_array($realisateurs)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
		{
			foreach ($realisateurs as $key => $realisateur) { $insertRealisateur = $this->model->setInsertRealisateurByFilm($id, $realisateur); } // Insertion des réalisateurs qui ont joué dans le film
		}

		$deleteActeurs = $this->model->setDeleteAllActeursByFilms($id); // Supprime tous les acteurs du film
		if(is_array($acteurs)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
		{
			foreach ($acteurs as $key => $acteur) { $insertActeur = $this->model->setInsertActeurByFilm($id, $acteur); } // Insertion des acteurs qui ont joué dans le film
		}

		$deleteGenres = $this->model->setDeleteAllGenresByFilms($id); // Supprime tous les genre du film
		if(is_array($genres)) // Si la variable genres est un tableau, des genres ont été sélectionné
		{
			foreach ($genres as $key => $genre) { $insertGenre = $this->model->setInsertGenreByFilm($id, $genre); } // Insertion des genres qui ont joué dans le film
		}

		echo $template->render(["message" => $message]); // Affiche la view et passe les données en paramêtres
		redirect("../../films/show/". $id ."", 0); // -> Redirection vers films/show/#id
	}

	###################################################
	#### SUPPRESSION D'UN FILM ########################
	###################################################

	public function suppression($id) // Page : films/suppression/#id
	{ 
		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$result = $this->model->getInfosByFilm($id); // Retourne les infos du film (besoin du chemin de l'image pour la supprimer)
		$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
		$poster = "". $repertoirePhotosFilms ."/". $result['poster_f'] .""; // Chemin complet de l'image
		if($poster && file_exists($poster)) unlink($poster); // Supprime la photo existante

		$deleteRealisateurs = $this->model->setDeleteRealisateursByFilms($id); // Supprime tous les réalisateurs du film de la bdd
		$deleteActeurs = $this->model->setDeleteAllActeursByFilms($id); // Supprime tous les acteurs du film de la bdd
		$deleteGenres = $this->model->setDeleteAllGenresByFilms($id); // Supprime tous les genres du film de la bdd
		$deleteCommentaires = $this->model->setDeleteAllCommentairesByFilms($id); // Supprime tous les commentaires du film de la bdd
		$deleteFilm = $this->model->setDeleteFilm($id); // Supprime le film de la bdd

		$message = "Film supprimé avec succès"; // Mesage à afficher

		echo $template->render(["message" => $message]); // Affiche la view et passe les données en paramêtres
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
		$insert_commentaire = $this->model->insert_commentaires_sql($film, $commentaire, $userid); // insert le commentaire dans la bdd

		$message = "Votre commentaire a été publié"; // Message à afficher
		echo $template->render(["message" => $message]); // Affiche la view et passe les données en paramêtres
		redirect("../films/show/". $film ."", 0); // -> Redirection vers films/show/#id
	}

	public function delete_commentaire($id)  // Page : films/add
	{
		global $film; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$films = $this->model->getFilmByCommentaire($id); // Récupère l'id du film pour la redirection à la fin du traitement

		if(is_array($films)) // Si la variable films est un tableau, l'id d'un film est retourné
		{
			foreach ($films as $key => $film){}  // Parcours le tableau et retourne l'id du film
		}

		$delete_commentaire = $this->model->delete_commentaires_sql($id); // Supprime le commentaire #id

		$message = "Commentaire supprimé";
		echo $template->render(["message" => $message]); // Affiche la view et passe les données en paramêtres
		redirect("../../films/show/". $film ."", 0); // -> Redirection vers films/show/#id
	}
}