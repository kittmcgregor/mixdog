<?php include_once'includes/header.php'; ?>
	
	<div id="banner"></div>

		<div id="content" class="wrapper clearfix">

				<div id="intro">Find the freshest brews on tap</div>

			
			<div id="searchForm">
				<form id="homeSearchForm" action="search.php" method="post" enctype="multipart/form-data">
					<div class="form-group clearfix">
						<div class="col-md-8"><input type="text" class="form-control" id="searchKeywords" name="keyword" placeholder="search keywords..." value=""/></div>
						<div class="col-md-4"><input type="submit" id="submit" value="release the hounds" class="btn"></div>
					</div>	
				</form>
			</div> <!-- end search form -->
			
			
		</div> <!-- end content container -->
			
			<div id="maincontent">

					<div class="wrapper clearfix">
						<div class="col-md-8">
						
						<p>New Zealand's craft beer tap guide <a class="btn btn-link" href="about.php">read more...</a></p>

						<?php	$totalTaps = Availability::totalTaps();
								//$totalBrews = Beer::totalBrews();
								$totalLoc = Location::totalLocations();
								$totalBreweries = Brewery::totalBreweries();
								
									echo '<p class="quicklinks">';
									echo '<a class="btn btn-default" href="brews">'.$totalTaps.' taps</a> ';
									//echo '<a class="btn btn-default" href="brews">'.$totalBrews.' brews</a> ';
									echo '<a class="btn btn-default" href="locations">'.$totalLoc.' Locations</a> ';
									echo '<a class="btn btn-default" href="breweries">'.$totalBreweries.' Breweries</a> ';
									//echo ' <a class="btn btn-default" href="exclusive">'.count($totalLocExcl).' tap exclusives </a>';
									//echo ' <a class="btn btn-default" href="freshhop"> '.count($aFreshHop).' Fresh Hop</a>';
									echo '</p>';
						?>
						<p class="quicklinks">
<a class="btn btn-success" href="addstatusupdate">Check In <i class="fa fa-check-square" aria-hidden="true"></i></a> <a class="btn btn-info" href="addstatusupdate">Rate <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></a> <a class="btn btn-warning" href="addstatusupdate">Review <i class="fa fa-pencil" aria-hidden="true"></i></a> <a type="button" class="btn btn-primary" href="addstatusupdate">Upload Photo <i class="fa fa-picture-o" aria-hidden="true"></i></a>
</p>

						<?php

							//$tapCount = Availability::getTapCount();
							
							// LOCATION ACTIVITY
							$aAvIDs = Availability::all();
			
							echo "<h4>Recently Tapped</h4>";
							
							$limit = 5;
							echo View::renderActivity($aAvIDs,$limit);
	
							echo '<p class="quicklinks"><a class="btn btn-default" href="taps.php"> more fresh taps</a></p>';
	
						?>
						
						<h4>Fresh Hop Brews</h4>
							<ul id="listings">
								<?php
								$aFreshHop = Beer::FreshHop();
								if(count($aFreshHop)<6){
									$xpp = count($aFreshHop);
								} else {
									$xpp = 5;
								}
								$paginate = 0;
								echo View::renderFreshHopBeers($aFreshHop,$xpp,$paginate,$domain);
								?>
							</ul>
							<?php echo '<p><a class="btn btn-default" href="freshhop">View all Fresh Hop Brews </a></p>'; ?>
						

						<div class="spacer clearfix"></div>
						
							
						
						</div>
						<div class="col-md-4">
							<div class="aside">
								<h4>Fresh Taps</h4>
								<div id="map" class="smallmap margintop"></div>
								<div id="mapfilters">
								<a href"#" class="btn btn-default" id="auckland">AKL</a> <a href"#" class="btn btn-default" id="wellington">WGTN</a> <a href"#" class="btn btn-default" id="chch">CHC</a> <a href"#" class="btn btn-default" id="reset">ALL</a>
								</div>
								<a href="/map">View larger map</a>
							</div>

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
						
							
						</div>
					</div>
			</div>
		
		
<?php
	$url = 'http://brewhound.nz/listLocationMarkers.php';
	$lat_long = file_get_contents($url);
	include_once'includes/footer.php'; 
?>
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
		    		    
		    for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[i][0];
			    var markername = markers[i].markername;
				var link = markers[i].link;
				var latest = markers[i].latest;
				var image = markers[i].image;
				var size = '25x25';
				var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
				var region = markers[i].region;
				
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
				  content: '<a href="http://brewhound.nz/location/' + link + '">' + latest + ' @ ' + markername + '</a>'
				});
				 
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
