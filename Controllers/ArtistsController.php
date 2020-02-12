<?php

class ArtistsController extends Controller
{
    ## CONSTRUCTEUR
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

		$listes = "";
		if($categorie == "1") $listes = $this->model->getAllActors(); // Appelle le model->getAllActors() : Fonction qui retourne la liste de tous les artistes qui ont joué dans un film
		if($categorie == "2") $listes = $this->model->getAllRealisators(); // Appelle le model->getAllRealisators() : Fonction qui retourne la liste de tous les artistes qui ont réalisé un film

		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"]; // Creation du tableau pour categorie acteurs
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"]; // Creation du tableau pour categorie réalisateurs

		echo $template->render(["categorie" => $categorie, "listes" => $listes, "admin" => $admin, "user" => $user, "section" => $section]); // Envoi des données à la View
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

		$result = $this->model->getArtiste($id); // Appelle le model->getArtiste() : Fonction qui retourne les infos de artiste #id
		$result['films_jouer'] = $this->model->getFilmsByActor($id);  // Appelle le model->getFilmsByActor() : Fonction qui retourne la liste de tous les films dans lesquels l'artiste a joué
		$result['films_realiser'] = $this->model->getFilmsByRealisator($id); // Appelle le model->getFilmsByRealisator() : Fonction qui retourne la liste de tous les films réalisé par artiste #id
		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"]; // Creation du tableau pour categorie acteurs
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"]; // Creation du tableau pour categorie réalisateurs
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

		$insert = $this->model->insertArtist($nom, $prenom, $date_de_naissance, $photo, $biographie); // Appelle le model->insertArtist(), fonction qui insert les données dans la bdd
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

		$result = $this->model->getArtiste($id); // $id element clef correspond a la table mysql artiste
		if(!$result['photo_a'] || !file_exists("". $repertoireImagesArtistes ."/". $result['photo_a'] ."")) $result['photo_a'] = "default.jpg"; // Si pas d'image ou erreur image, alors image par defaut

        $result['allfilms'] = $this->model->getAllFilms(); // Retourne la liste de tous les films pour select Films jouer/realiser
		$result['film_jouer'] = $this->model->getFilmsByActor($id); // Retourne tous les id des acteur du film
		$newtableaufilmsjouer = []; // Initialisation d'un nouveau tableau pour les acteurs 
		foreach ($result['film_jouer'] as $key => $film) { array_push($newtableaufilmsjouer, $film['id_f']); } // Push l'id dans le tableau
		$result['film_jouer'] = $newtableaufilmsjouer; // Retourne un tableau avec les id des acteurs du film

		$result['film_realiser'] = $this->model->getFilmsByRealisator($id); // Retourne tous les id des acteur du film
		$newtableaufilmsrealiser = []; // Initialisation d'un nouveau tableau pour les acteurs 
		foreach ($result['film_realiser'] as $key => $film) { array_push($newtableaufilmsrealiser, $film['id_f']); } // Push l'id dans le tableau
		$result['film_realiser'] = $newtableaufilmsrealiser; // Retourne un tableau avec les id des acteurs du film

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]); // Envoi les données à la view
	}

	##############################################################
	#### TRAITEMENT DES DONNEES - MODIFICATIONS D'UN ARTISTE #####
	##############################################################

	public function update($id) 
	{
		global $nom, $prenom, $date_de_naissance, $photo, $newphoto, $biographie; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

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

		$update = $this->model->updateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie); // Appelle le model->updateArtist(), fonction qui modifie les données dans la bdd
        $message = "Artiste modifié avec succès"; // Message à afficher

        echo $template->render(["message" => $message]); // On envoi les données et à la View

        redirect("../../artists", 1); // Redirection après 1s sur la paga artists
	}

	###################################################
	#### SUPPRESSION D'UN ARTISTE #####################
	###################################################

	public function suppression(int $id) 
	{
 		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$suppression = $this->model->deleteArtist($id); // Appelle le model->deleteArtist(), fonction qui supprime l'artiste de la bdd

        $message = "Artiste supprimé avec succès"; // Affiche le message
        echo $template->render(["message" => $message]); // Envoid des données à la View
        redirect("../../films", 1); // Redirection après 1s vers films
	}
}