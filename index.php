<?php
require_once 'vendor/autoload.php';

$router = new Router($_GET['url']);



//liste de nos routes
//routes des id
$router->get('/actors/show/:id', "Actors.show"); // Actors.show => Actors = ActorsController.php ; show = function show(méthod)
$router->get('/films/show/:id', "Home.show");
$router->get('/directors/show/:id', "Directors.show");

//route des lists
$router->get("/actors", "Actors.list");
$router->get("/directors", "Directors.list");
$router->get("/admin", "Admin.form");
$router->get("/genres", "Genres.cloud");

//routes home
$router->get("/", "Home.list");


$router->run();