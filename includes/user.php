<?php 

	require_once("connection.php");
	require_once("like.php");	
	
class User{
	private $iUserID;
	private $iBreweryID;
	private $iLocationID;
	private $sUsername;
	private $sFirstname;
	private $sLastname;
	private $sEmail;
	private $sPassword;
	private $sPhoto;
	
	private $aLikes;
	
	public  function __construct(){
		$this->iUserID = 0;
		$this->iBreweryID = 0;
		$this->iLocationID = 0;
		$this->sUsername = "";
		$this->sFirstname = "";
		$this->sLastname = "";
		$this->sEmail = "";
		$this->sPassword = "";
		$this->sPhoto = "";
 		$this->aLikes = array();
		}
	
	public function load($iUserID){
		
		//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT UserID, BreweryID, LocationID, UserName, FirstName, LastName, Email, Password FROM  user WHERE  UserID = $iUserID";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iUserID = $aRow["UserID"];
		$this->iBreweryID = $aRow["BreweryID"];
		$this->iLocationID = $aRow["LocationID"];
		$this->sUsername = $aRow["UserName"];
		$this->sFirstname = $aRow["FirstName"];
		$this->sLastname = $aRow["LastName"];
		$this->sEmail = $aRow["Email"];
		$this->sPassword = $aRow["Password"];
		
		//load liked beers
		$sSql = "SELECT likeID, beerID FROM `like` WHERE UserID =".$iUserID;
		$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$oLike = new Like();
			$oLike->load($aRow["likeID"]);
			$this->aLikes[] = $oLike; // add to array
		}
		
		//5 close connection
		$oCon->close();
	}
	
	public function loadUserNameProfile($username){
				//1 make connection
		$oCon = new Connection();

		//2 create query
		$sSql = "SELECT UserID, BreweryID, LocationID, UserName, FirstName, LastName, Email, Password FROM  user WHERE  UserName = '$username'";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);
		
		//4 fetch data
		$aRow = $oCon->fetchArray($oResultSet);
		$this->iUserID = $aRow["UserID"];
		$this->iBreweryID = $aRow["BreweryID"];
		$this->iLocationID = $aRow["LocationID"];
		$this->sUsername = $aRow["UserName"];
		$this->sFirstname = $aRow["FirstName"];
		$this->sLastname = $aRow["LastName"];
		$this->sEmail = $aRow["Email"];
		$this->sPassword = $aRow["Password"];
		
		//load liked beers
/*
		$sSql = "SELECT likeID, beerID FROM `like` WHERE UserID =".$iUserID;
		$oResultSet = $oCon->query($sSql);
*/
			
			//4 fetch data
		while($aRow = $oCon->fetchArray($oResultSet)){
			$oLike = new Like();
			$oLike->load($aRow["likeID"]);
			$this->aLikes[] = $oLike; // add to array
		}
		
		//5 close connection
		$oCon->close();
	}
	
	
public function save(){
		// insert a new page - for now
		$oCon = new Connection();
	
		if($this->userID==0){
		// beer does not exist - do insert
		$sSql = "INSERT INTO user (UserName, FirstName, LastName, Email, Password) 
			VALUES ('".$oCon->escape($this->
					sUsername)."', '".$oCon->escape($this->
					sFirstname)."', '".$oCon->escape($this->
					sLastname)."', '".$oCon->escape($this->
					sEmail)."', '".$oCon->escape($this->
					sPassword)."')";
			$bResult = $oCon->query($sSql);
			if($bResult==true){
				// update the iUserID
				$this->iUserID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
		}
		//5 close connection
		$oCon->close();
		
		}

public function update(){
		$oCon = new Connection();
		
		$sSql = "UPDATE user SET 
			UserName = '".$oCon->escape($this->
					sUsername)."', 
			FirstName = '".$oCon->escape($this->
					sFirstname)."', 
			LastName = '".$oCon->escape($this->
					sLastname)."',
			Email = '".$oCon->escape($this->
					sEmail)."' WHERE UserID = ".$this->
					iUserID;
		$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		
		$oCon->close();
		}

static public function checktoken($token,$email){
	$oCon = new Connection();
	$sSql = "SELECT token FROM password_resets WHERE token='$token' AND email='$email'";
	
	$resultSet = $oCon->query($sSql);
	
	$row = $oCon->fetchArray($resultSet);
	
	if($row == false){
		return false;
	}else{
		return true;
	}
	
		
	$oCon->close();
	}

public function updatepassword(){
		$oCon = new Connection();
		
		$sSql = "UPDATE user SET 
			Password = '".$oCon->escape($this->
					sPassword)."' WHERE UserID = ".$this->
					iUserID;
		$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		
		$oCon->close();
		}

public function loadByUserName($sUsername){
	

	//1 make connection
	$oCon = new Connection();
	
	//2 create query
	$sSql = "SELECT UserID FROM user WHERE UserName ='".$oCon->escape($sUsername)."'";

	//3 execute query
	$oResultSet = $oCon->query($sSql);
	
	//4 fetch data
	$aRow = $oCon->fetchArray($oResultSet);

	
	if ($aRow  == true){
		
		$iUserID = $aRow["UserID"];
		$this->load($iUserID);
		return true;
		//return $aRow; $this->iUserID
	} else {
		return false;
	}
		
	$oCon->close();
}

public function CheckEmailExists($email){
	

	//1 make connection
	$oCon = new Connection();
	
	//2 create query
	$sSql = "SELECT UserID FROM user WHERE Email ='".$oCon->escape($email)."'";

	//3 execute query
	$oResultSet = $oCon->query($sSql);
	
	//4 fetch data
	$aRow = $oCon->fetchArray($oResultSet);

	
	if ($aRow  == true){
		
		$iUserID = $aRow["UserID"];
		$this->load($iUserID);
		return true;
		//return $aRow; $this->iUserID
	} else {
		return false;
	}
		
	$oCon->close();
}

public function loadByUserEmail($email){
	

	//1 make connection
	$oCon = new Connection();
	
	//2 create query
	$sSql = "SELECT UserID FROM user WHERE Email ='".$oCon->escape($email)."'";

	//3 execute query
	$oResultSet = $oCon->query($sSql);
	
	//4 fetch data
	$aRow = $oCon->fetchArray($oResultSet);

	
	if ($aRow  == true){
		
		$iUserID = $aRow["UserID"];
		$this->load($iUserID);
		return true;
		//return $aRow; $this->iUserID
	} else {
		return false;
	}
		
	$oCon->close();
}

public function loadByUserByToken(){
	
	$token = $_GET['token'];

	//1 make connection
	$oCon = new Connection();
	
	//2 create query
	$sSql = "SELECT email FROM password_resets WHERE token =$token";

	//3 execute query
	$oResultSet = $oCon->query($sSql);
	
	//4 fetch data
	$aRow = $oCon->fetchArray($oResultSet);

	
	if ($aRow  == true){
		
		$iUserEmail = $aRow["email"];
		$this->loadByUserEmail($iUserEmail);
		return true;
		//return $aRow; $this->iUserID
	} else {
		return false;
	}
		
	$oCon->close();
}


	static public function all(){
		//1 make connection
		$oCon = new Connection();
			
		$aUsers = array();
		
		//2 create query
		$sSql = "SELECT userID FROM user";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
				$iUserID = $aRow["userID"];
				$oUser = new User();
				$oUser->load($iUserID);
				$aUsers[] = $oUser; // add to array
			}
			
		//5 close connection
		$oCon->close();
		return $aUsers;

	}
	

		
	static public function userlist(){
		//1 make connection
		$oCon = new Connection();
		$aUserList = array();
		
		//2 create query
		$sSql = "SELECT UserID FROM user ORDER BY UserName";
		
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
			while($aRow=$oCon->fetchArray($oResultSet)){
			
				$iUserID = $aRow["UserID"];
				$oUser = new User();
				$oUser->load($iUserID);
				$aUserList[$iUserID] = $oUser->username; // add to array
			}

		//5 close connection
		$oCon->close();

		return $aUserList;

	}

	static public function token($email,$token){
		
		$oCon = new Connection();
		$sSql = "INSERT INTO password_resets (email, token) VALUES ('".$email."','".$token."')";
		
		$bResult = $oCon->query($sSql);
			if($bResult==false){
				die($sSql."did not run");
			}
		$oCon->close();
	}
	
		
	// getter method
	public function __get($var){
		switch ($var){
		case 'userID';
			return $this->iUserID;
		break;
		case 'breweryID';
			return $this->iBreweryID;
		break;
		case 'locationID';
			return $this->iLocationID;
		break;		
		case 'username';
			return $this->sUsername;
		break;
		case 'firstname';
			return $this->sFirstname;
		break;
		case 'lastname';
			return $this->sLastname;
		break;
		case 'email';
			return $this->sEmail;
		break;
		case 'password';
			return $this->sPassword;
		break;
		case 'photo';
			return $this->sPhoto;
		break;
		case 'likes';
			return $this->aLikes;
		break;
		default;
			die($var . " is not accessible");
			break;
		}
	}
	
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'username';
			$this->sUsername = $value;
			break;
		case 'firstname';
			$this->sFirstname = $value;
			break;
		case 'lastname';
			$this->sLastname = $value;
			break;
		case 'email';
			$this->sEmail = $value;
			break;
		case 'password';
			$this->sPassword = $value;
			break;
		case 'photo';
			$this->sPhoto = $value;
			break;
		default;
			die($var . " is not accessible");
			break;
		}
	}
	
	
}

/*
$oUser = new User();
$oUser->load(2);

echo "<pre>";
print_r($oUser);

echo "</pre>";
*/

?>


