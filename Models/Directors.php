<?php

class Directors extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }
    public function getOneExemple($id) {
    $req = $this->pdo->prepare('SELECT realiser.*, artiste.id_a FROM realiser, artiste WHERE artistes.id_a ="'.$id.'"');
        $req->execute([$id]);
        return $req->fetch();
    }
    public function getAllDirectors()
    {
        $sql = 'SELECT realiser.*, artistes.*, films.* FROM realiser, artistes, films WHERE artistes.id_a = realiser.Artistes_id_a AND realiser.Films_id_f = films.id_f';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}

