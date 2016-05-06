<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
/*
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
*/
/*
	if(isset($_GET["userID"])){
		if($_GET["userID"]==1){
			echo View::renderClaimLocation($locationID);
		}
	}
*/
	
	$name = $_GET["name"];
	
	$oLocation = new Location();
	$oLocation->loadByName($name);
	$locationID = $oLocation->locationID;
	
	$claimstatus = $oLocation->claimstatus;
	echo View::renderLocation($oLocation);

	
// superadmin edit
	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			echo '<div class="wrapper">';
			echo '<a class="btn btn-default" href="'.$domain.'editlocation.php?locationID='.$locationID.'">superadmin edit</a>';
			echo '</div>';
		}
	}
	

	
	// Load Beers related to location
	$aAvailableIDs = Availability::loadIDs($locationID);
	
	if(isset($_GET['orderbybrewery'])){
		if($_GET['orderbybrewery']=true){
			$aAvailableIDs = Availability::loadIDsBrewery($locationID);
		}
	}

	
	//$aBeersAvailable = Availability::loadBeers($locationID);
	//echo View::renderBeersAvailable($aBeersAvailable);
	echo View::renderAvailableData($aAvailableIDs,$locationID,$claimstatus,$domain);
	
			
/*
echo "<pre>";
print_r($aAvailableIDs);
echo "</pre>";
*/


	include_once'includes/footer.php';
	$url = 'http://brewhound.nz/listSingleLocation.php?locationID='.$locationID;
	$lat_long = file_get_contents($url);

?>
<script>
	var markers = <?php echo $lat_long; ?>;
	//console.log(markers[0]);
	//console.log(markers);
	
	
	if(markers!=null){
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
		    		    
		    //for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[0];
/*
			    var markername = markers[i].markername;
				var link = markers[i].link;
				var latest = markers[i].latest;
				var image = markers[i].image;
				var size = '50x50';
				var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
*/
				//console.log(image);
				
			    marker = new google.maps.Marker({
					position: latlng,
					map: map,
					//icon: url,
					//zIndex: i, 
					//title: markername
				});
				
/*
				marker.info = new google.maps.InfoWindow({
				  content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
				});
*/

				//console.log(markername);
				 
/*
				google.maps.event.addListener(marker, 'click', function() {  
				    var marker_map = this.getMap();
				    this.info.open(marker_map, this);
				    // Note: If you call open() without passing a marker, the InfoWindow will use the position specified upon construction through the InfoWindowOptions object literal.
				});
*/
				 
			//}		
			
		    var bounds = new google.maps.LatLngBounds();

			bounds.extend (new google.maps.LatLng (markers[0].lat,markers[0].lng));
				  

			
			map.fitBounds(bounds);
			//}

		}
  	}
</script>
<?php if($lat_long!='null'){
	echo $lat_long;
	echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEwC6wpxW99D8hw95lEoBjCV1a_qVvcrs&callback=initMap"
    async defer>
</script>';
}
?>