<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="utf-8" />
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<header>
<?php 
	include( "PHP/template/header.php");
?>
	</header>
	<body>

	<div class="container-fluid">
<?php 
	include( "PHP/template/". $page .".php"); 
?>
	</div>

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</body>
</html>