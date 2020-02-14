<?php

class Search extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function search($search)
    {
        $sql = "SELECT * FROM films WHERE ". $search ."";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }



}
