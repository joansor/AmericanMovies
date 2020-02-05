<?php

class ActorsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Actors();
    }
    public function list()
    {
        $actors = $this->model->getAllActors();
        $pageTwig = 'actors.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["actors" => $actors]);
    }
}