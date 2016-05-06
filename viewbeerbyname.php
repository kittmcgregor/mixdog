<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	$uri = $_SERVER['REQUEST_URI'];
	
	// Create form object
	$oForm = new Form();

/*
echo "<pre>";
print_r($_GET);
echo "</pre>";
*/

	$beerName = $_GET["name"];
	//$beerID = $_GET["beerID"];
	
	$oBeer = new Beer();
	$oBeer->loadByName($beerName);
	$beerID = $oBeer->beerid;
	
	
	$userID = 0;
	$likeStatus = "";
	if(isset($_SESSION["UserID"])){
		$userID = $_SESSION["UserID"];
		// GET LIKE STATUS
	$likeStatus = new Like();
	$likeStatus->loadIfLiked($beerID,$userID);
	}

	if(isset($_POST["submit"])){
		// is it a post request?
		if(!isset($_GET["success"])){
			$oForm->data = $_POST;
		}

		$oForm->checkRequired("Comment");

		if($oForm->valid==true){
			// insert data to database to create new comment
			$oNewComment = new Comment();
			
			// set values;
			$oNewComment->beerID = $beerID;
			$oNewComment->userID = $_SESSION["UserID"];
			$oNewComment->comment = $_POST["Comment"];
			$oNewComment->save();
	
			$oUser = new User();
			$oUser->load($_SESSION["UserID"]);
			$sUsername = $oUser->username;
			
				// Send email notification to admin
			$to      = 'admin@version.nz';
			$subject = "Brewhound Notification: New Comment";
			
$message = '<html><body>';
$message .= '<p>A new comment was added at brewhound.nz</p>';
$message .= "<p><b>Comment:</b> ".strip_tags($_POST["Comment"])."</p>";
$message .= '<p><a href="http://brewhound.nz'.$uri.'">on this beer</a></p>';
$message .= "<p><b>User:</b> ".$sUsername."</p>";
$message .= "</body></html>";

						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers .= "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
		
			// redirect to success page
			header("location:viewbeer.php?newcomment=true&beerID=".$beerID);
			exit;
		
		}
	
	}
/*
	echo "<pre>";
	print_r($likeStatus);
	echo "</pre>";
*/

/*
	if($likeStatus->likeID==""){
		$liked=false;
	}
*/
	
	$aBeerlocations = Availability::loadLocationIDs($beerID);
	
	
	
// VIEW BEER

/*
		echo "<pre>";
		print_r($aBeerlocations);
		echo "</pre>";
*/
	
	
	

	//$oBeer->load($beerID);
	
/*
	echo "<pre>";
	print_r($oBeer);
	echo "</pre>";
*/
	
	echo '<div class="wrapper clearfix">';
	
	$aExistingLocations = array();
	foreach($oBeer->locations as $location){
		$aExistingLocations[] = $location->locationID;
	}
	
	$aExistingData = array();
	$aExistingData["Locations"] = $aExistingLocations;
	
	echo View::renderBeer($oBeer,$likeStatus,$userID,$aBeerlocations,$aExistingData,$domain);

// Show list of users who like this beer 
	$oLikers = Likes::loadLikers($beerID);
	
	echo View::renderLikers($oLikers);
	
	//show claim
	echo '<div class="claimbox">';
		echo '<div class="claimleft">Are you a stockist of this brew?<br/>Claim your free management account now.</div>';
		echo '<div class="claimleft"><a class="btn btn-default" href="about.php">Claim</a></div>';
	echo '</div>';			
	
	echo '<div id="map" class="smallmap"></div>';
	
	
	echo '</div>';
	echo '</div>';
	


	echo '<div class="wrapper clearfix">';
		echo '<div class="col-md-6">';
		
	// VIEW LOCATIONS
	
		echo View::renderAvailability($aBeerlocations,$domain);
		echo '<p>Please note: Freshness is a good indication of availability, although popular kegs often only last a matter of days. If a location is unclaimed then listings are crowd sourced and may not be current. If you\'d like to contribute then please <a href="/register.php" class="btn btn-default nomargin">sign up</a> and do so - cheers! </p>';

		echo "</div>";
	
	echo '<div class="col-md-6">';
			
	// VIEW CHECKINS
	
	echo '<h4>Checkins / Reviews / Ratings / Photos</h4>';
	
	echo '<p class="quicklinks">
<a class="btn btn-success" href="addstatusupdate?brew='.$beerID.'&name='.$beerName.'">Check In <i class="fa fa-check-square" aria-hidden="true"></i></a> <a class="btn btn-info" href="addstatusupdate">Rate <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></a> <a class="btn btn-warning" href="addstatusupdate">Review <i class="fa fa-pencil" aria-hidden="true"></i></a> <a type="button" class="btn btn-primary" href="addstatusupdate">Photo <i class="fa fa-picture-o" aria-hidden="true"></i></a>
</p>';
	
	
	
	$checkins = Status::loadbybeer($beerID);
	echo view::renderCheckins($checkins,$beerID,$domain);
	
/*
	// Load commentID array
	$oCommentIDs = CommentIDs::loadCommentIDs($beerID);
	
	// VIEW COMMENTS
	
	// Load commentID array
	$oCommentIDs = CommentIDs::loadCommentIDs($beerID);
	
	// Load comments


	
		echo View::renderComments($oCommentIDs);
		
	
		// Build & Render Comment form
	
		if (isset($_SESSION["UserID"])==true){
			$oForm->makeTextArea("Add Review","Comment","");
			$oForm->makeSubmit("Add Review","submit");
		} else {
			$oForm->makeTextArea("Add Review","Comment","you must be logged in to add a review");
		}
		echo View::renderCommentForm($oForm);
*/
		//echo $uri;
		//echo View::renderLoginRegister();
		echo '</div>';
	
	echo '</div>';
	
	include_once'includes/footer.php';
	$url = 'http://brewhound.nz/listBrewTaps.php?brew='.$beerID;
	$lat_long = file_get_contents($url);
?>
<script>
	var markers = <?php echo $lat_long; ?>;
	console.log(markers);
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