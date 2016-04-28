<?php
	require_once("includes/connection.php");
	require_once("includes/availabilitymanager.php"); 
	
		echo json_encode(Availability::listall());

?>