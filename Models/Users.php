<?php

class Users extends Model
{
	#########################################################################################################################
	#### CONSTRUCTEUR #######################################################################################################
	#########################################################################################################################

	public function __construct()
	{
		$this->pdo = parent::getPdo(); // Parent dans Model.php
	}

	#########################################################################################################################
	#### RETOURNE LA LISTE DE TOUS LES UTILISATEURS #########################################################################
	#########################################################################################################################

	public function getAllUsers()
	{
		$sql = "SELECT * FROM users";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	#########################################################################################################################
	#### RETOURNE LES INFOS DE L'UTILISATEUR #ID ############################################################################
	#########################################################################################################################

	public function getUser($id)
	{
		$sql = "SELECT * FROM users WHERE id_u = '". $id ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	#########################################################################################################################
	#### VERIFIE SI LE PSEUDO EXISTE DEJA DANS LA BDD #######################################################################
	#########################################################################################################################

	public function getVerifUser($username)
	{
		$sql = "SELECT * FROM users WHERE username = :username";
		$req = $this->pdo->prepare($sql);
		$req->execute([":username" => $username]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### VERIFIE SI LE L'EMAIL EXISTE DEJA DANS LA BDD #######################################################################
	#########################################################################################################################

	public function getVerifEmail($mailverif)
	{
		$sql = "SELECT * FROM users WHERE email = :email ";
		$req = $this->pdo->prepare($sql);
		$req->execute([":email" => $mailverif]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### TRAITEMENT CONNEXION UTILISATEUR ###################################################################################
	#########################################################################################################################

	public function getConnect($typeIdentification, $identifiant)
	{
		if($typeIdentification == "email") $sql = "SELECT * FROM users WHERE email = :identifiant";
		else $sql = 'SELECT * FROM users WHERE username = :identifiant';

		$req = $this->pdo->prepare($sql);
		$req->execute(["identifiant" => $identifiant]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### TRAITEMENT CREATION DE COMPTE - ENREGISTREMENT UTILISATEUR #########################################################
	#########################################################################################################################

	public function setRegistre($password, $username,$mail)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO users SET type_user = 'user', username = :username, 	password = :password, email = :mail";
		$req = $this->pdo->prepare($sql);
		$req->execute([":username" => $username, ":mail" => $mail, ":password" => $password]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### MODIFIE LES INFORMATIONS DE L'UTILISATEUR #ID ######################################################################
	#########################################################################################################################

	public function setUpdateUser($id, $type_user, $username, $email)
	{
		$sql = "UPDATE users SET type_user = :type_user , username = :username, email = :email WHERE id_u = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute([":type_user" => $type_user, ":username" => $username, ":email" => $email]);
	}

	#########################################################################################################################
	#### MODIFIE LE NEW PASSWORD APRES CHANGEMENT DE PASSWORD OU D'UNE DEMANDE DE REINITIALISATION DU MDP ###################
	#########################################################################################################################

	public function setUpdatePassword($password)
	{
		$sql = "UPDATE users SET password = :password";
		$req = $this->pdo->prepare($sql);
		$req->execute([":password" => $password]);
	}

	#########################################################################################################################
	#### SUPPRIME L'UTILISATEUR #ID #########################################################################################
	#########################################################################################################################

	public function setDeleteUser($id)
	{
		$sql = "DELETE FROM users WHERE id_u = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}





