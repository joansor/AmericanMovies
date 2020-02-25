<?php

class Appartient extends Model
{
	public function __construct()
	{
		$this->pdo = parent::getPdo();
    }

	############################################################################################################
	#### RELATION ENTRE GENRES ET FILMS ########################################################################
    ############################################################################################################

	################################################################
	##### RETOURNE TOUS LES GENRES DE FILM #ID #####################
	################################################################

	public function getGenresAppartientFilm($id)
	{
		$sql = "SELECT id_g, genre_du_film FROM genres, appartient, films WHERE films.id_f = '". $id ."' AND genres.id_g = appartient.Genre_id_g AND appartient.Films_id_f = films.id_f";
		$req = $this->pdo->prepare($sql);
		$req->execute();
		return $req->fetchAll();
    }

	################################################################
	##### INSERT LA RELATION ENTRE UN GENRE ET UN FILM #############
	################################################################

	public function setInsertGenreAppartientFilm($film, $genre)
	{
		$sql = "INSERT INTO appartient SET Films_id_f = :film, Genre_id_g = :genre";
		$req = $this->pdo->prepare($sql);
		$req->execute([":film" => $film, ":genre" => $genre]);
	}

	##########################################################################
	##### SUPPRIME TOUTES LES RELATIONS D'UN GENRE ###########################
	##### quand on supprime un genre, on supprime d'abbord ses relations #####

	public function setDeleteAllFilmsAppartientGenre($genre)
	{
		$sql = "DELETE FROM appartient WHERE Genre_id_g = '". $genre ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}

	#########################################################################################
	##### SUPPRIME TOUS LES GENRES D'UN FILM ################################################
	##### quand on update un film, on supprime tous les genres et on les reinsert 1 par 1 ###

	public function setDeleteAllGenresAppartientFilm($film)
	{
		$sql = "DELETE FROM appartient WHERE Films_id_f = '". $film ."'";
		$req = $this->pdo->prepare($sql);
		$req->execute();
	}
}