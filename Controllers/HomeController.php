<?php

class HomeController extends Controller
{

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
		global $admin;

		echo"--- $admin ---";

		$films = $this->model->getAllFilms();
		$pageTwig = 'films/index.html.twig';
		$template = $this->twig->load($pageTwig);
		echo $template->render(["films" => $films, "admin" => $admin]);
	}
	
	###################################################
	#### PAGE DE PRESENTATION D'UN FILM BY #ID ########
	###################################################

	public function show(int $id) // Page : films/show/#id
	{
		global $admin;
		echo"--- $admin ---";
		$pageTwig = 'films/show.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = $this->model->getOneExemple($id);
		$result['genres'] = $this->model->getGenresByFilm($id);
		$result['acteurs'] = $this->model->getActeursByFilm($id);
		$result['realisateurs'] = $this->model->getRealisateursByFilm($id);
		$result['commentaires'] = $this->model->getCommentairesByFilm($id);
		
		echo $template->render(["result" => $result, "admin" => $admin]);
	}

	###################################################
	#### FORMULAIRE D'AJOUT D'UN NOUVEAU FILM #########
	###################################################

	public function add()  // Page : films/add
	{  
		global $admin;
		echo"--- $admin ---";
		$pageTwig = 'films/add.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$result['allgenres'] = $this->model->getAllGenres(); // Retourne la liste de tous les genres
		$result['allacteurs'] = $this->model->getAllActeurs(); // Retourne la liste de tous les acteurs du site
		$result['allrealisateurs'] = $this->model->getAllRealisateurs(); // Retourne la liste de tous les réalisateurs du site

		echo $template->render(["result" => $result, "admin" => $admin]);
	}

	###################################################
	#### TRAITEMENT DES DONNEES - INSERTION DU FILM ###
	###################################################

	public function insert() // Page : films/insert
	{  
		
		global $admin, $titre_f, $poster_f,$annee_f,$resume_f,$video_f;
		echo"--- $admin ---";
		$pageTwig = 'films/insert.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		// Traitement des données
		// -> ajout du film dans la table film
		
		$result = $this->model->insertFilm($_POST['titre'],$_FILES['poster'],$_POST['annee'],$_POST['resume'],$_POST['video']);
	
		// -> Ajout des acteurs dans table jouer
		// -> Ajout du réalisateur dans table realiser
		// -> Ajout des genres dans table appartient
		// -> On récupère l'id et Redirection vers films/show/#id

		echo $template->render(["result" => $result, "admin" => $admin]);
	}

	###################################################
	#### FORMULAIRE DE REEDITION D'UN FILM ############
	###################################################

	public function edition(int $id) // Page : films/edition/#id
	{
		global $admin;
		echo"--- $admin ---";
		$pageTwig = 'films/edition.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = $this->model->getOneExemple($id);

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

		echo $template->render(["result" => $result, "admin" => $admin]);
	}

	#########################################################
	#### TRAITEMENT DES DONNEES - MODIFICATIONS DU FILM #####
	#########################################################

	public function update(int $id) // Page : films/update/#id
	{  
		global $admin;
		$pageTwig = 'films/update.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		// Traitement des données
		// -> update du film dans la table film (titre, poster, annee, resume, video)
		// -> Ajout des nouveaux acteurs dans table jouer et suppression des acteurs qui ont pu etre déccoché
		// -> Ajout du nouveaux réalisateur dans table realiser et suppression des réalisateurs qui ont pu etre déccoché
		// -> Ajout des nouveaux genres dans table appartient et suppression des genres qui ont pu etre déccoché
		// -> Redirection vers films/show/#id

		echo $template->render([]);
	}

	###################################################
	#### SUPPRESSION D'UN FILM ########################
	###################################################

	public function suppression() // Page : films/suppression/#id
	{  
		global $admin;
		echo"--- $admin ---";
		$pageTwig = 'films/suppression.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		// Traitement des données
		// Suppression du film table films
		// Suppression du realisateur table realiser
		// Suppression des acteurs table jouer
		// Suppression des genres table genre
		// -> Redirection vers films

		echo $template->render([]);
	}
}