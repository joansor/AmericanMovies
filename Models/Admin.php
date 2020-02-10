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

    public function registre($password, $username){

        $sql = "INSERT INTO utilisateurs(type_user, username, password) VALUES ('user','". $username ."','". $password ."')";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        var_dump($sql);
        return $req->fetch();

        }
}