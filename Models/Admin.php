<?php

class Admin extends Model
{
    public function __construct()
    {
        $this->pdo = parent::getPdo();
    }

    public function connect($username)
    {
        $sql = 'SELECT * FROM utilisateurs WHERE username = ?';
        $req = $this->pdo->prepare($sql);
        $req->execute([$username]);
        return $req->fetch();
    }
    
}