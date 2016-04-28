<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
/*
	if(isset($_GET["userID"])){
		if($_GET["userID"]==1){
			echo View::renderClaimLocation($locationID);
		}
	}
*/
	
	$oLocation = new Location();
	$oLocation->load($locationID);
	$claimstatus = $oLocation->claimstatus;
	echo View::renderLocation($oLocation);

// superadmin edit
	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			echo '<div class="wrapper">';
			echo '<a class="btn btn-default" href="editlocation.php?locationID='.$_GET["locationID"].'">superadmin edit</a>';
			echo '</div>';
		}
	}
	

	
	// Load Beers related to location
	$aAvailableIDs = Availability::loadIDs($locationID);
	
	if(isset($_GET['orderbybrewery'])){
		if($_GET['orderbybrewery']=true){
			$aAvailableIDs = Availability::loadIDsBrewery($locationID);
		}
	}

	
	//$aBeersAvailable = Availability::loadBeers($locationID);
	//echo View::renderBeersAvailable($aBeersAvailable);
	echo View::renderAvailableData($aAvailableIDs,$locationID,$claimstatus,$domain);
	
			
/*
echo "<pre>";
print_r($aAvailableIDs);
echo "</pre>";
*/


	include_once'includes/footer.php';

?>