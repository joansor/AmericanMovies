<?php

class Films extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function search($search)
    {
        $sql = "SELECT * FROM films WHERE ". $search ."";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
  
    ################################################################
    ##### GETTERS ##################################################
    ################################################################

    public function getInfosByFilm($id) {
        $req = $this->pdo->prepare('SELECT films.* FROM films WHERE films.id_f = "'.$id.'"');
        $req->execute([$id]);
        return $req->fetch();
    }
    
    public function getAllFilms()
    {
        $sql = 'SELECT * FROM films';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function getGenresByFilm($id)
    {
        $sql = "SELECT id_g, genre_du_film 
        FROM 
        genre, films, appartient 
        WHERE 
        films.id_f = '". $id ."' AND 
        genre.id_g = appartient.Genre_id_g AND 
        appartient.Films_id_f = films.id_f";

        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function getActeursByFilm($id)
    {
        $sql = 
        "SELECT id_a, nom_a, prenom_a 
        FROM 
        artistes, films, jouer 
        WHERE 
        Films_id_f = '". $id ."' AND 
        artistes.id_a = jouer.Artistes_id_a AND 
        jouer.Films_id_f = films.id_f";

        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function getAllActeurs()
    {
        $sql = "SELECT * FROM artistes ORDER BY prenom_a ASC";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function getAllRealisateurs()
    {
        $sql = "SELECT * FROM artistes ORDER BY prenom_a ASC";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function getAllGenres()
    {
        $sql = "SELECT genre.*, films.* FROM genre, films, appartient WHERE genre.id_g = appartient.Genre_id_g AND appartient.Films_id_f = films.id_f";

        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }



    public function getRealisateursByFilm($id)
    {
        $sql = "SELECT id_a, nom_a, prenom_a 
        FROM 
        artistes, films, realiser 
        WHERE 
        Films_id_f = '". $id ."' AND 
        artistes.id_a = realiser.Artistes_id_a AND 
        realiser.Films_id_f = films.id_f";

        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function insertFilm($titre, $poster, $annee, $synopsis, $video)
    {
		$sql = "INSERT INTO films SET titre_f = :titre, poster_f = :poster, annee_f = :annee, video_f = :video, resume_f = :synopsis";
        $req = $this->pdo->prepare($sql);
 		$req->execute([":titre" => $titre, ":poster" => $poster, ":annee" => $annee, ":video" => $video, ":synopsis" => $synopsis]);

        return $this->pdo->lastInsertId();
    }
   
    ################################################################
    ##### SETTERS ##################################################
    ################################################################

    public function setUpdateFilms($id, $titre, $poster, $annee, $video, $synopsis)
    {
		$sql = "UPDATE films SET titre_f = :titre, poster_f = :poster, annee_f = :annee, video_f = :video, resume_f = :synopsis WHERE id_f = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":titre" => $titre, ":poster" => $poster, ":annee" => $annee, ":video" => $video, ":synopsis" => $synopsis]);
    }

    public function setInsertActeurByFilm($film, $acteur)
    {
		$sql = "INSERT INTO jouer SET Films_id_f = :film, Artistes_id_a = :acteur";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":acteur" => $acteur]);
    }

    public function setInsertRealisateurByFilm($film, $realisateur)
    {
		$sql = "INSERT INTO realiser SET Films_id_f = :film, Artistes_id_a = :realisateur";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":realisateur" => $realisateur]);
    }

    public function setInsertGenreByFilm($film, $genre)
    {
		$sql = "INSERT INTO appartient SET Films_id_f = :film, Genre_id_g = :genre";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":genre" => $genre]);
    }

    ################################################################
    ##### DELETE ###################################################
    ################################################################

    public function setDeleteFilm($film)
    {
		$sql = "DELETE FROM films WHERE id_f = $film";
        $req = $this->pdo->prepare($sql);
 		$req->execute();
    }

    public function setDeleteAllActeursByFilms($film)
    {
		$sql = 'DELETE FROM jouer WHERE Films_id_f = '. $film .'';
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }

    public function setDeleteRealisateursByFilms($film)
    {
		$sql = "DELETE FROM realiser WHERE Films_id_f = $film";
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }

    public function setDeleteAllGenresByFilms($film)
    {
		$sql = "DELETE FROM appartient WHERE Films_id_f = $film";
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }

    ################################################################
    ##### COMMENTAIRES #############################################
    ################################################################

    public function getCommentairesByFilm($film)
    {
        $sql = "SELECT commentaires.*, utilisateurs.username from commentaires, utilisateurs WHERE Films_id_f = '". $film ."' AND id_u = commentaires.Utilisateurs_id_u ORDER BY id DESC";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }

    public function setDeleteAllCommentairesByFilms($film)
    {
        $sql = "DELETE FROM commentaires WHERE Films_id_f = '". $film ."'";
        $req = $this->pdo->prepare($sql);
        $req->execute();
    }

    public function insert_commentaires_sql($film, $commentaire, $userid)
    {
		$sql = "INSERT INTO commentaires SET Films_id_f = :film, commentaire_c = :commentaire, Utilisateurs_id_u = :userid";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":commentaire" => $commentaire, ":userid" => $userid]);
    }

    public function delete_commentaires_sql($id)
    {
		$sql = "DELETE FROM commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
    }

    public function getFilmByCommentaire($id)
    {
		$sql = "SELECT Films_id_f FROM commentaires WHERE id = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
        return $req->fetch();
    }


}