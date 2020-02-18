<?php

class Users extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
	}

	public function getAllUser()
	{
		$sql = 'SELECT * FROM utilisateurs';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
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

		$sql = "INSERT INTO utilisateurs SET type_user = 'user', username = :username, 	password = :password, email = :mail";
		$req = $this->pdo->prepare($sql);
		$req->execute([":username" => $username, ":mail" => $mail, ":password" => $password]);
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

	public function getUser($id)
	{
		$sql = "SELECT * FROM utilisateurs WHERE id_u = $id ";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	public function setDeleteUser($id)
	{
		$sql = "DELETE FROM utilisateurs WHERE id_u = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	public function setUpdateUser($id, $type_user, $username, $email)
	{
		$sql = "UPDATE utilisateurs SET type_user = :type_user , username = :username, email = :email WHERE id_u = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute([":type_user" => $type_user, ":username" => $username, ":email" => $email]);
	}

	public function setUpdatePassword($password)
	{
		$sql = "UPDATE utilisateurs SET password = :password";
		$req = $this->pdo->prepare($sql);
		$req->execute([":password" => $password]);
	}
}





