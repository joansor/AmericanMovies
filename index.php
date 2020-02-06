<?php
require_once 'vendor/autoload.php';

$router = new Router($_GET['url']);



//liste de nos routes
//deuxième niveau
$router->get('/actors/show/:id', "Actors.show"); // Actors.show => Actors = ActorsController.php ; show = function show
$router->get('/films/show/:id', "Home.show");
$router->get("/genres/list/:id", "Genres.list");

//premier niveau
$router->get("/actors", "Actors.list");
$router->get("/directors", "Directors.list");
$router->get("/admin", "Admin.form");
$router->get("/", "Home.list");
$router->get("/genres", "Genres.cloud");



$router->run();