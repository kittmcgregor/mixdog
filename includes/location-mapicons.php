<?php 
	require_once("connection.php"); 
	require_once("availabilitymanager.php");
	require_once("beer.php"); 
	require_once("brewery.php");
	require_once("phpThumb/phpThumb.config.php");
		
class Location{
	
	private $iLocationID;
	private $sLng;
	private $sLat;
	private $iClaimStatus;	
	private $sLocationName;
	private $sLocationAddress;
	private $sLocationSuburb;
	private $sLocationRegion;
	private $sLocationContact;
	private $sLocationWebsite;
	private $sLocationInfo;
	private $sLocationEvents;
	private $iOldLocationManagerID;
	private $iNewLocationManagerID;
	private $sSlug;
	
	public  function __construct(){
		$this->iLocationID = 0;
		$this->sLat = "";
		$this->sLng = "";
		$this->iClaimStatus = 0;
		$this->sLocationName = "";
		$this->sLocationAddress = "";
		$this->sLocationSuburb = "";
		$this->sLocationRegion = "";
		$this->sLocationContact = "";
		$this->sLocationWebsite = "";
		$this->sLocationInfo = "";
		$this->sLocationEvents = "";
		$this->iOldLocationManagerID = 0;
		$this->iNewLocationManagerID = 0;
		$this->sSlug = "";
	}

	public function loadByName($locationName){
		
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT locationID, slug, ClaimStatus, locationName, locationAddress, locationSuburb, locationRegion, locationContact, locationWebsite, locationInfo, locationEvents FROM `location` WHERE slug ='$locationName'";
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iLocationID = $aRow["locationID"];
			$this->sSlug = $aRow["slug"];
			$this->iClaimStatus = $aRow["ClaimStatus"];
			$this->sLocationName = $aRow["locationName"];
			$this->sLocationAddress = $aRow["locationAddress"];
			$this->sLocationSuburb = $aRow["locationSuburb"];
			$this->sLocationRegion = $aRow["locationRegion"];
			$this->sLocationContact = $aRow["locationContact"];
			$this->sLocationWebsite = $aRow["locationWebsite"];
			$this->sLocationInfo = $aRow["locationInfo"];
			$this->sLocationEvents = $aRow["locationEvents"];
			
			$sSql = "SELECT UserID FROM user WHERE locationID ='".$oCon->escape($this->iLocationID)."'";
			$oResultSet = $oCon->query($sSql);
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iOldLocationManagerID = $aRow["UserID"];
			//5 close connection
			$oCon->close();
		
	}

	public function load($locationID){
		
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT locationID, lat, lng, slug, ClaimStatus, locationName, locationAddress, locationSuburb, locationRegion, locationContact, locationWebsite, locationInfo, locationEvents FROM `location` WHERE locationID =".$locationID;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iLocationID = $aRow["locationID"];
			$this->sLat = $aRow["lat"];
			$this->sLng = $aRow["lng"];
			$this->sSlug = $aRow["slug"];
			$this->iClaimStatus = $aRow["ClaimStatus"];
			$this->sLocationName = $aRow["locationName"];
			$this->sLocationAddress = $aRow["locationAddress"];
			$this->sLocationSuburb = $aRow["locationSuburb"];
			$this->sLocationRegion = $aRow["locationRegion"];
			$this->sLocationContact = $aRow["locationContact"];
			$this->sLocationWebsite = $aRow["locationWebsite"];
			$this->sLocationInfo = $aRow["locationInfo"];
			$this->sLocationEvents = $aRow["locationEvents"];
			
			$sSql = "SELECT UserID FROM user WHERE locationID ='".$oCon->escape($locationID)."'";
			$oResultSet = $oCon->query($sSql);
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iOldLocationManagerID = $aRow["UserID"];
			//5 close connection
			$oCon->close();
		
	}
	

	public function save(){
		// insert
		$oCon = new Connection();
	
		if($this->locationID==0){
		// beer does not exist - do insert
		$sSql = "INSERT INTO `location` (`locationName`,`slug`, `locationAddress`, `locationSuburb`, `locationRegion`, `locationContact`, `locationWebsite`, `locationInfo`, `locationEvents`) 
			VALUES ('".$oCon->escape($this->
					sLocationName)."', '".$oCon->escape($this->
					sSlug)."', '".$oCon->escape($this->
					sLocationAddress)."', '".$oCon->escape($this->
					sLocationSuburb)."', '".$oCon->escape($this->
					sLocationRegion)."', '".$oCon->escape($this->
					sLocationContact)."', '".$oCon->escape($this->
					sLocationWebsite)."', '".$oCon->escape($this->
					sLocationInfo)."', '".$oCon->escape($this->
					sLocationEvents)."')";
					
					echo "<pre>";
					echo "$sSql";
					echo "</pre>";
					
			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iBeerID
				$this->iLocationID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
		
		} else {
		// beer exists
			$sSql = "UPDATE location SET 
			Title = '".escape($this->
					sLocationName)."', 
			Description = '".escape($this->
					sLocationAddress)."', 
			Style = '".escape($this->
					sLocationSuburb)."', 
			Brewery = '".escape($this->
					sLocationRegion)."', 
			Alcohol = '".escape($this->
					sLocationContact)."' WHERE locationID = ".escape($this->
					iLocationID);
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		}
		
		$oCon->close();
		}

	public function update(){
		// insert
		$oCon = new Connection();
		
		// beer exists
		$sSql = "UPDATE location SET 
		locationName = '".$oCon->escape($this->
				sLocationName)."', 
		locationAddress = '".$oCon->escape($this->
				sLocationAddress)."', 
		locationSuburb = '".$oCon->escape($this->
				sLocationSuburb)."', 
		locationRegion = '".$oCon->escape($this->
				sLocationRegion)."', 
		locationContact = '".$oCon->escape($this->
				sLocationContact)."',
		locationWebsite = '".$oCon->escape($this->
				sLocationWebsite)."',
		locationInfo = '".$oCon->escape($this->
				sLocationInfo)."',
		locationEvents = '".$oCon->escape($this->
				sLocationEvents)."' WHERE locationID = ".$oCon->escape($this->
				iLocationID);

/*
		echo $sSql;
		exit;
*/
		
		$bResult = $oCon->query($sSql);

		if($bResult==false){
			die($sSql."did not run");
		}
		
		
/*
		if($_POST["newLocationManagerID"]!=0){
			// set current user to 0
			$sSql = "UPDATE user SET 
			locationID = '0' WHERE UserID = ".$oCon->escape($this->
					iOldLocationManagerID);
					
								
	echo $sSql;
			$bResult = $oCon->query($sSql);
	
			if($bResult==false){
				die($sSql."did not run");
			}
		}
		
		if($this->iNewLocationManagerID!=0){	
					// set new user to locationID
			$sSql = "UPDATE user SET 
			locationID = '".$oCon->escape($this->
					iLocationID)."' WHERE UserID = ".$oCon->escape($this->
					iNewLocationManagerID);
			
			$bResult = $oCon->query($sSql);
			
			echo $sSql;
	
			if($bResult==false){
				die($sSql."did not run");
			}

		}
*/

		$oCon->close();
		}

public function updateLocationSlugs(){
	// update product
	$oCon = new Connection();
	
	$sSql = "UPDATE location SET 
		slug = '".$oCon->escape($this->
				sSlug)."' WHERE locationID = ".$this->iLocationID;	
		
		echo $sSql;
		
		$bResult = $oCon->query($sSql);
		if($bResult==false){
			die($sSql."did not run");
		}

	
	$oCon->close();
	}

		
	static public function all(){
		//1 make connection
		$oCon = new Connection();
		$aLocations = array();
		
		//2 create query
		$sSql = "SELECT locationID FROM location ORDER BY locationName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iLocationID = $aRow["locationID"];
				$oLocation = new Location();
				$oLocation->load($iLocationID);
				$aLocations[] = $oLocation; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aLocations;

	}
	
		static public function suburb($suburb){
		//1 make connection
		$oCon = new Connection();
		$aLocations = array();
		
		//2 create query
		$sSql = "SELECT locationID FROM location WHERE locationSuburb ='$suburb' ORDER BY locationName";
			
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iLocationID = $aRow["locationID"];
				$oLocation = new Location();
				$oLocation->load($iLocationID);
				$aLocations[] = $oLocation; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aLocations;

	}
	
	static public function lists(){
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

		return $aLocations;

	}
	
	static public function locationlist(){
		//1 make connection
		$oCon = new Connection();
		$aLocations = array();
		
		// exclude WHERE ClaimStatus=0
		
		//2 create query
		$sSql = "SELECT locationID FROM location ORDER BY locationName";
	
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

		return $aLocations;

	}
	
	static public function locationMarkers(){
		//1 make connection
		$oCon = new Connection();
		$aMarkers = array();
		
		// exclude WHERE ClaimStatus=0
		
		//2 create query
		$sSql = "SELECT locationID FROM location";
		//$sSql = "SELECT locationID FROM location ORDER BY lastactivity";
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iLocationID = $aRow["locationID"];
				
				$oLocation = new Location();
				$oLocation->load($iLocationID);		

				if(($oLocation->lat != 0) && ($oLocation->lng != 0) ){
					
					$AvID = Availability::loadlatestID($iLocationID);
/*
					echo "<pre>";
					echo $AvID;
					echo "</pre>";
*/
					
					$oAvail = new Availability();
					$oAvail->load($AvID);
					
					$beerID = $oAvail->beerID;
					
					
					$oBeer = new Beer();
					$oBeer->load($beerID);
					$brewname = $oBeer->title;
					
					$oBrewery = new Brewery();
					$oBrewery->load($oBeer->breweryID);
					$breweryname = $oBrewery->breweryname;
					
					
					if($oBeer->breweryID>1){
						$breweryphoto = $oBrewery->breweryphoto;
					}
					
					if ($oBeer->photo!=""){
						$iconfile = $oBeer->photo;
						//$iconimage = 'http://brewhound.nz/thumbs/25x25/images/'.$iconfile;
						
					} elseif ($oBeer->breweryID>1){
						$iconfile = $breweryphoto;
						//$iconimage = 'http://brewhound.nz/thumbs/25x25/images/'.$iconfile;
					} 
					else {
						$iconimage = 'http://brewhound.nz/assets/images/hound.png';
					}
					
					
/*
					$oAvail = new Availability();
					$latestBeer = $oAvail->load($latestAvID);
					
					$oBeer = new Beer();
					$oBeer->load($latestBeer);
					echo $oBeer->title;
*/
					
					// 'latest' => $oBeer->title,

					//$aMarkers[] = array( 'id'=>$iLocationID,'markername' => $oLocation->locationname, 'link' => $oLocation->slug, array('lat' => floatval($oLocation->lat),'lng' => floatval($oLocation->lng)));


					$aMarkers[] = array( 'id'=>$iLocationID,'markername' => $oLocation->locationname, 'link' => $oLocation->slug, 'latest' => $breweryname.' '.$brewname, 'image' => $iconfile, array('lat' => floatval($oLocation->lat),'lng' => floatval($oLocation->lng)));
					
				}
				
			}

		//5 close connection
		$oCon->close();

		return $aMarkers;

	}

	static public function activty($locationID,$date){
		$oCon = new Connection();
		
		$sSql = "UPDATE location SET lastactivity = '$date' WHERE locationID = $locationID";
		
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
	}

	static public function listAjaxLocations(){
		//1 make connection
		$oCon = new Connection();
		$aLocations = array();
		
		// exclude WHERE ClaimStatus=0
		
		//2 create query
		$sSql = "SELECT locationID FROM location ORDER BY locationName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iLocationID = $aRow["locationID"];
				$oLocation = new Location();
				$oLocation->load($iLocationID);
				$aLocations['-'.$iLocationID] = $oLocation->locationname; // add to array
			}

		//5 close connection
		$oCon->close();

		return $aLocations;

	}

	static public function locationIDs(){
		//1 make connection
		$oCon = new Connection();
		$aLocations = array();
		
		// exclude WHERE ClaimStatus=0
		
		//2 create query
		$sSql = "SELECT locationID FROM location";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iLocationID = $aRow["locationID"];
				$aLocations[] = $iLocationID; // add to array
			}

		//5 close connection
		$oCon->close();

		return $aLocations;

	}
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'locationID';
			return $this->iLocationID;
			break;
		case 'lat';
			return $this->sLat;
			break;
		case 'lng';
			return $this->sLng;
			break;
		case 'slug';
			return $this->sSlug;
			break;
		case 'oldlocationmanagerID';
			return $this->iOldLocationManagerID;
			break;
		case 'newlocationmanagerID';
			return $this->iNewLocationManagerID;
			break;	
		case 'claimstatus';
			return $this->iClaimStatus;
			break;
		case 'locationname';
			return $this->sLocationName;
			break;	
		case 'locationaddress';
			return $this->sLocationAddress;
			break;	
		case 'locationsuburb';
			return $this->sLocationSuburb;
			break;	
		case 'locationregion';
			return $this->sLocationRegion;
			break;	
		case 'locationcontact';
			return $this->sLocationContact;
			break;
		case 'locationwebsite';
			return $this->sLocationWebsite;
			break;
		case 'locationinfo';
			return $this->sLocationInfo;
			break;
		case 'locationevents';
			return $this->sLocationEvents;
			break;
		case 'alocations';
			return $this->aLocations;
			break;
		default;
		
			die($var . " is not accessible");
			break;
		}
	}
	
		// setter method
	public function __set($var,$value){
		switch ($var){
		case 'locationID';
			$this->sLocationID = $value;
			break;
		case 'slug';
			$this->sSlug = $value;
			break;
		case 'oldlocationmanagerID';
			$this->iOldLocationManagerID = $value;
			break;
		case 'newlocationmanagerID';
			$this->iNewLocationManagerID = $value;
			break;
		case 'locationname';
			$this->sLocationName = $value;
			break;
		case 'locationaddress';
			$this->sLocationAddress = $value;
			break;
		case 'locationsuburb';
			$this->sLocationSuburb = $value;
			break;
		case 'locationregion';
			$this->sLocationRegion = $value;
			break;				
		case 'locationcontact';
			$this->sLocationContact = $value;
			break;
		case 'locationwebsite';
			$this->sLocationWebsite = $value;
			break;
		case 'locationinfo';
			$this->sLocationInfo = $value;
			break;
		case 'locationevents';
			$this->sLocationEvents = $value;
			break;
		default;
			die($var . " setter is not accessible");
			break;
		}
	}
}
/*
$oLocation = new Location();
$oLocation->load(1);

echo "<pre>";
print_r($oLocation);

//echo "<h1>".$oLike->iUserID."</h1>";
echo "</pre>";
*/


?>