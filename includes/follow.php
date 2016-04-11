<?php
	require_once("connection.php");
	
class Follow{
	private $iFollowID;
	private $sDate;	
	private $iUserID;
	private $iFollowBreweryID;
	private $iFollowLocationID;
	private $iFollowUserID;
	
	public  function __construct(){
	$this->iFollowID = 0;
	$this->sDate = "";
	$this->iUserID = 0;
	$this->iFollowBreweryID = 0;
	$this->iFollowLocationID = 0;
	$this->iFollowUserID = 0;
	}
		
	public function load($iFollowID){
				
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT followID, date, userID, followBreweryID, followLocationID, followUserID FROM FollowingTable
	WHERE followID =".$iFollowID;
		//echo $sSql;
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iFollowID = $aRow["followID"];
		$this->sDate = $aRow["date"];
		$this->iUserID = $aRow["userID"];
		$this->iFollowBreweryID = $aRow["followBreweryID"];
		$this->iFollowLocationID = $aRow["followLocationID"];
		$this->iFollowUserID = $aRow["followUserID"];
		//5 close connection
		$oCon->close();
		
	}
	
	public function loadAll($aFollowingIDsList){
		$aFollowing = array();
	
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT followID FROM FollowingTable
		WHERE userID =".$iUserID;
		//echo $sSql;
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
	
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iFollowingID = $aRow["followID"];
			$oFollowing = new Follow();
			$oFollowing->load($iFollowingID);
			$aFollowing[] = $oFollowing; // add Following Objects to array
		}
		
		//5 close connection
		$oCon->close();
		
		return $aFollowing;
	
	}
	
	
	public function save(){
		// open connection
		$oCon = new Connection();
		
		// if like does not exist - do insert
		$sSql = "INSERT INTO FollowingTable(userID, followBreweryID, followLocationID, followUserID)
		VALUES ('".$oCon->escape($this->
				userID)."', '".$oCon->escape($this->
				iFollowBreweryID)."', '".$oCon->escape($this->
				iFollowLocationID)."', '".$oCon->escape($this->
				iFollowUserID)."')";	
		$bResult = $oCon->query($sSql);
		
		// echo $sSql;
		if($bResult==true){
			// update the iBeerID
			$this->iFollowID = $oCon->getInsertID();
		} else {
			die($sSql . "did not run");
		}
		
		// close connection
		$oCon->close();
	}
			
	static public function remove($iFollowID){	
		// open connection
		$oCon = new Connection();
		
		$sSql = "DELETE FROM FollowingTable WHERE followID=".$iFollowID;
		$oCon->query($sSql);
		
		// close connection
		$oCon->close();
	}
	
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'followID';
			return $this->iFollowID;
			break;
		case 'date';
			return $this->sDate;
			break;
		case 'userID';
			return $this->iUserID;
			break;
		case 'followbreweryID';
			return $this->iFollowBreweryID;
			break;
		case 'followlocationID';
			return $this->iFollowLocationID;
			break;
		case 'followuserID';
			return $this->iFollowUserID;
		break;
		default;
			die($var . " is not accessible by getter method");
			break;
		}
	}
		
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'userID';
			$this->iUserID = $value;
			break;
		case 'followbreweryID';
			$this->iFollowBreweryID = $value;
			break;
		case 'followlocationID';
			$this->iFollowLocationID = $value;
			break;
		case 'followuserID';
			$this->iFollowUserID = $value;
			break;
		default;
			die($var . " is not accessible by setter method");
			break;
		}
	}
	
	
	
}
/*
$oFollow = new Follow();
$oFollow->load(1);

echo "<pre>";
print_r($oFollow);


echo "</pre>";
*/


?>