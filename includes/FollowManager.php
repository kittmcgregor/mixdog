<?php 
	require_once("connection.php");
	require_once("follow.php");
	
class FollowManager{
	
		static public function getFollowersIDsList($id,$table){

			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT userID FROM FollowingTable
			WHERE $table =".$id;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			$aFollowers = array();
			//4 fetch data
			while($aRow = $oCon->fetchArray($oResultSet)){
			$iFollowerID = $aRow["userID"];
			$aFollowers[] = $iFollowerID; // add locations to list
			}		
	
			//5 close connection
			$oCon->close();
			
		return $aFollowers;
		}

		static public function getFollowersMailList($iUserID,$table){
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT userID FROM FollowingTable
			WHERE $table =".$iUserID;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			$aFollowersEmails = array();
			//4 fetch data
			while($aRow = $oCon->fetchArray($oResultSet)){
			$id = $aRow["userID"];
			//$aFollowers[] = $iFollowerID; // add to list
			
			$oUser = new User();
			$oUser->load($id);
			$email = $oUser->email;
			$aFollowersEmails[] = $email; // add to list
			}		
	
			//5 close connection
			$oCon->close();
		
		return $aFollowersEmails;
		}
		
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
			if($iFollowUserID!=0){
				$aFollowingUsers[] = $iFollowUserID; // add user to list
			}
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
		$sSql = "SELECT followBreweryID FROM FollowingTable
	WHERE userID IN ($comma_separated)";
		
		//echo $sSql;
		
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
	
	static public function getFollowBreweryID($userID,$Bid){
		
		//$comma_separated = implode(",", $aFollowingIDsList);
		
		//1 make connection
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT followID FROM FollowingTable
	WHERE userID=$userID AND followBreweryID=$Bid";
		
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
// $aFollowing = FollowManager::loadFollowing(1);

/*
$aFollowingIDsList = FollowManager::getFollowingIDsList(1);

echo "<pre>";
print_r($aFollowingIDsList);
echo "</pre>";
*/


?>