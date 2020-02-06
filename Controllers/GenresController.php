<?php

class GenresController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Genres();
    }
    public function cloud()
    {
        $genre = $this->model->getAllGenres();
        $pageTwig = 'genres/genres.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["genres" => $genre]);
    }
}