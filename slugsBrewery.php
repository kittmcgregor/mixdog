<?php
	
	require_once("includes/brewery.php");
	require_once("includes/view.php");
$breweries = Brewery::breweryIDlist();


createBrewerySlugs($breweries);

?>