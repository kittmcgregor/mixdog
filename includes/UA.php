<?php
	
	require_once("connection.php");
	require_once("location.php");


class UpdateAvailability{
	static public function updateSpecificAvailability($iBeerID,$aNewLocationList,$iBreweryID,$aExisting){
		
	echo "<pre>";
	echo "existing list: ";
	print_r($aExisting);
	echo "</pre>";
		
	echo "<pre>";
	echo "new list: ";
	print_r($aNewLocationList);
	echo "</pre>";
		
	$aLocationsDelete = array();
	
	//Compare existing with new list
	for($iCount=0;$iCount<count($aExisting);$iCount++){
		//existing location check
		if(!in_array($aExisting[$iCount], $aNewLocationList)){
			// must be new
			$aLocationsDelete[] = $aExisting[$iCount];
		} else {
			// do nothing
		}
	}
	
	echo "<pre>";
	echo "If not on both lists then delete: ";
	print_r($aLocationsDelete);
	echo "</pre>";
	
	$aAdd = array();
	$aElse = array();
	
	// Compare old new
	
	for($iCount=0;$iCount<count($aNewLocationList);$iCount++){
		//updated locations check
		if (!in_array($aNewLocationList[$iCount], $aExisting)){
			// was there previously so add to delete list
			$aAdd[] = $aNewLocationList[$iCount];
		} else {
			// add to list
		}
	}
	
	echo "<pre>";
	echo "add new: ";
	print_r($aAdd);
	echo "</pre>";
	



	$comma_separated = implode(",", $aLocationsDelete);

	//Delete from Availabilty where beerID = $iBeerID
	
	$oCon = new Connection();
	$sSql = "DELETE FROM availability WHERE IN availabilityID ($comma_separated)";
	echo $sSql . '<br>';
	
	
	
	$oCon->query($sSql);
	
	//insert into availab
	
	foreach($aAdd as $iLocation){
	$oCon = new Connection();
	$sSql = "INSERT INTO availability (beerID, breweryID, locationID ) VALUES ('".$iBeerID."','".$iBreweryID."','".$iLocation."')";
	echo $sSql . '<br>';
	
	$bResult = $oCon->query($sSql);
		if($bResult==false){
			die($sSql."did not run");
			}
	}
		
	$oCon->close();
	}
	
}
$iBeerID = 136;
$aLocations = array(1,2,7,8,9);
$iBreweryID = 19;
$aExisting = array(1,2,3,4,5);
	
$test = UpdateAvailability::updateSpecificAvailability($iBeerID,$aLocations,$iBreweryID,$aExisting);

//$aLikeStream = Likes::getUsersLikeActivity($aUserIDsOfFollowing);

	

?>