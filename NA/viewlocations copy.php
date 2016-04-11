<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}

	$suburb = "all";
	if(isset($_GET["suburb"])){
		$suburb = $_GET["suburb"];
		$aLocations = Location::suburb($suburb);	
	} else {
			$aLocations = Location::all();
	};
	//$LocationArray = Location::lists();
	//$LocationArrayClense = str_replace("'", "", $LocationArray);
?>
	
	<div class="wrapper">
	<input id="locationSearch">
	<div class="searcherror"></div>
	</div>



<?php
	
	echo View::renderLocationTabs($suburb);

	echo View::renderAllLocations($aLocations);


/*
	// Load Beers related to location

	$aBeersAvailable = Availability::loadBeers($locationID);
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	include_once'includes/footer.php';
?>
<script>
$('#locationSearch').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'ajax.php',
		      			dataType: "json",
						success: function( data ) {
							 response( $.map( data, function( item ) {
								return {
									label: item,
									value: item
								}
							}));
						},
						error: function() {
					         $('.searcherror').html('<p>An error has occurred</p>');
					    }
		      		});
		      	},
		      	autoFocus: true,
		      	minLength: 0     	
		      });	
	
	
	

</script>

