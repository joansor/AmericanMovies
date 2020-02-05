<?php

class Actors extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
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
