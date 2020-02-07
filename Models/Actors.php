<?php

class Actors extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }
    public function getOneExemple($id) {
    $req = $this->pdo->prepare('SELECT artistes.*, films.* FROM artistes, films, jouer WHERE artistes.id_a ='.$id.' AND artistes.id_a = jouer.Artistes_id_a AND jouer.Films_id_f = films.id_f');
        $req->execute([$id]);
        return $req->fetch();
    }
    public function getAllActors()
    {
        $sql = 'SELECT artistes.*, jouer.*, films.* FROM artistes, jouer, films WHERE artistes.id_a = jouer.Artistes_id_a AND jouer.Films_id_f = films.id_f';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
}
