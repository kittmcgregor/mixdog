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
	
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
		
/*
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
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
*/
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}

	if(isset($_GET["availableID"])){
		$iAvailableID = $_GET["availableID"];
	}
	
	Availability::removeResident($iAvailableID);
	
/*
	if(isset($_GET["quickadd"])){
		header("location:viewlocation.php?locationID=$locationID&addResident=true");
		exit;
	}
*/
	
	// redirect 
	header("location:viewlocation.php?locationID=$locationID&removeResidency=true"); 
	
	?>