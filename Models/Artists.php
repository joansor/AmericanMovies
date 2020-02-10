<?php

class Artists extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	public function getOneExemple($id) 
	{
		$req = $this->pdo->prepare('SELECT artistes.* FROM artistes WHERE artistes.id_a ='.$id.'');
		$req->execute([$id]);
		return $req->fetch();
	}

	public function getAllActors()
	{
		$sql = 'SELECT DISTINCT id_a, nom_a, prenom_a, photo_a FROM artistes, jouer, films WHERE artistes.id_a = jouer.Artistes_id_a';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	public function getAllRealisators()
	{
		$sql = 'SELECT DISTINCT id_a, nom_a, prenom_a, photo_a FROM artistes, realiser, films WHERE artistes.id_a = realiser.Artistes_id_a';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	public function getFilmsByActor($id)
	{
		$sql = 'SELECT films.id_f, films.titre_f FROM films, jouer WHERE films.id_f = jouer.Films_id_f AND jouer.Artistes_id_a = '. $id .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	public function getFilmsByRealisator($id)
	{
		$sql = 'SELECT films.id_f, films.titre_f FROM films, realiser WHERE films.id_f = realiser.Films_id_f AND realiser.Artistes_id_a = '. $id .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}


	public function insertArtist($nom, $prenom, $date_de_naissance, $photo, $biographie)
	{
		$sql = "INSERT INTO artistes SET nom_a = :nom, prenom_a = :prenom, date_de_naissance_a = :date_de_naissance, photo_a = :photo, biographie_a = :biographie";
		$req = $this->pdo->prepare($sql);
		$req->execute([":nom" => $nom, ":prenom" => $prenom, ":date_de_naissance" => $date_de_naissance, ":photo" => $photo, ":biographie" => $biographie]);
	}

	public function updateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie)
	{
		$sql = "UPDATE artistes SET nom_a = :nom, prenom_a = :prenom, date_de_naissance_a = :date_de_naissance, photo_a = :photo, biographie_a = :biographie WHERE id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":nom" => $nom, ":prenom" => $prenom, ":date_de_naissance" => $date_de_naissance, ":photo" => $photo, ":biographie" => $biographie]);
	}

	public function deleteArtist($id)
	{
		$sql = 'DELETE FROM artistes WHERE id = '. $id .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}
}

