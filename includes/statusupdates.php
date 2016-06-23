<?php 
	require_once("connection.php");
	
class Status{
	private $id;
	private $userid;
	private $beerid;
	private $locationid;
	private $created_at;
	private $photo;
	private $rating;
	private $review;
	
	public  function __construct(){
	$this->id = 0;
	$this->userid= 0;
	$this->beerid= 0;
	$this->locationid = 0;
	$this->created_at = "";
	$this->photo = "";
	$this->rating = 0;
	$this->review = "";
	}

	public function load($id){
		
		$statusupdates = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT id, userid, beerid, locationid, rating, review, photo, created_at FROM statusupdates WHERE id=$id";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->id = $aRow["id"];
		$this->userid = $aRow["userid"];
		$this->beerid = $aRow["beerid"];
		$this->locationid = $aRow["locationid"];
		$this->photo = $aRow["photo"];
		$this->rating = $aRow["rating"];
		$this->review = $aRow["review"];
		$this->created_at = $aRow["created_at"];
		
		//5 close connection
		$oCon->close();
		
		return $statusupdates;
	}

	public static function averageRating($beerid){
		//1 make connection
		$oCon = new Connection();
		//2 create query
		$sSql = "SELECT COUNT( * ), AVG(rating)
				FROM statusupdates
				WHERE beerid =$beerid AND rating > 0";
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		$aRow = $oCon->fetchArray($oResultSet);
		$averageRating = $aRow["AVG(rating)"];
		
		$oCon->close();
		
		$averageRatingRounded = round($averageRating,2);
		
		return $averageRatingRounded;
		}

	public function save(){
		
		$oCon = new Connection();
		$sSql = "INSERT INTO statusupdates ( userid, beerid, locationid, photo, rating, review ) VALUES ('".$oCon->escape($this->
					userid)."','".$oCon->escape($this->
					beerid)."', '".$oCon->escape($this->
					locationid)."', '".$oCon->escape($this->
					photo)."', '".$oCon->escape($this->
					rating)."', '".$oCon->escape($this->
					review)."')";
			echo $sSql;
			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iBeerID
				$this->id = $oCon->getInsertID();
			} else {
				die($sSql . "save status did not run");
			}
		
		$oCon->close();
	}

	public function saveAjaxTest(){
		
		$oCon = new Connection();
		$sSql = "INSERT INTO statusupdates ( beerid ) VALUES ('".$oCon->escape($this->
					beerid)."')";
			//echo $sSql;
			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iBeerID
				$this->id = $oCon->getInsertID();
			} else {
				die($sSql . "save status did not run");
			}
		
		$oCon->close();
	}
	
	public function loadByUser($userid){
		
		$statusupdates = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT id, userid, beerid, locationid, created_at FROM statusupdates WHERE userid=$userid";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->id = $aRow["id"];
		$this->userid = $aRow["userid"];
		$this->beerid = $aRow["beerid"];
		$this->locationid = $aRow["locationid"];
		$this->created_at = $aRow["created_at"];
		
		//5 close connection
		$oCon->close();
		
		return $statusupdates;
	}
	
	
	static public function followinglatest($aUserIDsOfFollowing){
		
		$comma_separated = implode(",", $aUserIDsOfFollowing);
		
		//return a list of all likes
		$aUsersActivity = array();
		
		// query all subject IDs
		$oCon = new Connection();
		$sSql = "SELECT id, created_at  FROM statusupdates WHERE userid IN ($comma_separated) ORDER BY created_at DESC LIMIT 0 , 10 ";
		//echo $sSql;
		
		$oResultSet = $oCon->query($sSql);
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iStatusID = $aRow["id"];
			$aUsersActivity[] = $iStatusID;
		}
		$oCon->close();
	
		return $aUsersActivity;
		}


	static public function followinglatestKeys($aUserIDsOfFollowing){
		
		$comma_separated = implode(",", $aUserIDsOfFollowing);
		
		//return a list of all likes
		$aUsersActivity = array();
		
		// query all subject IDs
		$oCon = new Connection();
		$sSql = "SELECT id, created_at  FROM statusupdates WHERE userid IN ($comma_separated) ORDER BY created_at DESC LIMIT 0 , 10 ";
		//echo $sSql;
		
		$oResultSet = $oCon->query($sSql);
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iStatusID = $aRow["id"];
			$time = $aRow["created_at"];
			$timestring = strtotime($time);
			$aUsersActivity[$timestring] = $iStatusID;		
		}
		$oCon->close();
	
		return $aUsersActivity;
		}

	static public function BACKUPfollowinglatest($aUserIDsOfFollowing){
		
		$comma_separated = implode(",", $aUserIDsOfFollowing);
		
		//return a list of all likes
		$aUsersActivity = array();
		
		// query all subject IDs
		$oCon = new Connection();
		$sSql = "SELECT id, created_at  FROM statusupdates WHERE userid IN ($comma_separated) ORDER BY created_at DESC LIMIT 0 , 10 ";
		//echo $sSql;
		
		$oResultSet = $oCon->query($sSql);
		
		while($aRow = $oCon->fetchArray($oResultSet)){
			$iStatusID = $aRow["id"];
			$aUsersActivity[] = $iStatusID;
/*
			$iLikeID = $aRow["likeID"];
			$oLike = new Like();
			$oLike->load($iLikeID);
			$aUsersLikeActivity[] = $oLike; // add locations to list
*/
		}
		$oCon->close();
	
		return $aUsersActivity;
		}

	public static function latest(){
		
		$statusupdates = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT id, created_at FROM statusupdates ORDER BY created_at DESC LIMIT 0 , 10 ";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
				//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$statusupdates[]= $aRow["id"];
		}
		
		//5 close connection
		$oCon->close();
		
		return $statusupdates;
	}

	public static function loadbybeer($beerid){
		
		$statusupdates = array();
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT id FROM statusupdates WHERE beerid=$beerid ORDER BY created_at DESC LIMIT 0 , 10 ";

		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$statusupdates[]= $aRow["id"];
		}
		
		//5 close connection
		$oCon->close();
		
		return $statusupdates;
	}

	static public function loadAuthor($statusid){
				
	// query all subject IDs
	$oCon = new Connection();
	$sSql = "SELECT userid  FROM `statusupdates` WHERE id=$statusid";
	
	//3 execute query
	$oResultSet = $oCon->query($sSql);
		
	//4 fetch data
	$aRow = $oCon->fetchArray($oResultSet);
	$iUserID = $aRow["userid"];
	
	$oCon->close();
	return $iUserID;
	
	}
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'id';
			return $this->id;
			break;			
		case 'userid';
			return $this->userid;
			break;	
		case 'beerid';
			return $this->beerid;
			break;
		case 'locationid';
			return $this->locationid;
			break;
		case 'photo';
			return $this->photo;
			break;
		case 'rating';
			return $this->rating;
			break;
		case 'review';
			return $this->review;
			break;
		case 'created_at';
			return $this->created_at;
			break;				
		default;
			die($var . " status getter is not accessible");
			break;
		}
	}
	
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'userid';
			$this->userid = $value;
			break;
		case 'beerid';
			$this->beerid = $value;
			break;
		case 'locationid';
			$this->locationid = $value;
			break;			
		case 'rating';
			$this->rating = $value;
			break;
		case 'review';
			$this->review = $value;
			break;
		case 'photo';
			$this->photo = $value;
			break;
		default;
			die($var . " status setter is not accessible");
			break;
		}
	}
}

/*
$userid = Status::loadAuthor(97);

echo "<pre>";
echo $userid;
echo "</pre>";
*/

/*
$status = new Status();
$status->load(1);
echo "<pre>";
print_r($status);
echo "</pre>";
*/