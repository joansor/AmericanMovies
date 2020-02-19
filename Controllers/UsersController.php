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
	#### FONCTION LISTING DE TOUS LES UTILISATEURS ################################
	###############################################################################

	public function listing()
	{
		global $admin, $user; // Superglobales

		if($admin)
		{
			$pageTwig = 'users/listing.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // chargement de la View

			$result = $this->model->getAllUser(); // Retourne la liste de tous les utilisateurs

			echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
	}

	###############################################################################
	#### FONCTION MON COMPTE - partie a construire ################################
	###############################################################################

	public function my_account()
	{
		global $admin, $user; // Superglobales
		$pageTwig = 'users/my_account.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		$result = $this->model->getUser ($user['userid']); // Vérifie dans la bdd si le pseudo existe

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
	}

	#################################################################################
	#### FONCTION UpdateChangePassword - UTILISATEUR ENREGISTREMENT NEW MDP #########
	#################################################################################

	public function UpdateChangePassword()
	{
		global $baseUrl, $admin, $user, $oldpass, $newpass, $confirmpass; // Superglobales
		$pageTwig = 'traitement.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		$result = $this->model->getUser ($user['userid']); // Retourne les infos de l'utilisateur

		if($oldpass)
		{
			if($newpass)
			{
				if($confirmpass)
				{
					if (password_verify($oldpass, $result["password"])) 
					{
						if($newpass == $confirmpass) 
						{
							$mdp = password_hash($newpass, PASSWORD_DEFAULT); // Hashage du mot de passe
							$update = $this->model->setUpdatePassword($mdp); // Modifie les données dans la bdd
							$message = "Votre mot de passe a bien été modifié";
							$redirection = "". $baseUrl ."/users/my_account";
						}
						else
						{
							$message = "Le nouveau mot de passe et sa confirmation ne sont pas identique";
							$redirection = "javascript:history.back()";
						}
					}
					else
					{
						$message = "L'ancien mot de passe n'est pas correct";
						$redirection = "javascript:history.back()";
					}
				}
				else
				{
					$message = "Vous n'avez pas saisi la confirmation mot de passe";
					$redirection = "javascript:history.back()";	
				}
			}
			else
			{
				$message = "Vous n'avez pas saisi le nouveau mot de passe";
				$redirection = "javascript:history.back()";	
			}
		}
		else
		{
			$message = "Vous n'avez pas saisi l'ancien mot de passe";
			$redirection = "javascript:history.back()";		
		}

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "message" => $message]); // Affiche la view et passe les données en paramêtres
		redirect($redirection, 2); // Redirection vers page users après 5s
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
		global $username, $email, $pass, $pswRepeat;

		$pageTwig = 'traitement.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View

		$redirection = "javascript:history.back()";

		if ($email && $username && $pass) // Si un pseudo et un mot de passe ont bien été saisi
		{
			$userverif = $this->model->getVerifUser($username); // Vérifie dans la bdd si le pseudo existe déjà
			$mailverif = $this->model->getVerifEmail($email); // Vérifie dans la bdd si l'email existe déjà

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				$message = "L'adresse email '$email' est considérée comme invalide.";
			}
			else if(strpos($username, " ") !== false)
			{
				$message = "Votre pseudo ne peut pas contenir des espaces. Utilisez _ ou - comme séparateur. Merci"; // Message a afficher
			}
			else if (preg_match(",^[a-zA-Z0-9\ [\]._-]+$,", $username)) // Vérifie que la chaine « username » ne contient que des caractères autorisés
			{
				if (preg_match(",^[\ [\]._-]+$,", substr($username,0,1))) // Vérifie le premier caractère
				{
					$message = "Votre pseudo doit commencer par un chiffre ou une lettre"; // Message à afficher
				} 
				else if (preg_match(",^[\ [\]._-]+$,", substr($username, -1, 1))) // Vérifie le dernier caractère
				{
					$message = "Votre pseudo doit se terminer par un chiffre ou une lettre"; // Message à afficher
				}
				else if(strlen($username) < 3) // le pseudo est trop petit
				{
					$message = "Le pseudo est trop petit. Minimum : 3 caractères, merci !"; // Message a afficher
				}
				else if(strlen($username) > 20) // le pseudo est trop long
				{
					$message = "Le pseudo est trop grand. Maximum : 20 caractères, merci !"; // Message a afficher
				}	
				else if($userverif) // le pseudo existe deja
				{
					$message = "Ce pseudo est deja pris"; // Message a afficher
				}
				else if($mailverif) // Le mail existe déjà
				{
					$message = "Cet email est deja pris"; // Message a afficher
				}
				else if($pass != $pswRepeat) // Les mot de passe ne sont pas identique
				{
					$message = "Le mot de passe et la confirmation du mot de passe ne sont pas identique"; // Message a afficher
				}
				else // Sinon tout est ok, on peut enfin créer le compte ! oufff !!!!
				{
					$username = ucwords(strtolower($username));
					$insertCompte = $this->model->registre($pass, $username, $email); // Insertion de l'utilisateur dans la bdd
					$message = "Votre compte a bien été créé"; // Message à afficher

					$mail = $email; // destinataire
					$subject = "Bienvenue sur AmericanMovies"; // Sujet !
					$corps = "Bonjour $username et bienvenue. Votre compte a bien été créé sur AmericanMovies"; // Corps du mail
					$from = "From: AmericanMovies <no-reply@AmericanMovies.com>\nReply-To: no-reply@AmericanMovies.com"; // Entêtes header from ..

					$subject = @html_entity_decode($subject); // Encodage ..
					$corps = @html_entity_decode($corps); // Encodage ..
					$from = @html_entity_decode($from); // Encodage ..
					$mail = @html_entity_decode($mail); // Encodage ..

					// mail($mail, $subject, $corps, $from); // Envoi du mail

					$redirection = "../films";
				}
			}
			else
			{
				$message = "Votre pseudo contient des caractères interdit ! Merci de n'utiliser que des chiffres, lettre ou - ou _ comme séparateur !";
			}

			redirect($redirection, 5); // Redirection vers page users après 5s
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
		global $login, $password;

		if(strstr($login, "@"))
		{
			$typeIdentification = "email";
		}
		else
		{
			$typeIdentification = "username";
		}

		$pageTwig = 'traitement.html.twig'; // Chemin vers la View
		$template = $this->twig->load($pageTwig); // Chargement de la view

		$userInfo = $this->model->connect($typeIdentification, $login); // Vérifie dans la bdd si le pseudo existe

		if ($userInfo) // Si $userInfo retoune une valeur, alors l'utilisateur existe dans la bdd
		{
			if (password_verify($password, $userInfo["password"])) // Si le password saisi = password bdd, alors la connexion est réussi
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
			if(strstr($login, "@")) $message = "L'email « ". $login ." » n'existe pas dans notre base de données"; // Message à afficher
			else $message = "L'utilisateur « ". $login ." » n'existe pas dans notre base de données"; // Message à afficher

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

		if($user)
		{
			$pageTwig = 'users/formulaire.contact.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // chargement de la View
			echo $template->render(["admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
		else
		{
			$pageTwig = 'traitement.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // chargement de la View
			$message = "Vous devez être membre pour acceder au formulaire de contact";
			echo $template->render([ "message" => $message, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
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

	#################################################################################
	#### FONCTION formnewpassword - FORMULAIRE MOD DE PASSE PERDU ###################
	#################################################################################

	public function formnewpassword()
	{
		global $admin, $user, $type_user, $username, $email; // Superglobales

		$pageTwig = '/users/formnewpassword.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View
		$message = "Utilisateur modifié !"; // Message à afficher
		echo $template->render(["admin" => $admin, "user" => $user, "message" => $message]); // Affiche la view et passe les données en paramêtres
	}

	#################################################################################
	#### TRAITEMENT MOD DE PASSE PERDU - GENERE ET ENVOI NEW MDP PAR EMAIL ##########
	#################################################################################

	public function envoipass()
	{
		global $baseUrl, $admin, $user, $email; // Superglobales

		$pageTwig = 'traitement.html.twig'; // Chemin de la View
		$template = $this->twig->load($pageTwig); // chargement de la View

		$result  = $this->model->getVerifEmail($email);

		if($email == $result['email']) // Si l'email saisi existe dans la bdd
		{
			function GenereMotDePasse()
			{
				$chaine = 'azertyuiopqsdfghjklmwxcvbn123456789'; // Chaine de caractères pour générer le mdp
				$nb_car = 8; // Nombre de caractères qui compose le mdp

				$nb_lettres = strlen($chaine) - 1; // Longueur de la variable « chaine »
				$generation = ''; // On initialise la variable qui va contenir le mdp
				for($i=0; $i < $nb_car; $i++) // Boucle, nombre de tours = $nb_car, soit 8!
				{
					$pos = mt_rand(0, $nb_lettres); // mt_rand — Génère une valeur aléatoire issue de la variable « $chaine » parametres = (min, max)
					$car = $chaine[$pos]; // Incrémente variable intermediare
					$generation .= $car; // Composition mdp final
				}

				return $generation; // Retourne le mot de passe généré
			}

			$newMotDePasse = GenereMotDePasse(); // Appelle la fonction ci dessus qui génère un new mot de passe
			$mdp = password_hash($newMotDePasse, PASSWORD_DEFAULT); // Hashage du mot de passe

			$update = $this->model->setUpdatePassword($mdp); // Modifie les données dans la bdd

			$message = "Un email vient de vous être envoyé à : $email !"; // Message à afficher

			$mail = $email; // destinataire
			$subject = "Réinitialisation de votre mot de passe"; // Sujet !
			$corps = "Bonjour ". $result['username'] ."./n A votre demande, nous venons de reinitialiser votre mot de passe./n Votre nouveau mot de passe est : ". $newMotDePasse ."./nPour vous connecter, merci de cliquer le lien ci dessous :/n ". $baseUrl ."/users"; // Corps du mail
			$from = "From: AmericanMovies <no-reply@AmericanMovies.com>\nReply-To: no-reply@AmericanMovies.com"; // Entêtes header from ..

			$subject = @html_entity_decode($subject); // Encodage ..
			$corps = @html_entity_decode($corps); // Encodage ..
			$from = @html_entity_decode($from); // Encodage ..
			$mail = @html_entity_decode($mail); // Encodage ..

			// mail($mail, $subject, $corps, $from); // Envoi du mail

			$redirection = "". $baseUrl ."/films";
		}
		else // Sinon
		{
			$message = "L'email « $email » n'existe pas dans notre base de données !"; // Message à afficher
		}

		echo $template->render(["admin" => $admin, "user" => $user, "message" => $message]); // Affiche la view et passe les données en paramêtres
	}

	public function edition($id)
	{
		global $admin, $user; // Superglobales

		if($admin)
		{
			$pageTwig = 'users/edition.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // chargement de la View

			$result = $this->model->getUser($id); // Vérifie dans la bdd si le pseudo existe

			echo $template->render(["result" => $result, "admin" => $admin, "user" => $user]); // Affiche la view et passe les données en paramêtres
		}
	}

	public function update($id)
	{
		global $baseUrl, $admin, $user, $type_user, $username, $email; // Superglobales

		if($admin || $id == $user['userid'])
		{
			if(!$type_user && !$admin) $type_user = "user";
			else if(!$type_user && $admin) $type_user = "admin";	

			$pageTwig = 'traitement.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // chargement de la View

			$update = $this->model->setUpdateUser($id, $type_user, ucwords(strtolower($username)), $email); // Vérifie dans la bdd si le pseudo existe

			$message = "Informations modifiées !"; // Message à afficher
			echo $template->render(["admin" => $admin, "user" => $user, "message" => $message]); // Affiche la view et passe les données en paramêtres

			if(!$admin && $id != $user['userid']) redirect("". $baseUrl ."/users/listing", 1); // Redirection vers le listing des utilisatateurs
			else redirect("". $baseUrl ."/users/my_account", 1); // Redirection immédiate vers la page Mon Compte
		}
	}

	public function suppression($id)
	{
		global $baseUrl, $admin, $user; // Superglobales

		if( ($admin || $id = $user['userid']) && $id != "1") // Si admin, ou utilisateur du compte à supprimer et que ce n'est pas le compte admin que l'on cherche a supprimer!
		{
			$pageTwig = 'traitement.html.twig'; // Chemin de la View
			$template = $this->twig->load($pageTwig); // chargement de la View
			$delete = $this->model->setDeleteUser($id); // Vérifie dans la bdd si le pseudo existe
			$message = "Utilisateur Supprimé !"; // Message à afficher
			echo $template->render(["admin" => $admin, "user" => $user, "message" => $message]); // Affiche la view et passe les données en paramêtres
			if($admin) redirect("$baseUrl/users/listing", 2); // Redirection vers listing des utilisateurs après 2s
			else redirect("$baseUrl/films", 2); // Redirection vers films après 2s
		}
	}
}