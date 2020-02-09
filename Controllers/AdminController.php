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

    // debut de fonction pour se connecter
    public function log()
    {
        if ($_POST["uname"] === "admin" && $_POST["psw"] ==="admin" ) {
            session_start();
            $_SESSION['type_user'] = "admin";
            header('Location: http://localhost/AmericanMovies/admin/dashboard');
        }
        if ($userInfo = $this->model->connect($_POST["uname"])) {
            //Le username existe dans la BDD

            if (password_verify($_POST["psw"], $userInfo["password"])){
                //Le username et le mot de passe correspondent, tout est ok
                // session_start();
                // $_SESSION['type'] = "user";
                header('Location: http://localhost/AmericanMovies/');
                echo "Bienvenue";
                
            } else {
                //Le username existe mais le mot de passe est faux
                echo "Mot de passe faux";
            }
        } else {
            //Le username n'existe pas dans la BDD
            echo"**************************************";
        }
    } //  fin function pour se connecter


    //debut de function enregistrer le donnée du form
    public function register()
    {
        
        if ($this->model->connect($_POST["register"])) {

            if (isset($_POST["pass"]) && (isset($_POST["username"]))) {
                session_start();
                $_SESSION['type_user'];
                var_dump($_POST["pass"]);
                var_dump($_POST["username"]);
                echo "enregister";
               
                header('Location: http://localhost/AmericanMovies/admin/dashboard');
            } else {

                echo "Veuillez remplir les champs";
            }
        }
    }
}
