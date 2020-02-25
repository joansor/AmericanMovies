<?php

class GenresController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR ####################################################
	######################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Genres(); // Nouvel Object : Genres
    }

	#######################################################
	#### FORMULAIRE D'AJOUT D'UN GENRE ####################
	#######################################################

	public function add() // Page : genres/add/#id
	{
		global $admin, $user;

		if($admin)
		{
			$pageTwig = 'genres/add.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
	}

	#######################################################
	#### TRAITEMENT DES DONNES - INSERTION DU GENRE #######
	#######################################################

	public function insert() // Page : genres/insert
	{
		global $baseUrl, $admin, $user, $titre;
	
		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$insertGenre = $this->model->setInsertGenre($titre); // insert le genre dans la bdd

			$message = "Genre inséré avec succès"; // Mesage à afficher
		
			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("". $baseUrl ."/films", 1); // -> Redirection vers films
		}
	}

	#######################################################
	#### FORMULAIRE D'EDITION D'UN GENRE ##################
	#######################################################

	public function edit($id) // Page : genres/edit/#id
	{ 
		global $admin, $user;
	
		if($admin)
		{
			$pageTwig = 'genres/edition.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$result = $this->model->getGenre($id); // Retourne les infos du genre
		
			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
	}

	#######################################################
	#### TRAITEMENT DES DONNES - REEDITION D'UN GENRE #####
	#######################################################

	public function update($id) // Page : genres/update/#id
	{
		global $baseUrl, $admin, $user, $titre;

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$updateGenre = $this->model->setUpdateGenre($id, trim($titre)); // Modifie les infos du genre dans la bdd

			$message = "Genre modifié avec succès"; // Mesage à afficher
		
			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("". $baseUrl ."/films", 1); // -> Redirection vers films
		}
	}

	#######################################################
	#### SUPPRRESSION D'UN DU GENRE #######################
	#######################################################

	public function delete($id) // Page : films/add
	{
		global $baseUrl, $admin, $user;

		if($admin)
		{
			$pageTwig = 'traitement.html.twig'; // Chemin la View
			$template = $this->twig->load($pageTwig); // Chargement de la View

			$instanceAppartient = new Appartient();
			$deleteFilmsAppartientGenre = $instanceAppartient->setDeleteAllFilmsAppartientGenre($id); // Supprime de la table appartient tous les film qui ont pour genre -> genre #id
			$deleteGenre = $this->model->setDeleteGenre($id); // Supprime le genre de la bdd

			$message = "Genre supprimé avec succès"; // Mesage à afficher

			echo $template->render(["url" => $_SERVER['REQUEST_URI'], "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
			redirect("". $baseUrl ."/films", 1); // -> Redirection vers films
		}
	}
}