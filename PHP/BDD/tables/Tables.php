<?php

	class Table {
		protected $_delete;
		protected $_update;
		protected $_updateWP;
		protected $_create;
		protected $_selectAll;
		protected $_selectOne;
		
		public static function generateDelete( $nomTable, $primary ) {
			
			$conditions = "";
			for($i=0; $i < count($primary); $i++) {
				$conditions .= $primary[$i] . " = :" .$primary[$i];
				if($i < count($primary)-1){ $conditions .= " AND "; }		
			}
			
			return "DELETE FROM " . $nomTable . " WHERE " . $conditions;
		}
		
		public static function generateUpdateWP( $nomTable, $primary, $attributes ) {
			$conditions = "";
			for($i=0; $i < count($primary); $i++) {
				$conditions .= $primary[$i] . " = :" .$primary[$i];
				if($i < count($primary)-1){ $conditions .= " AND "; }		
			}
			$keys = "";
			for($i=0; $i < count($primary); $i++) {
				$keys .= $primary[$i] . " = :" . $primary[$i];
				if($i < count($primary)-1){ $keys .= ", "; }
			}
			if(count($attributes) > 0) {
				$keys .= ", ";
				for($j=0; $j < count($attributes); $j++) {
					$keys .= $attributes[$j] . " = :" . $attributes[$j];
					if($j < count($attributes)-1){ $keys .= ", "; }
				}
			}
			
			return "UPDATE " . $nomTable . " SET " . $keys . " WHERE " . $conditions;
		}
		
		public static function generateUpdate( $nomTable, $primary, $attributes ) {
			$conditions = "";
			for($i=0; $i < count($primary); $i++) {
				$conditions .= $primary[$i] . " = :" .$primary[$i];
				if($i < count($primary)-1){ $conditions .= " AND "; }		
			}
			$keys = "";
			for($j=0; $j < count($attributes); $j++) {
				$keys .= $attributes[$j] . " = :" . $attributes[$j];
				if($j < count($attributes)-1){ $keys .= ", "; }
			}
			
			return "UPDATE " . $nomTable . " SET " . $keys . " WHERE " . $conditions;
		}
		
		public static function generateCreate( $nomTable, $primary, $attributes ) {
			$keys = "";
			$values = "";
			for($i=0; $i < count($primary); $i++) {
				$keys .= $primary[$i];
				$values .= ":" . $primary[$i];
				if($i < count($primary)-1){ 
					$keys .= ", ";
					$values .= ", ";
				}
			}
			if(count($attributes) > 0) {
				$keys .= ", ";
				$values .= ", ";
				for($j=0; $j < count($attributes); $j++) {
					$keys .= $attributes[$j];
					$values .= ":" . $attributes[$j];
					if($j < count($attributes)-1){ 
						$keys .= ", ";
						$values .= ", "; 
					}
				}
			}
			return "INSERT INTO " . $nomTable . " ( " . $keys . " ) VALUES ( " . $values . " )";
		}
		
		public static function generateSelectAll( $nomTable ) {
			return "SELECT * FROM " . $nomTable;
		}
		
		public static function generateSelectOne( $nomTable, $primary ) {
			$conditions = "";
			for($i=0; $i < count($primary); $i++) {
				
				$conditions .= $primary[$i] . " = :" .$primary[$i];
				if($i < count($primary)-1){ $conditions .= " AND "; }		
			}
			
			return "SELECT * FROM " . $nomTable . " WHERE " . $conditions;
		}
		
		public function __construct($nomTable, $primary, $attributes) {
			$this->_delete = Table::generateDelete( $nomTable, $primary );
			$this->_update = Table::generateUpdate( $nomTable, $primary, $attributes );
			$this->_updateWP = Table::generateUpdateWP( $nomTable, $primary, $attributes );
			$this->_selectAll = Table::generateSelectAll( $nomTable );
			$this->_selectOne = Table::generateSelectOne( $nomTable, $primary );
			$this->_create = Table::generateCreate( $nomTable, $primary, $attributes );
		}
		
		public function selectAll() {
			$req = BDD::getBDD()->prepare($this->_selectAll);
			
			$req->execute();
			
			return $req->fetchAll();
		}
		
		public function selectOne($primary) {
			$req = BDD::getBDD()->prepare($this->_selectOne);
			
			$req->execute($primary);
			
			return $req->fetch();
		}
		
		public function create($attributes) {
			$req = BDD::getBDD()->prepare($this->_create);
			
			$req->execute($attributes);
			
			return $req->rowCount();
		}
		
		public function deleteOne($primary) {
			$req = BDD::getBDD()->prepare($this->_delete);
			
			$req->execute($primary);
			
			return $req->rowCount();
		}
		
		public function update($attributes) {
			$req = BDD::getBDD()->prepare($this->_update);
			
			$req->execute($attributes);
			
			return $req->rowCount();
		}
		
		public function updateWP($attributes) {
			$req = BDD::getBDD()->prepare($this->_updateWP);
			
			$req->execute($attributes);
			
			return $req->rowCount();
		}
		
	}
	
	require_once('TableArticle.php');
	require_once('TableRubrique.php');
	require_once('TableCommentaire.php');
	require_once('TableUtilisateur.php');
	
?>