<?php

class SearchController extends Controller
{
	###################################################
	#### CONSTRUCTEUR #################################
	###################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new Search(); // Nouvel Object : Search
	}

	public function search()
	{
		global $search, $and;

		$pageTwig = 'films/search.html.twig'; // Chemin la View
		$template = $this->twig->load($pageTwig); // Chargement de la View
		$explode = explode(" ", $search);

		$sep = "";
		$and = "(";

		for($i = 0; $i < count($explode); $i++)
		{
			if($search == "") $myresultat = "titre_f != ''"; 
			else $myresultat = "titre_f LIKE '%" . $explode[$i] . "%'"; 

			$and .= $sep . "($myresultat)";
			if($i < count($explode) - 1)$sep = " OR "; 
		}

		$and .= ")";

		$result = $this->model->search($and);

		echo $template->render(["result" => $result]); // Affiche la view et passe les données en paramêtres

	}
}
