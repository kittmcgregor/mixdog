<?php 
	ob_start(); // for redirection to work
	session_start();
	include_once'includes/header.php';
	
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
	
	echo View::renderLocationTabs();
	
	if(isset($_GET["suburb"])){
		$suburb = $_GET["suburb"];
		$aLocations = Location::suburb($suburb);
	}
	
	$aAllLocations = Location::all();
	

/*
		echo "<pre>";
		print_r($aAllLocations);
		echo "</pre>";
*/

echo View::renderAllLocations($aAllLocations);


/*
	// Load Beers related to location

	$aBeersAvailable = Availability::loadBeers($locationID);
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	include_once'includes/footer.php';

?>