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

	public function getAllUser()
	{
		$sql = 'SELECT * FROM utilisateurs';
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
	}

	#########################################################################################################################
	#### TRAITEMENT CONNEXION UTILISATEUR ###################################################################################
	#########################################################################################################################

	public function connect($typeIdentification, $login)
	{
		if($typeIdentification == "email") $sql = 'SELECT * FROM utilisateurs WHERE email = :login';
		else $sql = 'SELECT * FROM utilisateurs WHERE username = :login';

		$req = $this->pdo->prepare($sql);
		$req->execute(["login" => $login]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### TRAITEMENT CREATION DE COMPTE - ENREGISTREMENT UTILISATEUR #########################################################
	#########################################################################################################################

	public function registre($password, $username,$mail)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "INSERT INTO utilisateurs SET type_user = 'user', username = :username, 	password = :password, email = :mail";
		$req = $this->pdo->prepare($sql);
		$req->execute([":username" => $username, ":mail" => $mail, ":password" => $password]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### VERIFIE SI LE PSEUDO EXISTE DEJA DANS LA BDD #######################################################################
	#########################################################################################################################

	public function getVerifUser($pseudo)
	{
		$sql = "SELECT * FROM utilisateurs WHERE username = :username ";
		$req = $this->pdo->prepare($sql);
		$req->execute([":username" => $pseudo]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### VERIFIE SI LE L'EMAIL EXISTE DEJA DANS LA BDD #######################################################################
	#########################################################################################################################

	public function getVerifEmail($mailverif)
	{
		$sql = "SELECT * FROM utilisateurs WHERE email = :email ";
		$req = $this->pdo->prepare($sql);
		$req->execute([":email" => $mailverif]);
		return $req->fetch();
	}

	#########################################################################################################################
	#### RETOURNE LES INFOS DE L'UTILISATEUR #ID ############################################################################
	#########################################################################################################################

	public function getUser($id)
	{
		$sql = "SELECT * FROM utilisateurs WHERE id_u = $id ";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetch();
	}

	#########################################################################################################################
	#### SUPPRIME L'UTILISATEUR #ID #########################################################################################
	#########################################################################################################################

	public function setDeleteUser($id)
	{
		$sql = "DELETE FROM utilisateurs WHERE id_u = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	#########################################################################################################################
	#### UPDATE L'UTILISATEUR #ID DANS LA BDD ###############################################################################
	#########################################################################################################################

	public function setUpdateUser($id, $type_user, $username, $email)
	{
		$sql = "UPDATE utilisateurs SET type_user = :type_user , username = :username, email = :email WHERE id_u = $id";
		$req = $this->pdo->prepare($sql);
		$req->execute([":type_user" => $type_user, ":username" => $username, ":email" => $email]);
	}

	#########################################################################################################################
	#### UPDATE DANS LA BDD LE NEW PASSWORD APRES CHANGEMENT DE PASSWORD OU D'UNE DEMANDE DE REINITIALISATION DU MDP ########
	#########################################################################################################################

	public function setUpdatePassword($password)
	{
		$sql = "UPDATE utilisateurs SET password = :password";
		$req = $this->pdo->prepare($sql);
		$req->execute([":password" => $password]);
	}
}





