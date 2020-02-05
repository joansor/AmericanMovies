<?php
require_once 'vendor/autoload.php';

$router = new Router($_GET['url']);



//liste de nos routes
$router->get('/actors/show/:id', "Actors.show"); // Actors.show => Actors = ActorsController.php ; show = function show
$router->get("/", "Home.show");
$router->get("/actors", "Actors.list");
$router->get("/genres", "Genres.show");
$router->get("/admin", "Admin.form");

$router->run();