<?php

class Comments extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	###################################################################################
	##### RETOURNE TOUS LES COMMENTAIRES D'UN FILM AVEC NOM DE L'AUTEUR ###############
	###################################################################################

	public function getCommentairesByModuleAndIdd($module, $idd)
	{
		$sql = "SELECT commentaires.*, utilisateurs.username from commentaires, utilisateurs WHERE module = '". $module ."' AND idd = '". $idd ."' AND id_u = commentaires.Utilisateurs_id_u ORDER BY id DESC";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	###################################################################################
	##### RETOURNE LES INFOS D'UN COMMENTAIRE D'APRES SON #ID #########################
	###################################################################################

	public function getInfosByCommentaire($id)
	{
		$sql = "SELECT * from commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	####################################################################################
	##### INSERT NEW COMMENTAIRE DANS LA BDD ###########################################
	####################################################################################

	public function setInsertCommentaire($idd, $module, $commentaire, $userid, $rating)
	{
		$sql = "INSERT INTO commentaires SET idd = :idd, module = :module, commentaire_c = :commentaire, Utilisateurs_id_u = :userid, note = :note";
		$req = $this->pdo->prepare($sql);
		$req->execute([":idd" => $idd, ":module" => $module, ":commentaire" => $commentaire, ":userid" => $userid, ":note" => $rating]);
	}

	####################################################################################
	##### SUPPRIME COMMENTAIRE #ID DE LA BDD ###########################################
	####################################################################################

	public function setDeleteCommentaire($id)
	{
		$sql = "DELETE FROM commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	######################################################################################
	##### SUPPRIME TOUS LES COMMENTAIRES D'UN FILM OU D'UN ARTISTE #######################
	######################################################################################

	public function setDeleteAllCommentairesByModuleAndIdd($module, $idd)
	{
		$sql = "DELETE FROM commentaires WHERE module = '". $module ."' AND idd = '". $idd ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	########################################################################################
	#### RETOURNE LA NOTE MOYENNE D'UN FILM OU D'UN ARTISTE ################################
    ########################################################################################

	public function getNoteMoyenne($module, $idd)
	{
        $sql = "SELECT AVG(note) FROM commentaires WHERE module = '". $module ."' AND idd = $idd ";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	##########################################################################################
	##### MODIFIE LA NOTE MOYENNE D'UN FILM OU D'UN ARTISTE ##################################
	##### CHAQUE COMMENTAIRE EST ACCOMPAGNER D'UNE NOTE ######################################
	##########################################################################################

	public function setUpdateNoteMoyenne($module, $id, $note)
	{
        if($module == "Films") $sql = "UPDATE films SET note_f = '". $note ."' WHERE id_f = '". $id ."'";
        else if($module == "Artists") $sql = "UPDATE artistes SET note_a = '". $note ."' WHERE id_a = '". $id ."'";

		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}