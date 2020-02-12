<?php

// ATTENTION
// il faut encore ajouté un select pour selectionner la catégorie Acteurs ou Réalisateurs

class ArtistsController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR NOUVEL ARTISTE #####################################
	######################################################################

	public function __construct()
	{
		parent::__construct();
		$this->model = new Artists();
	}

	#######################################################################
	####  FONCTION INDEX - BASE DU MODULE #################################
	####  Choix vers listing items catégorie : Réalisateurs ou Acteurs ####
	#######################################################################

	public function index()
	{
		global $admin, $user; // Superglobale

		$pageTwig = 'artists/index.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$actors = $this->model->getAllActors(); // Appelle le model->getAllActors() : Fonction qui retourne la liste de tous les artistes qui ont joué dans un film
		$realisators = $this->model->getAllRealisators(); // Appelle le model->getAllRealisators() : Fonction qui retourne la liste de tous les artistes qui ont réalisé un film

		echo $template->render(["admin" => $admin, "user" => $user, "actors" => $actors,"realisators" => $realisators]); // Envoi des données à la View
	}

	#########################################################
	####  LISTE ACTEURS OU REALISATEURS SELON CATEGORIE #####
	#########################################################

	public function categorie($categorie)
	{
		global $admin, $user, $section; // Superglobale

		$pageTwig = 'artists/categorie.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		if($categorie)
		{
			$listes = $this->model->getArtistesByCategorie($categorie); // Appelle le model->getArtistesByCategorie() : Fonction qui retourne la liste de tous les artistes qui sont dans la catégorie (Acteurs ou Réalisateurs)
			if($categorie == "1") $categorie = ["id" => $categorie, "nom" => "Acteurs"]; // Redefinition categorie en tableau pour avoir le nom dans la view
			if($categorie == "2") $categorie = ["id" => $categorie, "nom" => "Réalisateur"]; // Redefinition categorie en tableau pour avoir le nom dans la view
		}

		echo $template->render(["categorie" => $categorie, "listes" => $listes, "admin" => $admin, "user" => $user, "section" => $section]); // Affiche la View et passe les données en paramêtres
	}

	###################################################
	#### PAGE DE PRESENTATION D'UN ARTISTE BY #ID #####
	###################################################

	public function show(int $categorie, int $id) 
	{
		global $admin, $user, $section; // Superglobale

		$repertoireImagesArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes

		$pageTwig = 'artists/show.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$result = $this->model->getInfosByArtiste($id); // Appelle le model->getInfosByArtiste() : Fonction qui retourne les infos de artiste #id
		$result['films_jouer'] = $this->model->getFilmsByActor($id); // Appelle le model->getFilmsByActor() : Retourne un tableau associatif avec les id et titres des films dans lesquels l'artiste a joué
		$result['films_realiser'] = $this->model->getFilmsByRealisator($id);  // Appelle le model->getFilmsByRealisator() : Retourne un tableau associatif avec les id et titres des films que l'artiste a réalisé
		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"]; // Creation du tableau pour categorie acteurs
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"]; // Creation du tableau pour categorie réalisateurs
		if($categorie == "3") $categorie = ["id" => "3", "nom" => "acteurs-réalisateurs"]; // Creation du tableau pour categorie réalisateurs

		if(!$result['biographie_a']) $result['biographie_a'] = "Infos à complêter"; // Si biographie vide, on affiche le message : Infos à complêter
		if(!$result['photo_a'] || !file_exists("". $repertoireImagesArtistes ."/". $result['photo_a'] ."")) $result['photo_a'] = "default.jpg"; // Si pas de photo ou erreur photo, image par defaut

		echo $template->render(["result" => $result, "categorie" => $categorie, "admin" => $admin, "user" => $user, "section" => $section]); // On envoi les infos à la view
	}

	###################################################
	#### FORMULAIRE D'AJOUT D'UN NOUVEAU FILM #########
	###################################################

	public function add() 
	{
		global $admin, $user, $section; // Superglobale

		$pageTwig = 'artists/add.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$result['allfilms'] = $this->model->getAllFilms(); // Retourne la liste de tous les films pour select Films jouer/realiser

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]);
	}

	######################################################
	#### TRAITEMENT DES DONNEES - INSERTION DU ARTISTE ###
	######################################################

	public function insert() 
	{
		global $baseUrl, $nom, $prenom, $date_de_naissance, $photo, $photo, $biographie, $realiser, $jouer, $admin;

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$nom = ucwords(strtolower($nom)); // Premiere lettre du prenom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
		$prenom = ucwords(strtolower($prenom));  // Premiere lettre du nom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
		if(!$date_de_naissance) $date_de_naissance = "1970-01-01"; // Si pas de date de naissance, obligé de mettre une date par defaut sinon impossible de faire le insert sql -> Error !

		$repertoirePhotosArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes
		$fichier = $_FILES['photo']['name']; // Fichier -> photo envoyée via le formulaire

		if($fichier) // Si il y a une une photo
		{
			$img_name = $fichier; // Variable intermediare du nom de fichier
			$ext = get_extension($img_name); // fonction qui retourne l'extention de l'image. Fonction placée dans racine->functions.php

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Si l'extention est conforme
			{
				$fichier = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($prenom) ."-". strtolower($nom) ."", $ext); // Renome le fichier d'après le nom et le prénom de l"artiste

				move_uploaded_file($_FILES['photo']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplace l'image dans le dossier de destination ou erreur. Attention CHMOD IMPORTANT EN CAS D'ERREUR, METTRE CHMOD DU REPERTOIRE à 777
				@chmod ($fichier, 0644); // On met le CHMOD a 644 (seul le script peut modifier le fichier)

				$photo = redimentionne_image("". $repertoirePhotosArtistes ."", $fichier); // Redimentionne l'image à 250px max width/height. Fonction placée dans racine->functions.php
			}
			else
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n"; // Message à afficher
				redirect("javascript:history.back()", 5); // Redirection apès 5s sur la page formulaire d'ajout d'un artiste
			}
		}
		else // Sinon, pas de photo uploadée
		{
			$photo = ""; // La photo est donc egal à rien !
		}

		$photo = str_replace("". $repertoirePhotosArtistes ."/", "", $photo); // On enleve le chemin du repertoire pour ne stocker que le nom de fichier final dans la bdd

		$insert = $this->model->setInsertArtist($nom, $prenom, $date_de_naissance, $photo, $biographie); // Appelle le model->setInsertArtist(), fonction qui insert les données dans la bdd
		$message = "Artiste ajouté avec succès"; // Message à afficher

		echo $template->render(["message" => $message]);  // Envoi les données à la View
		redirect("../artists", 0); // Redirige vers la page artistes
	}

	###################################################
	#### FORMULAIRE DE REEDITION D'UN ARTISTE #########
	###################################################

	public function edition(int $id) 
	{
		global $admin, $user, $section; // Superglobales

		$repertoireImagesArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes

		$pageTwig = 'artists/edition.html.twig'; // Chemin de la view
		$template = $this->twig->load($pageTwig); // Chargement de la view

		$result = $this->model->getInfosByArtiste($id); // Appelle le model->getInfosByArtiste() : Fonction qui retourne les infos de artiste #id

		if(!$result['photo_a'] || !file_exists("". $repertoireImagesArtistes ."/". $result['photo_a'] ."")) $result['photo_a'] = "default.jpg"; // Si pas d'image ou erreur image, alors image par defaut

		$result['allfilms'] = $this->model->getAllFilms(); // Retourne la liste de tous les films du site --> pour select Films:jouer/realiser dans formualaire

		$result['film_jouer'] = $this->model->getFilmsByActor($id); // Appelle le model->getFilmsByActor() : Retourne un tableau associatif avec les id et titres des films dans lesquels l'artiste a joué
		$newtableaufilmsjouer = []; // Initialisation d'un nouveau tableau non associatif dans lequels nous allons mettre tous les id des films dans lequel l'acteur a joué --> pour auto select les films dans le formulaire
		foreach ($result['film_jouer'] as $key => $film) { array_push($newtableaufilmsjouer, $film['id_f']); } // Push l'id dans le tableau
		$result['film_jouer'] = $newtableaufilmsjouer; // Retourne un tableau avec les id des films dans lesquels l'acteur a joué

		$result['film_realiser'] = $this->model->getFilmsByRealisator($id); // Appelle le model->getFilmsByRealisator() : Retourne un tableau associatif avec les id et titres des films que l'artiste a réalisé
		$newtableaufilmsrealiser = []; // Initialisation d'un nouveau tableau non associatif  dans lequels nous allons mettre tous les id des films que l'artiste a réalisé --> pour auto select les films dans le formulaire
		foreach ($result['film_realiser'] as $key => $film) { array_push($newtableaufilmsrealiser, $film['id_f']); } // Push l'id dans le tableau
		$result['film_realiser'] = $newtableaufilmsrealiser; // Retourne un tableau avec les id des films que l'artiste a réalisé

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]); // affiche la View et passe les données en paramêtres
	}

	##############################################################
	#### TRAITEMENT DES DONNEES - MODIFICATIONS D'UN ARTISTE #####
	##############################################################

	public function update($id) 
	{
		global $nom, $prenom, $date_de_naissance, $photo, $newphoto, $biographie, $realiser, $jouer; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		if(is_array($jouer)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
		{
			$deleteFilms = $this->model->setDeleteFilmsByActeur($id); // Supprime tous les films dans lesquels l'artiste a joué
			foreach ($jouer as $key => $film) { $insertRealisateur = $this->model->setInsertFilmJouerByArtiste($film, $id); } // Insertion des réalisateurs qui ont joué dans le film
		}

		if(is_array($realiser)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
		{
			$deleteActeurs = $this->model->setDeleteFilmsByRealisateur($id);  // Supprime tous les films que l'artiste a réalisé
			foreach ($realiser as $key => $film) { $insertActeur = $this->model->setInsertFilmRealiserByArtiste($film, $id); } // Insertion des acteurs qui ont joué dans le film
		}

		$nom = ucwords(strtolower($nom)); // Premiere lettre du prenom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
		$prenom = ucwords(strtolower($prenom));  // Premiere lettre du nom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
		if(!$date_de_naissance) $date_de_naissance = "1970-01-01"; // Si pas de date de naissance, obligé de mettre une date par defaut sinon impossible de faire le insert sql -> Error !

		$repertoirePhotosArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes
		$fichier = $_FILES['newphoto']['name']; // Fichier -> photo envoyée via le formulaire

		if($fichier) // Si il y a une une photo
		{
			$img_name = $fichier; // Variable intermediare du nom de fichier
			$ext = get_extension($img_name); // fonction qui retourne l'extention de l'image. Fonction placée dans racine->functions.php

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Si l'extention est conforme
			{
				if($photo && file_exists($photo)) unlink($photo); // Supprime la photo existante

				$fichier = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($prenom) ."-". strtolower($nom) ."", $ext); // Renome le fichier d'apres le nom et prenom de l'artiste

				move_uploaded_file($_FILES['newphoto']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplace l'image dans le dossier de destination ou erreur. Attention CHMOD IMPORTANT EN CAS D'ERREUR, METTRE CHMOD DU REPERTOIRE à 777
				@chmod ($fichier, 0644); // On met le CHMOD a 644 (seul le script peut modifier le fichier)

				$photo = redimentionne_image("". $repertoirePhotosArtistes ."", $fichier); // Redimentionne l'image à 250px max width/height. Fonction placée dans racine->functions.php
			}
			else
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n"; // Message à afficher
				redirect("javascript:history.back()", 5); // Redirection apès 5s sur la page formulaire d'ajout d'un artiste
			}
		}
		else if($photo)
		{
			$ext = get_extension($photo); // fonction qui retourne l'extention de l'image. Fonction placée dans racine->functions.php
			$newphoto = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($prenom) ."-". strtolower($nom) ."", $ext);  // fonction qui retourne la nouvelle mise en forme du nouveau nom de l'image. Fonction placée dans racine->functions.php
			rename ("". $repertoirePhotosArtistes ."/". $photo ."", $newphoto); // Renome le fichier d'après le prénom et le nom de l'artiste
			$photo = $newphoto;
		}

		$photo = str_replace("". $repertoirePhotosArtistes ."/", "", $photo); // On enleve le chemin du repertoire pour ne stocker que le nom de fichier final dans la bdd

		$update = $this->model->setUpdateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie); // Appelle le model->setUpdateArtist(), fonction qui modifie les données dans la bdd
		$message = "Artiste modifié avec succès"; // Message à afficher

		echo $template->render(["message" => $message]); // On envoi les données et à la View

		redirect("../../artists/3/show/". $id ."", 1); // Redirection après 1s sur la paga artists
	}

	###################################################
	#### SUPPRESSION D'UN ARTISTE #####################
	###################################################

	public function suppression(int $id) 
	{
 		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$result = $this->model->getInfosByArtiste($id); // Appelle de la fonction qui retourne les infos du film (besoin du chemin de l'image pour la supprimer)
		$repertoirePhotosArtistes = "assets/images/artistes"; // Repertoire de destination de l'image
		$poster = "". $repertoirePhotosArtistes ."/". $result['photo_a'] .""; // Chemin complet de l'image
		if($poster && file_exists($poster)) unlink($poster); // Supprime la photo existante

		$suppression = $this->model->deleteArtist($id); // Appelle le model->deleteArtist(), fonction qui supprime l'artiste de la bdd

		$message = "Artiste supprimé avec succès"; // Affiche le message
		echo $template->render(["message" => $message]); // Envoid des données à la View
		redirect("../../films", 1); // Redirection après 1s vers films
	}
}