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
    public function registre(){
        $sql = 'INSERT INTO utilisateurs(id_u,type_user, username, password, email, create_time) VALUES (id_u,type_user,username,password,email,create_time)';
        $req = $this->pdo->prepare($sql);
        $req->execute([]);
        return $req->fetch();
        }
}