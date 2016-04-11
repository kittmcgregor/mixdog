<?php 
	require_once("connection.php");
	require_once("location.php");
	
class Availability{
		private $iAvailabilityID;
		private $sDate;
		private $iBreweryID;
		private $iBeerID;
		private $iLocationID;
		private $iUserID;
		private $bResidentStatus;

		public  function __construct(){
			$this->iAvailabilityID = 0;
			$this->sDate="";
			$this->iBreweryID = 0;
			$this->iBeerID = 0;
			$this->iLocationID = 0;
			$this->iLocationID = 0;
			$this->bResidentStatus = 0;
		}

	static public function loadIDs($locationID){
		
		$aAvIDs = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT availabilityID, date, breweryID, beerID, locationID FROM `availability` WHERE locationID=$locationID AND archive=0 ORDER BY date DESC";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$aAvIDs[]= $aRow["availabilityID"];
		}
		//5 close connection
		$oCon->close();
		
		return $aAvIDs;
		}

	static public function loadAllIDs($locationID){
		
		$aAvIDs = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT availabilityID, date, breweryID, beerID, locationID FROM `availability` WHERE locationID=$locationID ORDER BY date DESC";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$aAvIDs[]= $aRow["availabilityID"];
		}
		//5 close connection
		$oCon->close();
		
		return $aAvIDs;
		}
	
	static public function loadIDsBrewery($locationID){
		
		$aAvIDs = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT a.availabilityID, a.BreweryID , b.BreweryID, b.BreweryName 
				FROM `availability` a INNER JOIN BreweryTable b ON a.BreweryID = b.BreweryID 
				WHERE locationID=$locationID AND archive=0 
				ORDER BY b.BreweryName";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$aAvIDs[]= $aRow["availabilityID"];
		}
		//5 close connection
		$oCon->close();
		
		return $aAvIDs;
		}
		
	public function load($iAvialID){
		
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT availabilityID, date, breweryID, beerID, locationID, residentStatus FROM `availability` WHERE availabilityID=".$iAvialID;

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iAvailabilityID = $aRow["availabilityID"];
		$this->sDate = $aRow["date"];
		$this->iBreweryID = $aRow["breweryID"];
		$this->iBeerID = $aRow["beerID"];
		$this->iLocationID = $aRow["locationID"];
		$this->bResidentStatus = $aRow["residentStatus"];
		
		//5 close connection
		$oCon->close();
		}

	public function findAvID($iBeerID,$iLocationID){
		
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT availabilityID FROM `availability` WHERE beerID=$iBeerID AND locationID=$iLocationID";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$iAvialID = $aRow["availabilityID"];
		
		//5 close connection
		$oCon->close();
		
		return $iAvialID;
		}

static public function lastMonth($iLocationID){
		$aAvIDs = array();
		$oCon = new Connection();
		$sSql = "SELECT availabilityID,  `date` 
		FROM availability
		WHERE  `date` >= NOW( ) - INTERVAL 1 MONTH
		AND locationID =$iLocationID";

		$oResultSet = $oCon->query($sSql);
		// load all subjects and add to array
		while($aRow = $oCon->fetchArray($oResultSet)){
		$aAvID = $aRow["availabilityID"];
		$aAvIDs[] = $aAvID;
		}
		$oCon->close();
		
		return $aAvIDs;
	}
	
	
static public function lastWeek($iLocationID){
		$aAvIDs = array();
		$oCon = new Connection();
		$sSql = "SELECT availabilityID,  `date` 
		FROM availability
		WHERE  `date` >= NOW( ) - INTERVAL 1 WEEK
		AND locationID =$iLocationID";

		$oResultSet = $oCon->query($sSql);
		// load all subjects and add to array
		while($aRow = $oCon->fetchArray($oResultSet)){
		$aAvID = $aRow["availabilityID"];
		$aAvIDs[] = $aAvID;
		}
		$oCon->close();
		
		return $aAvIDs;
	}
	
	static public function all(){
		$oCon = new Connection();
		$sSql = "SELECT availabilityID FROM `availability` WHERE archive = 0 ORDER BY date DESC LIMIT 0 , 30";
		
		$aAvIDs = array();
		
		$oResultSet = $oCon->query($sSql);
		// load all subjects and add to array
		while($aRow = $oCon->fetchArray($oResultSet)){
		$aAvID = $aRow["availabilityID"];
/*
		$oAvial = new Availability();
		$oAvial->load($aAvID);
*/
		$aAvIDs[] = $aAvID;
		}
		//5 close connection
		$oCon->close();
		
		return $aAvIDs;
		}
	
	static public function loadLocationIDs($beerID){
	//return a list of all likes
	$aLocationIDs = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT `locationID` FROM `availability` WHERE archive = 0 AND beerID=$beerID";
	
	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iLocationID = $aRow["locationID"];
		
/*
		$oLocation = new Location();
		$oLocation->load($iLocationID);
*/
		$aLocationIDs[] = $iLocationID; // add locations to list
	}
		$oCon->close();
		
		return $aLocationIDs;
	}
	
	
	static public function loadBeers($locationID){
	//return a list of all likes
	$aLocations = array();
				
	// query 
	$oCon = new Connection();
	$sSql = "SELECT `beerID` FROM `availability` WHERE locationID='$locationID' AND archive=0 ORDER BY beerID DESC";
	
	$oResultSet = $oCon->query($sSql);
	$aBeers = array();
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iBeerID = $aRow["beerID"];
		
		$oBeer = new Beer();
		$oBeer->load($iBeerID);
		$aBeers[] = $oBeer; // add locations to list
	}
		$oCon->close();
		
		return $aBeers;
	}

static public function loadBeerIDs($locationID){
	//return a list of all likes
	$aLocations = array();
				
	// query 
	$oCon = new Connection();
	$sSql = "SELECT `beerID` FROM `availability` WHERE locationID='$locationID' AND archive = 0 ORDER BY beerID DESC";
	
	$oResultSet = $oCon->query($sSql);
	$aBeers = array();
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iBeerID = $aRow["beerID"];

		$aBeers[] = $iBeerID; // add locations to list
	}
		$oCon->close();
		
		return $aBeers;
	}
	
	static public function totalTaps(){
	//return a list of all likes
				
	// query 
	$oCon = new Connection();
	//$sSql = "SELECT `beerID` FROM `availability` WHERE locationID='$locationID' ORDER BY beerID DESC";
	$sSql = "SELECT COUNT(*) FROM availability WHERE archive = 0";

	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	$aRow = $oCon->fetchArray($oResultSet);
	$iTotal = $aRow["COUNT(*)"];
	
	$oCon->close();
	return $iTotal;
	}
	
	static public function totalBreweryTaps($BeerID){
		//return a list of all likes
					
		// query 
		$oCon = new Connection();
		//$sSql = "SELECT `beerID` FROM `availability` WHERE locationID='$locationID' ORDER BY beerID DESC";
		$sSql = "SELECT COUNT(*) FROM availability WHERE archive=0 AND beerID=$BeerID";
	
		$oResultSet = $oCon->query($sSql);
			
		// load all subjects and add to array
		$aRow = $oCon->fetchArray($oResultSet);
		$iTotal = $aRow["COUNT(*)"];
		
		$oCon->close();
	return $iTotal;
	}
	
	static public function loadBrewery($breweryID){
	//return a list of all likes
	$aBeers = array();
				
	// query 
	$oCon = new Connection();
	$sSql = "SELECT DISTINCT `beerID` FROM `availability` WHERE breweryID='$breweryID' ORDER BY beerID DESC";

	$oResultSet = $oCon->query($sSql);
	$aBeers = array();
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iBeerID = $aRow["beerID"];
		
		$oBeer = new Beer();
		$oBeer->load($iBeerID);
		$aBeers[] = $oBeer; // add beers to list
	}
		$oCon->close();
		
		return $aBeers;
	}
	
	static public function updateAvailability($iBeerID,$aLocations,$iBreweryID,$userID){
		//insert into avalab

		foreach($aLocations as $iLocation){
			$oCon = new Connection();
			$sSql = "INSERT INTO availability (beerID, breweryID, locationID, userID ) VALUES ('".$iBeerID."','".$iBreweryID."','".$iLocation."','".$userID."')";
			echo $sSql . '<br>';
			
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
				}
			$oCon->close();
		}
			
	}

	static public function updateAvailabilityLocationManager($iBeerID,$locationID,$iBreweryID){
		//insert into avalab

		$oCon = new Connection();
		$sSql = "INSERT INTO availability (beerID, breweryID, locationID ) VALUES ('".$iBeerID."','".$iBreweryID."','".$locationID."')";
		echo $sSql . '<br> <2';

		$bResult = $oCon->query($sSql);
		if($bResult==false){
			die($sSql."did not run");
		}
		$oCon->close();
			
	}
	
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
	if(!empty($aNewLocationList)){
		for($iCount=0;$iCount<count($aExisting);$iCount++){
			//existing location check
			if(!in_array($aExisting[$iCount], $aNewLocationList)){
				// must be new
				$aLocationsDelete[] = $aExisting[$iCount];
			} else {
				// do nothing
			}
		}
	} else {
		echo 'array empty';
	}
	
	
	echo "<pre>";
	echo "If not on both lists then delete: ";
	print_r($aLocationsDelete);
	echo "</pre>";
	
	$aAdd = array();
	$aElse = array();
	
	// Compare old new
	if(!empty($aNewLocationList)){
		for($iCount=0;$iCount<count($aNewLocationList);$iCount++){
			//updated locations check
				if (!in_array($aNewLocationList[$iCount], $aExisting)){
					// was there previously so add to delete list
					echo 'checking array';
					$aAdd[] = $aNewLocationList[$iCount];
				} else {
					// add to list
				}
		}
		} else {	
		// no list 
		echo 'array empty';
	}
	
	echo "<pre>";
	echo "add new: ";
	print_r($aAdd);
	echo "</pre>";

	

	if(empty($aNewLocationList)){
		$comma_separated = implode(",", $aExisting);
	} else {
		$comma_separated = implode(",", $aLocationsDelete);
	}
	
	//Delete from Availabilty where beerID = $iBeerID
	
	$oCon = new Connection();
	$sSql = "DELETE FROM availability WHERE beerID=$iBeerID AND locationID IN ($comma_separated)";
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

	
		
	static public function addAvLoc($beerID,$breweryID,$locationID,$userID){
		
		$oCon = new Connection();
		$sSql = "INSERT INTO availability (beerID, breweryID, locationID, userID ) VALUES ('".$beerID."','".$breweryID."','".$locationID."','".$userID."')";
		
		$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		$oCon->close();
	}
	
	static public function addResident($iAvailableID){
		$oCon = new Connection();
		$sSql = "UPDATE  availability SET residentStatus = '1' WHERE  availabilityID =$iAvailableID";
		$bResult = $oCon->query($sSql);
		if($bResult==false){
				die($sSql."did not run");
			}
		$oCon->close();
	}
	
	static public function removeResident($iAvailableID){
		$oCon = new Connection();
		$sSql = "UPDATE  availability SET residentStatus = '0' WHERE  availabilityID =$iAvailableID";
		$bResult = $oCon->query($sSql);
		if($bResult==false){
				die($sSql."did not run");
			}
		$oCon->close();
	}	
	
	public static function searchDeep($keyword){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		
		$sSql = "SELECT  BeerID FROM  beer WHERE (Title LIKE '%".$keyword."%' OR Description LIKE '%".$keyword."%' OR Brewery LIKE '%".$keyword."%') AND Active='1' ORDER BY Title";

/*
		$sSql = "SELECT a.BeerID 
		FROM beer a, availability b, location c 
		WHERE  
		(a.beerID = b.beerID 
		AND b.locationID = c.locationID 
		AND locationsuburb ='$keyword') 
		
		OR (a.Title LIKE '%".$keyword."%' 
		OR a.Description LIKE '%".$keyword."%' 
		OR a.Brewery LIKE '%".$keyword."%')
		";
*/

		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		$aBeers = array();
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			
			$oBeer = new Beer();
			$oBeer->load($aRow["BeerID"]);
			$aBeers[] = $oBeer;
		}
		
		return $aBeers;
		
	}
	
	static public function removeAvLoc($iAvailableID){
		// open connection
		$oCon = new Connection();
		
		$sSql = "UPDATE availability SET archive = '1' WHERE availabilityID =$iAvailableID";
		
/*
			echo "<pre>";
			echo "$sSql";
			echo "</pre>";
*/	
		$oCon->query($sSql);
		
		// close connection
		$oCon->close();
	}
	
	static public function getLocationActivity($aFollowingLocationsIDsList){
		
		$comma_separated = implode(",", $aFollowingLocationsIDsList);
		
		//return a list of all likes
		$aLocationActivity = array();
		
		// query all subject IDs
		$oCon = new Connection();
		$sSql = "SELECT availabilityID, beerID, date, locationID FROM `availability` WHERE `locationID` IN ($comma_separated) ORDER BY date DESC";
		//echo $sSql;
		
		$oResultSet = $oCon->query($sSql);
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iAvailabilityID = $aRow["availabilityID"];
			$aLocationActivity[] = $iAvailabilityID;
/*
			$iLikeID = $aRow["likeID"];
			$oLike = new Like();
			$oLike->load($iLikeID);
			$aUsersLikeActivity[] = $oLike; // add locations to list
*/
		}
		$oCon->close();
	
		return $aLocationActivity;
		}

	// getter method
	public function __get($var){
		switch ($var){
		case 'availabilityID';
			return $this->iAvailabilityID;
			break;
		case 'date';
			return $this->sDate;
			break;
		case 'breweryID';
			return $this->iBreweryID;
			break;
		case 'beerID';
			return $this->iBeerID;
			break;
		case 'locationID';
			return $this->iLocationID;
			break;
		case 'userID';
			return $this->sUserID;
			break;
		case 'residentstatus';
			return $this->bResidentStatus;
			break;
		default;
			die($var . " getter is not accessible");
			break;
		}
	}
	
}

/*
$oNewBeerlocations = Availability::updateAvailabilty(38,array(10,3,4));

echo "<pre>";
echo "hello";
print_r($oNewBeerlocations);
echo "</pre>";
*/

?>