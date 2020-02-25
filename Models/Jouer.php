<?php

class Jouer extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
    }

	############################################################################################################
	#### RELATION ENTRE FILMS ET ACTEURS #######################################################################
    ############################################################################################################

	##########################################################################
	#### RETOURNE LA LISTE DES FILMS DANS LESQUELS ARTISTE #ID A JOUER #######
	##########################################################################

	public function getFilmsJouerArtiste($id)
	{
		$sql = "SELECT films.id_f, films.titre_f FROM films, jouer WHERE films.id_f = jouer.film_id_f AND jouer.artiste_id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### RETOURNE LA LISTE DES ACTEURS AYANT JOUER DANS FILM #ID ##
	################################################################

	public function getActeursJouerFilm($id)
	{
		$sql = 
		"SELECT id_a, nom_a, prenom_a FROM artistes, films, jouer WHERE film_id_f = '". $id ."' AND artistes.id_a = jouer.artiste_id_a AND jouer.film_id_f = films.id_f";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
    }

	################################################################
	##### INSERT UNE RELATION ENTRE UN ACTEUR ET UN FILM ###########
	################################################################

	public function setInsertActeurByFilm($film, $acteur)
	{
		$sql = "INSERT INTO jouer SET film_id_f = :film, artiste_id_a = :acteur";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":acteur" => $acteur]);
	}

	##########################################################################
	#### INSERT FILM #ID ET ARTISTE #ID DANS LA TABLE JOUER ##################
	##########################################################################

	public function setInsertFilmJouerByArtiste($film, $artiste)
	{
		$sql = "INSERT INTO jouer SET film_id_f = :film, artiste_id_a = :artiste";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":artiste" => $artiste]);
    }

	################################################################
	##### SUPPRIME TOUTES LES RELATIONS ENTRE ACTEURS ET UN FILM ###
	################################################################

	public function setDeleteAllActeursByFilms($film)
	{
		$sql = "DELETE FROM jouer WHERE film_id_f = '". $film ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }

	##########################################################################
	#### SUPPRIME TOUS LES FILMS DANS LESQUELS L'ARTISTE A JOUER #############
	##########################################################################

	public function setDeleteFilmsByActeur($id)
	{
		$sql = "DELETE FROM jouer WHERE artiste_id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}