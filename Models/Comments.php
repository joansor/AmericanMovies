<?php

class Comments extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	public function getInfosByFilm($id) {
		$req = $this->pdo->prepare("SELECT films.* FROM films WHERE films.id_f = $id ");
		$req->execute([$id]);
		return $req->fetch();
	}

	##########################################################################
	#### RETOURNE LES INFORMATIONS DE NOTE MOYENNE #ID #######################
    ##########################################################################

	public function updateNoteMoyenne($module, $id, $note)
	{
        if($module == "Films") $sql = "UPDATE films SET note_f = $note WHERE id_f = $id";
        else if($module == "Artists") $sql = "UPDATE artistes SET note_a = $note WHERE id_a = $id";

		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	public function getUserVoteThisCom($idcom, $iduser)
	{
		$sql = "SELECT id_vote FROM commentaires_votes WHERE id_utilisateur = '". $iduser ."' AND id_commentaire = '". $idcom ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	public function setInsertVote($idcom, $iduser, $vote)
	{
		$sql = "INSERT INTO commentaires_votes SET id_commentaire = :id_commentaire, id_utilisateur = :id_utilisateur, vote = :vote";
		$req = $this->pdo->prepare($sql);
		$req->execute([":id_commentaire" => $idcom, ":id_utilisateur" => $iduser, ":vote" => $vote]);
	}

	public function setUpdateVote($id_vote, $vote)
	{
		$sql = "UPDATE commentaires_votes SET vote = :vote WHERE id_vote = :id_vote";
		$req = $this->pdo->prepare($sql);
		$req->execute([":id_vote" => $id_vote, ":vote" => $vote]);
	}

	public function getNbVotesByCom($idcom, $sens)
	{
		if($sens == "positif")  $sens = "1"; else $sens = "-1";

		$sql = "SELECT COUNT(*) FROM commentaires_votes WHERE id_commentaire = $idcom AND vote = $sens";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
    }

	######################################################################################################
	##### COMMENTAIRES ###################################################################################
	######################################################################################################

	##########################################################################
	#### Calcule la note moyenne d'un film ou d'un artiste ###################
    ##########################################################################

	public function calcul_moyenne($module, $idd)
	{
        $sql = "SELECT AVG(note) FROM commentaires WHERE module = '". $module ."' AND idd = $idd ";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	// public function getCommentairesByFilm($idd, $module)
	// {
	// 	$sql = "SELECT commentaires.*, utilisateurs.username from commentaires, utilisateurs WHERE idd = '". $idd ."' AND module = 'Films' AND module = 'Films' AND id_u = commentaires.Utilisateurs_id_u ORDER BY id DESC";
	// 	$req = $this->pdo->prepare($sql);
	// 	$req->execute();
	// 	return $req->fetchAll();
	// }

	public function getInfosByCommentaire($id)
	{
		$sql = "SELECT * from commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	public function getInfosByArtiste($id)
	{
		$sql = "SELECT id_a, nom_a, prenom_a FROM artistes WHERE id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}


	// public function setDeleteAllCommentairesByFilms($idd, $module)
	// {
	// 	$sql = "DELETE FROM commentaires WHERE idd = '". $idd ."' AND module = 'Films'";
	// 	$req = $this->pdo->prepare($sql);
	// 	$req->execute();
	// }

	public function insert_commentaires_sql($idd, $module, $commentaire, $userid, $rating)
	{
		$sql = "INSERT INTO commentaires SET idd = :idd, module = :module, commentaire_c = :commentaire, Utilisateurs_id_u = :userid, note = :note";
		$req = $this->pdo->prepare($sql);
		$req->execute([":idd" => $idd, ":module" => $module, ":commentaire" => $commentaire, ":userid" => $userid, ":note" => $rating]);
	}

	public function delete_commentaires_sql($id)
	{
		$sql = "DELETE FROM commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	public function getFilmByCommentaire($id)
	{
		$sql = "SELECT idd FROM commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}
}