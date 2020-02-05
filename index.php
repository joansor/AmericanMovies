<?php
require_once 'vendor/autoload.php';

$router = new Router($_GET['url']);



//liste de nos routes
$router->get("/", "Home.show");
$router->get("/actors", "Actors.show");
$router->get("/genres", "Genres.show");

$router->run();