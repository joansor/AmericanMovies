<?php

class Genres extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	############################################################################################################
	#### GENRES ################################################################################################
	############################################################################################################

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
}