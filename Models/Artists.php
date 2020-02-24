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
	#### SUPPRIME L'ARTISTE DE LA BDD ########################################
	##########################################################################

	public function setDeleteArtist($id)
	{
		$sql = "DELETE FROM artistes WHERE id_a = '$id'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	##########################################################################
	#### RETOURNE LE NOMBRE D'ARTISTES TOTAL OU PAR CATEGORIE ################
	##########################################################################

	public function getNbArtistesTotal($search, $categorie)
	{
		if($categorie) $sql = "SELECT * FROM artistes, artistes_categories, metier WHERE artistes_categories.id_c = '". $categorie ."' AND artistes_categories.id_c = metier.categories_id_c AND artistes.id_a = metier.artistes_id_a";
        else $sql = "SELECT * FROM artistes WHERE $search";

        $req = $this->pdo->prepare($sql);
        $req->execute();
		$count = $req->rowCount();
        return $count;
	}
	
	##########################################################################
	#### INSERT LE NOUVEL ARTISTE DANS LA BDD ################################
	##########################################################################

	public function setInsertArtist($nom, $prenom, $date_de_naissance, $photo, $biographie)
	{
		$sql = "INSERT INTO artistes SET nom_a = :nom, prenom_a = :prenom, date_de_naissance_a = :date_de_naissance, photo_a = :photo, biographie_a = :biographie";
		$req = $this->pdo->prepare($sql);
		$req->execute([":nom" => $nom, ":prenom" => $prenom, ":date_de_naissance" => $date_de_naissance, ":photo" => $photo, ":biographie" => $biographie]);

		return $this->pdo->lastInsertId();
	}

	##########################################################################
	#### MODIFIE LES INFORMATIONS DE L'ARTISTE DANS LA BDD ###################
	##########################################################################

	public function setUpdateArtist($id, $nom, $prenom, $date_de_naissance, $photo, $biographie)
	{
		$sql = "UPDATE artistes SET nom_a = :nom, prenom_a = :prenom, date_de_naissance_a = :date_de_naissance, photo_a = :photo, biographie_a = :biographie WHERE id_a = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":nom" => $nom, ":prenom" => $prenom, ":date_de_naissance" => $date_de_naissance, ":photo" => $photo, ":biographie" => $biographie]);
	}

	##########################################################################
	#### RETOURNE LES INFORMATIONS DE L'ARTISTE #ID ##########################
	##########################################################################

	public function getInfosByArtiste($id) 
	{
		// $req = $this->pdo->prepare('SELECT artistes.* FROM artistes WHERE artistes.id_a ='.$id.'');
		$req = $this->pdo->prepare('SELECT DISTINCT id_a, nom_a, prenom_a, photo_a, biographie_a, date_de_naissance_a, note_a, id_c FROM artistes, artistes_categories, metier WHERE artistes.id_a = '.$id.' AND metier.artistes_id_a = artistes.id_a');
		$req->execute([$id]);
		return $req->fetch();
	}

	##########################################################################
	#### RETOURNE LA LISTE DE TOUS LES ACTEURS DU SITE #######################
	##########################################################################

	public function getAllActors()
	{
		$sql = 'SELECT DISTINCT id_a, nom_a, prenom_a, photo_a, note_a FROM artistes, jouer, films WHERE artistes.id_a = jouer.Artistes_id_a';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LA LISTE DE TOUS LES REALISATEURS DU SITE ##################
	##########################################################################

	public function getAllRealisators()
	{
		$sql = 'SELECT DISTINCT id_a, nom_a, prenom_a, photo_a, note_a FROM artistes, realiser, films WHERE artistes.id_a = realiser.Artistes_id_a';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LA LISTE DES CATEGORIES D'ARTISTES (ACTEURS/REALISATEUR) ###
	##########################################################################

	public function getAllCategories()
	{
		$sql = 'SELECT * FROM artistes_categories';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LA LISTE DES CATEGORIES D'ARTISTES (ACTEURS/REALISATEUR) ###
	##########################################################################

	public function getCategoriesByArtiste($artiste)
	{
		$sql = 'SELECT categories_id_c FROM metier WHERE artistes_id_a = '. $artiste .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### INSERT CATEGORIE #ID ET ARTISTE #ID DANS LA TABLE METIER ############
	##########################################################################

	public function setInsertMetierByArtiste($categorie, $artiste)
	{
		$sql = 'INSERT INTO metier SET categories_id_c = '. $categorie .', artistes_id_a = ' .$artiste .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	##########################################################################
	#### RETOURNE LA LISTE DE TOUS LES FILMS DU SITE #########################
	##########################################################################

	public function getAllFilms()
	{
		$sql = 'SELECT * FROM films ORDER BY titre_f ASC';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LA LISTE DES FILMS REALISER PAR ARTISTE #ID ################
	##########################################################################

	public function getFilmsByRealisator($id)
	{
		$sql = 'SELECT films.id_f, films.titre_f FROM films, realiser WHERE films.id_f = realiser.Films_id_f AND realiser.Artistes_id_a = '. $id .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LA LISTE DES FILMS DANS LESQUELS ARTISTE #ID A JOUER #######
	##########################################################################

	public function getFilmsByActor($id)
	{
		$sql = 'SELECT films.id_f, films.titre_f FROM films, jouer WHERE films.id_f = jouer.Films_id_f AND jouer.Artistes_id_a = '. $id .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### INSERT FILM #ID ET ARTISTE #ID DANS LA TABLE REALISER ###############
	##########################################################################

	public function setInsertFilmRealiserByArtiste($film, $artiste)
	{
		$sql = "INSERT INTO realiser SET Films_id_f = :film, Artistes_id_a = :artiste";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":artiste" => $artiste]);
	}

	##########################################################################
	#### SUPPRIME TOUS LES METIERS DE L'ARTISTE : ACTEURS/REALISATEURS #######
	##########################################################################

	public function setDeleteMetierByArtiste($id)
	{
		$sql = "DELETE FROM metier WHERE artistes_id_a = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	##########################################################################
	#### SUPPRIME TOUS LES FILMS DANS LESQUELS L'ARTISTE A JOUER #############
	##########################################################################

	public function setDeleteFilmsByActeur($id)
	{
		$sql = "DELETE FROM jouer WHERE Artistes_id_a = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	##########################################################################
	#### SUPPRIME TOUS LES FILMS QUE L'ARTISTE A REALISER ####################
	##########################################################################

	public function setDeleteFilmsByRealisateur($id)
	{
		$sql = "DELETE FROM realiser WHERE Artistes_id_a = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	##########################################################################
	#### INSERT FILM #ID ET ARTISTE #ID DANS LA TABLE JOUER ##################
	##########################################################################

	public function setInsertFilmJouerByArtiste($film, $artiste)
	{
		$sql = "INSERT INTO jouer SET Films_id_f = :film, Artistes_id_a = :artiste";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":artiste" => $artiste]);
	}

	##########################################################################
	#### RETOURNE LA LISTE DES ARTISTES DANS LA CATEGORIE #ID ################
	##########################################################################

	public function getArtistesByCategorie($categorie, $limit, $p)
	{
		if (!$p) $p = 1;

        $start = $p * $limit - $limit;

		if($limit) $limite = " LIMIT $start, $limit"; else $limite = "";

		$sql = "SELECT DISTINCT artistes.id_a, artistes.prenom_a, artistes.nom_a, artistes.photo_a, artistes.note_a, artistes_categories.id_c, artistes_categories.nom_c FROM artistes, artistes_categories, metier WHERE artistes_categories.id_c = $categorie AND artistes_categories.id_c = metier.categories_id_c AND artistes.id_a = metier.artistes_id_a $limite ";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	##########################################################################
	#### RETOURNE LA LISTE DES METIERS D'UN ARTISTE (ACTEUR/REALISATEUR)######
	##########################################################################

	public function getMetierByArtiste($id)
	{
		$sql = "SELECT id_c, nom_c FROM artistes, artistes_categories, metier WHERE id_a = $id AND id_a = Artistes_id_a AND Categories_id_c = id_c";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}
	
	################################################################
	##### RETOURNE TOUS LES COMMENTAIRES DE ARTISTE #ID ############
	################################################################

	public function getCommentairesByArtiste($module, $artiste)
	{
		$sql = "SELECT commentaires.*, utilisateurs.username from commentaires, utilisateurs WHERE module = '". $module ."' AND idd = '". $artiste ."' AND id_u = commentaires.Utilisateurs_id_u ORDER BY id DESC";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	################################################################
	##### SUPPRIME TOUS LES COMMENTAIRES D'UN FILM #################
	################################################################

	public function setDeleteAllCommentairesByArtiste($artiste)
	{
		$sql = "DELETE FROM commentaires WHERE module = 'Artists' AND idd = '". $artiste ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	################################################################
	##### RETOURNE NOMBRE DE VOTES POSITIF OU NEGATIF SUR 1 COM ####
	################################################################

	public function getNbVotesByCom($idcom, $sens)
	{
		if($sens == "positif")  $sens = "1"; else $sens = "-1";

		$sql = "SELECT COUNT(*) FROM commentaires_votes WHERE id_commentaire = $idcom AND vote = $sens";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
    }
}