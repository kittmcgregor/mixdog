<?php 
	ob_start(); // for redirection to work
	session_start();
	include_once'includes/header.php';
	
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
	
	//$suburb = "Mt Eden";
	$suburb = $_GET["suburb"];
	
	echo View::renderLocationTabs();

	
	$aLocations = Location::suburb($suburb);
	

/*
		echo "<pre>";
		print_r($aAllLocations);
		echo "</pre>";
*/

echo View::renderAllLocations($aLocations);


/*
	// Load Beers related to location

	$aBeersAvailable = Availability::loadBeers($locationID);
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	include_once'includes/footer.php';

?>