<?php 
	require_once("connection.php"); 

class Like{
	
	private $iLikeID;
	private $iBeerID;
	private $iUserID;
	private $sDate;
		
	public  function __construct(){
	$this->iLikeID = 0;	
	$this->iBeerID = 0;
	$this->iUserID = 0;
	$this->sDate = "";
	}
	
	public function load($likeID){
		
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT likeID, userID, beerID, date FROM `like` WHERE likeID =".$likeID;
			//echo $sSql;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iLikeID = $aRow["likeID"];
			$this->iUserID = $aRow["userID"];
			$this->iBeerID = $aRow["beerID"];
			$this->sDate = $aRow["date"];
			
			//5 close connection
			$oCon->close();
		
	}
	
		public function loadByUser($userID){
		
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT likeID, userID, beerID FROM `like` WHERE userID =".$userID;
			//echo $sSql;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iLikeID = $aRow["likeID"];
			$this->iUserID = $aRow["userID"];
			$this->iBeerID = $aRow["beerID"];
			
			//5 close connection
			$oCon->close();
		
	}

	public function loadIfLiked($beerID,$userID){
	
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = 'SELECT likeID, userID, beerID FROM `like` WHERE beerID ='.$beerID.' AND userID = '.$userID;
			//echo $sSql;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
/*
			echo "<pre>";
			print_r($oResultSet);
			echo "</pre>";
*/

			//4 fetch data			
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iLikeID = $aRow["likeID"];
			$this->iUserID = $aRow["userID"];
			$this->iBeerID = $aRow["beerID"];

			
/*
			if( $aRow["num_rows"] == 0){
				return false;
			} else {
				return true;
			}
*/
			//5 close connection
			$oCon->close();
	}

public function save(){
			// open connection
			$oCon = new Connection();
			
			// if like does not exist - do insert
			$sSql = "INSERT INTO `like`(`beerID`, `userID`)
			VALUES ('".$oCon->escape($this->
					iBeerID)."', '".$oCon->escape($this->
					iUserID)."')";	
			$bResult = $oCon->query($sSql);
			
			echo $sSql;
			if($bResult==true){
				// update the iBeerID
				$this->iBeerID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
			
			// close connection
			$oCon->close();
		}
		
static public function remove($likeID){	
		// open connection
		$oCon = new Connection();
		
		$sSql = "DELETE FROM `like` WHERE `likeID`=".$likeID;
		
		$oCon->query($sSql);
		
		// close connection
		$oCon->close();
	}
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'likeID';
			return $this->iLikeID;
			break;
		case 'beerID';
			return $this->iBeerID;
			break;
		case 'userID';
			return $this->iUserID;
			break;
		case 'date';
			return $this->sDate;
			break;	
		default;
			die($var . " is not accessible");
			break;
		}
	}
	
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'likeID';
			$this->iLikeID = $value;
			break;
		case 'beerID';
			$this->iBeerID = $value;
			break;
		case 'userID';
			$this->iUserID = $value;
			break;
		default;
			die($var . " is not accessible by setter method");
			break;
		}
	}
	
	
	
}
/*
$oLike = new Like();
$oLike->load(38);

echo "<pre>";
print_r($oLike);

//echo "<h1>".$oLike->iUserID."</h1>";
echo "</pre>";
*/


?>