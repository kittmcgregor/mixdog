<?php 
session_start();
require_once'includes/user.php';

	$data = array();      // array to pass back data
    
    if(isset($_GET['username'])){
		
		$oUser = new User();
		$oUser->loadByUserName($_GET['username']);   
	    $password = $oUser->password;
	    
		if($password==$_GET["password"]){
			$data['success'] = true;
			$_SESSION["UserID"] = $oUser->userID;
			$_SESSION["LocationManagerID"] = $oUser->locationID;
			$_SESSION["BreweryManagerID"] = $oUser->breweryID;
	    } else {
		    $data['error'] = 'incorrect details';
	    }
	        
	    echo json_encode($data);
    
	}
    
?>