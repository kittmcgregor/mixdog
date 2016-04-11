<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	$uri = $_SERVER['REQUEST_URI'];
	
	// Create form object
	$oForm = new Form();


	
	$beerID = $_GET["beerID"];
	
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
			$oNewComment->beerID = $_GET["beerID"];
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

	$oBeer = new Beer();
	$oBeer->load($beerID);
	
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
	
	echo View::renderBeer($oBeer,$likeStatus,$userID,$aBeerlocations,$aExistingData);

// Show list of users who like this beer 
	$oLikers = Likes::loadLikers($beerID);
	
	echo View::renderLikers($oLikers);
	
	//show claim
	echo '<div class="claimbox">';
		echo '<div class="claimleft">Are you a stockist of this brew?<br/>Claim your free management account now.</div>';
		echo '<div class="claimleft"><a class="btn btn-default" href="about.php">Claim</a></div>';
	echo '</div>';			
	
	echo '</div>';
	echo '</div>';
	


	echo '<div class="wrapper clearfix">';
		echo '<div class="col-md-6">';
		
	// VIEW LOCATIONS
	
		echo View::renderAvailability($aBeerlocations);
		echo '<p>Please note: Freshness is a good indication of availability, although popular kegs often only last a matter of days. If a location is unclaimed then listings are crowd sourced and may not be current. If you\'d like to contribute then please <a href="/register.php" class="btn btn-default nomargin">sign up</a> and do so - cheers! </p>';

		echo "</div>";
	
// VIEW COMMENTS

	// Load commentID array
	$oCommentIDs = CommentIDs::loadCommentIDs($beerID);
	
	// Load comments

		echo '<div class="col-md-6">';
	
		echo View::renderComments($oCommentIDs);
		
	
		// Build & Render Comment form
	
		if (isset($_SESSION["UserID"])==true){
			$oForm->makeTextArea("Add Review","Comment","");
			$oForm->makeSubmit("Add Review","submit");
		} else {
			$oForm->makeTextArea("Add Review","Comment","you must be logged in to add a review");
		}
		echo View::renderCommentForm($oForm);
		//echo $uri;
		//echo View::renderLoginRegister();
		echo '</div>';
	
	echo '</div>';
	
	include_once'includes/footer.php'; 

?>