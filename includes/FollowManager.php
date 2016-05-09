<?php 
	require_once("connection.php");
	require_once("follow.php");
	
class FollowManager{
		static public function getFollowingIDsList($iUserID){
				
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT followID FROM FollowingTable
	WHERE userID =".$iUserID;
		//echo $sSql;
		
		//3 execute query
		
		$oResultSet = $oCon->query($sSql);
		
		$aFollowing = array();
		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
		$iFollowingID = $aRow["followID"];
		
		$aFollowing[] = $iFollowingID; // add locations to list
		}
		
		//5 close connection
		$oCon->close();
		
		return $aFollowing;
	}

	static public function getFollowingUserIDsList($aFollowingIDsList){
		
		$comma_separated = implode(",", $aFollowingIDsList);
		
		$aFollowingUsers = array();
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT FollowUserID FROM FollowingTable
	WHERE followID IN ($comma_separated)";
		
		//3 execute query
		
		$oResultSet = $oCon->query($sSql);
		

		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
		$iFollowUserID = $aRow["FollowUserID"];
		
		$aFollowingUsers[] = $iFollowUserID; // add locations to list
		}
		
		//5 close connection
		$oCon->close();
		
		return $aFollowingUsers;
	}
	
	static public function getFollowingBreweryIDsList($aFollowingIDsList){
		
		$comma_separated = implode(",", $aFollowingIDsList);
		
		$aFollowing = array();
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT FollowBreweryID FROM FollowingTable
	WHERE followID IN ($comma_separated)";
		
		//3 execute query
		
		$oResultSet = $oCon->query($sSql);
		

		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
		$iFollowID = $aRow["FollowBreweryID"];
		
		$aFollowing[] = $iFollowID; // add locations to list
		}
		
		//5 close connection
		$oCon->close();
		
		return $aFollowing;
	}

	static public function getFollowingLocationIDsList($aFollowingIDsList){
		
		$comma_separated = implode(",", $aFollowingIDsList);
		
		$aFollowing = array();
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT FollowLocationID FROM FollowingTable
	WHERE followID IN ($comma_separated)";
		
		//3 execute query
		
		$oResultSet = $oCon->query($sSql);
		

		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
		$iFollowID = $aRow["FollowLocationID"];
		
		$aFollowing[] = $iFollowID; // add locations to list
		}
		
		//5 close connection
		$oCon->close();
		
		return $aFollowing;
	}

	static public function getFollowingLocationIDByUSer($userID,$locationID){
		
		//$comma_separated = implode(",", $aFollowingIDsList);
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT followID FROM FollowingTable
	WHERE userID=$userID AND followLocationID=$locationID";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$followID = $aRow["followID"];
		
		//5 close connection
		$oCon->close();

		return $followID;
	}

}
/*
$aFollowing = FollowManager::loadFollowing(1);

echo "<pre>";
print_r($aFollowing);
echo "</pre>";
*/
?>