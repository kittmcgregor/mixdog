<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	$iBreweryID = 1;
	
	if(isset($_GET["breweryID"])){
		$iBreweryID = $_GET["breweryID"];
	}
	
/*
	if(isset($_GET["userID"])){
	if($_GET["userID"]==1){
			echo View::renderClaimBrewery($iBreweryID);
		}
	}
*/
	
	//$oBrewery = new Brewery();
	//$oBrewery->load($iBreweryID);
	
	
	//echo View::renderBrewery($oBrewery);


	// Load Beers related to location
	
	$aBeers = Beer::loadIDsByBrewery($iBreweryID);
	
	echo View::renderBeersByBrewery($aBeers);

/*
	echo "<pre>";
	print_r($aBeers);
	echo "</pre>";
*/

	//echo View::renderBeersAvailable($oBeerList);

	include_once'includes/footer.php';

/*
		echo "<pre>";
		print_r($oBrewery);
		echo "</pre>";
*/
		
?>