<?php

class GenresController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Genres();
    }

    ////function pour la route genres par la method list on recupere tous les elements de la table appartient 

    public function list()
    {
        $MoviesByGenres = $this->model->getAllMoviesByGenres();
        $pageTwig = 'genres/list.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["MoviesByGenres" => $MoviesByGenres]); // mots clef désigné ici qui sera répris dans list.html.twig
    }


    public function cloud()
    {
        $genre = $this->model->getAllGenres();
        $pageTwig = 'genres/genres.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["genre" => $genre]);
    }
}