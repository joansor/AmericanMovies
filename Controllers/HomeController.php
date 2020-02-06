<?php

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Films();
    }
    public function list()
    {
        $films = $this->model->getAllFilms();
        $pageTwig = 'index.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["films" => $films]);
    }

    
   //function pour la route films par rapport a son id
public function films(int $id) {
    $pageTwig = 'films.html.twig';
    $template = $this->twig->load($pageTwig);
    $result = $this->model->getOneExemple($id);
    echo $template->render(["result" => $result]);
}
}