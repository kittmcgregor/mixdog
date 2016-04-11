<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";
	
	if($_GET["addlike"]==true){
		$newLike = new Like();
		$newLike->beerID = $beerID;
		$newLike->userID = $_GET["userID"];
		$newLike->save();
		
	}
	if($_GET["removelike"]==true){
		echo 'remove like';
		Like::remove($_GET["likeID"]);
	}
	
	
	// redirect 
	header("location:index.php"); 
	
	?>