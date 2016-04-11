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
	
echo '	<style>
	header {display:none;}
	.locationDetails {display:none;}
	footer {display:none;}
	.wrapper {width:100%;}
	</style>';
	
	$oLocation = new Location();
	$oLocation->load($locationID);
	$claimstatus = $oLocation->claimstatus;
	echo View::renderLocation($oLocation);


	// Load Beers related to location
	
	$aAvailableIDs = Availability::loadIDs($locationID);
	//$aBeersAvailable = Availability::loadBeers($locationID);
	//echo View::renderBeersAvailable($aBeersAvailable);
	echo View::embedAvailableData($aAvailableIDs,$locationID,$claimstatus);
	
			
/*
echo "<pre>";
print_r($aAvailableIDs);
echo "</pre>";
*/


	include_once'includes/footer.php';

?>