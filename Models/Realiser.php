<?php

class Realiser extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
    } 

	############################################################################################################
	#### RELATION ENTRE FILMS ET REALISATEURS ##################################################################
    ############################################################################################################

	##########################################################################
	#### RETOURNE LA LISTE DES FILMS REALISER PAR ARTISTE #ID ################
	##########################################################################

	public function getFilmsRealiserArtiste($id)
	{
		$sql = "SELECT films.id_f, films.titre_f FROM films, realiser WHERE films.id_f = realiser.film_id_f AND realiser.artiste_id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
    }

	################################################################
	##### RETOURNE LA LISTE DE TOUS LES REALISATEURS DE FILM #ID ###
	################################################################

	public function getRealisateursByFilm($id)
	{
		$sql = "SELECT id_a, nom_a, prenom_a FROM artistes, realiser, films WHERE film_id_f = '". $id ."' AND artistes.id_a = realiser.artiste_id_a AND realiser.film_id_f = films.id_f";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
    }

	################################################################
	##### INSERT UNE RELATION ENTRE UN REALISATEUR ET UN FILM ######
	################################################################

	public function setInsertRealisateurByFilm($film, $realisateur)
	{
		$sql = "INSERT INTO realiser SET film_id_f = :film, artiste_id_a = :realisateur";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":realisateur" => $realisateur]);
	}

	##########################################################################
	#### INSERT FILM #ID ET ARTISTE #ID DANS LA TABLE REALISER ###############
	##########################################################################

	public function setInsertFilmRealiserByArtiste($film, $artiste)
	{
		$sql = "INSERT INTO realiser SET film_id_f = :film, artiste_id_a = :artiste";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":artiste" => $artiste]);
	}

	################################################################
	##### SUPPRIME TOUTES LES RELATIONS ENTRE REALISA. ET UN FILM ##
	################################################################

	public function setDeleteAllRealisateursByFilms($film)
	{
		$sql = "DELETE FROM realiser WHERE film_id_f = '". $film ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }

	##########################################################################
	#### SUPPRIME TOUS LES FILMS QUE L'ARTISTE A REALISER ####################
	##########################################################################

	public function setDeleteFilmsByRealisateur($id)
	{
		$sql = "DELETE FROM realiser WHERE artiste_id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}