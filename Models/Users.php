<?php

class Users extends Model
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

    public function registre($password, $username,$mail)
    {
		$password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateurs(type_user, username, password, email) VALUES ('user','". $username ."','". $password ."','". $mail ."')";
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetch();
    }
    
    public function getVerifUser($pseudo)
    {
        $sql = "SELECT * FROM utilisateurs WHERE username = :username ";
        $req = $this->pdo->prepare($sql);
		$req->execute([":username" => $pseudo]);
        return $req->fetch();
    }

    public function getVerifEmail($mailverif)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email ";
        $req = $this->pdo->prepare($sql);
		$req->execute([":email" => $mailverif]);
        return $req->fetch();
    }
}