<?php

class Genres extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function getAllMoviesByGenres($id) {
        $req = $this->pdo->prepare('SELECT genre.*, films.* FROM genre, films, appartient WHERE genre.id_g ='.$id.' AND genre.id_g = appartient.genre_id_g AND appartient.Films_id_f = films.id_f');
            $req->execute([$id]);
            return $req->fetch();
        }

    public function getAllGenres()
    {
        
       
    }
    
}

