<?php
	require_once("includes/connection.php");
	require_once("includes/beer.php"); 
	
// $term=$_GET["term"];

/*
		//1 make connection
		$oCon = new Connection();
		$aLocations = array();
		
		// exclude WHERE ClaimStatus=0
		
		//2 create query
		$sSql = "SELECT locationID FROM location WHERE ClaimStatus=0 ORDER BY locationName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iLocationID = $aRow["locationID"];
				$oLocation = new Location();
				$oLocation->load($iLocationID);
				$aLocations[$iLocationID] = $oLocation->locationname; // add to array
			}

		//5 close connection
		$oCon->close();
*/
/*
		echo "<pre>";
		print_r(Beer::listBrews());
		echo "</pre>";
*/
/*
		$listBrews = Beer::listBrews();
		$sortArray = ksort($listBrews);
*/
		echo json_encode(Beer::listBrews());
		
		
?>