<?php

class ActorsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function show()
    {
        $pageTwig = 'actors.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render();
    }
}