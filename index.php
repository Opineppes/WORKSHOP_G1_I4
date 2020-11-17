<?php


if(!empty($_POST)) {
    echo PROTOCOLE::chooser();
    exit();
}

if(isset($_GET['page'])) {
    $page = $_GET['page'];
    if($page == "accueil" or $page == "statistique") { //utilisateur lambda
        include_once("PHP/template/index.php");
        exit(0);
    } else {
        $page = "accueil";
        include_once("PHP/template/index.php");
        exit(0);
    }
}
?>