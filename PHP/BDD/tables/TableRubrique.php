<?php

class TableRubrique extends Table {
	
	public function __construct()
	{
		parent::__construct("rubrique" ,array("nomRubrique"), array("image", "description","infos"));
	}
}

?>