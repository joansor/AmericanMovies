<?php

class Exercer extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	############################################################################################################
	#### RELATION ENTRE ARTISTES ET METIERS ####################################################################
    ############################################################################################################

	#########################################################################
	##### RETOURNE TOUS LES ARTISTES QUI EXERCENT LE METIER #ID #############
	#########################################################################

	public function getAllArtistesExercerMetier($metier, $limit, $p)
	{
		if (!$p) $p = 1;
		if (!$limit) $limit = "9999999";

        $start = $p * $limit - $limit;

		if($limit) $limite = " LIMIT $start, $limit"; else $limite = "";

		$sql = "SELECT artistes.*, metiers.id_c, metiers.nom_c FROM artistes, exercer, metiers WHERE metiers.id_c = '". $metier ."' AND metiers.id_c = exercer.metier_id_c AND artistes.id_a = exercer.artiste_id_a ORDER BY artistes.prenom_a ASC, artistes.nom_a ASC $limite";
		$req = $this->pdo->prepare($sql);
		$req->execute();

		return $req->fetchAll();
    }

	########################################################################
	#### RETOURNE TOUTES LES METIER EXERCER PAR ARTISTE #ID ################
	#### CATEGORIE ACTEUR OU REALISATEUR ###################################
	########################################################################

	public function getMetiersExercerArtiste($id)
	{
		$sql = "SELECT id_c, nom_c FROM artistes, exercer, metiers WHERE id_a = '". $id ."' AND id_a = artiste_id_a AND metier_id_c = id_c";
		$req = $this->pdo->prepare($sql);
		$req->execute();

		return $req->fetchAll();
    }

	##########################################################################
	#### INSERT ARTISTE #ID ET METIER #ID DANS LA TABLE EXERCER ##############
	#### Crée la relation entre un artiste et une catégorie d'artistes #######
	##########################################################################

	public function setInsertExercerArtiste($metier, $artiste)
	{
		$sql = "INSERT INTO exercer SET artiste_id_a = '" .$artiste ."', metier_id_c = '". $metier ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

    #############################################################################
	#### SUPPRIME TOUS LES METIERS EXERCER PAR L'ARTISTE : ACTEURS/REALISATEURS #
	#### Supprime les relations entre un artiste et les catégories d'artistes ###
	#############################################################################

	public function setDeleteExercerArtiste($id)
	{
		$sql = "DELETE FROM exercer WHERE artiste_id_a = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }
}