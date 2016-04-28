<?php
	require_once("includes/connection.php");
	require_once("includes/beer.php"); 

/*
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";
*/

	$id = $_GET["id"];
	
/*
	echo "<pre>";
	print_r(Beer::listBrewData($id));
	echo "</pre>";
*/
	
	echo json_encode(Beer::listBrewData($id));
		
		
?>