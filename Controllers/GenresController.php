<?php

class GenresController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Genres();
    }

	#######################################################################
	####  FONCTION INDEX - ################################################
	#######################################################################

    public function index(int $id)
    {
        global $admin, $user, $section; // Superglobales
        
        $pageTwig = 'genres/list.html.twig'; // Chemin de la view
        $template = $this->twig->load($pageTwig); // Chargement de la view

        $genres = $this->model->getAllGenres(); // Retourne la liste de tous les genres
        $moviesByGenres = $this->model->getAllMoviesByGenres($id); // Retourne la liste des films qui appartienent à genre #id
        $listByGenres = $this->model->listByGenres($id); // Retourne la liste des genres sont relier a un film via la table appartient

        echo $template->render(["MoviesByGenres" => $moviesByGenres, "admin" => $admin, "user" => $user, "section" => $section,"listByGenres" => $listByGenres, "genres" => $genres]); // Affiche la view et passe les données en paramêtres
    }

	#######################################################################
	####  FONCTION CLOUD - ################################################
	#######################################################################

    public function cloud()
    {
        global $admin, $user, $section; // Superglobales

        $pageTwig = 'genres/genres.html.twig'; // Chemin de la view
        $template = $this->twig->load($pageTwig); // Chargement de la view

        $genre = $this->model->getAllGenres(); // Retourne la liste de tous les genres

        echo $template->render(["genre" => $genre, "admin" => $admin, "user" => $user, "section" => $section]); // Affiche la view et passe les données en paramêtres
    }



}