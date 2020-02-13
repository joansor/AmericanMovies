<?php

session_start();
    require_once 'vendor/autoload.php';
    include("functions.php");

    setlocale(LC_TIME, 'fr_FR.utf8','fra');

	$superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

	foreach($superglobals as $superglobal)
	{ 
		foreach($superglobal as $key => $value) { if(!is_array($value)) { ${$key} = trim(rawurldecode($value)); /* echo "$key $value<br>"; */ }  else { ${$key} = $value; } }
	}

    global $admin, $logout;



    if(!empty($_SESSION["user"]))
    {
        $user = $_SESSION["user"];

        if($user["usertype"] == "admin") $admin = true ; else $admin = false;

        // $user["userid"] = id de l'utilisateur  
        // $user["username"] = pseudo de l'utilisateur
        // $user["usertype"] = type d'utilisateur (admin/user)
        // $user["usermail"] = email de l'utilisateur
    } else $admin = false;

    if(isset($_SERVER['REDIRECT_QUERY_STRING']))
    {
        // Récupère la première partie de l'url (artists/1)
        $adresse = $_SERVER['REDIRECT_QUERY_STRING'];
        $explode = explode("/", $adresse);
        $section = $explode[0];
        $section = str_replace("url=", "", $section);
        $count = count($explode);

        // Récupère la première partie de l'url (artists/1)
        if($count > 1) $repertoire = $explode[1]; else $repertoire = "";

        // Récupère la deuxieme partie de l'url (artists/1)
        // $dossier = $_SERVER['REQUEST_URI'];
        // $dossier = explode("/", $dossier);
        // $repertoire = end($dossier);

        if($section == "artists" && ($repertoire == "1" | $repertoire == "2")) $section .= "/$repertoire";
    }

    if(isset($_GET['url']))
    {
        $router = new Router($_GET['url']);

        //liste de nos routes
        //deuxième niveau

        $router->get("/artists/:categorie/show/:id", "Artists.show"); // Artists.show => Artists = ArtistsController.php ; show = function show(méthod)
        $router->get('/artists/add', "Artists.add");
        $router->post('/artists/insert', "Artists.insert");
        $router->get('/artists/edition/:id', "Artists.edition");
        $router->post('/artists/update/:id', "Artists.update");
        $router->get('/artists/suppression/:id', "Artists.suppression");
        $router->get("/artists/:categorie", "Artists.categorie");
        $router->get("/artists", "Artists.index");

        $router->get('/films/show/:id', "Films.show");
        $router->get('/films/add', "Films.add");
        $router->post('/films/insert', "Films.insert");
        $router->post('/films/insert_commentaire', "Films.insert_commentaire");
        $router->get('/films/edition/:id', "Films.edition");
        $router->post('/films/update/:id', "Films.update");
        $router->get('/films/delete_commentaire/:id', "Films.delete_commentaire");
        $router->get('/films/suppression/:id', "Films.suppression");
        $router->get("/films", "Films.listing");

        $router->get("/genres/list/:id", "Genres.list");
        $router->get("/genres", "Genres.cloud");

        $router->get('/users/my_account', 'Users.my_account');
        $router->post('/users/traitement_connexion', 'Users.traitement_connexion');
        $router->get('/users/logout', 'Users.logout');
        $router->post('/users/register', 'Users.register');
        $router->get("/users", "Users.index");


        //routes Films
        $router->get("/", "Films.listing");

        $router->run();
    }

