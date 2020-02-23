<?php

class Films extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	######################################################################################
	#### FILMS ###########################################################################
	######################################################################################

	function setNbFilmsTotal($search, $genre)
    {
		if(!$search) $search = "titre_f != ''"; 

		if($genre) $sql = "SELECT genre.*, films.* FROM genre, films, appartient WHERE $search AND genre.id_g = '".$genre."' AND genre.id_g = appartient.Genre_id_g AND appartient.Films_id_f = films.id_f";
		else $sql = "SELECT * FROM films WHERE $search";
		$req = $this->pdo->prepare($sql);
		$req->execute();

        $count = $req->rowCount();
        return $count;
    }

	################################################################
	##### PAGE PRINCIPALE QUI LISTE LES FILMS AVEC RECHERCHE #######
	################################################################

	public function listingFilms($search, $genre, $limit, $p)
	{
		if(!$search) $search = "titre_f != ''"; 

		if (!$p) $p = 1;

        $start = $p * $limit - $limit;

		if($limit) $limite = " LIMIT $start, $limit"; else $limite = "";


		if($genre) $sql = "SELECT genre.*, films.* FROM genre, films, appartient WHERE $search AND genre.id_g = '".$genre."' AND genre.id_g = appartient.Genre_id_g AND appartient.Films_id_f = films.id_f ORDER BY id_f DESC ". $limite ." ";
		else $sql = "SELECT * FROM films WHERE $search ORDER BY id_f DESC ". $limite ."";
		$req = $this->pdo->prepare($sql);
		$req->execute();

		return $req->fetchAll();
	}

	################################################################
	##### RETOURNE LES INFOS DE FILM #ID ###########################
	################################################################

	public function getInfosByFilm($id) 
	{
		$req = $this->pdo->prepare("SELECT films.* FROM films WHERE films.id_f = $id ");
		$req->execute([$id]);
		return $req->fetch();
	}

	################################################################
	##### RETOURNE LES INFOS DE FILM PRECEDENT #ID #################
	################################################################

	public function getInfosByFilmPrecedent($id) 
	{
		$req = $this->pdo->prepare("SELECT films.* FROM films WHERE films.id_f > '$id' ORDER BY id_f ASC LIMIT 0,1");
		$req->execute([$id]);
		return $req->fetch();
	}

	################################################################
	##### RETOURNE LES INFOS DE FILM SUIVANT #ID ###################
	################################################################

	public function getInfosByFilmSuivant($id) 
	{
		$req = $this->pdo->prepare("SELECT films.* FROM films WHERE films.id_f < '$id' ORDER BY id_f DESC LIMIT 0,1");
		$req->execute([$id]);
		return $req->fetch();
	}

	################################################################
	##### SUPPRIME FILM #ID ########################################
	################################################################

	public function setDeleteFilm($film)
	{
		$sql = "DELETE FROM films WHERE id_f = $film";
		$req = $this->pdo->prepare($sql);
 		$req->execute();
	}

	######################################################################################
	#### ARTISTES ########################################################################
	######################################################################################

	################################################################
	##### RETOURNE LA LISTE DE TOUS LES ARTISTES ###################
	################################################################

	public function getAllArtistes()
	{
		$sql = "SELECT DISTINCT id_a, nom_a, prenom_a, photo_a, note_a, metier.* FROM artistes, artistes_categories, metier WHERE artistes.id_a = metier.artistes_id_a AND artistes_categories.id_c = metier.categories_id_c group by id_a ORDER BY artistes.prenom_a ASC";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LES INFORMATIONS DE L'ARTISTE #ID ##########################
	##########################################################################

	public function getInfosByArtiste($id) 
	{
		$req = $this->pdo->prepare("SELECT DISTINCT id_a, nom_a, prenom_a, photo_a, biographie_a, date_de_naissance_a, note_a, id_c FROM artistes, artistes_categories, metier WHERE artistes.id_a = '.$id.' AND metier.artistes_id_a = artistes.id_a");
		$req->execute();
		return $req->fetch();
	}

	################################################################
	##### RETOURNE LA LISTE DE TOUS LES ACTEURS ####################
	################################################################

	public function getAllActeurs()
	{
		$sql = "SELECT DISTINCT id_a, artistes.* FROM artistes, artistes_categories, metier WHERE artistes.id_a = metier.artistes_id_a AND artistes_categories.id_c = metier.categories_id_c AND artistes_categories.id_c = 1 ORDER BY artistes.prenom_a ASC";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### RETOURNE LA LISTE DE TOUS LES REALISATEURS ###############
	################################################################

	public function getAllRealisateurs()
	{
		$sql = "SELECT DISTINCT id_a, artistes.* FROM artistes, artistes_categories, metier WHERE artistes.id_a = metier.artistes_id_a AND artistes_categories.id_c = metier.categories_id_c AND artistes_categories.id_c = 2 ORDER BY artistes.prenom_a ASC";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### RETOURNE LA LISTE DES ACTEURS AYANT JOUER DANS FILM #ID ##
	################################################################

	public function getActeursByFilm($id)
	{
		$sql = 
		"SELECT id_a, nom_a, prenom_a 
		FROM 
		artistes, films, jouer 
		WHERE 
		Films_id_f = '". $id ."' AND 
		artistes.id_a = jouer.Artistes_id_a AND 
		jouer.Films_id_f = films.id_f";

		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### RETOURNE LA LISTE DE TOUS LES REALISATEURS DE FILM #ID ###
	################################################################

	public function getRealisateursByFilm($id)
	{
		$sql = "SELECT id_a, nom_a, prenom_a 
		FROM 
		artistes, films, realiser 
		WHERE 
		Films_id_f = '". $id ."' AND 
		artistes.id_a = realiser.Artistes_id_a AND 
		realiser.Films_id_f = films.id_f";

		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	######################################################################################
	#### GENRES ##########################################################################
	######################################################################################

	################################################################
	##### RETOURNE LA LISTES DE TOUS LES GENRES ####################
	################################################################

	public function getAllGenres()
	{
		$sql = "SELECT * FROM genre ORDER BY genre_du_film";

		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### RETOURNE LES INFOS DE GENRE #ID ##########################
	################################################################

	public function getGenre($id)
	{
		$sql = "SELECT * FROM genre WHERE id_g = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	################################################################
	##### RETOURNE LA LISTE DE TOUS LES GENRES DE FILM #ID #########
	################################################################

	public function getGenresByFilm($id)
	{
		$sql = "SELECT id_g, genre_du_film 
		FROM 
		genre, films, appartient 
		WHERE 
		films.id_f = '". $id ."' AND 
		genre.id_g = appartient.Genre_id_g AND 
		appartient.Films_id_f = films.id_f";

		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### INSERT LA RELATION ENTRE UN GENRE ET UN FILM #############
	################################################################

	public function setInsertGenreByFilm($film, $genre)
	{
		$sql = "INSERT INTO appartient SET Films_id_f = :film, Genre_id_g = :genre";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":genre" => $genre]);
	}

	################################################################
	##### INSERT UN GENRE DANS LA BDD ##############################
	################################################################

	public function setInsertGenre($titre)
	{
		$titre = ucwords(strtolower($titre));

		$sql = "INSERT INTO genre SET genre_du_film = :titre";
		$req = $this->pdo->prepare($sql);
		$req->execute([":titre" => $titre]);
	}

	################################################################
	##### MODIFIE UN GENRE DANS LA BDD #############################
	################################################################

	public function setUpdateGenre($id, $titre)
	{
		$titre = ucwords(strtolower($titre));

		$sql = "UPDATE genre SET genre_du_film = :titre WHERE id_g = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":titre" => $titre]);
	}

	################################################################
	##### SUPPRIME UN GENRE DE LA BDD ##############################
	################################################################

	public function setDeleteGenre($id)
	{
		$sql = "DELETE FROM genre WHERE id_g = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	################################################################
	##### SUPPRIME TOUTES LES RELATIONS D'UN GENRE #################
	################################################################

	public function setDeleteAllFilmsByGenre($genre)
	{
		$sql = "DELETE FROM appartient WHERE Genre_id_g = '". $genre ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	################################################################
	##### SUPPRIME TOUS LES GENRES D'UN FILM #######################
	################################################################

	public function setDeleteAllGenresByFilms($film)
	{
		$sql = "DELETE FROM appartient WHERE Films_id_f = $film";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	################################################################
	##### INSERT UN FILM DANS LA BDD ###############################
	################################################################

	public function insertFilm($titre, $poster, $annee, $synopsis, $video, $duree)
	{
		$sql = "INSERT INTO films SET titre_f = :titre, poster_f = :poster, annee_f = :annee, video_f = :video, resume_f = :synopsis, duree_f = :duree";
		$req = $this->pdo->prepare($sql);
 		$req->execute([":titre" => $titre, ":poster" => $poster, ":annee" => $annee, ":video" => $video, ":synopsis" => $synopsis, ":duree"=> $duree]);

		return $this->pdo->lastInsertId();
	}

	################################################################
	##### MODIFIE UN FILM DANS LA BDD ##############################
	################################################################

	public function setUpdateFilms($id, $titre, $poster, $annee, $video, $synopsis, $duree)
	{
		$sql = "UPDATE films SET titre_f = :titre, poster_f = :poster, annee_f = :annee, video_f = :video, resume_f = :synopsis, duree_f = :duree WHERE id_f = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":titre" => $titre, ":poster" => $poster, ":annee" => $annee, ":video" => $video, ":synopsis" => $synopsis, ":duree"=> $duree]);
	}

	################################################################
	##### INSERT UNE RELATION ENTRE UN ACTEUR ET UN FILM ###########
	################################################################

	public function setInsertActeurByFilm($film, $acteur)
	{
		$sql = "INSERT INTO jouer SET Films_id_f = :film, Artistes_id_a = :acteur";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":acteur" => $acteur]);
	}

	################################################################
	##### SUPPRIME TOUTES LES RELATIONS ENTRE ACTEURS ET UN FILM ###
	################################################################

	public function setDeleteAllActeursByFilms($film)
	{
		$sql = 'DELETE FROM jouer WHERE Films_id_f = '. $film .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	################################################################
	##### INSERT UNE RELATION ENTRE UN REALISATEUR ET UN FILM ######
	################################################################

	public function setInsertRealisateurByFilm($film, $realisateur)
	{
		$sql = "INSERT INTO realiser SET Films_id_f = :film, Artistes_id_a = :realisateur";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":realisateur" => $realisateur]);
	}

	################################################################
	##### SUPPRIME TOUTES LES RELATIONS ENTRE REALISA. ET UN FILM ##
	################################################################

	public function setDeleteRealisateursByFilms($film)
	{
		$sql = "DELETE FROM realiser WHERE Films_id_f = $film";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	##########################################################################################
	##### COMMENTAIRES #######################################################################
	##########################################################################################

	################################################################
	##### LISTE TOUS LES COMMENTAIRES D'UN FILM ####################
	################################################################

	public function getCommentairesByFilm($module, $film)
	{
		$sql = "SELECT commentaires.*, utilisateurs.username from commentaires, utilisateurs WHERE module = '". $module ."' AND idd = '". $film ."' AND id_u = commentaires.Utilisateurs_id_u ORDER BY id DESC";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### SUPPRIME TOUS LES COMMENTAIRES D'UN FILM #################
	################################################################

	public function setDeleteAllCommentairesByFilms($film)
	{
		$sql = "DELETE FROM commentaires WHERE module = 'Films' AND idd = '". $film ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	public function getNbVotesByCom($idcom, $sens)
	{
		if($sens == "positif")  $sens = "1"; else $sens = "-1";

		$sql = "SELECT COUNT(*) FROM votes_commentaires WHERE id_commentaire = $idcom AND vote = $sens";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
    }

}