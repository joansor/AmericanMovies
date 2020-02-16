<?php

class UsersController extends Controller
{
	###############################################################################
	#### CONSTRUCTEUR NOUVEL UTILISATEUR ##########################################
	###############################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Users(); // Nouvel Object : Films
	}

	###############################################################################
	#### FONCTION MON COMPTE - partie a construire ################################
	###############################################################################

	public function my_account()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/my_account.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
	}

	#################################################################################
	#### FONCTION INDEX - CHOIX DE SE CONNECTER OU DE SE CREER UN COMPTE ############
	#################################################################################

	public function index()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/index.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
	}

	##################################################################################
	#### FONCTION QUI TRAITE LES DONNEES SAISIENT LORS D'UNE NOUVELLE INSCRIPTION ####
	##################################################################################

	public function register()
	{
		$pageTwig = 'traitement.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View

		if (isset($_POST["pass"]) && (isset($_POST["username"]) && (isset($_POST["email"])))) // Si un pseudo et un mot de passe ont bien été saisi
		{
			$userverif = $this->model->getVerifUser($_POST["username"]); // Vérifie dans la bdd si le pseudo existe déjà
			$mailverif = $this->model->getVerifEmail($_POST["email"]); // Vérifie dans la bdd si l'email existe déjà

			if($userverif) // le pseudo existe deja
			{
				$message = "Ce pseudo est deja pris"; // Message a afficher
			}

			if($mailverif) // Le mail existe déjà
			{
				$message = "Cet email est deja pris"; // Message a afficher
			}
			else // Pseudo et Mail dispo, donc on peut enregistrer le nouvel utilisateur
			{
				$insertCompte = $this->model->registre($_POST["pass"], $_POST["username"], $_POST["email"]); // Insertion de l'utilisateur dans la bdd
				$message = "Votre compte a bien été créé"; // Message à afficher
			}

			redirect("../", 5); // Redirection vers page users après 5s
		}
		else 
		{
			$message = "Veuillez remplir les champs. Patientez, vous allez être redirigé"; // Message à afficher
			redirect("javascript:history.back()", 5); // Redirection vers page users
		}

		echo $template->render(["message" => $message]); // Affiche la view et passe les données en paramêtres
	}

	###############################################################################
	#### FONCTION QUI TRAITE LES DONNEES SAISIENT DANS FORMULAIRE DE CONNEXION ####
	###############################################################################

	public function traitement_connexion()
	{
		$pageTwig = 'traitement.html.twig'; // Chemin vers la View
		$template = $this->twig->load($pageTwig); // Chargement de la view

		$userInfo = $this->model->connect($_POST["uname"]); // Vérifie dans la bdd si le pseudo existe

		if ($userInfo) // Si $userInfo retoune une valeur, alors l'utilisateur existe dans la bdd
		{
			if (password_verify($_POST["psw"], $userInfo["password"])) // Si le password saisi = password bdd, alors la connexion est réussi
			{
				$message = "Connexion En cours ..."; // Message à afficher)
				$_SESSION["connected"]=true; // Création d'une variable connected pour resté connecté sur toutes les pages
				$_SESSION["user"] = ["userid" => $userInfo['id_u'], "username" => ucwords(strtolower($userInfo['username'])), "usertype" => $userInfo['type_user'], "usermail" => $userInfo['email']];
				redirect("../films", 1); // Redirection vers page films après 1s
			} 
			else // le mot de passe est faux
			{
				$message = "Mot de passe ne correspond pas. Merci de patientez, vous allez être redirigé"; // Message à afficher
				$_SESSION["connected"] = false; // Variable connected false = l'utilisateur reste non connecté
				$_SESSION["user"] = ""; // Variable utilisateur = rien
				redirect("javascript:history.back()", 5); // Redirection vers la page du formulaire de connexion après 5s
			}
		} 
		else // Sinon, l'username saisi n'existe pas dans la bdd
		{
			$message = "L'utilisateur « ". $_POST["psw"] ." » n'existe pas dans notre base de données"; // Message à afficher
			$_SESSION["connected"] = false; // Variable connected false = l'utilisateur reste non connecté
			$_SESSION["user"] = ""; // Variable utilisateur = rien
			redirect("javascript:history.back()", 5); // Redirection vers la page du formulaire de connexion après 5s
		}

		echo $template->render(["message" => $message]); // Affiche la view et passe les données en paramêtres
	}

	#################################################################################
	#### FONCTION LOGOUT - DECONNEXION DE L'UTILISATEUR #############################
	#################################################################################

	public function logout()
	{
		$_SESSION["connected"] = false; // Variable connected false = l'utilisateur n'est plus connecté
		$_SESSION["user"] = ""; // Variable utilisateur = rien
		session_destroy(); // Détruit la session
		redirect("../Films", 0); // Redirection immédiate vers films
	}

	public function formulaire_contact()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/formulaire.contact.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
	}

	public function privacy()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/privacy.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
	}

	public function about()
	{
		global $admin, $user; // Superglobales

		$pageTwig = 'users/about.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		echo $template->render(["admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
	}
}