<?php
class NotFoundController extends Controller 
{
	######################################################################
	#### CONSTRUCTEUR ####################################################
	######################################################################

	public function __construct() 
	{
		$this->twig = parent::getTwig();
	}

	######################################################################
	#### FONCTION INDEX - BASE DU MODULE ################################
	######################################################################

	public function index() 
	{
		// header("HTTP/1.0 404 Not Found"); // Affiche page introuvable
		$pageTwig = '404.html.twig'; // Chemin de la view
		$template = $this->twig->load($pageTwig); // Chargement de la view
		echo $template->render(); // Affiche la view
	}
}