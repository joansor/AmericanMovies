<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Admin();
    }

    public function form()
    {
        $pageTwig = 'admin.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render();
    }

    public function log()
    {
        if ($userInfo = $this->model->connect($_POST["uname"])) {
            //Le username existe dans la BDD
            if (password_verify($_POST["psw"], $userInfo["password"])) {
                //Le username et le mot de passe correspondent, tout est ok
                echo "Bienvenue";
               if($_POST["uname"] === "admin"){
                    header('Location: http://www.google.com/');
                }
            } else {
                //Le username existe mais le mot de passe est faux
                echo "Mot de passe faux";
            }
        } else {
            //Le username n'existe pas dans la BDD
        }
    }
}
