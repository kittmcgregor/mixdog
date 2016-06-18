<?php 
	
	require_once("connection.php"); 
	require_once("like.php");
	require_once("availabilitymanager.php");
	
class Beer{
	private $iBeerID;
	private $sTitle;
	private $sDescription;
	private $iStyleID;
	private $sNewStyle;
	private $sBrewery;
	private $iBreweryID;
	private $sBreweryName;
	private $sAlcohol;
	private $sIBU;
	private $bActive;
	private $bExclusive;
	private $bFreshHop;
 	private $sNewLocation;
	private $sPhoto;
	private $aLikes;
	private $aLocations;
	private $aBeers;
	private $sSlug;

	public  function __construct(){
		$this->iBeerID = 0;
		$this->sTitle = "";
		$this->sDescription = "";
		$this->iStyleID = 0;
		$this->sNewStyle = "";
		$this->sBrewery = "";
		$this->iBreweryID = 0;
		$this->sBreweryName = "";
		$this->sAlcohol = 0;
		$this->sIBU = '';
		$this->bActive = 1;
		$this->bExclusive = 0;
		$this->bFreshHop = 0;
 		$this->sNewLocation = "";
		$this->sPhoto = "";
		$this->aLikes = array();
		$this->aLocations = array();
		$this->aBeers = array();
		$this->sSlug = "";
		
	}

	public function loadByName($beerName){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BeerID, slug, Title,  Description, StyleID, BreweryID, Brewery, BreweryName, Alcohol, IBU, Active, Exclusive, FreshHop, Photo FROM  beer WHERE  slug ='$beerName' AND active=1";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iBeerID = $aRow["BeerID"];
		$this->sSlug = $aRow["slug"];
		$this->sTitle = $aRow["Title"];
		$this->sDescription = $aRow["Description"];
		$this->iStyleID = $aRow["StyleID"];
//		$this->sNewStyle = $aRow["NewStyle"];
		$this->iBreweryID = $aRow["BreweryID"];
		$this->sBrewery = $aRow["Brewery"];
		$this->sBreweryName = $aRow["BreweryName"];
		$this->sAlcohol = $aRow["Alcohol"];
		$this->sIBU = $aRow["IBU"];
		$this->bActive = $aRow["Active"];
		$this->bExclusive = $aRow["Exclusive"];
		$this->bFreshHop = $aRow["FreshHop"];
// 		$this->sNewLocation = $aRow["NewLocation"];
		$this->sPhoto = $aRow["Photo"];

		
/*
		$sSql = "SELECT likeID FROM `like` WHERE BeerID =1";
		$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			
			$oLike = new Like();
			$oLike->load($aRow["likeID"]);
			$this->aLikes[] = $oLike;
		}
		
		$sSql = "SELECT `locationID` FROM `availability` WHERE beerID=1";
		$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			
			$oLocation = new Location();
			$oLocation->load($aRow["locationID"]);
			$this->aLocations[] = $oLocation; // add locations to list
		}
*/	
		
		//5 close connection
		$oCon->close();
		}


	public function load($iBeerID){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BeerID, slug, Title,  Description, StyleID, BreweryID, Brewery, BreweryName, Alcohol, IBU, Active, Exclusive, FreshHop, Photo FROM  beer WHERE  BeerID =".$iBeerID;
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iBeerID = $aRow["BeerID"];
		$this->sSlug = $aRow["slug"];
		$this->sTitle = $aRow["Title"];
		$this->sDescription = $aRow["Description"];
		$this->iStyleID = $aRow["StyleID"];
//		$this->sNewStyle = $aRow["NewStyle"];
		$this->iBreweryID = $aRow["BreweryID"];
		$this->sBrewery = $aRow["Brewery"];
		$this->sBreweryName = $aRow["BreweryName"];
		$this->sAlcohol = $aRow["Alcohol"];
		$this->sIBU = $aRow["IBU"];
		$this->bActive = $aRow["Active"];
		$this->bExclusive = $aRow["Exclusive"];
		$this->bFreshHop = $aRow["FreshHop"];
// 		$this->sNewLocation = $aRow["NewLocation"];
		$this->sPhoto = $aRow["Photo"];
		
		$sSql = "SELECT likeID FROM `like` WHERE BeerID =".$iBeerID;
		$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			
			$oLike = new Like();
			$oLike->load($aRow["likeID"]);
			$this->aLikes[] = $oLike;
		}
		
		$sSql = "SELECT `locationID` FROM `availability` WHERE beerID=$iBeerID";
		$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			
			$oLocation = new Location();
			$oLocation->load($aRow["locationID"]);
			$this->aLocations[] = $oLocation; // add locations to list
		}	
		
		//5 close connection
		$oCon->close();
		}
	
	public static function loadIDsByBrewery($iBreweryID){
	
		$aBeers = array();
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BeerID FROM  beer WHERE BreweryID =".$iBreweryID." AND active=1 ORDER BY Title";
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			
			$oBeers = new Beer();
			$oBeers->load($aRow["BeerID"]);
			$aBeers[] = $oBeers; // add locations to list
		}
		return $aBeers;
		
		//5 close connection
		$oCon->close();
	}

	static public function totalBrews(){
	//return a list of all likes
				
	// query 
	$oCon = new Connection();
	//$sSql = "SELECT `beerID` FROM `availability` WHERE locationID='$locationID' ORDER BY beerID DESC";
	$sSql = "SELECT COUNT(*) FROM beer";

	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	$aRow = $oCon->fetchArray($oResultSet);
	$iTotal = $aRow["COUNT(*)"];
	
	$oCon->close();
	return $iTotal;
	}
	
	
	public function	allBeers(){
		//1 make connection
		$oCon = new Connection();
		$aAllActive = array();
		
		//2 create query
		$sSql = "SELECT BeerID FROM beer";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				$aAllActive[] = $oBeer; // add to array
			}

		//5 close connection
		$oCon->close();
		return count($aAllActive);
	}

	public function	listBrews(){
		//1 make connection
		$oCon = new Connection();
		$aAllBrews = array();
		
		//2 create query
		$sSql = "SELECT BeerID FROM beer ORDER BY Title";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				if($oBeer->breweryID!=1){
					$breweryname = $oBeer->breweryname;
					} else {
					$breweryname = "";
					}				
				$aAllBrews['-'.$iBeerID] = $oBeer->title.' - '.$breweryname; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aAllBrews;
		
		
	}

	public function	listBrewsMeta(){
		//1 make connection
		$oCon = new Connection();
		$aAllBrews = array();
		
		//2 create query
		$sSql = "SELECT slug, Title, BreweryName FROM beer ORDER BY Title";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$slug = $aRow["slug"];
				$title = $aRow["Title"];
				$brewery = $aRow["BreweryName"];
/*
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
*/
/*
				if($oBeer->breweryID!=1){
					$breweryname = $oBeer->breweryname;
				} else {
				$breweryname = "";
				}
				if($oBeer->photo){
					$photo = $oBeer->photo;
				} else {
					$oBrewery = new Brewery();
					$oBrewery->load($oBeer->breweryID);
					$photo = $oBrewery->breweryphoto;
				}
*/
				
				//$aAllBrews[$oBeer->slug] = array('brewtitle' => "$oBeer->title - $breweryname",'brewimg' => $photo);
				$aAllBrews[$slug] = $title.' - '.$brewery; // add to array
				//$aAllBrews[$oBeer->slug] = $oBeer->title.' - '.$breweryname; // add to array
				//$aAllBrews['-'.$iBeerID] = $oBeer->title.' - '.$breweryname; // add to array
			}

		//5 close connection
		$oCon->close();
		return $aAllBrews;
		
		
	}

	public function	listAjaxBrews(){
		//1 make connection
		$oCon = new Connection();
		$aAllBrews = array();
		
		//2 create query
		$sSql = "SELECT BeerID FROM beer ORDER BY Title";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				if($oBeer->breweryID!=1){
					$breweryname = $oBeer->breweryname;
					} else {
					$breweryname = "";
					}
				$aAllBrews[] = array('id'=>$iBeerID, 'name'=>"$oBeer->title - $breweryname"); // add to array
			}

		//5 close connection
		$oCon->close();
		return $aAllBrews;
		
		
	}

	
public function	listBrewData($id){
		//1 make connection
		$oCon = new Connection();
		$aBrewData = array();
		$avArray = array();
		
		//2 create query
		$sSql = "SELECT BeerID FROM beer WHERE BeerID=$id";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$iBeerID = $aRow["BeerID"];
		$oBeer = new Beer();
		$oBeer->load($iBeerID);
		$beername = $oBeer->title;
		$beerphoto = $oBeer->photo;
		
		$oAv = Availability::loadLocationIDs($id);
		$aLocs = array();
		for($i=0;$i<count($oAv);$i++){
			$oLoc = new Location();
			$oLoc->load($oAv[$i]);
			$LocID = $oLoc->locationID;
			$aLocs[$i] = $LocID;
		}

		
		$aBrewData[] = array('Brewid'=>$id,'Brew'=>$beername,'BrewPhoto'=>$beerphoto,$aLocs);
		
		//5 close connection
		$oCon->close();
		return $aBrewData;
		
	}
	
	public function save(){
		// insert
		$oCon = new Connection();
	
		if($this->beerID==0){
		// beer does not exist - do insert
		$sSql = "INSERT INTO beer (Title, slug, Description, StyleID, NewStyle, BreweryID, Brewery, BreweryName, Alcohol, IBU, Exclusive, FreshHop, Active, NewLocation, Photo) 
			VALUES ('".$oCon->escape($this->
					sTitle)."','".$oCon->escape($this->
					sSlug)."', '".$oCon->escape($this->
					sDescription)."', '".$oCon->escape($this->
					iStyleID)."', '".$oCon->escape($this->
					sNewStyle)."', '".$oCon->escape($this->
					iBreweryID)."', '".$oCon->escape($this->
					sBrewery)."', '".$oCon->escape($this->
					sBreweryName)."', '".$oCon->escape($this->
					sAlcohol)."', '".$oCon->escape($this->
					sIBU)."', '".$oCon->escape($this->
					bExclusive)."', '".$oCon->escape($this->
					bFreshHop)."', '".$oCon->escape($this->
					bActive)."', '".$oCon->escape($this->
					sNewLocation)."', '".$oCon->escape($this->
					sPhoto)."')";
			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iBeerID
				$this->iBeerID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
		
		} else {
		// beer exists
			$sSql = "UPDATE beer SET 
			Title = '".$this->
					sTitle."', 
			Description = '".$this->
					sDescription."', 
			NewStyle = '".$this->
					sNewStyle."', 
			Brewery = '".$this->
					sBrewery."', 
			Alcohol = '".$this->
					sAlcohol."',
			Active = '".$this->
					bActive."', 
			Photo = '".$this->
					sPhoto."' WHERE BeerID = ".$this->
					iBeerID;
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		}
		
		$oCon->close();
		}
	
public function updateslugs(){
	// update product
	$oCon = new Connection();
	
	$sSql = "UPDATE beer SET 
		slug = '".$oCon->escape($this->
				sSlug)."' WHERE BeerID = ".$this->
				iBeerID;	
		
		echo $sSql;
		
		$bResult = $oCon->query($sSql);
		if($bResult==false){
			die($sSql."did not run");
		}

	
	$oCon->close();
	}
			
	public function update(){
		// update product
		$oCon = new Connection();
		
		$sSql = "UPDATE beer SET 
			Title = '".$oCon->escape($this->
					sTitle)."', 
			Description = '".$oCon->escape($this->
					sDescription)."', 
			StyleID = '".$oCon->escape($this->
					iStyleID)."',
			BreweryID = '".$oCon->escape($this->
					iBreweryID)."',
			Brewery = '".$oCon->escape($this->
					sBrewery)."',
			BreweryName = '".$oCon->escape($this->
					sBreweryName)."', 
			Alcohol = '".$oCon->escape($this->
					sAlcohol)."', 
			IBU = '".$oCon->escape($this->
					sIBU)."',
			Exclusive = '".$oCon->escape($this->
					bExclusive)."', 
			FreshHop = '".$oCon->escape($this->
					bFreshHop)."', 
			Photo = '".$oCon->escape($this->
					sPhoto)."' WHERE BeerID = ".$this->
					iBeerID;
				
			
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}

		
		$oCon->close();
		}
		
	public static function search($keyword){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT BeerID FROM beer WHERE (Title LIKE '%".$oCon->escape($keyword)."%' OR Description LIKE '%".$oCon->escape($keyword)."%' OR Brewery LIKE '%".$oCon->escape($keyword)."%' OR BreweryName LIKE '%".$oCon->escape($keyword)."%') AND Active='1' ORDER BY Title";
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
	

	public function all(){
		
		$aBeers = array();
		//1 make connection
		$oCon = new Connection();
		//2 create query
		$sSql = "SELECT beerID FROM beer";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow=$oCon->fetchArray($oResultSet)){
			$iBeerID = $aRow["beerID"];
			$oBeer = new Beer();
			$oBeer->load($iBeerID);
			$aBeers[] = $oBeer->beerid;
		}
		return $aBeers;
	}
	
/*
		public function exclusive(){
		//1 make connection
		$oCon = new Connection();
		//2 create query
		$sSql = "SELECT beerID FROM beer WHERE Exclusive=1";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow=$oCon->fetchArray($oResultSet)){
			$iBeerID = $aRow["beerID"];
			$oBeers = new Beer();
			$oBeer->load($iBeerID);
		}
	}
*/
	
	public function loadMostLikes(){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT BeerID, COUNT( BeerID ) TotalLike
				FROM  `like` 
				GROUP BY BeerID
				ORDER BY TotalLike DESC ";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		
		//load all products of type
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				
				$this->aBeers[] = $oBeer; // add to array
			}

		//5 close connection
		$oCon->close();
		}
		
	public function loadLikeIDs(){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT BeerID, COUNT( BeerID ) TotalLike
				FROM  `like` 
				GROUP BY BeerID
				ORDER BY TotalLike DESC ";

		//3 execute query
		$oResultSet = $oCon->query($sSql);

		
		//load all products of type
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				
				$this->aBeers[] = $oBeer; // add to array
			}

		//5 close connection
		$oCon->close();
		}
		
	static public function beerLikelist(){
		//1 make connection
		$oCon = new Connection();
		
		$aBeerLikelist = array();
		
		//2 create query
		$sSql = "SELECT BeerID, COUNT( BeerID ) TotalLike
				FROM  `like` 
				GROUP BY BeerID
				ORDER BY TotalLike DESC ";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iBeerID = $aRow["BeerID"];
				$iTotalLikes = $aRow["TotalLike"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				$aBeerLikelist[$iBeerID] = $oBeer->aLikes; // add to array
			}

		//5 close connection
		$oCon->close();

		return $aBeerLikelist;
	}
	
	static public function exclusive(){
		//1 make connection
		$oCon = new Connection();
		
		$aExclusives = array();
		
		//2 create query
		$sSql = "SELECT BeerID  
				FROM  beer 
				WHERE Exclusive=1
				ORDER BY BeerID DESC ";
		//echo $sSql;
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
		while($aRow=$oCon->fetchArray($oResultSet)){
		
			$iBeerID = $aRow["BeerID"];
/*
			$oBeer = new Beer();
			$oBeer->load($iBeerID);
*/
			$aExclusives[] = $iBeerID; // add Exclusives to list
		}

		//5 close connection
		$oCon->close();

		return $aExclusives;
	}
	
	static public function FreshHop(){
		//1 make connection
		$oCon = new Connection();
		
		$aFreshHop = array();
		
		//2 create query
		$sSql = "SELECT BeerID  
				FROM  beer 
				WHERE FreshHop=1
				ORDER BY BeerID DESC ";
		//echo $sSql;
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
		while($aRow=$oCon->fetchArray($oResultSet)){
		
			$iBeerID = $aRow["BeerID"];

			$aFreshHop[] = $iBeerID; // add Exclusives to list
		}

		//5 close connection
		$oCon->close();

		return $aFreshHop;
	}
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'slug';
			return $this->sSlug;
			break;
		case 'beerid';
			return $this->iBeerID;
			break;
		case 'beerID';
			return $this->iBeerID;
			break;
		case 'title';
			return $this->sTitle;
			break;
		case 'description';
			return $this->sDescription;
			break;
		case 'styleID';
			return $this->iStyleID;
			break;
		case 'style';
			return $this->sStyle;
			break;				
		case 'brewery';
			return $this->sBrewery;
			break;
		case 'breweryID';
			return $this->iBreweryID;
			break;
		case 'breweryname';
			return $this->sBreweryName;
			break;
		case 'alcohol';
			return $this->sAlcohol;
			break;
		case 'ibu';
			return $this->sIBU;
			break;
		case 'active';
			return $this->bActive;
			break;
		case 'exclusive';
			return $this->bExclusive;
			break;
		case 'freshhop';
			return $this->bFreshHop;
			break;
		case 'photo';
			return $this->sPhoto;
			break;
		case 'likes';
			return $this->aLikes;
			break;
		case 'locations';
			return $this->aLocations;
			break;
		case 'locationslist';
			return $this->aLocations;
			break;
		case 'allBeers';	
			return $this->aBeers;
			break;
		default;
			die($var . " beer getter is not accessible");
			break;
		}
	}
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'beerID';
			$this->iBeerID = $value;
			break;
		case 'slug';
			$this->sSlug = $value;
			break;
		case 'title';
			$this->sTitle = $value;
			break;
		case 'description';
			$this->sDescription = $value;
			break;
		case 'styleID';
			$this->iStyleID = $value;
			break;
		case 'newstyle';
			$this->sNewStyle = $value;
			break;				
		case 'brewery';
			$this->sBrewery = $value;
			break;
		case 'breweryID';
			$this->iBreweryID = $value;
			break;
		case 'breweryname';
			$this->sBreweryName = $value;
			break;
		case 'alcohol';
			$this->sAlcohol = $value;
			break;
		case 'ibu';
			$this->sIBU = $value;
			break;
		case 'active';
			$this->bActive = $value;
			break;
		case 'exclusive';
			$this->bExclusive = $value;
			break;
		case 'freshhop';
			$this->bFreshHop = $value;
			break;
		case 'newlocation';
			$this->sNewLocation = $value;
			break;
		case 'locations';
			$this->aLocations = $value;
			break;
		case 'photo';
			$this->sPhoto = $value;
			break;
		default;
			die($var . " setter is not accessible");
			break;
		}
	}


}

/*
$aExclusive = Beer::exclusive();
	echo "<pre>";
	print_r($aExclusive);
	echo "</pre>";
*/

/*
$oBeers = new Beer();
$oBeers->all();

echo "<pre>";
//print_r(Beer::search("Maori"));
print_r($oBeer);
//echo "<h1>".$oBeer->title."</h1>";
echo "</pre>";
*/

?>