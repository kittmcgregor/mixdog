<?php include_once'includes/header.php'; 
?>
	
	
	
	
<!-- 	<div id="banner"></div> -->
<!-- 		<div id="content" class="wrapper clearfix"></div> -->
			
			<div id="searchResults">

					<div class="wrapper clearfix">
			
						<div class="col-md-8">
							<div id="mapfilters" class="marginbottom">
							<a href"#" class="btn btn-default" id="auckland">AKL</a> <a href"#" class="btn btn-default" id="wellington">WGTN</a> <a href"#" class="btn btn-default" id="chch">CHC</a> <a href"#" class="btn btn-default" id="reset">ALL</a>
							</div>

							<div id="map" class="fullheightmap"></div>
<!-- 							<a href"#" class="btn btn-default" id="today">tapped today</a> <a href"#" class="btn btn-default" id="thisweek">tapped this week</a>  
	<a href"#" class="btn btn-default" id="hawkesbay">Hawkes Bay</a>
	 -->
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
	
	$datetime = new DateTime();
	$now = '2016-05-09 18:00:00';
	//$now = $datetime->format('Y-m-d H:m:s');
	$midnight = $datetime->setTime(0,0,0);


		echo "<pre>";
		echo $midnight->format('Y-m-d H:m:s');
		echo "</pre>";
		
		echo "<pre>";
		echo $now;
		echo "</pre>";
	
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
		    
/*
		    var t = <?php echo $now; ?>.split(/[- :]/);
		    var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
*/

		    var now = '2016-05-09 18:00:00';
			var midnight = '2016-05-09 00:05:00';
			var lastweek = '2016-05-06 00:05:00';
		    //console.log(markers[0].markername);
		    //console.log(d);
		    // create array of marker objects
		    markerobjects = [];
		    	    
		    for (var i = 0; i < markers.length; i++) {

			    var latlng = markers[i][0];
			    var markername = markers[i].markername;
				var link = markers[i].link;
				var latest = markers[i].latest;
				var image = markers[i].image;
				var size = '50x50';
				var url = 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
				var updated = markers[i].updated;
				var region = markers[i].region;
				//console.log(image);
				console.log(markers[i].region);
				
			    marker = new google.maps.Marker({
					position: latlng,
					map: map,
					icon: url,
					zIndex: i, 
					title: markername,
					 // Custom Attributes / Data / Key-Values
				    updated: updated,
				    region: region
				});
				
				// add marker to array
				//console.log(marker);
				markerobjects.push(marker);
				
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
			
			$(document).on('click', '#today', function(){
				var bounds = new google.maps.LatLngBounds();
				$.each(markerobjects, function(i, marker) {
				    if( (marker.updated > midnight) && (marker.updated < now) ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});
			
			$(document).on('click', '#thisweek', function(){
				var bounds = new google.maps.LatLngBounds();
				$.each(markerobjects, function(i, marker) {
				    if( (marker.updated > lastweek) && (marker.updated < now) ){
					    marker.setVisible(true);
				        // extending bounds to contain this visible marker position
						bounds.extend( marker.getPosition() );
				    } else {
					    marker.setVisible(false);
				    }
				});
				map.fitBounds(bounds);
			});
			
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