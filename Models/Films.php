<?php

class Films extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function getOneExemple($id) {
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
        $sql = "SELECT * FROM `genre` ORDER BY genre_du_film ASC";

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
    public function getCommentairesByFilm($id)
    {

    }

    public function setUpdateFilms($id, $titre, $poster, $annee, $video, $synopsis)
    {
		$sql = "UPDATE films SET titre_f = :titre, poster_f = :poster, annee_f = :annee, video_f = :video, resume_f = :synopsis WHERE id_f = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute([":titre" => $titre, ":poster" => $poster, ":annee" => $annee, ":video" => $video, ":synopsis" => $synopsis]);
    }

}