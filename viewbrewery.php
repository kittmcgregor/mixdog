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

	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			echo '<div class="wrapper">';
			echo '<a class="btn btn-default" href="editbrewery.php?breweryID='.$_GET["breweryID"].'">superadmin edit</a>';
			echo '</div>';
		}
	}


	$oBrewery = new Brewery();
	$oBrewery->load($iBreweryID);
					
	echo View::renderBrewery($oBrewery);


	// Load Beers related to location


	$aBeers = Beer::loadIDsByBrewery($iBreweryID);
	
	echo View::renderBeersByBrewery($aBeers);

/*
	$aBeersAvailable = Availability::loadBrewery($iBreweryID);
	
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	//echo View::renderBeersUnAvailable($aBeersAvailable);

	include_once'includes/footer.php';

/*
		echo "<pre>";
		print_r($oBrewery);
		echo "</pre>";
*/
		
?>