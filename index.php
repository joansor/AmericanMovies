<?php

	session_start();

	require_once 'vendor/autoload.php';
	include("functions.php");

	setlocale(LC_TIME, 'fr_FR.utf8','fra'); // Pour mettre les dates en français

	$baseUrl = "http://ToutUnCinema";

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

		//setcookie("toutuncinema","connected", time()+3600*24);
		
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
	#### ROUTES ####################################################
	################################################################

	if(isset($_GET['url']))
	{
		$router = new Router($_GET['url']);


		//routes artistes

		$router->get("/artistes/show/:id/:slug", "Artistes.show"); // Artists.show => Artists = ArtistsController.php ; show = function show(méthod)
		$router->get('/artistes/add', "Artistes.add");
		$router->get('/artistes/jouer', "Artistes.jouer");
		$router->post('/artistes/insert', "Artistes.insert");
		$router->get('/artistes/edition/:id', "Artistes.edition");
		$router->post('/artistes/update/:id', "Artistes.update");
		$router->get('/artistes/suppression/:id', "Artistes.suppression");
		$router->get("/artistes/:metier/:p", "Artistes.listing");
		$router->get("/artistes/:metier", "Artistes.listing");
		$router->get("/artistes", "Artistes.listing");

	//	$router->get("/artists&search=:search", "Artists.index");


	//routes films
		$router->get('/films/show/:id/:slug', "Films.show");
		$router->get('/films/show/:id', "Films.show");
		$router->get('/films/add', "Films.add");
		$router->post('/films/insert', "Films.insert");
		$router->get('/films/edition/:id', "Films.edition");
		$router->post('/films/update/:id', "Films.update");
		$router->get('/films/suppression/:id', "Films.suppression");
		$router->get('/films/addgenre', "Films.addGenreFormulaire");
		$router->post('/films/insertgenre', "Films.insertGenre");
		$router->get('/films/editiongenre/:id', "Films.editGenreFormulaire");
		$router->post('/films/updategenre/:id', "Films.updateGenre");
		$router->get('/films/suppressiongenre/:id', "Films.deleteGenre");
		$router->get("/films/:p", "Films.listing");
		$router->get("/films", "Films.listing");
		$router->get("/", "Films.listing");
		$router->get("/films/:genre", "Films.listing");
		$router->get("/films/:genre/:p", "Films.listing");
	

		//routes genres
		$router->get('/genres/add', "Genres.add");
		$router->post('/genres/insert', "Genres.insert");
		$router->get('/genres/edition/:id', "Genres.edit");
		$router->post('/genres/update/:id', "Genres.update");
		$router->get('/genres/suppression:id', "Genres.delete");

		//routes commentaires
		
		$router->post('/comments/insert', "Comments.insert");
		$router->get('/comments/delete/:id', "Comments.delete");


		//route vote like commentaire
	
		
		$router->get('/vote/:idcom/:iduser/:vote', "CommentsVotes.updateVote");


		//routes user
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

