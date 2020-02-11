<?php

class GenresController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Genres();
    }

    ////function pour la route genres par la method list on recupere tous les elements de la table appartient 

    public function list(int $id)
    {
        global $admin;
        echo"--- $admin ---";
        $pageTwig = 'genres/list.html.twig';
        $template = $this->twig->load($pageTwig);
        $moviesByGenres = $this->model->getAllMoviesByGenres($id);
        echo $template->render(["MoviesByGenres" => $moviesByGenres, "admin" => $admin]); // mots clef désigné ici qui sera répris dans list.html.twig
    }


    public function cloud()
    {
        global $admin;
        echo"--- $admin ---";
        $genre = $this->model->getAllGenres();
        $pageTwig = 'genres/genres.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["genre" => $genre, "admin" => $admin]);
    }
}