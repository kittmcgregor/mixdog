<?php 
	
	require_once("connection.php");
	require_once("like.php");
	require_once("user.php");
	require_once("beer.php");

class Likes{
// no attributes
	
	static public function loadBeers($userID){
	//return a list of all likes
	$aLocations = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT beerID  FROM `like` WHERE userID=$userID";
	
	$oResultSet = $oCon->query($sSql);
		
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
	
	static public function loadList($userID){
	//return a list of all likes
	$aLikes = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT beerID  FROM `like` WHERE userID=$userID";

	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iBeerID = $aRow["beerID"];

		$aLikes[] = $iBeerID; // add locations to list
	}
	$oCon->close();

	return $aLikes;
	}
	
	static public function getRecentLikeActivity(){
		
		//$comma_separated = implode(",", $aUserIDsOfFollowing);
		
		//return a list of all likes
		$aRecentLikeActivity = array();
		
		// query all subject IDs
		$oCon = new Connection();
		$sSql = "SELECT likeID FROM `like` ORDER BY date DESC";
		//echo $sSql;
		
		$oResultSet = $oCon->query($sSql);
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iLikeID = $aRow["likeID"];
			$aRecentLikeActivity[] = $iLikeID;
/*
			$iLikeID = $aRow["likeID"];
			$oLike = new Like();
			$oLike->load($iLikeID);
			$aUsersLikeActivity[] = $oLike; // add locations to list
*/
		}
		$oCon->close();
	
		return $aRecentLikeActivity;
		}

	
	static public function getUsersLikeActivity($aUserIDsOfFollowing){
		
		$comma_separated = implode(",", $aUserIDsOfFollowing);
		
		//return a list of all likes
		$aUsersLikeActivity = array();
		
		// query all subject IDs
		$oCon = new Connection();
		$sSql = "SELECT likeID, beerID, userID, date  FROM `like` WHERE `userID` IN ($comma_separated) ORDER BY date DESC";
		//echo $sSql;
		
		$oResultSet = $oCon->query($sSql);
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iLikeID = $aRow["likeID"];
			$aUsersLikeActivity[] = $iLikeID;
/*
			$iLikeID = $aRow["likeID"];
			$oLike = new Like();
			$oLike->load($iLikeID);
			$aUsersLikeActivity[] = $oLike; // add locations to list
*/
		}
		$oCon->close();
	
		return $aUsersLikeActivity;
		}
	
	static public function loadLikers($beerID){
	//return a list of all likes
	$aUsers = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT userID  FROM `like` WHERE beerID=$beerID";
	
	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iUserID = $aRow["userID"];
		
		$oUser = new User();
		$oUser->load($iUserID);
		$aUsers[] = $oUser;
	}
		$oCon->close();
		
		return $aUsers;
	}

}	

/*
$oLikedBeers = Likes::loadLikedBeers();

	echo "<pre>";
	print_r($oLikedBeers);
	echo "</pre>";
*/

?>