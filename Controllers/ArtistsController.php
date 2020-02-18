<?php

class ArtistsController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR NOUVEL ARTISTE #####################################
	######################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Artists(); // Nouvel Object : Artists
	}

	#######################################################################
	####  FONCTION INDEX - BASE DU MODULE #################################
	####  Choix vers listing items catégorie : Réalisateurs ou Acteurs ####
	#######################################################################

	public function index($categorie = null)
	{
		global $admin, $user, $section, $search, $artistes; // Superglobale

		$pageTwig = 'artists/index.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View 
		$actors = $this->model->getAllActors(); // Appelle le model->getAllActors() : Fonction qui retourne la liste de tous les artistes qui ont joué dans un film
		$realisators = $this->model->getAllRealisators(); // Appelle le model->getAllRealisators() : Fonction qui retourne la liste de tous les artistes qui ont réalisé un film
	
		$requete = "("; // Ouvre la parenthèse dans la laquelle va etre inserée la composition de la requête
		$separator = ""; // Initialise la variable
		$explode = explode(" ", $search); // On décompose la chaine en mots -> explode[0] = mot 1, explode[1] = mot 2 ... etc

		for($i = 0; $i < count($explode); $i++) // Boucle pour faire une recherche sur tous les mots qui composent la recherche ($search)
		{
			if($search == "") $recherche = "(nom_a != '' || prenom_a != '')"; // On ne recherche rien, donc listing de tous les films
			else $recherche = "(nom_a LIKE '%" . $explode[$i] . "%' || prenom_a LIKE '%" . $explode[$i] . "%')"; // Recherche sur le titre du film

			$requete .= $separator . "". $recherche .""; // Compose et incrémente la requête
			$separator = " OR "; // Séparateur, dans la requête
		}

		$requete .= ")"; // Referme la parenthèse qui contient la requête
		
		if($categorie) $artistes = $this->model->getArtistesByCategorie($categorie); // Fonction qui retourne la liste de tous les artistes qui sont dans la catégorie (Acteurs ou Réalisateurs)
		else $artistes = $this->model->getAllArtists($requete); // Fonction qui retourne la liste de tous les artistes qui sont dans la catégorie (Acteurs ou Réalisateurs)

		if(!$categorie) $categorie = ["id" => "3", "nom" => "Acteurs/Réalisateurs"]; // Redefinition categorie en tableau pour avoir le nom dans la view
		else if($categorie == "1") $categorie = ["id" => $categorie, "nom" => "Acteurs"]; // Redefinition categorie en tableau pour avoir le nom dans la view
		else if($categorie == "2") $categorie = ["id" => $categorie, "nom" => "Réalisateur"]; // Redefinition categorie en tableau pour avoir le nom dans la view

		echo $template->render(["categorie" => $categorie, "admin" => $admin, "user" => $user, "actors" => $actors,"realisators" => $realisators, "section" => $section, "search" => $search, "artistes"=>$artistes]); // Affiche la view et passe les données en paramêtres	
	}


	###################################################
	#### PAGE DE PRESENTATION D'UN ARTISTE BY #ID #####
	###################################################

	public function show(int $id) 
	{
		global $admin, $user, $section; // Superglobale

		$repertoireImagesArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes

		$pageTwig = 'artists/show.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$result = $this->model->getInfosByArtiste($id); // Retourne les infos de artiste #id
		$result['films_jouer'] = $this->model->getFilmsByActor($id); // Retourne un tableau associatif avec les id et titres des films dans lesquels l'artiste a joué
		$result['films_realiser'] = $this->model->getFilmsByRealisator($id);  // Retourne un tableau associatif avec les id et titres des films que l'artiste a réalisé
		$metier = $this->model->getMetierByArtiste($id);

		if(!$result['biographie_a']) $result['biographie_a'] = "Infos à complêter"; // Si biographie vide, on affiche le message : Infos à complêter
		if(!$result['photo_a'] || !file_exists("". $repertoireImagesArtistes ."/". $result['photo_a'] ."")) $result['photo_a'] = "default.jpg"; // Si pas de photo ou erreur photo, image par defaut

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "metiers" => $metier]); // Affiche la view et passe les données en paramêtres
	}

	###################################################
	#### FORMULAIRE D'AJOUT D'UN NOUVEAU FILM #########
	###################################################

	public function add() 
	{
		global $admin, $user, $section; // Superglobale

		if($admin)
		{
			$pageTwig = 'artists/add.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$result['allcategories'] = $this->model->getAllCategories(); // Retourne un tableau associatif avec les id et noms de toutes les categories artistes du site
			$result['allfilms'] = $this->model->getAllFilms(); // Retourne la liste de tous les films pour select Films jouer/realiser
			echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]); // Affiche la view et passe les données en paramêtres
		}
	}

	######################################################
	#### TRAITEMENT DES DONNEES - INSERTION DU ARTISTE ###
	######################################################

	public function insert() 
	{
		global $admin, $user, $nom, $prenom, $date_de_naissance, $photo, $photo, $biographie, $realiser, $jouer, $categories;

		if($admin)
		{
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

				if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Verifie la conformité de l'extention de l'image
				{
					$fichier = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($prenom) ."-". strtolower($nom) ."", $ext); // Renome le fichier d'après le nom et le prénom de l"artiste

					move_uploaded_file($_FILES['photo']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplace l'image dans le dossier de destination ou erreur. Attention CHMOD IMPORTANT EN CAS D'ERREUR, METTRE CHMOD DU REPERTOIRE à 777
					@chmod ($fichier, 0644); // Redéfinition du CHMOD de l'image (droits d'accès => seul le script peut modifier le fichier)

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

			$id = $insert;

			if(is_array($categories)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				foreach ($categories as $key => $categorie) { $insertCategorie = $this->model->setInsertMetierByArtiste($categorie, $id); } // Insertion des métiers de l'artiste (Acteurs / Réalisateurs)
			}

			if(is_array($jouer)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
			{
				foreach ($jouer as $key => $film) { $insertFilmJouer = $this->model->setInsertFilmJouerByArtiste($film, $id); } // Insertion des réalisateurs qui ont joué dans le film
			}

			if(is_array($realiser)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				foreach ($realiser as $key => $film) { $insertFilmRealiser = $this->model->setInsertFilmRealiserByArtiste($film, $id); } // Insertion des acteurs qui ont joué dans le film
			}

			$message = "Artiste ajouté avec succès"; // Message à afficher

			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../artists", 0); // Redirige vers la page artistes
		}
	}

	###################################################
	#### FORMULAIRE DE REEDITION D'UN ARTISTE #########
	###################################################

	public function edition(int $id) 
	{
		global $admin, $user, $section; // Superglobales

		if($admin)
		{
			$repertoireImagesArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes

			$pageTwig = 'artists/edition.html.twig'; // Chemin de la view
			$template = $this->twig->load($pageTwig); // Chargement de la view

			$result = $this->model->getInfosByArtiste($id); // Appelle le model->getInfosByArtiste() : Fonction qui retourne les infos de artiste #id

			$result['allcategories'] = $this->model->getAllCategories(); // Appelle le model->getAllCategories() : Retourne un tableau associatif avec les id et noms de toutes les categories artistes du site
			$result['categories'] = $this->model->getCategoriesByArtiste($id); // Appelle le model->getCategoriesByArtiste() : Retourne un tableau associatif avec les id et noms de categories auquelles l'artiste appartient
			$newtableaucategoriesartiste = []; // Initialisation d'un nouveau tableau non associatif dans lequels nous allons mettre tous les id des catégories --> pour auto select les catégories dans le formulaire
			foreach ($result['categories'] as $key => $cat) { array_push($newtableaucategoriesartiste, $cat['categories_id_c']); } // Push l'id dans le tableau
			$result['categories'] = $newtableaucategoriesartiste; // Retourne un tableau non associatif avec les id des catégories auquelles l'acteur appartient (dont est le metier)

			if(!$result['photo_a'] || !file_exists("". $repertoireImagesArtistes ."/". $result['photo_a'] ."")) $result['photo_a'] = "default.jpg"; // Si pas d'image ou erreur image, alors image par defaut

			$result['allfilms'] = $this->model->getAllFilms(); // Retourne la liste de tous les films du site --> pour select Films:jouer/realiser dans formualaire

			$result['film_jouer'] = $this->model->getFilmsByActor($id); // Appelle le model->getFilmsByActor() : Retourne un tableau associatif avec les id et titres des films dans lesquels l'artiste a joué
			$newtableaufilmsjouer = []; // Initialisation d'un nouveau tableau non associatif dans lequels nous allons mettre tous les id des films dans lequel l'acteur a joué --> pour auto select les films dans le formulaire
			foreach ($result['film_jouer'] as $key => $film) { array_push($newtableaufilmsjouer, $film['id_f']); } // Push l'id dans le tableau
			$result['film_jouer'] = $newtableaufilmsjouer; // Retourne un tableau non associatif avec les id des films dans lesquels l'acteur a joué -> pour comparaison avec les #id du listing de tous les films

			$result['film_realiser'] = $this->model->getFilmsByRealisator($id); // Appelle le model->getFilmsByRealisator() : Retourne un tableau associatif avec les id et titres des films que l'artiste a réalisé
			$newtableaufilmsrealiser = []; // Initialisation d'un nouveau tableau non associatif  dans lequels nous allons mettre tous les id des films que l'artiste a réalisé --> pour auto select les films dans le formulaire
			foreach ($result['film_realiser'] as $key => $film) { array_push($newtableaufilmsrealiser, $film['id_f']); } // Push l'id dans le tableau
			$result['film_realiser'] = $newtableaufilmsrealiser; // Retourne un tableau non associatif avec les id des films que l'artiste a réalisé -> pour comparaison avec les #id du listing de tous les films

			echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]); // affiche la View et passe les données en paramêtres
		}
	}

	##############################################################
	#### TRAITEMENT DES DONNEES - MODIFICATIONS D'UN ARTISTE #####
	##############################################################

	public function update($id) 
	{
		global $admin, $user, $nom, $prenom, $date_de_naissance, $photo, $newphoto, $biographie, $realiser, $jouer, $categories; // Superglobales

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			if(is_array($categories)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				$deleteCategorie = $this->model->setDeleteMetierByArtiste($id);  // Supprime tous les metiers de l'artiste (Acteurs / Réalisateurs)
				foreach ($categories as $key => $categorie) { $insertCategorie = $this->model->setInsertMetierByArtiste($categorie, $id); } // Insertion des métiers de l'artiste (Acteurs / Réalisateurs)
			}

			if(is_array($jouer)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
			{
				$deleteFilmsJouer = $this->model->setDeleteFilmsByActeur($id); // Supprime tous les films dans lesquels l'artiste a joué
				foreach ($jouer as $key => $film) { $insertFilmJouer = $this->model->setInsertFilmJouerByArtiste($film, $id); } // Insertion des réalisateurs qui ont joué dans le film
			}

			if(is_array($realiser)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				$deleteFilmsRealiser = $this->model->setDeleteFilmsByRealisateur($id);  // Supprime tous les films que l'artiste a réalisé
				foreach ($realiser as $key => $film) { $insertFilmRealiser = $this->model->setInsertFilmRealiserByArtiste($film, $id); } // Insertion des acteurs qui ont joué dans le film
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

				if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png"))  // Verifie la conformité de l'extention de l'image
				{
					if($photo && file_exists($photo)) unlink($photo); // Supprime la photo existante

					$fichier = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($prenom) ."-". strtolower($nom) ."", $ext); // Renome le fichier d'apres le nom et prenom de l'artiste

					move_uploaded_file($_FILES['newphoto']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!"); // Déplace l'image dans le dossier de destination ou erreur. Attention CHMOD IMPORTANT EN CAS D'ERREUR, METTRE CHMOD DU REPERTOIRE à 777
					@chmod ($fichier, 0644); // Redéfinition du CHMOD de l'image (droits d'accès => seul le script peut modifier le fichier)

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

			$update = $this->model->setUpdateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie); // Modifie les données dans la bdd
			$message = "Artiste modifié avec succès"; // Message à afficher

			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../../artists/3/show/". $id ."", 1); // Redirection après 1s sur la page show de artiste #id
		}
	}

	###################################################
	#### SUPPRESSION D'UN ARTISTE #####################
	###################################################

	public function suppression(int $id) 
	{
		global $admin, $user;

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			$result = $this->model->getInfosByArtiste($id); // Retourne les infos de l'artiste (besoin du chemin de l'image pour la supprimer)
			$repertoirePhotosArtistes = "assets/images/artistes"; // Repertoire de destination de l'image
			$poster = "". $repertoirePhotosArtistes ."/". $result['photo_a'] .""; // Chemin complet de l'image
			if($poster && file_exists($poster)) unlink($poster); // Supprime definitivement la photo du dossier 

			$suppression = $this->model->deleteArtist($id); // Supprime l'artiste de la bdd

			$message = "Artiste supprimé avec succès"; // Affiche le message
			echo $template->render(["message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../../films", 1); // Redirection après 1s vers films
		}
	}


}