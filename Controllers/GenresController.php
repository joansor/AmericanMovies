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
        
        $pageTwig = 'genres/list.html.twig';
        $template = $this->twig->load($pageTwig);
        $moviesByGenres = $this->model->getAllMoviesByGenres($id);
        echo $template->render(["MoviesByGenres" => $moviesByGenres]); // mots clef désigné ici qui sera répris dans list.html.twig
    }


    public function cloud()
    {
        $genre = $this->model->listByGenres();
        $pageTwig = 'genres/list.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["genre" => $genre]);
    }
}