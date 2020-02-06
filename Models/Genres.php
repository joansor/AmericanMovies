<?php

class Genres extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function getOneExemple($id) {
        $req = $this->pdo->prepare('SELECT genre.*, films.poster_f, films.id_f FROM genre, films, appartient WHERE genre.id_g ='.$id.' AND genre.id_g = appartient.Artistes_id_a AND appartient.Films_id_f = films.id_f');
            $req->execute([$id]);
            return $req->fetch();
        }

    public function getAllGenres()
    {
        $sql = 'SELECT * FROM genre';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}
