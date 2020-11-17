<?php

class TableArticle extends Table {
	
	protected $_getAllByUser ; 
	protected $_getAllByRubrique ;
	protected $_search;

	public function __construct()
	{
		parent::__construct("article" ,array("id"), array("titre", "dateCreation", "infos","emailUtilisateur","nomRubrique, description"));
		$this->_create = "INSERT INTO article ( titre, dateCreation, infos, emailUtilisateur, nomRubrique, description ) VALUES ( :titre, :dateCreation, :infos, :emailUtilisateur, :nomRubrique, :description )";
		$this->_getAllByUser = "SELECT *, DATE_FORMAT(dateCreation, ' %d/%m/%Y à %H:%i') AS dateInscriptionFormatee FROM article WHERE emailUtilisateur = :emailUtilisateur ORDER BY dateCreation DESC";
		$this->_getAllByRubrique = "SELECT *, DATE_FORMAT(dateCreation, ' %d/%m/%Y à %H:%i') AS dateInscriptionFormatee  FROM article WHERE nomRubrique = :nomRubrique ORDER BY dateCreation DESC";
		$this->_selectOne = "SELECT *, DATE_FORMAT(dateCreation, ' %d/%m/%Y à %H:%i') AS dateInscriptionFormatee FROM article WHERE id = :id ";
		$this->_search = "SELECT *, DATE_FORMAT(dateCreation, ' %d/%m/%Y à %H:%i') AS dateInscriptionFormatee FROM article WHERE nomRubrique = :nomRubrique AND (titre LIKE :test OR infos LIKE :test OR emailUtilisateur LIKE :test OR description LIKE :test) ORDER BY dateCreation DESC";
	}

	public function getAllByUser($args) {
		$req = BDD::getBDD()->prepare($this->_getAllByUser);
			
		$req->execute($args);
		
		return $req->fetchAll();
	}

	public function getAllByRubrique($args) {
		$req = BDD::getBDD()->prepare($this->_getAllByRubrique);
			
		$req->execute($args);
		
		return $req->fetchAll();
	}

	public function search($args) {
		$req = BDD::getBDD()->prepare($this->_search);
			
		$req->execute($args);
		
		return $req->fetchAll();
	}

}

?>