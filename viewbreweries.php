<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	if(isset($_GET["breweryID"])){
		$locationID = $_GET["breweryID"];
	}

	//$suburb = "all";
	
/*
	if(isset($_GET["region"])){
		$region = $_GET["region"];
		$aLocations = Brewery::region($$region);	
	} else {
			$aLocations = Brewery::all();
	}
*/
?>

	<div class="wrapper">
	<input id="brewerySearch" placeholder="Quick search" value="">
	<div class="searcherror"></div>
	</div>

<?php
	
	$aBreweries = Brewery::all();
	//echo View::renderLocationTabs($suburb);

	echo View::renderAllBreweries($aBreweries);


/*
	// Load Beers related to location

	$aBeersAvailable = Availability::loadBeers($locationID);
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	include_once'includes/footer.php';

?>
<script>
	
	$('input:text').focus(
    function(){
        $(this).val('');
    });
$('#brewerySearch').autocomplete({
  	source: function( request, response ) {
  		$.ajax({
  			url : 'listBreweries.php',
  			dataType: "json",
  			type: 'Get',
  			data: {term: request.term},
			success: function( data ) {
				 var array = ( $.map( data, function( item,i ) {
					return {
						label: item,
						value: i
					}
				}));
			//call the filter here
            response($.ui.autocomplete.filter(array, request.term));
			console.log(request.term);
			},
			error: function() {
		         $('.searcherror').html('<p>An error has occurred</p>');
		    }
  		});
  	},
  	select: function(event, ui) {  
	  	console.log(ui);
               location.href="viewbrewery.php?breweryID=" + ui.item.value;
        } 	
});	
	
/*
  	autoFocus: true,
  	minLength: 0,
*/

</script>