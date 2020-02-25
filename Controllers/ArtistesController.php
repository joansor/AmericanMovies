<?php

class ArtistesController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR ####################################################
	######################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Artistes(); // Nouvel Object : Artistes
	}

	#######################################################################
	####  FONCTION INDEX - BASE DU MODULE #################################
	####  Choix vers listing items catégorie : Réalisateurs ou Acteurs ####
	#######################################################################

	public function listing($metier = null, $p = null)
	{
		global $baseUrl, $admin, $user, $section, $search, $artistes; // Superglobale

		$nbElementsParPage = "5"; // Nombre d'artistes à afficher par page
		if (!$p) $p = 1; // Pas de page, alors la page est egal à 1 (pour pages prev/next)
	
		$pageTwig = 'artistes/index.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View 

		$instanceExercer = new Exercer();
		$actors = $instanceExercer->getAllArtistesExercerMetier("1", "", ""); // Retourne la liste de tous les artistes qui excerce le metier d'acteurs
		$realisators = $instanceExercer->getAllArtistesExercerMetier("2", "", ""); // Retourne la liste de tous les artistes qui exerce le metier de realisateurs

		$requete = "("; // Ouvre la parenthèse dans la laquelle va être inserée la composition de la requête
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

		if($metier) $artistes = $instanceExercer->getAllArtistesExercerMetier($metier, $nbElementsParPage, $p); // Fonction qui retourne la liste de tous les artistes qui sont dans la catégorie (Acteurs ou Réalisateurs)
		else $artistes = $this->model->getAllArtistes($requete, $nbElementsParPage, $p); // Fonction qui retourne la liste de tous les artistes qui sont dans la catégorie (Acteurs ou Réalisateurs)

		if($metier == "1") $metier = ["id" => $metier, "nom" => "Acteurs"]; // Redefinition metier en tableau pour avoir le nom dans la view
		else if($metier == "2") $metier = ["id" => $metier, "nom" => "Réalisateur"]; // Redefinition metier en tableau pour avoir le nom dans la view
		else $metier = ["id" => "0", "nom" => "All"]; // Redefinition metier en tableau pour avoir le nom dans la view

		$nbArtistes = $this->model->getNbArtistes($requete, $metier['id']); // Retourne le nombre total d'artistes

		$paginator = number($nbElementsParPage, "$baseUrl/artistes/". $metier['id'] ."", $nbArtistes, $p);

		foreach ($artistes as $key => $artiste) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$artiste['url2'] = rewrite_url($artiste['nom_a'] ); // Retourne une composante pour une url propre basée sur le noms de l'artite
			$artiste['url'] = rewrite_url($artiste['prenom_a'] ); // Retourne une composante pour une url propre basée sur le noms de l'artite

			$artistes[$key]["url"] = "". $artiste['url'] ."-". $artiste['url2'] .""; // Incrémente le tableau avec l'url
		}

		echo $template->render(["url" => $_SERVER['REQUEST_URI'], "metier" => $metier, "admin" => $admin, "user" => $user, "actors" => $actors,"realisators" => $realisators, "section" => $section, "search" => $search, "artistes"=>$artistes, "paginator" => $paginator]); // Affiche la view et passe les données en paramêtres	
	}

	###################################################
	#### PAGE DE PRESENTATION D'UN ARTISTE BY #ID #####
	###################################################

	public function show(int $id) 
	{
		global $admin, $user, $section; // Superglobale

		$repertoireImagesArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes

		$pageTwig = 'artistes/show.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		$result = $this->model->getInfosByArtiste($id); // Retourne les infos de artiste #id

		$instanceJouer = new Jouer();
		$result['films_jouer'] = $instanceJouer->getFilmsJouerArtiste($id); // Retourne un tableau associatif avec les id et titres des films dans lesquels l'artiste a joué
		foreach ($result['films_jouer'] as $key => $film) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$film['url'] = rewrite_url($film['titre_f'] );
			$result['films_jouer'][$key]["url"] = "". $film['url'] .""; // Incrémente le tableau avec l'url
		}

		$instanceRealiser = new Realiser();
		$result['films_realiser'] = $instanceRealiser->getFilmsRealiserArtiste($id);  // Retourne un tableau associatif avec les id et titres des films que l'artiste a réalisé
		foreach ($result['films_realiser'] as $key => $film) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$film['url'] = rewrite_url($film['titre_f'] );
			$result['films_realiser'][$key]["url"] = "". $film['url'] .""; // Incrémente le tableau avec l'url
		}

		$instanceCommentsVotes = new CommentsVotes();
		$instanceComments = new Comments();
		$result['commentaires'] = $instanceComments->getCommentairesByModuleAndIdd("Artistes", $id); // Retourne tous les commentaires du artiste

		foreach ($result['commentaires'] as $key => $commentaire) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$commentaire['positif'] = $instanceCommentsVotes->getNbVotesByCom($commentaire['id'] , "positif");
			$commentaire['negatif'] = $instanceCommentsVotes->getNbVotesByCom($commentaire['id'], "negatif");
			$result['commentaires'][$key]["negatif"] = $commentaire['negatif']['COUNT(*)']; 
			$result['commentaires'][$key]["positif"] = $commentaire['positif']['COUNT(*)']; 	
		}

		$instanceExercer = new Exercer();
		$metiers = $instanceExercer->getMetiersExercerArtiste($id);

		echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user, "metiers" => $metiers]); // Affiche la view et passe les données en paramêtres
	}

	###################################################
	#### FORMULAIRE D'AJOUT D'UN NOUVEL ARTISTE #######
	###################################################

	public function add()
	{
		global $admin, $user, $section; // Superglobale

		if ($admin) {
			$pageTwig = 'artistes/add.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$instanceMetiers = new Metiers();
			$result['allmetiers'] = $instanceMetiers->getAllMetiers(); // Retourne un tableau associatif avec les id et noms de toutes les metiers

			$instanceFilms = new Films();
			$result['allfilms'] = $instanceFilms->getAllFilms("", "", "", ""); // Retourne la liste de tous les films pour select Films jouer/realiser
			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user, "section" => $section]); // Affiche la view et passe les données en paramêtres
		}
	}

	######################################################
	#### TRAITEMENT DES DONNEES - INSERTION DE L'ARTISTE #
	######################################################

	public function insert()
	{
		global $baseUrl, $admin, $user, $nom, $prenom, $date_de_naissance, $photo, $photo, $biographie, $realiser, $jouer, $metiers;

		if ($admin) 
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			$nom = ucwords(strtolower($nom)); // Premiere lettre du prenom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
			$prenom = ucwords(strtolower($prenom));  // Premiere lettre du nom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
			if (!$date_de_naissance) $date_de_naissance = "1970-01-01"; // Si pas de date de naissance, obligé de mettre une date par defaut sinon impossible de faire le insert sql -> Error !

			$repertoirePhotosArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes
			$fichier = $_FILES['photo']['name']; // Fichier -> photo envoyée via le formulaire

			if ($fichier) // Si il y a une une photo
			{
				$img_name = $fichier; // Variable intermediare du nom de fichier
				$ext = get_extension($img_name); // fonction qui retourne l'extention de l'image. Fonction placée dans racine->functions.php

				if (($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png")) // Verifie la conformité de l'extention de l'image
				{
					$fichier = renome_image("" . $repertoirePhotosArtistes . "", "" . strtolower($prenom) . "-" . strtolower($nom) . "", $ext); // Renome le fichier d'après le nom et le prénom de l"artiste

					move_uploaded_file($_FILES['photo']['tmp_name'], $fichier) or die("L'envoi du fichier a echoué !!!"); // Déplace l'image dans le dossier de destination ou erreur. Attention CHMOD IMPORTANT EN CAS D'ERREUR, METTRE CHMOD DU REPERTOIRE à 777
					@chmod($fichier, 0644); // Redéfinition du CHMOD de l'image (droits d'accès => seul le script peut modifier le fichier)

					$photo = redimentionne_image("" . $repertoirePhotosArtistes . "", $fichier); // Redimentionne l'image à 250px max width/height. Fonction placée dans racine->functions.php
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

			$photo = str_replace("" . $repertoirePhotosArtistes . "/", "", $photo); // On enleve le chemin du repertoire pour ne stocker que le nom de fichier final dans la bdd

			$insert = $this->model->setInsertArtiste($nom, $prenom, $date_de_naissance, $photo, $biographie); // Insert les données dans la bdd

			$id = $insert;

			$instanceExercer = new Exercer();
			if (is_array($metiers)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				foreach ($metiers as $key => $metier) { $insertmetier = $instanceExercer->setInsertExercerArtiste($metier, $id); } // Insertion des métiers de l'artiste (Acteurs / Réalisateurs)
			}

			$instanceJouer = new Jouer();
			if (is_array($jouer)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
			{
				foreach ($jouer as $key => $film) { $insertFilmJouer = $instanceJouer->setInsertFilmJouerByArtiste($film, $id); } // Insertion des réalisateurs qui ont joué dans le film
			}

			$instanceRealiser = new Realiser();
			if (is_array($realiser)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				foreach ($realiser as $key => $film) { $insertFilmRealiser = $instanceRealiser->setInsertFilmRealiserByArtiste($film, $id); } // Insertion des acteurs qui ont joué dans le film
			}

			$result = $this->model->getInfosByArtiste($id); // Retourne les infos de artiste #id
			$url = rewrite_url($result['nom_a'] );
			$url2 = rewrite_url($result['prenom_a'] );

			$message = "Artiste ajouté avec succès"; // Message à afficher

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("$baseUrl/artistes/show/" . $id . "/$url-$url2", 1); // Redirection après 1s sur la page show de artiste #id
		}
	}

	###################################################
	#### FORMULAIRE DE REEDITION D'UN ARTISTE #########
	###################################################

	public function edition(int $id)
	{
		global $admin, $user, $section; // Superglobales

		if ($admin) 
		{
			$repertoireImagesArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes

			$pageTwig = 'artistes/edition.html.twig'; // Chemin de la view
			$template = $this->twig->load($pageTwig); // Chargement de la view

			$result = $this->model->getInfosByArtiste($id); // Retourne les infos de artiste #id

			$instanceMetiers = new Metiers();
			$result['allmetiers'] = $instanceMetiers->getAllMetiers(); // Retourne un tableau associatif avec les id et noms de tous les metiers

			$instanceExercer = new Exercer();
			$result['metiers'] = $instanceExercer->getMetiersExercerArtiste($id); // Retourne un tableau associatif avec les id et noms des metiers exercer par l'artiste
			$newtableaumetiersartiste = []; // Initialisation d'un nouveau tableau non associatif dans lequels nous allons mettre tous les id des catégories --> pour auto select les catégories dans le formulaire
			foreach ($result['metiers'] as $key => $metier) { array_push($newtableaumetiersartiste, $metier['id_m']); } // Push l'id dans le tableau
			$result['metiers'] = $newtableaumetiersartiste; // Retourne un tableau non associatif avec les id des catégories auquelles l'acteur appartient (dont est le metier)

			$instanceFilms = new Films();
			$result['allfilms'] = $instanceFilms->getAllFilms("", "", "", ""); // Retourne la liste de tous les films du site --> pour select Films:jouer/realiser dans formualaire

			$instanceJouer = new Jouer();
			$result['film_jouer'] = $instanceJouer->getFilmsJouerArtiste($id); // Retourne un tableau associatif avec les id et titres des films dans lesquels l'artiste a joué
			$newtableaufilmsjouer = []; // Initialisation d'un nouveau tableau non associatif dans lequels nous allons mettre tous les id des films dans lequel l'acteur a joué --> pour auto select les films dans le formulaire
			foreach ($result['film_jouer'] as $key => $film) { array_push($newtableaufilmsjouer, $film['id_f']); } // Push l'id dans le tableau
			$result['film_jouer'] = $newtableaufilmsjouer; // Retourne un tableau non associatif avec les id des films dans lesquels l'acteur a joué -> pour comparaison avec les #id du listing de tous les films

			$instanceRealiser = new Realiser();
			$result['film_realiser'] = $instanceRealiser->getFilmsRealiserArtiste($id); // Retourne un tableau associatif avec les id et titres des films que l'artiste a réalisé
			$newtableaufilmsrealiser = []; // Initialisation d'un nouveau tableau non associatif  dans lequels nous allons mettre tous les id des films que l'artiste a réalisé --> pour auto select les films dans le formulaire
			foreach ($result['film_realiser'] as $key => $film) { array_push($newtableaufilmsrealiser, $film['id_f']); } // Push l'id dans le tableau
			$result['film_realiser'] = $newtableaufilmsrealiser; // Retourne un tableau non associatif avec les id des films que l'artiste a réalisé -> pour comparaison avec les #id du listing de tous les films

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user, "section" => $section]); // affiche la View et passe les données en paramêtres
		}
	}

	##############################################################
	#### TRAITEMENT DES DONNEES APRES REEDITION D'UN ARTISTE #####
	##############################################################

	public function update($id)
	{
		global $baseUrl, $admin, $user, $nom, $prenom, $date_de_naissance, $photo, $newphoto, $biographie, $realiser, $jouer, $metiers; // Superglobales

		if ($admin) 
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			$instanceExercer = new Exercer();
			if (is_array($metiers)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				$deleteMetier = $instanceExercer->setDeleteExercerArtiste($id);  // Supprime tous les metiers exercer par artiste #id
				foreach ($metiers as $key => $metier) { $insertMetier = $instanceExercer->setInsertExercerArtiste($metier, $id); } // Insertion des métiers exercer l'artiste (Acteurs / Réalisateurs)
			}

			$instanceJouer = new Jouer();
			if (is_array($jouer)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
			{
				$deleteFilmsJouer = $instanceJouer->setDeleteFilmsByActeur($id); // Supprime tous les films dans lesquels l'artiste a joué
				foreach ($jouer as $key => $film) { $insertFilmJouer = $instanceJouer->setInsertFilmJouerByArtiste($film, $id); } // Insertion des réalisateurs qui ont joué dans le film
			}

			$instanceRealiser = new Realiser();
			if (is_array($realiser)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				$deleteFilmsRealiser = $instanceRealiser->setDeleteFilmsByRealisateur($id); // Supprime tous les films que l'artiste a réalisé  
				foreach ($realiser as $key => $film) { $insertFilmRealiser = $instanceRealiser->setInsertFilmRealiserByArtiste($film, $id); } // Insertion des acteurs qui ont joué dans le film
			}

			$nom = ucwords(strtolower($nom)); // Premiere lettre du prenom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
			$prenom = ucwords(strtolower($prenom));  // Premiere lettre du nom en majuscule -> strtolower = chaine en minuscule et ucwords = premier caractere de chaque mot en majuscule
			if (!$date_de_naissance) $date_de_naissance = "1970-01-01"; // Si pas de date de naissance, obligé de mettre une date par defaut sinon impossible de faire le insert sql -> Error !

			$repertoirePhotosArtistes = "assets/images/artistes"; // Répertoire ou sont stockées les images des artistes
			$fichier = $_FILES['newphoto']['name']; // Fichier -> photo envoyée via le formulaire

			if ($fichier) // Si il y a une une photo
			{
				$img_name = $fichier; // Variable intermediare du nom de fichier
				$ext = get_extension($img_name); // fonction qui retourne l'extention de l'image. Fonction placée dans racine->functions.php

				if (($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png"))  // Verifie la conformité de l'extention de l'image
				{
					if ($photo && file_exists($photo)) unlink($photo); // Supprime la photo existante

					$fichier = renome_image("" . $repertoirePhotosArtistes . "", "" . strtolower($prenom) . "-" . strtolower($nom) . "", $ext); // Renome le fichier d'apres le nom et prenom de l'artiste

					move_uploaded_file($_FILES['newphoto']['tmp_name'], $fichier) or die("L'envoi du fichier a echoué !!!"); // Déplace l'image dans le dossier de destination ou erreur. Attention CHMOD IMPORTANT EN CAS D'ERREUR, METTRE CHMOD DU REPERTOIRE à 777
					@chmod($fichier, 0644); // Redéfinition du CHMOD de l'image (droits d'accès => seul le script peut modifier le fichier)

					$photo = redimentionne_image("" . $repertoirePhotosArtistes . "", $fichier); // Redimentionne l'image à 250px max width/height. Fonction placée dans racine->functions.php
				} 
				else 
				{
					$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n"; // Message à afficher
					redirect("javascript:history.back()", 5); // Redirection apès 5s sur la page formulaire d'ajout d'un artiste
				}
			} 
			else if ($photo) 
			{
				$ext = get_extension($photo); // fonction qui retourne l'extention de l'image. Fonction placée dans racine->functions.php
				$newphoto = renome_image("" . $repertoirePhotosArtistes . "", "" . strtolower($prenom) . "-" . strtolower($nom) . "", $ext);  // fonction qui retourne la nouvelle mise en forme du nouveau nom de l'image. Fonction placée dans racine->functions.php
				rename("" . $repertoirePhotosArtistes . "/" . $photo . "", $newphoto); // Renome le fichier d'après le prénom et le nom de l'artiste
				$photo = $newphoto;
			}

			$photo = str_replace("" . $repertoirePhotosArtistes . "/", "", $photo); // On enleve le chemin du repertoire pour ne stocker que le nom de fichier final dans la bdd

			$result = $this->model->getInfosByArtiste($id); // Retourne les infos de artiste #id
			$url2 = rewrite_url($result['nom_a'] );
			$url = rewrite_url($result['prenom_a'] );

			$update = $this->model->setUpdateArtiste($id, $nom, $prenom, $date_de_naissance, $photo, $biographie); // Modifie les données dans la bdd
			$message = "Artiste modifié avec succès"; // Message à afficher

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("$baseUrl/artistes/show/" . $id . "/$url-$url2", 1); // Redirection après 1s sur la page show de artiste #id
		}
	}

	###################################################
	#### SUPPRESSION D'UN ARTISTE #####################
	###################################################

	public function suppression(int $id)
	{
		global $admin, $user;

		if ($admin) 
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			$result = $this->model->getInfosByArtiste($id); // Retourne les infos de l'artiste (besoin du chemin de l'image pour la supprimer)

			$repertoirePhotosArtistes = "assets/images/artistes"; // Repertoire de destination de l'image
			$poster = "" . $repertoirePhotosArtistes . "/" . $result['photo_a'] . ""; // Chemin complet de l'image
			if ($poster && file_exists($poster)) unlink($poster); // Supprime definitivement la photo du dossier 

			$instanceComments = new Comments();
			$suppressionCommentaires = $instanceComments->setDeleteAllCommentairesByModuleAndIdd("Artistes", $result['id_a']); // Supprime les commentaires sur l'artiste #id

			$suppression = $this->model->setDeleteArtiste($id); // Supprime l'artiste de la bdd

			$message = "Artiste supprimé avec succès"; // Affiche le message
			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../../films", 1); // Redirection après 1s vers films
		}
	}
}
