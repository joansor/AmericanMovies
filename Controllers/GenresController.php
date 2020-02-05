<?php

class GenresController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function show()
    {
        $pageTwig = 'genres.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render();
    }
}