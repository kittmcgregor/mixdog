<?php 
	require_once("connection.php"); 

class Style{
	
	private $iStyleID;
	private $sStyleName;
		
	public  function __construct(){
	$this->iStyleID = 0;	
	$this->sStyleName = "";
	}
	
	public function load($beerID){
		
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT StyleID, StyleName FROM `style` WHERE styleID =".$beerID;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iStyleID = $aRow["StyleID"];
			$this->sStyleName = $aRow["StyleName"];
		
			//5 close connection
			$oCon->close();
		
	}

		public function save(){
		// insert
			$oCon = new Connection();
		
			$sSql = "INSERT INTO `style` (`styleName`) 
				VALUES ('".$oCon->escape($this->
						sStyleName)."')";
					
			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iBeerID
				$this->iStyleID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
		}


	static public function all(){
		//return a list of all styles
		$aStyles = array();
		
		// query all style IDs
		$oCon = new Connection();
		$sSql = "SELECT  `StyleID` FROM  `style`";
		
		$oResultSet = $oCon->query($sSql);
		
		// load all styles and add to array
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iStyleID = $aRow["StyleID"];
			$oStyle = new Style();
			$oStyle->load($iStyleID);
			$aStyles[] = $oStyle; // add styles to list
		}
			$oCon->close();
			
			return $aStyles;
		}


	static public function stylelist(){
		//1 make connection
		$oCon = new Connection();
		$aStyleList = array();
		
		//2 create query
		$sSql = "SELECT styleID FROM style ORDER BY StyleName";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iStyleID = $aRow["styleID"];
				$oStyle = new Style();
				$oStyle->load($iStyleID);
				$aStyleList[$iStyleID] = $oStyle->stylename; // add to array
			}

		//5 close connection
		$oCon->close();

		return $aStyleList;

	}


	
	// getter method
	public function __get($var){
		switch ($var){
		case 'styleID';
			return $this->iStyleID;
			break;
		case 'stylename';
			return $this->sStyleName;
			break;	
		default;
			die($var . " is not accessible");
			break;
		}
	}
			// setter method
	public function __set($var,$value){
		switch ($var){
		case 'stylename';
			$this->sStyleName = $value;
			break;
		default;
			die($var . " setter is not accessible");
			break;
		}
	}
}
/*
$oStyle = new Style();
$oStyle->load(3);

echo "<pre>";
print_r($oStyle);

//echo "<h1>".$oLike->iUserID."</h1>";
echo "</pre>";
*/


?>