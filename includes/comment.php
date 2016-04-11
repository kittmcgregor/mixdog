<?php 
	require_once("connection.php"); 

class Comment{
	
	private $iCommentID;
	private $sDate;
	private $iBeerID;
	private $iUserID;
	private $sComment;
		
	public  function __construct(){
	$this->iCommentID = 0;
	$this->sDate = "";
	$this->iBeerID = 0;
	$this->iUserID = 0;
	$this->sComment = "";
	}
	
	public function load($commentID){
		
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT commentID, date, userID, beerID, comment FROM `commentTable` WHERE commentID =".$commentID;
			//echo $sSql;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iCommentID = $aRow["commentID"];
			$this->sDate = $aRow["date"];
			$this->iUserID = $aRow["userID"];
			$this->iBeerID = $aRow["beerID"];
			$this->sComment = $aRow["comment"];
			
			//5 close connection
			$oCon->close();
		
	}

public function save(){
			// open connection
			$oCon = new Connection();
			
			// if like does not exist - do insert
			$sSql = "INSERT INTO `commentTable`(beerID, userID, comment)
			VALUES ('".$oCon->escape($this->
					iBeerID)."', '".$oCon->escape($this->
					iUserID)."', '".$oCon->escape($this->
					sComment)."')";	
			$bResult = $oCon->query($sSql);
			
			// echo $sSql;
			if($bResult==true){
				// update the iBeerID
				$this->iBeerID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
			
			// close connection
			$oCon->close();
		}
		

		
		
static public function remove($commentID){	
		// open connection
		$oCon = new Connection();
		
		$sSql = "DELETE FROM `commentTable` WHERE `commentID`=".$commentID;
		
		$oCon->query($sSql);
		
		// close connection
		$oCon->close();
	}
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'commentID';
			return $this->iCommentID;
			break;
		case 'date';
			return $this->sDate;
			break;
		case 'beerID';
			return $this->iBeerID;
			break;
		case 'userID';
			return $this->iUserID;
			break;
		case 'comment';
			return $this->sComment;
			break;
		default;
			die($var . " is not accessible");
			break;
		}
	}
	
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'commentID';
			$this->iCommentID = $value;
			break;
		case 'beerID';
			$this->iBeerID = $value;
			break;
		case 'userID';
			$this->iUserID = $value;
			break;
		case 'comment';
			$this->sComment = $value;
			break;
		default;
			die($var . " is not accessible by setter method");
			break;
		}
	}
	
	
	
}
/*
$oComment = new Comment();
$oComment->load(1);

echo "<pre>";
print_r($oComment);

//echo "<h1>".$oLike->iUserID."</h1>";
echo "</pre>";
*/


?>