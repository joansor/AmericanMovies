<?php

class CommentsVotesController extends Controller
{
	######################################################################
	#### CONSTRUCTEUR ####################################################
	######################################################################

	public function __construct()
	{
		parent::__construct(); // Parent dans Controller.php
		$this->model = new CommentsVotes(); // Nouvel Object : CommentsVotes
    }

	###################################################
	#### VOTE SUR COMMENTAIRE - J'aime/J'aime pas #####
	###################################################

	public function updateVote($idcom,$iduser,$vote)
	{
		$aDejaVote = $this->model->getUserVoteThisCom($idcom, $iduser); // Retourne l'id du vote si l'utilisateur à déjà évalué ce commentaire

		if($aDejaVote['id_vote']) // Si il a déja évalué ce commentaire
		{
			$updateVote = $this->model->setUpdateVote($aDejaVote['id_vote'], $vote); // update le vote rxistant dans la bdd
		}
		else // Sinon, il n'a pas encore évalué ce commentaire
		{
			$insertVote = $this->model->setInsertVote($idcom, $iduser, $vote); // insert le vote dans la bdd
		}

		$nbVotesPositif = $this->model->getNbVotesByCom($idcom, "positif"); // Retourne le nombre de vote positif pour ce commentaire
		$nbVotesNegatif = $this->model->getNbVotesByCom($idcom, "negatif"); // Retourne le nombre de vote negatif pour ce commentaire

		$data = array('0' => $nbVotesNegatif['COUNT(*)'] , '1' => $nbVotesPositif['COUNT(*)']); // Tableau Json pour pour lecture avec ajax
        echo json_encode($data); // Affiche le résultat qui sera récuperer via ajax
	}
}