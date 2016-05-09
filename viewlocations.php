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
	function renderLocationTabs($suburb,$domain){
		
		$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<input id="locationSearch" placeholder="Quick search" value=""> <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filters" aria-expanded="false" aria-controls="collapseExample">Filter Locations</a>';
			if ($suburb!="all"){
				$sHTML .= '<a href="viewlocations.php" class="btn btn-default">Show all</a>';
			}
				$sHTML .= '<div class="collapse" id="filters">';

				$sHTML .= '<h5>Auckland</h5>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Auckland%20Central" class="btn btn-default">Auckland Central</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Birkenhead" class="btn btn-default">Birkenhead</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Browns%20Bay" class="btn btn-default">Browns Bay</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Eden%20Terrace" class="btn btn-default">Eden Terrace</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Forrest%20Hill" class="btn btn-default">Forrest Hill</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Henderson" class="btn btn-default">Henderson</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Howick" class="btn btn-default">Howick</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Kingsland" class="btn btn-default">Kingsland</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Kumeu" class="btn btn-default">Kumeu</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Morningside" class="btn btn-default">Morningside</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Mt%20Eden" class="btn btn-default">Mt Eden</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Newmarket" class="btn btn-default">Newmarket</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Onehunga" class="btn btn-default">Onehunga</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Ponsonby" class="btn btn-default">Ponsonby GreyLynn</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Rosedale" class="btn btn-default">Rosedale</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Takapuna" class="btn btn-default">Takapuna</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Titirangi" class="btn btn-default">Titirangi</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Waiheke" class="btn btn-default">Waiheke</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Westlynn" class="btn btn-default">Westlynn</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Westmere" class="btn btn-default">Westmere</a>';

				$sHTML .= '<h5>Rest of NZ</h5>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Christchurch" class="btn btn-default">Christchurch</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Hamilton" class="btn btn-default">Hamilton</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Leigh" class="btn btn-default">Leigh</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Mangawhai" class="btn btn-default">Mangawhai</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Matakana" class="btn btn-default">Matakana</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Mt%20Maunganui" class="btn btn-default">Mt Maunganui</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Nelson" class="btn btn-default">Nelson</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Rotorua" class="btn btn-default">Rotorua</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Tauranga" class="btn btn-default">Tauranga</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Waihi%20Beach" class="btn btn-default">Waihi Beach</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Waipu" class="btn btn-default">Waipu</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Warkworth" class="btn btn-default">Warkworth</a>';
				$sHTML .= '<a href="'.$domain.'viewlocations.php?suburb=Wellington" class="btn btn-default">Wellington</a>';

			$sHTML .= '</div>';
			
		$sHTML .= '</div>';
	return $sHTML;
	}
?>

<?php	//echo View::renderLocationTabs($suburb);
	echo renderLocationTabs($suburb,$domain); ?>

<div class="wrapper clearfix">
	
	<div class="col-md-8">
	<?php	echo View::renderAllLocations($aLocations,$domain); ?>
	</div>
	
	<div class="col-md-4 desktopmap">
		<div id="map" class="tallmap"></div>
		<a href="/map">View larger map</a>
		<div id="mapfilters">
		<a href"#" class="btn btn-default" id="auckland">Auckland</a> <a href"#" class="btn btn-default" id="wellington">Wellington</a> <a href"#" class="btn btn-default" id="chch">Christchurch</a> <a href"#" class="btn btn-default" id="reset">all</a>
		</div>
	</div>
</div>

<?php
/*
	// Load Beers related to location

	$aBeersAvailable = Availability::loadBeers($locationID);
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	include_once'includes/footer.php';
	$url = 'http://brewhound.nz/listLocationMarkers.php';
	$lat_long = file_get_contents($url);
/*
	echo "<pre>";
	print_r($lat_long);
	echo "</pre>";
*/
?>
<script>
	
	$('input:text').focus(
    function(){
        $(this).val('');
    });
	$('#locationSearch').autocomplete({
	  	source: function( request, response ) {
	  		$.ajax({
	  			url : '<?php echo $domain ?>listLocations.php',
	  			dataType: "json",
	  			type: 'Get',
	  			data: {term: request.term},
				success: function( data ) {
					 var array = ( $.map( data, function( item,i ) {
						return {
							label: item.name,
							value: i,
							slug: item.slug
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
			location.href="<?php echo $domain ?>location/" + ui.item.slug;
			//location.href="<?php echo $domain ?>viewlocation.php?locationID=" + ui.item.value;
	    } 	
	});
</script>



<script>	
	var map;
	function initMap() {
		    map = new google.maps.Map(document.getElementById('map'), {
		      center: {lat:-36.865367, lng: 174.761234},
		      mapTypeControl: false,
		      streetViewControl: false,
		      zoom: 13
		    });
			
		    var markers = <?php echo $lat_long; ?>;
		    var markerobjects = [];
		    //console.log(markers[0].markername);
		    		    
		    for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[i][0];
			    var markername = markers[i].markername;
				var link = markers[i].link;
				var latest = markers[i].latest;
				var image = markers[i].image;
				var size = '50x50';
				var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
				var region = markers[i].region;
				//console.log(image);
				
			    marker = new google.maps.Marker({
					position: latlng,
					map: map,
					icon: url,
					zIndex: i, 
					title: markername,
					region: region
				});
				
				markerobjects.push(marker);
				
				marker.info = new google.maps.InfoWindow({
				  content: latest + ' @ <a href="http://brewhound.nz/location/' + link + '">' + markername + '</a>'
				});

				//console.log(markername);
				 
				google.maps.event.addListener(marker, 'click', function() {  
				    var marker_map = this.getMap();
				    this.info.open(marker_map, this);
				    // Note: If you call open() without passing a marker, the InfoWindow will use the position specified upon construction through the InfoWindowOptions object literal.
				});
				 
			}		
			
			
			$(document).on('click', '#reset', function(){
				$.each(markerobjects, function(i, marker) {
				marker.setVisible(true);
				bounds.extend( marker.getPosition() );
				});
				map.fitBounds(bounds);
			});

			$(document).on('click', '#auckland', function(){
				var bounds = new google.maps.LatLngBounds();
				console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Auckland' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});

			$(document).on('click', '#hawkesbay', function(){
				var bounds = new google.maps.LatLngBounds();
				console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Hawkes Bay' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});			

			$(document).on('click', '#wellington', function(){
				var bounds = new google.maps.LatLngBounds();
				console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Wellington' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});	


			$(document).on('click', '#chch', function(){
				var bounds = new google.maps.LatLngBounds();
				console.log(marker.region);
				$.each(markerobjects, function(i, marker) {
				    if( marker.region == 'Christchurch' ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});	

			
			
			
			
			
			
			
		    var bounds = new google.maps.LatLngBounds();
			
			for (var i = 0; i < markers.length; i++) {
			 		//  And increase the bounds to take this point
				  bounds.extend (new google.maps.LatLng (markers[i][0].lat,markers[i][0].lng));
			}
			
			map.fitBounds(bounds);
	
  	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEwC6wpxW99D8hw95lEoBjCV1a_qVvcrs&callback=initMap"
    async defer>
</script>