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
	
<!--
	<div class="wrapper">
	
	<div class="searcherror"></div>
	</div>
-->

<?php
	function renderLocationTabs($suburb){
		
		$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<input id="locationSearch" placeholder="Quick search" value=""> <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filters" aria-expanded="false" aria-controls="collapseExample">Filter Locations</a>';
			if ($suburb!="all"){
				$sHTML .= '<a href="viewlocations.php" class="btn btn-default">Show all</a>';
			}
				$sHTML .= '<div class="collapse" id="filters">';

				$sHTML .= '<h5>Auckland</h5>';
				$sHTML .= '<a href="viewlocations.php?suburb=Auckland%20Central" class="btn btn-default">Auckland Central</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Birkenhead" class="btn btn-default">Birkenhead</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Browns%20Bay" class="btn btn-default">Browns Bay</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Eden%20Terrace" class="btn btn-default">Eden Terrace</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Forrest%20Hill" class="btn btn-default">Forrest Hill</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Henderson" class="btn btn-default">Henderson</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Howick" class="btn btn-default">Howick</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Kingsland" class="btn btn-default">Kingsland</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Kumeu" class="btn btn-default">Kumeu</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Morningside" class="btn btn-default">Morningside</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mt%20Eden" class="btn btn-default">Mt Eden</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Newmarket" class="btn btn-default">Newmarket</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Onehunga" class="btn btn-default">Onehunga</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Ponsonby" class="btn btn-default">Ponsonby GreyLynn</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Rosedale" class="btn btn-default">Rosedale</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Takapuna" class="btn btn-default">Takapuna</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Titirangi" class="btn btn-default">Titirangi</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Waiheke" class="btn btn-default">Waiheke</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Westlynn" class="btn btn-default">Westlynn</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Westmere" class="btn btn-default">Westmere</a>';
				
				$sHTML .= '<h5>Rest of NZ</h5>';
				$sHTML .= '<a href="viewlocations.php?suburb=Christchurch" class="btn btn-default">Christchurch</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Hamilton" class="btn btn-default">Hamilton</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Leigh" class="btn btn-default">Leigh</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mangawhai" class="btn btn-default">Mangawhai</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Matakana" class="btn btn-default">Matakana</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mt%20Maunganui" class="btn btn-default">Mt Maunganui</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Nelson" class="btn btn-default">Nelson</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Rotorua" class="btn btn-default">Rotorua</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Tauranga" class="btn btn-default">Tauranga</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Waihi%20Beach" class="btn btn-default">Waihi Beach</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Waipu" class="btn btn-default">Waipu</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Warkworth" class="btn btn-default">Warkworth</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Wellington" class="btn btn-default">Wellington</a>';

			$sHTML .= '</div>';
			
		$sHTML .= '</div>';
	return $sHTML;
	}
?>

<?php
	
	//echo View::renderLocationTabs($suburb);
	echo renderLocationTabs($suburb);
	
	echo View::renderAllLocations($aLocations);


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
$('#locationSearch').autocomplete({
  	source: function( request, response ) {
  		$.ajax({
  			url : 'listLocations.php',
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
               location.href="viewlocation.php?locationID=" + ui.item.value;
        } 	
});	
	
/*
  	autoFocus: true,
  	minLength: 0,
*/

</script>

