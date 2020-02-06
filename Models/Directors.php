<?php

class Directors extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }
    public function getOneExemple($id) {
    $req = $this->pdo->prepare('SELECT realiser.* FROM realiser WHERE artistes.id_a ="'.$id.'"');
        $req->execute([$id]);
        return $req->fetch();
    }
    public function getAllDirectors()
    {
        $sql = 'SELECT * FROM directors';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}

