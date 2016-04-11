<?php 
	
	require_once("connection.php"); 
	require_once("like.php");
	require_once("availabilitymanager.php");
	
class Beer{
	private $iBeerID;
	private $sTitle;
	private $sDescription;
	private $iStyleID;
	private $sStyle;
	private $sBrewery;
	private $sAlcohol;
	private $bActive;
	private $sPhoto;
	private $aStyles;
	private $aLikes;
	private $aLocations;

	public  function __construct(){
		$this->iBeerID = 0;
		$this->sTitle = "";
		$this->sDescription = "";
		$this->iStyleID = 0;
		$this->sStyle = "";
		$this->sBrewery = "";
		$this->sAlcohol = 0;
		$this->bActive = 1;
		$this->sPhoto = "";
		$this->aStyles = array();
		$this->aLikes = array();
		$this->aLocations = array();
	}
		
	public function load($iBeerID){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BeerID,  Title,  Description, styleID, Style, Brewery, Alcohol, Active, Photo FROM  beer WHERE  BeerID =".$iBeerID;
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iBeerID = $aRow["BeerID"];
		$this->sTitle = $aRow["Title"];
		$this->sDescription = $aRow["Description"];
		$this->iStyleID = $aRow["styleID"];
		$this->sStyle = $aRow["Style"];
		$this->sBrewery = $aRow["Brewery"];
		$this->sAlcohol = $aRow["Alcohol"];
		$this->bActive = $aRow["Active"];
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

	public function save(){
		// insert
		$oCon = new Connection();
	
		if($this->beerID==0){
		// beer does not exist - do insert
		$sSql = "INSERT INTO beer (Title, Description, styleID, Style, Brewery, Alcohol, Active, Photo) 
			VALUES ('".$oCon->escape($this->
					sTitle)."', '".$oCon->escape($this->
					sDescription)."', '".$oCon->escape($this->
					iStyleID)."', '".$oCon->escape($this->
					sStyle)."', '".$oCon->escape($this->
					sBrewery)."', '".$oCon->escape($this->
					sAlcohol)."', '".$oCon->escape($this->
					bActive)."', '".$oCon->escape($this->
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
			Style = '".$this->
					sStyle."', 
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
		
	public function update(){
		// update product
		$oCon = new Connection();
		
		$sSql = "UPDATE beer SET 
			Title = '".$oCon->escape($this->
					sTitle)."', 
			Description = '".$oCon->escape($this->
					sDescription)."', 
			styleID = '".$oCon->escape($this->
					iStyleID)."',
			Style = '".$oCon->escape($this->
					sStyle)."', 
			Brewery = '".$oCon->escape($this->
					sBrewery)."', 
			Alcohol = '".$oCon->escape($this->
					sAlcohol)."', 
			Active = '".$oCon->escape($this->
					bActive)."', 
			Photo = '".$oCon->escape($this->
					sPhoto)."' WHERE BeerID = ".$this->
					iBeerID;
			$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}

		
		$oCon->close();
		}
		
	// getter method
	public function __get($var){
		switch ($var){
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
		case 'alcohol';
			return $this->sAlcohol;
			break;
		case 'active';
			return $this->bActive;
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
		default;
			die($var . " is not accessible");
			break;
		}
	}
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'beerID';
			$this->iBeerID = $value;
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
			case 'style';
			$this->sStyle = $value;
			break;				
		case 'brewery';
			$this->sBrewery = $value;
			break;
		case 'alcohol';
			$this->sAlcohol = $value;
			break;
		case 'active';
			$this->bActive = $value;
			break;
		case 'photo';
			$this->sPhoto = $value;
			break;
		default;
			die($var . " is not accessible");
			break;
		}
	}


}
/*
$oBeer = new Beer();
$oBeer->load(38);

echo "<pre>";
print_r($oBeer);

echo "<h1>".$oBeer->title."</h1>";
echo "</pre>";
*/

?>