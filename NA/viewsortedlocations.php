<?php 
	ob_start(); // for redirection to work
	session_start();
	include_once'includes/header.php';
	
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
	
	
	
	$aLocationSuburb = Location::suburb("Mt Eden");
	

/*
		echo "<pre>";
		print_r($aAllLocations);
		echo "</pre>";
*/

echo View::renderAllLocations($aLocationSuburb);


/*
	// Load Beers related to location

	$aBeersAvailable = Availability::loadBeers($locationID);
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	include_once'includes/footer.php';

?>