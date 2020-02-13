<?php

class UsersController extends Controller
{
	## CONSTRUCTEUR
	public function __construct()
	{
		parent::__construct();
		$this->model = new Users();
	}

	## FONCTION MON COMPTE - partie a construire
	public function my_account()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/my_account.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Envoi des données à la View
	}

	## FONCTION INDEX - CHOIX DE SE CONNECTER OU DE SE CREER UN COMPTE
	public function index()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/index.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Envoi des données à la View
	}

	## FONCTION QUI TRAITE LES DONNEES SAISIENT LORS D'UNE NOUVELLE INSCRIPTION'
	public function register()
	{
		$pageTwig = 'traitement.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View

		if (isset($_POST["pass"]) && (isset($_POST["username"]) && (isset($_POST["email"]))))  // Si un pseudo et un mot de passe ont bien été saisi
		{
			$userverif = $this->model->getVerifUser($_POST["username"]); // Appelle la fonction pour verifier le pseudo
			$mailverif  = $this->model->getVerifEmail($_POST["email"]); //Appelle la fonction pour verifier le mail
			
			if($userverif ) // le pseudo existe deja
			{
				$message = "Ce pseudo est deja pris";
				
			}
			if($mailverif)// Le pseudo est dispo
			{

				$message = "Ce mail est deja pris";
			}else{

				$insertCompte = $this->model->registre($_POST["pass"], $_POST["username"], $_POST["email"]); // Appelle la fonction modele qui gère l'insertion des données et lui passe en parametres le pseudo et le mot de passe et mail
				$message = "Votre compte a bien été créé"; // Message à afficher
			}
			
			redirect("../Users", 5); // Redirection vers page users après 5s
		}
		else 
		{
			$message = "Veuillez remplir les champs. Patientez, vous allez être redirigé"; // Message à afficher
			redirect("javascript:history.back()", 5); // Redirection vers page users
		}

		echo $template->render(["message" => $message]); // Envoi des données à la View
	}

	## FONCTION QUI TRAITE LES DONNEES SAISIENT LORS DU FORMULAIRE DE CONNEXION
	public function traitement_connexion()
	{
		$pageTwig = 'users/traitement.html.twig'; // Chemin vers la View
		$template = $this->twig->load($pageTwig); // Chargement de la view

		$userInfo = $this->model->connect($_POST["uname"]); // Appelle la fonction connect() et passe le pseudo saisi en parametre

		if ($userInfo) // Si $userInfo retoune une valeur, alors l'utilisateur existe dans la BDD
		{
			if (password_verify($_POST["psw"], $userInfo["password"])) // Si le password saisi = password bdd, alors la connexion est réussi
			{
				$message = "Connexion En cours ..."; // Message à afficher
				$usertype = $userInfo["type_user"]; // Type d'utilisateur (user/admin)
				$_SESSION["connected"]=true; // Création d'une variable admin, pour resté connecté sur toutes les pages
				$_SESSION["user"] = ["userid" => $userInfo['id_u'], "username" => ucwords(strtolower($userInfo['username'])), "usertype" => $userInfo['type_user'], "usermail" => $userInfo['email']];
				redirect("http://localhost/AmericanMovies/films", 1); // Redirection vers page films
			} 
			else // le mot de passe est faux
			{
				$message = "Mot de passe ne correspond pas. Merci de patientez, vous allez être redirigé"; // Message à afficher
				$_SESSION["connected"] = false; // Variable admin false = l'utilisateur reste non connecté
				$_SESSION["user"] = ""; // Variable utilisateur = rien
				redirect("javascript:history.back()", 5); // Redirection vers la page du formulaire de connexion
			}
		} 
		else // Sinon, l'username saisi n'existe pas dans la BDD
		{
			$message = "L'utilisateur « ". $_POST["psw"] ." » n'existe pas dans notre base de données"; // Message à afficher
			$_SESSION["connected"] = false; // Variable admin false = l'utilisateur reste non connecté
			$_SESSION["user"] = ""; // Variable utilisateur = rien
			redirect("javascript:history.back()", 5); // Redirection vers la page du formulaire de connexion
		}

		echo $template->render(["message" => $message]); // Envoi les données à la vue
	}

	## FONCTION LOGOUT - DECONNEXION DE L'UTILISATEUR
	public function logout()
	{
		$_SESSION["connected"] = false; // Variable admin false = l'utilisateur n'est plus connecté
		$_SESSION["user"] = ""; // Variable utilisateur = rien
		session_destroy(); // Détruit la session
		redirect("http://localhost/AmericanMovies/Films", 0); // Redirection vers films
	}
}
