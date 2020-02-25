<?php

class FilmsController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR ####################################################
	######################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Films(); // Nouvel Object : Films
	}

	###################################################
	#### PAGE DE LISTING DE TOUS LES FILMS ############
	###################################################

	public function listing($genre = null, $p = null)
	{
		global $baseUrl, $admin, $user, $search, $requete; // SuperGlobales

		$nbElementsParPage = "18";
		if (!$p) $p = 1;
		if(!$genre) $genre = "0";

		$pageTwig = 'films/index.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View

		#### traitement de la recherche ###########################################################################################################
	
		$requete = "("; // Ouvre la parenthèse dans la laquelle va etre inserée la composition de la requête
		$separator = ""; // Initialise la variable
		$explode = explode(" ", $search); // On décompose la chaine en mots -> explode[0] = mot 1, explode[1] = mot 2 ... etc

		for($i = 0; $i < count($explode); $i++) // Boucle pour faire une recherche sur tous les mots qui composent la recherche ($search)
		{
			if($search == "") $recherche = "titre_f != ''"; // On ne recherche rien, donc listing de tous les films
			else $recherche = "titre_f LIKE '%" . $explode[$i] . "%'"; // Recherche sur le titre du film

			$requete .= $separator . "". $recherche .""; // Compose et incrémente la requête
			$separator = " OR "; // Séparateur, dans la requête
		}

		$requete .= ")"; // Referme la parenthèse qui contient la requête

		#### end  traitement de la recherche #####################################################################################################

		$instanceArtists = new Artists();

		$films = $this->model->getAllFilms($requete, $genre, $nbElementsParPage, $p); // Retourne la liste des films selon la recherche ou le genre sélectionné

		$instanceGenres = new Genres();
		$genres = $instanceGenres->getAllGenres(); // Retourne la liste de tous les genres
		$artistes = $instanceArtists->getAllArtists("id_a != ''", $nbElementsParPage, $p); // Retourne la liste de tous les artistes
		$nbFilmsTotal = $this->model->getNbFilms($requete, $genre, $nbElementsParPage, $p); // Retourne le nombre total de films

		$paginator = number($nbElementsParPage, "$baseUrl/films/$genre", $nbFilmsTotal, $p);

		foreach ($films as $key => $film) // Parcours le tableau associatif des films pour y inserer une variable url basé sur les noms des films
		{ 
			$film['url'] = rewrite_url($film['titre_f']); // Retourne une url propre basée sur le titre du film
			$films[$key]["url"] = $film['url']; // Incrémente le tableau avec l'url
		}

		foreach ($artistes as $key => $artiste) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$artiste['url2'] = rewrite_url($artiste['nom_a'] ); // Retourne une composante pour une url propre basée sur le noms des artites
			$artiste['url'] = rewrite_url($artiste['prenom_a'] ); // Retourne une composante pour une url propre basée sur le noms des artites
			
			$artistes[$key]["url"] = "". $artiste['url'] ."-". $artiste['url2'] .""; // Incrémente le tableau avec l'url
		}

		$instanceGenres = new Genres();
		if($genre) $genrename = $instanceGenres->getGenre($genre); else $genrename = ""; // Retourne les infos du genre pour creer le titre dans la view

		echo $template->render(["url" => $_SERVER['REQUEST_URI'], "films" => $films, "artistes" => $artistes, "admin" => $admin, "user" => $user, "genrename" => $genrename, "genreActif" => $genre, "genres" => $genres, "search" => $search, "paginator" => $paginator]); // Affiche la view et passe les données en paramêtres
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
		$recommandations = $this->model->getAllFilms("", "", "4", ""); // Retourne la liste des récommandations

		$suivant = $this->model->getInfosByFilmSuivant($id); // Retourne les infos du film suivant
		$precedent = $this->model->getInfosByFilmPrecedent($id); // Retourne les infos du film précedent

		$precedent['urlprecedent'] = rewrite_url($precedent['titre_f'] ); // Retourne une composante pour une url propre basée sur les titres de films
		$suivant['urlsuivant'] = rewrite_url($suivant['titre_f'] ); // Retourne une composante pour une url propre basée sur les titres de films

		$instanceAppartient = new Appartient();
		$result['genres'] = $instanceAppartient->getGenresAppartientFilm($id); // Retourne tous les genres du film

		$instanceRealiser = new Realiser();
		$result['realisateurs'] = $instanceRealiser->getRealisateursByFilm($id); // Retourne tous les réalisateurs du film

		$instanceJouer = new Jouer();
		$result['acteurs'] = $instanceJouer->getActeursJouerFilm($id); // Retourne tous les acteurs du film

		$instanceCommentsVotes = new CommentsVotes();
		$instanceComments = new Comments();
		$result['commentaires'] = $instanceComments->getCommentairesByModuleAndIdd("Films", $id); // Retourne tous les commentaires du film
		foreach ($result['commentaires'] as $key => $commentaire) // Parcours le tableau associatif des commentaires  pour y inserer 2 variable contenant les votes +1 ou -1 sur un commentaire
		{
			$commentaire['positif'] = $instanceCommentsVotes->getNbVotesByCom($commentaire['id'] , "positif"); // Retourne le nombre de vote positif sur commentaire #id
			$commentaire['negatif'] = $instanceCommentsVotes->getNbVotesByCom($commentaire['id'], "negatif"); // Retourne le nombre de vote negatif sur commentaire #id
			$result['commentaires'][$key]["negatif"] = $commentaire['negatif']['COUNT(*)']; // Increment le tableau des commentaires
			$result['commentaires'][$key]["positif"] = $commentaire['positif']['COUNT(*)']; // Increment le tableau des commentaires	
		}

		if(!$result['poster_f'] || !file_exists("". $repertoireImagesFilms ."/". $result['poster_f'] ."")) $result['poster_f'] = "default.jpg"; // Si pas d'image ou erreur image alors image par défaut !
		if(!$result['resume_f']) $result['resume_f'] = "Information à complêter"; // Si pas de résumé, alors on affiche le message : Information à complêter

		foreach ($result['realisateurs'] as $key => $realisateur) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$realisateur['url2'] = rewrite_url($realisateur['nom_a'] ); // Retourne une composante pour une url propre basée sur le noms des artites
			$realisateur['url'] = rewrite_url($realisateur['prenom_a'] );// Retourne une composante pour une url propre basée sur le noms des artites
			
			$result['realisateurs'][$key]["url"] = "". $realisateur['url'] ."-". $realisateur['url2'] .""; // Incrémente le tableau avec l'url
		}

		foreach ($recommandations as $key => $recommandation) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$recommandation['url'] = rewrite_url($recommandation["titre_f"] ); // Retourne une url propre basée sur le noms des artites
			$recommandations[$key]["url"] = "". $recommandation['url'] .""; // Incrémente le tableau avec l'url
		}

		foreach ($result['acteurs'] as $key => $acteur) // Parcours le tableau associatif des artistes  pour y inserer une variable url basé sur les noms des artistes
		{
			$acteur['url2'] = rewrite_url($acteur['nom_a'] ); // Retourne une composante pour une url propre basée sur le noms des artites
			$acteur['url'] = rewrite_url($acteur['prenom_a'] ); // Retourne une composante pour une url propre basée sur le noms des artites
			
			$result['acteurs'][$key]["url"] = "". $acteur['url'] ."-". $acteur['url2'] .""; // Incrémente le tableau avec l'url
		}

		echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "recommandations" => $recommandations, "admin" => $admin, "user" => $user, "precedent" => $precedent, "suivant" => $suivant]); // Affiche la view et passe les données en paramêtres
	}

	###################################################
	#### FORMULAIRE D'AJOUT D'UN NOUVEAU FILM #########
	###################################################

	public function add() // Page : films/add
	{ 
		global $admin, $user;

		if($admin)
		{
			$pageTwig = 'films/add.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$instanceGenres = new Genres();
			$result['allgenres'] = $instanceGenres->getAllGenres(); // Retourne la liste de tous les genres

			$instanceExercer = new Exercer();
			$result['allacteurs'] = $instanceExercer->getAllArtistesExercerMetier("1", "", ""); // Retourne la liste de tous les acteurs du site
			$result['allrealisateurs'] = $instanceExercer->getAllArtistesExercerMetier("2", "", ""); // Retourne la liste de tous les réalisateurs du site

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
	}

	###################################################
	#### TRAITEMENT DES DONNEES - INSERTION D'UN FILM #
	###################################################

	public function insert() // Page : films/insert
	{ 
		global $admin, $user, $titre, $poster, $annee, $resume, $video, $realisateurs, $acteurs, $genres, $duree; // Superglobales

		if($admin)
		{
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

			$result = $this->model->setInsertFilm($titre, $poster, $annee,$resume, $video, $duree); // Insertion des données
			$id = $result; // Retourne l'#id du film inséré

			$instanceRealiser = new Realiser();
			if(is_array($realisateurs)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
			{
				foreach ($realisateurs as $key => $realisateur) { $insertRealisateur = $instanceRealiser->setInsertRealisateurByFilm($id, $realisateur); } // Insertion dans la table realiser des réalisateurs du film
			}

			$instanceJouer = new Jouer();
			if(is_array($acteurs)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				foreach ($acteurs as $key => $acteur) { $insertActeur = $instanceJouer->setInsertActeurByFilm($id, $acteur); } // Insertion dans la table jouer des acteurs qui ont joué dans le film
			}

			$instanceAppartient = new Appartient();
			if(is_array($genres)) // Si la variable genres est un tableau, des genres ont été sélectionné
			{
				foreach ($genres as $key => $genre) { $insertGenre = $instanceAppartient->setInsertGenreAppartientFilm($id, $genre); } // Insertion dans la table appartient des genres sélectionnés pour le film
			}

			$message = "Film inséré avec succès"; // Message à afficher

			$nomdufilm = rewrite_url($titre); // Retourne une url propre basée sur le titre du film

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user, "message" => $message]); // Affiche la view et passe les données en paramêtres
			redirect("../../films/show/". $id ."/". $nomdufilm ."", 0); // redirection vers films/show/#id
		}
	}

	###################################################
	#### FORMULAIRE DE REEDITION D'UN FILM ############
	###################################################

	public function edition(int $id) // Page : films/edition/#id
	{
		global $admin, $user;

		if($admin)
		{
			$pageTwig = 'films/edition.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // Chargement de la view
			$result = $this->model->getInfosByFilm($id); // Appelle de la fonction

			$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
			if(!$result['poster_f'] || !file_exists("". $repertoirePhotosFilms ."/". $result['poster_f'] ."")) $result['poster_f'] = "default.jpg"; // Si pas d'image ou erreur image alors image par défaut!

			$instanceExercer = new Exercer();
			$result['allacteurs'] = $instanceExercer->getAllArtistesExercerMetier("1", "", ""); // Retourne la liste de tous les acteurs du site
			$result['allrealisateurs'] = $instanceExercer->getAllArtistesExercerMetier("2", "", ""); // Retourne la liste de tous les réalisateurs du site

			$instanceJouer = new Jouer();
			$result['acteurs'] = $instanceJouer->getActeursJouerFilm($id); // Retourne un tableau associatif avec tous les id des acteur du film
			$newtableauacteurs = []; // Initialisation d'un nouveau tableau non associatif pour les acteurs 
			foreach ($result['acteurs'] as $key => $acteur) { array_push($newtableauacteurs, $acteur['id_a']); } // Push l'id dans le tableau
			$result['acteurs'] = $newtableauacteurs; // Retourne un tableau non associatif avec les id des acteurs du film -> pour comparaison avec les #id du listing de tous les acteurs

			$instanceRealiser = new Realiser();
			$result['realisateurs'] = $instanceRealiser->getRealisateursByFilm($id); // Retourne un tableau associatif avec tous les id des réalisateurs du films
			$newtableaurealisateurs = []; // Initialisation d'un nouveau tableau non associatif pour les réalisateurs 
			foreach ($result['realisateurs'] as $key => $realisateur) { array_push($newtableaurealisateurs, $realisateur['id_a']); } // Push l'id dans le tableau
			$result['realisateurs'] = $newtableaurealisateurs; // Retourne un tableau non associatif avec les id des realisateurs du film -> pour comparaison avec les #id du listing de tous les réalisateurs

			$instanceGenres = new Genres();
			$result['allgenres'] = $instanceGenres->getAllGenres(); // Retourne la liste de tous les genres

			$instanceAppartient = new Appartient();
			$result['genres'] = $instanceAppartient->getGenresAppartientFilm($id); // Retourne un tableau associatif avec tous les id des genres du film
			$newtableaugenres = []; // Initialisation d'un nouveau tableau non associatif pour les genres 
			foreach ($result['genres'] as $key => $genre) { array_push($newtableaugenres, $genre['id_g']); } // Push l'id dans le tableau
			$result['genres'] = $newtableaugenres; // Retourne un tableau non associatif avec les id des genres du film -> pour comparaison avec les #id du listing de tous les genres

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
	}

	#########################################################
	#### TRAITEMENT DES DONNEES - REEDITION D'UN FILM #######
	#########################################################

	public function update(int $id) // Page : films/update/#id
	{
		global $admin, $user, $titre, $poster, $newposter, $annee, $realisateurs, $acteurs, $genres, $video, $resume, $duree; // Superglobales

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
			$fichier = $_FILES['newposter']['name']; // Definition du fichier

			$poster = redimentionne_image("". $repertoirePhotosFilms ."", $poster);

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

			$update = $this->model->setUpdateFilms($id, $titre, $poster, $annee, $video, $resume, $duree); // -> update du film dans la table film (titre, poster, annee, resume, video)

			$message = "Film modifié avec succès"; // Message à afficher

			$instanceRealiser = new Realiser();
			$deleteRealisateurs = $instanceRealiser->setDeleteAllRealisateursByFilms($id); // Supprime tous les réalisateurs du film
			if(is_array($realisateurs)) // Si la variable realisateurs est un tableau, des réalisateurs ont été sélectionné
			{
				foreach ($realisateurs as $key => $realisateur) { $insertRealisateur = $instanceRealiser->setInsertRealisateurByFilm($id, $realisateur); } // Insertion des réalisateurs qui ont joué dans le film
			}

			$instanceJouer = new Jouer();
			$deleteActeurs = $instanceJouer->setDeleteAllActeursByFilms($id); // Supprime tous les acteurs du film
			if(is_array($acteurs)) // Si la variable acteurs est un tableau, des acteurs ont été sélectionné
			{
				foreach ($acteurs as $key => $acteur) { $insertActeur = $instanceJouer->setInsertActeurByFilm($id, $acteur); } // Insertion des acteurs qui ont joué dans le film
			}

			$instanceAppartient = new Appartient();
			$deleteGenres = $instanceAppartient->setDeleteAllGenresAppartientFilm($id); // Supprime tous les genre du film
			if(is_array($genres)) // Si la variable genres est un tableau, des genres ont été sélectionné
			{
				foreach ($genres as $key => $genre) { $insertGenre = $instanceAppartient->setInsertGenreAppartientFilm($id, $genre); } // Insertion des genres qui ont joué dans le film
			}

			$nomdufilm = rewrite_url($titre); // Retourne une url propre basée sur le titre du film

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../../films/show/". $id ."/". $nomdufilm ."", 1); // -> Redirection vers films/show/#id
		}
	}

	###################################################
	#### SUPPRESSION D'UN FILM ########################
	###################################################

	public function suppression($id) // Page : films/suppression/#id
	{
		global $admin, $user;

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Appelle la View
			$template = $this->twig->load($pageTwig); // Charge la page

			$result = $this->model->getInfosByFilm($id); // Retourne les infos du film (besoin du chemin de l'image pour la supprimer)
			$repertoirePhotosFilms = "assets/images/films"; // Repertoire de destination de l'image
			$poster = "". $repertoirePhotosFilms ."/". $result['poster_f'] .""; // Chemin complet de l'image
			if($poster && file_exists($poster)) unlink($poster); // Supprime la photo existante

			$instanceRealiser = new Realiser();
			$deleteRealisateurs = $instanceRealiser->setDeleteAllRealisateursByFilms($id); // Supprime tous les réalisateurs du film de la bdd

			$instanceJouer = new Jouer();
			$deleteActeurs = $instanceJouer->setDeleteAllActeursByFilms($id); // Supprime tous les acteurs du film de la bdd

			$instanceAppartient = new Appartient();
			$deleteGenres = $instanceAppartient->setDeleteAllGenresAppartientFilm($id); // Supprime tous les genres du film de la bdd

			$instanceComments = new Comments();
			$deleteCommentaires = $instanceComments->setDeleteAllCommentairesByModuleAndIdd("Films", $id); // Supprime tous les commentaires du film de la bdd
			$deleteFilm = $this->model->setDeleteFilm($id); // Supprime le film de la bdd

			$message = "Film supprimé avec succès"; // Mesage à afficher

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("../../films", 2); // -> Redirection vers films
		}
	}

}