<?php

class Genres extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function getAllGenres()
    {
        $sql = 'SELECT * FROM genre';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}
