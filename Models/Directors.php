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
        $sql = 'SELECT realiser.* FROM realiser';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}

