<?php 
	
	require_once("connection.php");
	require_once("beer.php");
	
class AllBeers{
	private $iBeerID;
	private $sTitle;
	private $sDescription;
	private $sBrewery;
	private $sAlcohol;
	private $sPhoto;
	
	private $aBeers;
// 	private $iTotalBeers;

	public  function __construct(){
		$this->iBeerID = 0;
		$this->sTitle ="";
		$this->sDescription ="";
		$this->sBrewery ="";
		$this->sAlcohol = 0;
		$this->sPhoto ="";
		
		$this->aBeers = array();
	}
	
	public function load(){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BeerID FROM beer WHERE active = 1 ORDER BY Title;";


		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		
		//load all beers
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				
				$this->aBeers[] = $oBeer; // add to array
			}
		
		//5 close connection
		$oCon->close();
		}

	public function loadPagination($limit){
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT  BeerID,  Title,  Description,  styleID, Style,  Brewery,  Alcohol, Active, Photo FROM beer WHERE active = 1 ORDER BY beerID DESC $limit";


		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		
		//load all beers
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iBeerID = $aRow["BeerID"];
				$oBeer = new Beer();
				$oBeer->load($iBeerID);
				
				$this->aBeers[] = $oBeer; // add to array
			}
		
		//5 close connection
		$oCon->close();
		}
		
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
			case 'brewery';
				return $this->sBrewery;
				break;
			case 'alcohol';
				return $this->sAlcohol;
				break;
			case 'photo';
				return $this->sPhoto;
				break;
			case 'allBeers';
				return $this->aBeers;
				break;
			case 'totalBeers';
				return array_sum($this->aBeers);
			break;
			default;
				die($var . " is not accessible");
				break;
			}
		}
}

/*
$oAllBeers = new AllBeers();
$oAllBeers->load();

echo "<pre>";
print_r($oAllBeers);

//echo "<h1>".$oBeer->title."</h1>";
echo "</pre>";
*/
?>