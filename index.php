<?php

session_start();
	require_once 'vendor/autoload.php';
	include("functions.php");

	setlocale(LC_TIME, 'fr_FR.utf8','fra'); // Pour mettre les dates en français

	$baseUrl = "http://localhost/AmericanMovies";

	################################################################
	##### SUPERGLOBALES ############################################
	################################################################

	$superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

	foreach($superglobals as $superglobal)
	{ 
		foreach($superglobal as $key => $value) { if(!is_array($value)) { ${$key} = trim(rawurldecode($value)); /* echo "$key $value<br>"; */ }  else { ${$key} = $value; } }
	}

	global $admin, $logout, $url;

	################################################################
	################################################################
	################################################################

	if(!empty($_SESSION["user"]))
	{
		$user = $_SESSION["user"];

		if($user["usertype"] == "admin") $admin = true ; else $admin = false;

		// $user["userid"] = id de l'utilisateur  
		// $user["username"] = pseudo de l'utilisateur
		// $user["usertype"] = type d'utilisateur (admin/user)
		// $user["usermail"] = email de l'utilisateur
	} 
	else 
	{
		$admin = false;
	}

	################################################################
	################################################################
	################################################################

	if(isset($_SERVER['REDIRECT_QUERY_STRING']))
	{
		// Récupère la première partie de l'url (artists/1)
		$adresse = $_SERVER['REDIRECT_QUERY_STRING'];
		$explode = explode("/", $adresse);
		$section = $explode[0];
		$section = str_replace("url=", "", $section);
		$count = count($explode);
		if($count > 1) $repertoire = $explode[1]; else $repertoire = "";
	}

	################################################################
	#### ROUTES ####################################################
	################################################################

	if(isset($_GET['url']))
	{
		$router = new Router($_GET['url']);

		$router->get("/artists/show/:id/:slug", "Artists.show"); // Artists.show => Artists = ArtistsController.php ; show = function show(méthod)
		$router->get('/artists/add', "Artists.add");
		$router->get('/artists/jouer', "Artists.jouer");
		$router->post('/artists/insert', "Artists.insert");
		$router->get('/artists/edition/:id', "Artists.edition");
		$router->post('/artists/update/:id', "Artists.update");
		$router->get('/artists/suppression/:id', "Artists.suppression");
		$router->get("/artists/:categorie/:p", "Artists.index");
		$router->get("/artists/:categorie", "Artists.index");
		$router->get("/artists", "Artists.index");

		$router->get('/films/show/:id/:slug', "Films.show");
		$router->get('/films/show/:id', "Films.show");
		$router->post('/films/insert_commentaire', "Films.insert_commentaire");
		$router->get('/films/add', "Films.add");
		$router->post('/films/insert', "Films.insert");
		$router->get('/films/edition/:id', "Films.edition");
		$router->post('/films/update/:id', "Films.update");
		$router->get('/films/suppression/:id', "Films.suppression");
		$router->get('/vote/:idcom/:iduser/:vote', "Films.updateVote");
		$router->get('/films/addgenre', "Films.addGenreFormulaire");
		$router->post('/films/insertgenre', "Films.insertGenre");
		$router->get('/films/editiongenre/:id', "Films.editGenreFormulaire");
		$router->post('/films/updategenre/:id', "Films.updateGenre");
		$router->get('/films/suppressiongenre/:id', "Films.deleteGenre");
		$router->get('/films/delete_commentaire/:id', "Films.delete_commentaire");

		$router->get("/films/:genre", "Films.listing");
		$router->get("/films/:genre/:p", "Films.listing");
		$router->get("/films", "Films.listing");

		$router->get("/", "Films.listing");

		$router->get('/contact', 'Users.formulaire_contact');
		$router->post('/contact/send', 'Users.traitement_formulaire_contact');

		$router->get('/privacy', 'Users.privacy');
		$router->get('/about', 'Users.about');
		$router->get('/users/my_account', 'Users.my_account');
		$router->get('/users/listing', 'Users.listing');	
		$router->post('/users/traitement_connexion', 'Users.traitement_connexion');
		$router->get('/users/logout', 'Users.logout');
		$router->get('/users/suppression/:id', 'Users.suppression');
		$router->get('/users/edition/:id', 'Users.edition');
		$router->post('/users/update/:id', 'Users.update');
		$router->post('/users/register', 'Users.register');
		$router->get('/users/newpassword', 'Users.formnewpassword');
		$router->post('/users/envoipass', 'Users.envoipass');
		$router->post('/users/updatechangepassword', 'Users.UpdateChangePassword');
		$router->get("/users", "Users.index");



		$router->run();
	}

