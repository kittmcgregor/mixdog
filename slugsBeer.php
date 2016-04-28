<?php
	
	require_once("includes/beer.php");
	require_once("includes/view.php");
	require_once("includes/brewery.php");
$beers = Beer::all();

createslugs($beers);

?>