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

	################################################################
	##### PAGE PRINCIPALE QUI LISTE LES FILMS AVEC RECHERCHE #######
	################################################################

	public function getAllFilms($search, $genre, $limit, $p)
	{
		if(!$search) $search = "titre_f != ''"; 

		if (!$p) $p = 1;
		if (!$limit) $limit = "9999999";

        $start = $p * $limit - $limit;

		if($limit) $limite = " LIMIT $start, $limit"; else $limite = "";

		if($genre) $sql = "SELECT films.*, genres.* FROM films, appartient, genres WHERE $search AND genres.id_g = '".$genre."' AND genres.id_g = appartient.Genre_id_g AND appartient.Films_id_f = films.id_f ORDER BY id_f DESC $limite";
		else $sql = "SELECT * FROM films WHERE $search ORDER BY id_f DESC ". $limite ."";
		$req = $this->pdo->prepare($sql);
		$req->execute();

		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LE NOMBRE DE FILMS TOTAL OU PAR GENRE ######################
	##########################################################################

	public function getNbFilms($search, $genre)
    {
		if(!$search) $search = "titre_f != ''"; 

		if($genre) $sql = "SELECT genres.*, films.* FROM genres, films, appartient WHERE $search AND genres.id_g = '".$genre."' AND genres.id_g = appartient.Genre_id_g AND appartient.film_id_f = films.id_f";
		else $sql = "SELECT * FROM films WHERE $search";
		$req = $this->pdo->prepare($sql);
		$req->execute();

        $count = $req->rowCount();
        return $count;
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
	##### INSERT UN FILM DANS LA BDD ###############################
	################################################################

	public function setInsertFilm($titre, $poster, $annee, $synopsis, $video, $duree)
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
	##### SUPPRIME FILM #ID ########################################
	################################################################

	public function setDeleteFilm($film)
	{
		$sql = "DELETE FROM films WHERE id_f = $film";
		$req = $this->pdo->prepare($sql);
 		$req->execute();
	}
}