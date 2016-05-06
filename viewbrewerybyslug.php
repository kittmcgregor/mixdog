<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	$iBreweryID = 1;
	
/*
	if(isset($_GET["breweryID"])){
		$iBreweryID = $_GET["breweryID"];
	}
*/
	
	$slug = $_GET["slug"];
	
	$oBrewery = new Brewery();
	$oBrewery->loadBySlug($slug);
	$iBreweryID = $oBrewery->breweryID;
	
	
/*
	if(isset($_GET["userID"])){
	if($_GET["userID"]==1){
			echo View::renderClaimBrewery($iBreweryID);
		}
	}
*/

	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			echo '<div class="wrapper">';
			echo '<a class="btn btn-default" href="'.$domain.'editbrewery.php?breweryID='.$iBreweryID.'">superadmin edit</a>';
			echo '</div>';
		}
	}


	$oBrewery = new Brewery();
	$oBrewery->load($iBreweryID);
					
	echo View::renderBrewery($oBrewery,$domain);


	// Load Beers related to location


	$aBeers = Beer::loadIDsByBrewery($iBreweryID);
	
	echo '<div class="wrapper clearfix">';
	
		echo '<div class="col-md-8">';
		echo View::renderBeersByBrewery($aBeers,$domain);
		echo '</div>';
	
		echo '<div class="col-md-4">';
		echo '<div id="map" class="tallmap"></div>';
		echo '</div>';
	
	
	echo '</div>';
	
/*
	$aBeersAvailable = Availability::loadBrewery($iBreweryID);
	
	echo View::renderBeersAvailable($aBeersAvailable);
*/


	//echo View::renderBeersUnAvailable($aBeersAvailable);

	include_once'includes/footer.php';

	$url = 'http://brewhound.nz/listBreweryTaps.php?brewery='.$iBreweryID;
	$lat_long = file_get_contents($url);
?>
<script>
	var markers = <?php echo $lat_long; ?>;
	//console.log(markers);
	if(markers!=false){
			var map;
			function initMap() {
		    map = new google.maps.Map(document.getElementById('map'), {
		      center: {lat:-36.865367, lng: 174.761234},
		      mapTypeControl: false,
		      streetViewControl: false,
		      maxZoom: 15,
		      zoom: 10
		    });
		    
		    //console.log(markers[0].markername);
		    		    
		    for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[i][0];
			    console.log(latlng);
			    var markername = markers[i].markername;
				var link = markers[i].link;
				var latest = markers[i].latest;
				var image = markers[i].image;
				var size = '50x50';
				var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
				//console.log(image);
				
			    marker = new google.maps.Marker({
					position: latlng,
					map: map,
					icon: url,
					zIndex: i, 
					title: markername
				});
				
				marker.info = new google.maps.InfoWindow({
				  content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
				});

				//console.log(markername);
				 
				google.maps.event.addListener(marker, 'click', function() {  
				    var marker_map = this.getMap();
				    this.info.open(marker_map, this);
				    // Note: If you call open() without passing a marker, the InfoWindow will use the position specified upon construction through the InfoWindowOptions object literal.
				});
				 
			}		
						
		    var bounds = new google.maps.LatLngBounds();

				for (var i = 0; i < markers.length; i++) {
			 		//  And increase the bounds to take this point
				  bounds.extend (new google.maps.LatLng (markers[i][0].lat,markers[i][0].lng));

			
			map.fitBounds(bounds);
			}

		}
  	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEwC6wpxW99D8hw95lEoBjCV1a_qVvcrs&callback=initMap"
    async defer>
</script>