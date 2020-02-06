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
}