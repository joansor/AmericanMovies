<?php

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Users();
    }

    public function form()
    {
        global $admin;
        $pageTwig = 'admin.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["admin" => $admin]);
    }

    public function dashboard()
    {
        global $admin;
        $pageTwig = 'dashboard.html.twig';
        $template = $this->twig->load($pageTwig);
        echo $template->render(["admin" => $admin]);
    }



    //debut de function enregistrer le donnée du form
    public function register()
    {
        $seconnect = $this->model->registre($_POST["pass"], $_POST["username"]);

        if (isset($_POST["pass"]) && (isset($_POST["username"]))) {

            session_start();
          
            var_dump($_SESSION['type_user']);
            echo "enregister";
            // header('Location: http://localhost/AmericanMovies/admin');
        } else {

            echo "Veuillez remplir les champs";
        }
    }



    // debut de fonction pour se connecter
    public function log()
    {
        if ($_POST["uname"] === "admin" && $_POST["psw"] === "admin") {

            $_SESSION['admin'] = "1";

            // $pageTwig = 'dashboard.html.twig';
            // $template = $this->twig->load($pageTwig);
            // echo $template->render();
            $_SESSION['type'] = "admin";
            header('Location: http://localhost/AmericanMovies/admin/dashboard');

            $_SESSION['admin'] = true;
            
        } elseif ($userInfo = $this->model->connect($_POST["uname"])) {
            //Le username existe dans la BDD
          
            if (password_verify($_POST["psw"], $userInfo["password"])) {


                //Le username et le mot de passe correspondent, tout est ok

                $_SESSION["admin"]= true;
                redirect("http://localhost/AmericanMovies/", 0);

            } else {
                //Le username existe mais le mot de passe est faux
                echo "Mot de passe faux";
                $_SESSION['admin'] = false;
            }
        } else {
            //Le username n'existe pas dans la BDD
            echo "**************************************";
            $_SESSION['admin'] = false;
        }
    } //  fin function pour se connecter



}
