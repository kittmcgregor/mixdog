<?php 
	require_once'includes/statusupdates.php';
	
	$data = array();      // array to pass back data
	
	$data['success'] = true;
    $data['message'] = 'Success!';

    
	$brewcheckinid="test";
	
	if(isset($_GET['beerselect'])){
		$brewcheckinid=$_GET['beerselect'];
	}

    $data['beerselect'] = $brewcheckinid;
    
    $oNewStatusUpdate = new Status();
    $oNewStatusUpdate->beerid = $_GET['beerselect'];
    //$oNewStatusUpdate->saveAjaxTest();

	// return all our data to an AJAX call
    echo json_encode($data);
    
?>