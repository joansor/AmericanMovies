<?php
require_once 'vendor/autoload.php';

$router = new Router($_GET['url']);



//liste de nos routes
//routes actors
$router->get('/actors/show/:id', "Actors.show"); // Actors.show => Actors = ActorsController.php ; show = function show
$router->get("/actors", "Actors.list");
//route films et page home
$router->get('/films/:id', "Home.films");
$router->get("/admin", "Admin.form");
$router->get("/", "Home.list");
//routes genres
$router->get("/genres", "Genres.show");


$router->run();