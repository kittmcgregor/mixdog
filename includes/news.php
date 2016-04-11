<?php 
	require_once("connection.php"); 

class News{
	
	private $iNewsID;
	private $sDate;
	private $iUserID;
	private $sNewsTitle;
	private $sNewsContent;
	private $sNewsExcerpt;
	private $sNewsImageLink;
	private $sNewsImageLinkAddress;
	
	public  function __construct(){
		$this->iNewsID = 0;
		$this->sDate = "";
		$this->iUserID = 0;
		$this->sNewsTitle = "";
		$this->sNewsContent = "";
		$this->sNewsExcerpt  = "";
		$this->sNewsImageLink = "";
		$this->sNewsImageLinkAddress = "";
	}
	
	public function load($newsID){
			//1 make connection
			$oCon = new Connection();
			
			//2 create query
			$sSql = "SELECT newsID, date, userID, newsTitle, newsContent, newsExcerpt, newsImageLink, newsImageLinkAddress FROM `newsTable` WHERE newsID =".$newsID;
			//echo $sSql;
			
			//3 execute query
			$oResultSet = $oCon->query($sSql);
			
			//4 fetch data
			$aRow = $oCon->fetchArray($oResultSet);
			$this->iNewsID = $aRow["newsID"];
			$this->sDate = $aRow["date"];
			$this->iUserID = $aRow["userID"];
			$this->sNewsTitle = $aRow["newsTitle"];
			$this->sNewsContent = $aRow["newsContent"];
			$this->sNewsExcerpt = $aRow["newsExcerpt"];
			$this->sNewsImageLink = $aRow["newsImageLink"];
			$this->sNewsImageLinkAddress = $aRow["newsImageLinkAddress"];
			//5 close connection
			$oCon->close();
		
	}
	
	
	public function save(){
			// open connection
			$oCon = new Connection();
			
			// if like does not exist - do insert
			$sSql = "INSERT INTO `newsTable`(userID, newsTitle, newsContent, newsExcerpt, newsImageLink, newsImageLinkAddress)
			VALUES ('".$oCon->escape($this->
					iUserID)."', '".$oCon->escape($this->
					sNewsTitle)."', '".$oCon->escape($this->
					sNewsContent)."', '".$oCon->escape($this->
					sNewsExcerpt)."', '".$oCon->escape($this->
					sNewsImageLink)."', '".$oCon->escape($this->
					sNewsImageLinkAddress)."')";	
			$bResult = $oCon->query($sSql);
			
			// echo $sSql;
			if($bResult==true){
				// update the iNewsID
				$this->iNewsID = $oCon->getInsertID();
			} else {
				die($sSql . "did not run");
			}
			
			// close connection
			$oCon->close();
		}

public function update(){
		// insert
		$oCon = new Connection();
		
		// beer exists
		$sSql = "UPDATE newsTable SET 
		newsTitle = '".$oCon->escape($this->
				sNewsTitle)."', 
		newsContent = '".$oCon->escape($this->
				sNewsContent)."', 
		newsExcerpt = '".$oCon->escape($this->
				sNewsExcerpt)."', 
		newsImageLink = '".$oCon->escape($this->
				sNewsImageLink)."', 
		newsImageLinkAddress = '".$oCon->escape($this->
				sNewsImageLinkAddress)."' WHERE newsID = ".$oCon->escape($this->
				iNewsID);
		
		$bResult = $oCon->query($sSql);

		if($bResult==false){
			die($sSql."did not run");
		}

				$oCon->close();
		}

		
	static public function all(){
		$oCon = new Connection();

		$aNews = array();
		
		//2 create query
		$sSql = "SELECT newsID FROM newsTable ORDER BY newsID DESC";
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
		while($aRow=$oCon->fetchArray($oResultSet)){
			$iNewsID = $aRow["newsID"];
			$aNews[] = $iNewsID; // add to array
		}

		// close connection
		$oCon->close();
		return $aNews;
	}

	static public function newsimage($sNewsID){
		$oCon = new Connection();
		
		//2 create query
		$sSql = "SELECT newsImageLink FROM newsTable WHERE newsID =".$sNewsID;
	
		//3 execute query
		$oResultSet = $oCon->query($sSql);

		//4 fetch data
		$aRow=$oCon->fetchArray($oResultSet);
		$sNewsImage = $aRow["newsImageLink"];
		
		// close connection
		$oCon->close();
		return $sNewsImage;
	}
	
	// getter method
	public function __get($var){
		switch ($var){
		case 'newsID';
			return $this->iNewsID;
			break;
		case 'date';
			return $this->sDate;
			break;
		case 'userID';
			return $this->iUserID;
			break;
		case 'newstitle';
			return $this->sNewsTitle;
			break;
		case 'newscontent';
			return $this->sNewsContent;
			break;
		case 'newsexcerpt';
			return $this->sNewsExcerpt;
			break;
		case 'newsimagelink';
			return $this->sNewsImageLink;
			break;
		case 'newsimagelinkaddress';
			return $this->sNewsImageLinkAddress;
			break;	
		default;
			die($var . " is not accessible by getter method");
			break;
		}
	}
	
	// setter method
	public function __set($var,$value){
		switch ($var){
		case 'newsID';
			$this->iNewsID = $value;
			break;			
		case 'userID';
			$this->iUserID = $value;
			break;
		case 'newstitle';
			$this->sNewsTitle = $value;
			break;
		case 'newscontent';
			$this->sNewsContent = $value;
			break;
		case 'newsexcerpt';
			$this->sNewsExcerpt = $value;
			break;
		case 'newsimagelink';
			$this->sNewsImageLink = $value;
			break;
		case 'newsimagelinkaddress';
			$this->sNewsImageLinkAddress = $value;
			break;
		default;
			die($var . " is not accessible by setter method");
			break;
		}
	}
	
	
	
}
/*
$oNews = new News();
$oNews->load(1);

echo "<pre>";
print_r($oNews);

//echo "<h1>".$oLike->iUserID."</h1>";
echo "</pre>";
*/


?>