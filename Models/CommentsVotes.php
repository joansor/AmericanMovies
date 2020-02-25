<?php

class CommentsVotes extends Model
{
	#######################################################################################################
	#### CONSTRUCTEUR CONNEXION BDD #######################################################################
	#######################################################################################################

	public function __construct()
	{
		$this->pdo = parent::getPdo();
    }

	######################################################################################################
	##### VOTES J'AIME/J'AIME PAS UN COMMENTAIRE #########################################################
	######################################################################################################

	##########################################################################################
	##### RETOURNE LE NOMBRE VOTE POSITIF OU NEGATIF SUR UN COMMENTAIRE ######################
	##########################################################################################

	public function getNbVotesByCom($idcom, $sens)
	{
		if($sens == "positif")  $sens = "1"; else $sens = "-1";

		$sql = "SELECT COUNT(*) FROM commentaires_votes WHERE id_commentaire = $idcom AND vote = $sens";
		$req = $this->pdo->prepare($sql);
		$req->execute();

		return $req->fetch();
	}

	##########################################################################################
	##### RETOURNE L'#ID DU VOTE SI L'UTILISATEUR A DEJA VOTE SUR CE COMMENTAIRE #############
	##########################################################################################

	public function getUserVoteThisCom($idcom, $iduser)
	{
		$sql = "SELECT id_vote FROM commentaires_votes WHERE id_utilisateur = '". $iduser ."' AND id_commentaire = '". $idcom ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	################################################################
	##### INSERTION DU VOTE ########################################
	################################################################

	public function setInsertVote($idcom, $iduser, $vote)
	{
		$sql = "INSERT INTO commentaires_votes SET id_commentaire = :id_commentaire, id_utilisateur = :id_utilisateur, vote = :vote";
		$req = $this->pdo->prepare($sql);
		$req->execute([":id_commentaire" => $idcom, ":id_utilisateur" => $iduser, ":vote" => $vote]);
	}

	################################################################
	##### MODIFICATION DU VOTE #####################################
	################################################################

	public function setUpdateVote($id_vote, $vote)
	{
		$sql = "UPDATE commentaires_votes SET vote = :vote WHERE id_vote = :id_vote";
		$req = $this->pdo->prepare($sql);
		$req->execute([":id_vote" => $id_vote, ":vote" => $vote]);
	}

	################################################################
	##### SUPPRESSION DES VOTES SUR COMMENTAIRE #ID ################
	################################################################

	public function setDeleteVotesByCom($id_commentaire)
	{
		$sql = "DELETE FROM commentaires_votes WHERE id_commentaire = '". $id_commentaire ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}