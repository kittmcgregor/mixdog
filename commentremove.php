<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";
	
	if($_GET["removeCommentID"]==true){
		echo 'remove comment';
		Comment::remove($_GET["removeCommentID"]);
	}
	
	
	// redirect 
	header("location:viewbeer.php?commentremoved=1&beerID=".$beerID); 
	
	?>