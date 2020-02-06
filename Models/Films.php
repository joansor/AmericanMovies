<?php

class Films extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }
    public function getOneExemple($id) {
        $req = $this->pdo->prepare('SELECT films.id_f, films.titre_f, films.annee_f, films.resume_f FROM films');
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