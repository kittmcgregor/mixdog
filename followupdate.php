<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	if(isset($_SESSION["userID"])){
		$userID = $_SESSION["userID"];
	}
	
	echo "<pre>";
	echo "GET ";
	print_r($_GET);
	echo "</pre>";
	
	echo "<pre>";
	echo "SESSION ";
	print_r($_SESSION);
	echo "</pre>";
	
	if(isset($_GET["addfollow"])&&$_GET["addfollow"]==true){
		$newFollow = new Follow();
		$newFollow->userID = $_SESSION["UserID"];
		if(isset($_GET["fb"])){
			$newFollow->followbreweryID = $_GET["fb"];	
		}
		if(isset($_GET["fl"])){
			$newFollow->followlocationID = $_GET["fl"];	
		}
		if(isset($_GET["fu"])){
			$newFollow->followuserID = $_GET["fu"];
		}

		$newFollow->save();
	
		// Redirect accordingly
		
		echo "redirect";
		
		
		if(isset($_GET["revb"])){
			echo "redirect to back brewery id = ".$_GET["fb"];
			header("location:viewbrewery.php?breweryID=".$_GET["fb"]);
		}
		

		if(isset($_GET["revu"])){
			echo "redirect to back user id = ".$_GET["fu"];
			header("location:viewuser.php?viewUserID=".$_GET["fu"]);	
		}


		if(isset($_GET["revl"])){
			echo "redirect to back location id = ".$_GET["fl"];
			header("location:viewlocation.php?locationID=".$_GET["fl"]);
		}
		
		exit;
		
/*
		$viewUserID = $newFollow->followuserID;
		header("location:viewuser.php?viewUserID=".$viewUserID);
*/
	

	}
	
	
	if(isset($_GET["removefollow"])&&$_GET["followID"]==true){
		echo "remove follow ".$_GET["followID"];
		Follow::remove($_GET["followID"]);
		header("location:viewuseradmin.php");
	
	}
	if(isset($_GET["removefollowFromViewUser"])&&$_GET["followID"]==true){

		Follow::remove($_GET["followID"]);
		
		$redirect = $_GET["redirectViewUser"];
		header("location:viewuseradmin.php?viewUserID=".$redirect);
	
	}

?>