<?php
require_once 'vendor/autoload.php';

$router = new Router($_GET['url']);



//liste de nos routes
//routes artistes
$router->get('/actors/show/:id', "Actors.show"); // Actors.show => Actors = ActorsController.php ; show = function show
$router->get('/films/show/:id', "Home.show");
$router->get("/actors", "Actors.list");
$router->get("/directors", "Directors.list");
//route films et page home

$router->get("/admin", "Admin.form");
$router->get("/", "Home.list");
//routes genres
$router->get("/genres", "Genres.cloud");


$router->run();