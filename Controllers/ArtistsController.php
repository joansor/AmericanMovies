<?php

class ArtistsController extends Controller
{
    ## CONSTRUCTEUR
	public function __construct()
	{
		parent::__construct();
		$this->model = new Artists();
	}

	## FONCTION INDEX : BASE DU MODULE => Choix vers listing items catégorie : Réalisateurs ou Acteurs
	public function index()
	{
        global $admin, $user;
		$pageTwig = 'artists/index.html.twig';
		$template = $this->twig->load($pageTwig);

		$actors = $this->model->getAllActors();
		$realisators = $this->model->getAllRealisators();

		echo $template->render(["admin" => $admin, "user" => $user, "actors" => $actors,"realisators" => $realisators]); // mots clef désigné ici qui sera répris dans artists.html.twig
	}

    ## FONCTION CATEGORIE : LISTE DE TOUS LES ARTISTES PAR CATEGORIE : ACTEURS OU REALISATEURS
	public function categorie($categorie)
	{
        global $admin, $user, $section;

		$pageTwig = 'artists/categorie.html.twig';
		$template = $this->twig->load($pageTwig);

		$listes = "";
		if($categorie == "1") $listes = $this->model->getAllActors();
		if($categorie == "2") $listes = $this->model->getAllRealisators();

		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"];
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"];

		echo $template->render(["categorie" => $categorie, "listes" => $listes, "admin" => $admin, "user" => $user, "section" => $section]); // mots clef désigné ici qui sera répris dans artists.html.twig
	}

    ## FONCTION SHOW : AFFICHAGE BIOGRAPHIE ARTISTE BY #ID
	public function show(int $categorie, int $id) 
	{
        global $admin, $user, $section;

		$pageTwig = 'artists/show.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = $this->model->getOneExemple($id);// $id element clef correspond a la table mysql artiste
		$result['films_jouer'] = $this->model->getFilmsByActor($id);
		$result['films_realiser'] = $this->model->getFilmsByRealisator($id);
		if($categorie == "1") $categorie = ["id" => "1", "nom" => "acteurs"];
		if($categorie == "2") $categorie = ["id" => "2", "nom" => "réalisateurs"];

		if(!$result['biographie_a']) $result['biographie_a'] = "Infos à complêter";

		if(!$result['photo_a'] || !file_exists($result['photo_a'])) $result['photo_a'] = "assets/images/artistes/default.jpg";

		echo $template->render(["result" => $result, "categorie" => $categorie, "admin" => $admin, "user" => $user, "section" => $section]);
	}

	// Formulaire pour creer un nouvel artiste
	public function add() 
	{
        global $admin, $user, $section;

		$pageTwig = 'artists/add.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = "";// $id element clef correspond a la table mysql artiste

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]);
	}

	// Insertion du nouvel artiste
	public function insert() 
	{
		global $baseUrl, $nom, $prenom, $date_de_naissance, $photo, $photo, $biographie, $admin;

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

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
        $message = "Artiste ajouté avec succès";

	    echo $template->render(["message" => $message]);  // Envoi les données à la View
        redirect("../films", 0);
	}

	// Formulaire de réédition d'un artiste
	public function edition(int $id) 
	{
        global $admin, $user, $section;

		$pageTwig = 'artists/edition.html.twig';
		$template = $this->twig->load($pageTwig);
		$result = $this->model->getOneExemple($id); // $id element clef correspond a la table mysql artiste

		echo $template->render(["result" => $result, "admin" => $admin, "user" => $user, "section" => $section]);
	}

	// Enregistrement des modifications
	public function update($id) 
	{
		global $nom, $prenom, $date_de_naissance, $photo, $newphoto, $biographie;

		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

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

        $message = "Artiste modifié avec succès";

		$update = $this->model->updateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie);
        echo $template->render(["message" => $message]);

        redirect("../../films", 1);
	}

	// Suppression d'un artiste
	public function suppression(int $id) 
	{
		global $admin;

 		$pageTwig = 'traitement.html.twig'; // Appelle la View
		$template = $this->twig->load($pageTwig); // Charge la page

		$suppression = $this->model->deleteArtist($id);

        $message = "Artiste supprimé avec succès";
        echo $template->render(["message" => $message]);
        redirect("../../films", 1); // Redirection vers films
	}
}