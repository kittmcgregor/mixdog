<?php 
	ob_start(); // for redirection to work
	//session_start();
	include_once'includes/header.php';
	
/*
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php");
	}
*/
	if(isset($_SESSION["UserID"])){
		$iUserID = $_SESSION["UserID"];
	}
	
	$aAllUsers = User::all();
		
/*
		echo "<pre>";
		print_r($aAllUsers);
		echo "</pre>";
*/
	
	echo View::renderAllUsers($aAllUsers);

	include_once'includes/footer.php';

?>