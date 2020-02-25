<?php

class Artists extends Model
{
	#######################################################################################################
	#### CONSTRUCTEUR CONNEXION BDD #######################################################################
	#######################################################################################################

	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	################################################################
	##### PAGE QUI LISTE LES ARTISTES AVEC RECHERCHE ###############
	################################################################

	public function getAllArtists($search, $limit, $p)
	{
		if (!$p) $p = 1;

        $start = $p * $limit - $limit;

		if($limit) $limite = " LIMIT $start, $limit"; else $limite = "";

		$sql = "SELECT * FROM artistes WHERE $search $limite";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LE NOMBRE D'ARTISTES TOTAL OU PAR CATEGORIE ################
	##########################################################################

	public function getNbArtistes($search, $categorie)
	{
		if($categorie) $sql = "SELECT * FROM artistes, metiers, exercer WHERE metiers.id_c = '". $categorie ."' AND metiers.id_c = exercer.metier_id_c AND artistes.id_a = exercer.artiste_id_a";
        else $sql = "SELECT * FROM artistes WHERE $search";

        $req = $this->pdo->prepare($sql);
        $req->execute();
		$count = $req->rowCount();
        return $count;
	}

	##########################################################################
	#### RETOURNE LES INFORMATIONS DE L'ARTISTE #ID ##########################
	##########################################################################

	public function getInfosByArtiste($id) 
	{
		$req = $this->pdo->prepare('SELECT DISTINCT id_a, nom_a, prenom_a, photo_a, biographie_a, date_de_naissance_a, note_a, id_c FROM artistes, metiers, exercer WHERE artistes.id_a = '.$id.' AND exercer.artiste_id_a = artistes.id_a');
		$req->execute([$id]);
		return $req->fetch();
	}

	##########################################################################
	#### INSERT LE NOUVEL ARTISTE DANS LA BDD ################################
	##########################################################################

	public function setInsertArtiste($nom, $prenom, $date_de_naissance, $photo, $biographie)
	{
		$sql = "INSERT INTO artistes SET nom_a = :nom, prenom_a = :prenom, date_de_naissance_a = :date_de_naissance, photo_a = :photo, biographie_a = :biographie";
		$req = $this->pdo->prepare($sql);
		$req->execute([":nom" => $nom, ":prenom" => $prenom, ":date_de_naissance" => $date_de_naissance, ":photo" => $photo, ":biographie" => $biographie]);

		return $this->pdo->lastInsertId();
	}

	##########################################################################
	#### MODIFIE LES INFORMATIONS DE L'ARTISTE DANS LA BDD ###################
	##########################################################################

	public function setUpdateArtiste($id, $nom, $prenom, $date_de_naissance, $photo, $biographie)
	{
		$sql = "UPDATE artistes SET nom_a = :nom, prenom_a = :prenom, date_de_naissance_a = :date_de_naissance, photo_a = :photo, biographie_a = :biographie WHERE id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":nom" => $nom, ":prenom" => $prenom, ":date_de_naissance" => $date_de_naissance, ":photo" => $photo, ":biographie" => $biographie]);
	}

	##########################################################################
	#### SUPPRIME L'ARTISTE DE LA BDD ########################################
	##########################################################################

	public function setDeleteArtiste($id)
	{
		$sql = "DELETE FROM artistes WHERE id_a = '$id'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}