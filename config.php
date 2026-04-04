<?php
$host = "localhost";
$dbname = "ecommerce";
$user = "root";  // Par défaut sur Laragon
$password = "";  // Mot de passe vide sur Laragon

date_default_timezone_set('Europe/Paris');
	$lien_base= mysqli_connect($host,$user, $password, $dbname) ;
	mysqli_set_charset ( $lien_base ,  'utf8' );
	
?>
