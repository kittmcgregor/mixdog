<?php 
session_start();

	$data = array();      // array to pass back data
    
    if(isset($_SESSION["UserID"])){
		$data['success'] = true;
    } else {
	    $data['error'] = 'not logged in';
    }
	        
	echo json_encode($data);
    
?>