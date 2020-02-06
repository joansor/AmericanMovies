<?php

class ActorsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Actors();
    }

    ////function pour la route actors par la method list on recupere tous les elements de table artistes
    public function list()
    {
        $actors = $this->model->getAllActors();
        $pageTwig = 'actors/actors.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["actors" => $actors]); // mots clef désigné ici qui sera répris dans actors.html.twig
    }

    //function pour la route show par rapport a son id
    public function show(int $id) {
        $pageTwig = 'actors/show.html.twig';
        $template = $this->twig->load($pageTwig);
        $result = $this->model->getOneExemple($id);// $id element clef correspond a la table mysql artiste
        echo $template->render(["result" => $result]);
    }
}