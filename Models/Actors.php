<?php

class Actors extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }
    public function getOneExemple($id) {
        $req = $this->pdo->prepare('SELECT artistes.id_a, artistes.nom_a, artistes.prenom_a, artistes.photo_a, artistes.biographie_a, artistes.date_de_naissance_a FROM artistes');
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

/*$actors = new Actors;
var_dump($actors->getAllActors());*/
