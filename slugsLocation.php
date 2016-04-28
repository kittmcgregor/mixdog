<?php
	
	require_once("includes/location.php");
	require_once("includes/view.php");
$locations = Location::locationIDs();


createLocationSlugs($locations);

?>