<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	// redirect if not logged in
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
	
/*
	if ($_SESSION["UserID"]==$_GET["userID"]){
			echo "<pre>";
			echo "logged in matches admin role";
			echo "</pre>";
	} else {
		header("location:login.php"); 
	}
*/
	
/*
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
	
	exit;
*/
	
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
	if(isset($_GET["locationmanagerID"])){
		$locationmanagerID = $_GET["locationmanagerID"];
	}
	if(isset($_GET["breweryID"])){
		$breweryID = $_GET["breweryID"];
	}

	if(isset($_SESSION["UserID"])){
		$userID = $_SESSION["UserID"];
	}

	Availability::addAvLoc($beerID,$breweryID,$locationID,$userID);
	Location::activty($locationID,date('Y-m-d H:i:s'));
	
	if(isset($_GET["quickadd"])){
		header("location:viewbeer.php?beerID=$beerID&addAvLoc=true");
		exit;
	}
	
	// redirect 
	header("location:index.php?addAvLoc=true"); 
	
	?>