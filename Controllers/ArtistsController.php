<?php

class ArtistsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->model = new Artists();
	}

	////function pour la route artists par la method list on recupere tous les elements de table artistes
	public function index()
	{
		$pageTwig = 'artists/index.html.twig';
		$template = $this->twig->load($pageTwig);

		$actors = $this->model->getAllActors();
		$realisators = $this->model->getAllRealisators();

		if(!$actors['photo_a'] || !file_exists($actors['photo_a'])) $actors['photo_a'] = "assets/images/artistes/default.jpg";
		if(!$realisators['photo_a'] || !file_exists($realisators['photo_a'])) $realisators['photo_a'] = "assets/images/artistes/default.jpg";

		echo $template->render(["actors" => $actors,"realisators" => $realisators]); // mots clef désigné ici qui sera répris dans artists.html.twig
	}

	public function categorie($categorie)
	{
		$pageTwig = 'artists/categorie.html.twig';
		$template = $this->twig->load($pageTwig);

		$listes = "";
		if($categorie == "1") $listes = $this->model->getAllActors();
		if($categorie == "2") $listes = $this->model->getAllRealisators();

		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"];
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"];

		echo $template->render(["categorie" => $categorie, "listes" => $listes]); // mots clef désigné ici qui sera répris dans artists.html.twig
	}

	//function pour la route show par rapport a son id
	public function show(int $categorie, int $id) 
	{
		$pageTwig = 'artists/show.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = $this->model->getOneExemple($id);// $id element clef correspond a la table mysql artiste
		$result['films_jouer'] = $this->model->getFilmsByActor($id);
		$result['films_realiser'] = $this->model->getFilmsByRealisator($id);
		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"];
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"];

		if(!$result['biographie_a']) $result['biographie_a'] = "Infos à complêter";

		if(!$result['photo_a'] || !file_exists($result['photo_a'])) $result['photo_a'] = "assets/images/artistes/default.jpg";

		echo $template->render(["result" => $result, "categorie" => $categorie]);
	}

	// Formulaire pour creer un nouvel artiste
	public function add() 
	{
		$pageTwig = 'artists/add.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = "";// $id element clef correspond a la table mysql artiste

		echo $template->render(["result" => $result]);
	}

	// Insertion du nouvel artiste
	public function insert() 
	{
		global $baseUrl, $nom, $prenom, $date_de_naissance, $photo, $photo, $biographie;

		$nom = ucwords(strtolower($nom));
		$prenom = ucwords(strtolower($prenom));
		if(!$date_de_naissance) $date_de_naissance = "1970-01-01";

		$repertoirePhotosArtistes = "assets/images/artistes";
		$fichier = $_FILES['photo']['name'];

		if($fichier)
		{
			$img_name = $fichier;
			$ext = get_extension($img_name);

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png"))
			{
				$fichier = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($nom) ."-". strtolower($prenom) ."", $ext); // Renome le fichier

				move_uploaded_file($_FILES['photo']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!");
				@chmod ($fichier, 0644);

				$photo = redimentionne_image("". $repertoirePhotosArtistes ."", $fichier);
			}
			else
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n";
                redirect("javascript:history.back()", 5); 
			}
		}
		else
		{
			$photo = "";
		}

		$insert = $this->model->insertArtist($nom, $prenom, $date_de_naissance, $photo, $biographie); 
	
       redirect("../films", 0);
	}

	// Formulaire de réédition d'un artiste
	public function edition(int $id) 
	{
		$pageTwig = 'artists/edition.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = $this->model->getOneExemple($id); // $id element clef correspond a la table mysql artiste

		echo $template->render(["result" => $result]);
	}

	// Enregistrement des modifications
	public function update($id) 
	{
		global $baseUrl, $nom, $prenom, $date_de_naissance, $photo, $newphoto, $biographie;

		$nom = ucwords(strtolower($nom));
		$prenom = ucwords(strtolower($prenom));
		if(!$date_de_naissance) $date_de_naissance = "1970-01-01";

		$repertoirePhotosArtistes = "assets/images/artistes";
		$fichier = $_FILES['newphoto']['name'];

		if($fichier)
		{
			$img_name = $fichier;
			$ext = get_extension($img_name);

			if(($img_name) && ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png"))
			{
				if($photo && file_exists($photo)) unlink($photo); // Supprime la photo existante

				$fichier = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($nom) ."-". strtolower($prenom) ."", $ext); // Renome le fichier

				move_uploaded_file($_FILES['newphoto']['tmp_name'], $fichier) or die ("L'envoi du fichier a echoué !!!");
				@chmod ($fichier, 0644);

				$photo = redimentionne_image("". $repertoirePhotosArtistes ."", $fichier);
			}
			else
			{
				$message = "Erreur, l'image n'a pu etre chargée.</br></br>Seuls les formats .jpg, .png et .gif sont autorisés !!!</br>Veuillez patientez, vous allez être redirigé</div>\n";
                redirect("javascript:history.back()", 5); 
			}
		}
        else if($photo)
        {
            $ext = get_extension($photo);
			$newphoto = renome_image("". $repertoirePhotosArtistes ."", "". strtolower($nom) ."-". strtolower($prenom) ."", $ext); // Renome le fichier
            rename ($photo, $newphoto);
            $photo = $newphoto;
        }

		$update = $this->model->updateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie); 
        redirect("../../films", 1);
	}

	// Suppression d'un artiste
	public function suppression(int $id) 
	{
		$suppression = $this->model->deleteArtist($id);
		// Redirection vers artists
	}
}