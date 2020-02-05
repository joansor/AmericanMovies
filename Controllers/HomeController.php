<?php

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function show()
    {
        $pageTwig = 'index.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render();
    }
}
