<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Admin();
    }
    
    public function form() {
        $result = $this->model->connect();
        $pageTwig = 'admin.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["result" => $result]);
        
    }
}