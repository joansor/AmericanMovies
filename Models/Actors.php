<?php

class Actors extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }
    public function getOneExemple($id) {
    $req = $this->pdo->prepare('SELECT artistes.* FROM artistes WHERE artistes.id_a ="'.$id.'"');
        $req->execute([$id]);
        return $req->fetch();
    }
    public function getAllActors()
    {
        $sql = 'SELECT * FROM artistes';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}

