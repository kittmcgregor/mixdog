<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	
	if($_GET["addlike"]==true){
		$newLike = new Like();
		$newLike->beerID = $beerID;
		$newLike->userID = $_GET["userID"];
		$newLike->save();
	
		$oUser = new User();
		$oUser->load($_GET["userID"]);
		
		$oBeer = new Beer();
		$oBeer->load($beerID);
	}
	
	if($_GET["removelike"]==true){
		echo 'remove like';
		Like::remove($_GET["likeID"]);
	}
	
	
	// redirect 
	header("location:viewbeer.php?beerID=".$beerID); 
	
	?>