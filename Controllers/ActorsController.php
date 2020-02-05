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
        echo $template->render(["actors" => $actors]);// mots clef désigné ici qui sera répris dans actors.html.twig
    }
    public function show(int $id) {
        $pageTwig = 'show.html.twig';
        $template = $this->twig->load($pageTwig);
        $result = $this->model->getOneExemple($id);
        echo $template->render(["result" => $result]);
    }
}