<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8" />
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>
	<header>
<?php 
	include( "PHP/template/header.php");
?>
	</header>
	<body>

	<div class="row">
		<div class="container-fluid">
<?php 
	include( "PHP/template/". $page .".php"); 
?>
		</div>
	</div>

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</body>
</html>