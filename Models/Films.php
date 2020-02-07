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
        $sql = 'SELECT id_g, genre_du_film FROM genre, films, appartient WHERE films.id_f ='. $id .' AND genre.id_g = appartient.Genre_id_g AND appartient.Films_id_f = films.id_f';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
}