<?php

	class BDD {
		private static $adresseBDD = "127.0.0.1";
		private static $nomBDD = "site";
		private static $utilisateurBDD= "root";
		private static $motdepasseBDD= "root";

		private static $bd;

		public static function connectBDD() {
			BDD::$bd = new PDO('mysql:host='.BDD::$adresseBDD.';dbname='.BDD::$nomBDD.';', BDD::$utilisateurBDD);
			if(BDD::$bd != NULL)
				return true;
			return false;
		}
		
		public static function getBDD() {
			return BDD::$bd;
		}


	}

	require_once('tables/Tables.php');

	

?>