<?php

class DirectorsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Directors();
    }

    ////function pour la route directors par la method list on recupere tous les elements de table directors
    public function list()
    {
        $directors = $this->model->getAllDirectors();
        $pageTwig = 'directors/directors.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["directors" => $directors]); // mots clef désigné ici qui sera répris dans directors.html.twig
    }

    //function pour la route show par rapport a son id
    public function show(int $id) {
        $pageTwig = 'directors/show.html.twig';
        $template = $this->twig->load($pageTwig);
        $result = $this->model->getOneExemple($id);
        echo $template->render(["result" => $result]);
    }
}