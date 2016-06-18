<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";

// 	if ($_SESSION["UserID"]==$_GET["userID"]){
	
	if (isset($_SESSION["UserID"])){
			echo "<pre>";
			echo "logged in matches admin role";
			echo "</pre>";
	} else {
		header("location:login.php"); 
	}
	
	if(isset($_GET["availableID"])){
		$iAvailableID = $_GET["availableID"];
	}
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}

	if(isset($_GET["slug"])){
		$slug = $_GET["slug"];
	}
			
			echo "<pre>";
			echo $iAvailableID;
			echo "</pre>";
		
			echo 'remove AvLoc';
		Availability::removeAvLoc($iAvailableID);	

	if(isset($_GET["quickremove"])){
		
		header("location:$domain$slug?removedAvLoc=true");
		//header("location:viewbeer.php?beerID=$beerID&removeAvLoc=true");
		exit;
	}

	// redirect 
	
	header("location:viewuseradmin.php?removedAvLoc=true&removedAvLocbeerID=$beerID"); 
	exit;
	?>