<?php
	require_once("connection.php");
	
class Brewery{
		private $iBreweryID;
		private $iClaimStatus;	
		private $sName;
		private $sFullName;
		private $sBreweryWebsite;
		private $sAddress;
		private $sPhoto;
		private $sSlug;
		
		public  function __construct(){
		$this->iBreweryID = 0;
		$this->iClaimStatus = 0;
		$this->sName = "";
		$this->sFullName = "";		
		$this->sBreweryWebsite = "";
		$this->sAddress = "";
		$this->sPhoto = "";
		$this->sSlug = "";
		}
		
	public function load($iBreweryID){
			
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BreweryID, slug, ClaimStatus, BreweryName, FullName, BreweryWebsite, BreweryAddress, BreweryPhoto FROM  BreweryTable WHERE  BreweryID =".$iBreweryID;
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iBreweryID = $aRow["BreweryID"];
		$this->sSlug = $aRow["slug"];
		$this->iClaimStatus = $aRow["ClaimStatus"];		
		$this->sName = $aRow["BreweryName"];
		$this->sFullName = $aRow["FullName"];
		$this->sBreweryWebsite = $aRow["BreweryWebsite"];
		$this->sAddress = $aRow["BreweryAddress"];
		$this->sPhoto = $aRow["BreweryPhoto"];
		
		//5 close connection
		$oCon->close();
		}


	public function loadBySlug($slug){
			
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BreweryID, slug, ClaimStatus, BreweryName, FullName, BreweryWebsite, BreweryAddress, BreweryPhoto FROM  BreweryTable WHERE  slug ='$slug'";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iBreweryID = $aRow["BreweryID"];
		$this->sSlug = $aRow["slug"];
		$this->iClaimStatus = $aRow["ClaimStatus"];		
		$this->sName = $aRow["BreweryName"];
		$this->sFullName = $aRow["FullName"];
		$this->sBreweryWebsite = $aRow["BreweryWebsite"];
		$this->sAddress = $aRow["BreweryAddress"];
		$this->sPhoto = $aRow["BreweryPhoto"];
		
		//5 close connection
		$oCon->close();
		}

	static public function totalBreweries(){
		//return a list of all likes
					
		// query 
		$oCon = new Connection();
		//$sSql = "SELECT `beerID` FROM `availability` WHERE locationID='$locationID' ORDER BY beerID DESC";
		$sSql = "SELECT COUNT(*) FROM BreweryTable";
	
		$oResultSet = $oCon->query($sSql);
			
		// load all subjects and add to array
		$aRow = $oCon->fetchArray($oResultSet);
		$iTotal = $aRow["COUNT(*)"];
		
		$oCon->close();
		return $iTotal;
	}

	static public function all(){
		//1 make connection
		$oCon = new Connection();
		$aBreweries = array();
		
		//2 create query
		$sSql = "SELECT BreweryID FROM BreweryTable ORDER BY BreweryName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBreweryID = $aRow["BreweryID"];
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				$aBreweries[] = $oBrewery; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aBreweries;

	}
		
		public function save(){
			// insert
			$oCon = new Connection();
		
			if($this->breweryID==0){
			// beer does not exist - do insert
			$sSql = "INSERT INTO BreweryTable (BreweryName, FullName, slug, BreweryWebsite, BreweryAddress, BreweryPhoto) 
			VALUES ('".$oCon->escape($this->
					sName)."', '".$oCon->escape($this->
					sFullName)."','".$oCon->escape($this->
					sSlug)."', '".$oCon->escape($this->
					sBreweryWebsite)."', '".$oCon->escape($this->
					sAddress)."', '".$oCon->escape($this->
					sPhoto)."')";

			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iBeerID
				$this->iBreweryID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
		
		} else {
		// beer exists
			$sSql = "UPDATE BreweryTable SET 
			BreweryName = '".$this->
					sName."', 
			BreweryAddress = '".$this->
					sAddress."', 
			BreweryPhoto = '".$this->
					sPhoto."' WHERE BreweryID = ".$this->
					iBreweryID;
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		}
	}	
	
	public function update(){

	$oCon = new Connection();
	$sSql = "UPDATE BreweryTable SET 
			BreweryName = '".$this->
					sName."', 
			FullName = '".$this->
					sFullName."', 
			BreweryWebsite = '".$this->
					sBreweryWebsite."', 
			BreweryAddress = '".$this->
					sAddress."', 
			BreweryPhoto = '".$this->
					sPhoto."' WHERE BreweryID = ".$this->
					iBreweryID;
					
/*
			echo $sSql;
			exit;
*/
					
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}

	
	}
		static public function brewerylist(){
		//1 make connection
		$oCon = new Connection();
		$aBreweryList = array();
		
		//2 create query
		$sSql = "SELECT breweryID FROM BreweryTable ORDER BY BreweryName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBreweryID = $aRow["breweryID"];
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				$aBreweryList[$iBreweryID] = $oBrewery->breweryname; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aBreweryList;

	}

	static public function breweryIDlist(){
		//1 make connection
		$oCon = new Connection();
		$aBreweryList = array();
		
		//2 create query
		$sSql = "SELECT breweryID FROM BreweryTable ORDER BY BreweryName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBreweryID = $aRow["breweryID"];
				$aBreweryList[] = $iBreweryID; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aBreweryList;

	}

public function updateBrewerySlugs(){
	// update product
	$oCon = new Connection();
	
	$sSql = "UPDATE BreweryTable SET 
		slug = '".$oCon->escape($this->
				sSlug)."' WHERE BreweryID = ".$this->iBreweryID;	
		
		echo $sSql;
		
		$bResult = $oCon->query($sSql);
		if($bResult==false){
			die($sSql."did not run");
		}

	
	$oCon->close();
	}
	
static public function brewerylistoffset(){
		//1 make connection
		$oCon = new Connection();
		$aBreweryList = array();
		
		//2 create query
		$sSql = "SELECT breweryID FROM BreweryTable ORDER BY BreweryName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBreweryID = $aRow["breweryID"];
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				if($iBreweryID==1){
					continue;
				} else {
					//$aBreweryList[$iBreweryID] = $oBrewery->breweryname; // add to array
					$aBreweryList[$iBreweryID] = array('name'=>$oBrewery->breweryname,'slug'=>$oBrewery->slug);
				}
			}

		//5 close connection
		$oCon->close();
		return $aBreweryList;

	}


	// getter method
	public function __get($var){
		switch ($var){
		case 'breweryID';
			return $this->iBreweryID;
			break;
		case 'slug';
			return $this->sSlug;
			break;
		case 'claimstatus';
			return $this->iClaimStatus;
			break;	
		case 'breweryname';
			return $this->sName;
			break;
		case 'fullbreweryname';
			return $this->sFullName;
			break;			
		case 'brewerywebsite';
			return $this->sBreweryWebsite;
			break;
		case 'breweryaddress';
			return $this->sAddress;
			break;
		case 'breweryphoto';
			return $this->sPhoto;
			break;
		default;
			die($var . " getter is not accessible");
			break;
		}
	}
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'breweryID';
			$this->iBreweryID = $value;
			break;
		case 'slug';
			$this->sSlug = $value;
			break;
		case 'breweryname';
			$this->sName = $value;
			break;
		case 'fullbreweryname';
			$this->sFullName = $value;
			break;
		case 'brewerywebsite';
			$this->sBreweryWebsite = $value;
			break;
		case 'breweryaddress';
			$this->sAddress = $value;
			break;
		case 'breweryphoto';
			$this->sPhoto = $value;
			break;	
		default;
			die($var . " setter is not accessible");
			break;
		}
	}

}

/*
$oBrewery = new Brewery();

$oBrewery->load(1);

echo "<pre>";
//print_r(Beer::search("Maori"));
print_r($oBrewery);
//echo "<h1>".$oBeer->title."</h1>";
echo "</pre>";
*/


?>