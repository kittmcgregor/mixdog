<?php include_once'includes/header.php'; 
?>
	
	
	
	
	<div id="banner"></div>

		<div id="content" class="wrapper clearfix">
			
		</div> <!-- end content container -->
			
			<div id="searchResults">

					<div class="wrapper clearfix">
			
						<div class="col-md-8">
							<div id="map" class="fullheightmap"></div>

						</div>
						<div class="col-md-4">
							
							<div class="aside">
								<h4>Social Media</h4>
								<h5><a href="https://www.facebook.com/brewhoundnz/" target="_blank"><i class="fa fa-facebook-square"></i> facebook</a> &nbsp;<a href="https://twitter.com/brewhoundnz" target="_blank"><i class="fa fa-twitter-square"></i> twitter</a>
							</div>
							
							<div class="activity">
								<h4>Activity</h4>
								<?php
									$aStatusUpdates = Status::latest();
									$limit = 5;
									echo view::renderStatusUpdates($aStatusUpdates,$domain,$limit);
								?>
							
							</div>
							
							<div class="mostliked">
							<h4>Most Liked</h4>
							<?php
							$show = 5;
							$oAllBeers = new AllBeers();
							$oAllBeers->loadMostLikes();
							echo View::rendermostLiked($oAllBeers,$show,$domain);
							?>
<!-- 						<a href="stats.php" class="">view all</a> -->
							</div>							
							
						</div>
					</div>
			</div>
		
		
<?php
		$url = 'http://brewhound.nz/listLocationMarkers.php';
	$lat_long = file_get_contents($url);
	
	 include_once'includes/footer.php'; ?>

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
		    
		    //console.log(markers[0].markername);
		    		    
		    for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[i][0];
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
				  //content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
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
			}
			
			map.fitBounds(bounds);
	
  	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEwC6wpxW99D8hw95lEoBjCV1a_qVvcrs&callback=initMap"
    async defer>
</script>