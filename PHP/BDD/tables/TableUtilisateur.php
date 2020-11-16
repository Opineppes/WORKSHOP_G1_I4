<?php

class TableUtilisateur extends Table {
	
	protected $_valid;
	protected $_exist;
	protected $_change_image;
	protected $_change_passd;
	protected $_search;
	
	public function __construct()
	{
		parent::__construct("utilisateur" ,array("email"), array("prenom", "nom","annee","admin", "image", "mdp", "campus"));
		$this->_create = "INSERT INTO utilisateur(email, prenom, nom, annee, mdp, campus) VALUES (:email, :prenom, :nom, :annee, :mdp, :campus);";
		$this->_update = "UPDATE utilisateur SET prenom = :prenom, nom = :nom, annee = :annee, campus = :campus WHERE email = :email";
		
		$this->_exist = "SELECT * FROM utilisateur WHERE email = :email";
		$this->_valid = "SELECT * FROM utilisateur WHERE email = :email and mdp = :mdp";
		$this->_change_image= "UPDATE utilisateur SET image = :image WHERE email = :email";
		$this->_change_passd = "UPDATE utilisateur SET mdp = :mdp WHERE email = :email";

		$this->_search = "SELECT * FROM utilisateur WHERE email LIKE :test OR prenom LIKE :test OR nom LIKE :test OR annee LIKE :test OR campus LIKE :test";
	}
	
	public function exist($args) {
		$req = BDD::getBDD()->prepare($this->_exist);
			
		$req->execute($args);
		
		return $req->rowCount() > 0;
	}
	
	public function valid($args) {
		$req = BDD::getBDD()->prepare($this->_valid);
			
		$req->execute($args);
		
		return $req->rowCount() > 0;
	}
	
	public function update_image($args) {
		$req = BDD::getBDD()->prepare($this->_change_image);
		
		$req->execute($args);
		
		return $req->rowCount();
	}
	
	public function update_passwd($args) {
		$req = BDD::getBDD()->prepare($this->_change_passd);
		
		$req->execute($args);
		
		return $req->rowCount();
	}

	public function search($args) {
		$req = BDD::getBDD()->prepare($this->_search);
			
		$req->execute($args);
		
		return $req->fetchAll();
	}
}

?>