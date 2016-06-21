<?php 
	
	require_once("connection.php");
	require_once("comment.php");
	require_once("user.php");

class CommentIDs{
// no attributes
	
/*
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
*/
	
	static public function loadCommentIDs($beerID){
	//return a list of all Comments
	$aCommentIDs = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT commentID  FROM `commentTable` WHERE beerID=$beerID ORDER BY date DESC";
	
	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iCommentID = $aRow["commentID"];
		
		$oCommentID = new Comment();
		$oCommentID->load($iCommentID);
		$aCommentIDs[] = $oCommentID;
	}
		$oCon->close();
		
		return $aCommentIDs;
	}
	
	static public function loadCommentIDsByUser($userID){
	//return a list of all Comments
	$aCommentIDs = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT commentID  FROM `commentTable` WHERE userID=$userID ORDER BY date DESC";
	
	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iCommentID = $aRow["commentID"];
		
		$oCommentID = new Comment();
		$oCommentID->load($iCommentID);
		$aCommentIDs[] = $oCommentID;
	}
		$oCon->close();
		
		return $aCommentIDs;
	}

	static public function loadCommentIDsByStatus($iStatus){
	//return a list of all Comments
	$aCommentIDs = array();
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT commentID  FROM `commentTable` WHERE statusID=$iStatus ORDER BY date ASC";
	
	$oResultSet = $oCon->query($sSql);
		
	// load all subjects and add to array
	while($aRow = $oCon->fetchArray($oResultSet)){
		$iCommentID = $aRow["commentID"];
		
		$oCommentID = new Comment();
		$oCommentID->load($iCommentID);
		$aCommentIDs[] = $oCommentID->commentID;
	}
		$oCon->close();
		
		return $aCommentIDs;
	}


}	
/*
$oCommentIDs = CommentIDs::loadCommentIDs(72);

	echo "<pre>";
	print_r($oCommentIDs);
	echo "</pre>";
*/

?>