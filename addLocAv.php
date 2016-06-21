<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	// redirect if not logged in
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
	
/*
	if ($_SESSION["UserID"]==$_GET["userID"]){
			echo "<pre>";
			echo "logged in matches admin role";
			echo "</pre>";
	} else {
		header("location:login.php"); 
	}
*/
	
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
	

	if(isset($_GET["slug"])){
		$slug = $_GET["slug"];
	}	
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	if(isset($_GET["locationID"])){
		$locationID = $_GET["locationID"];
	}
	if(isset($_GET["locationmanagerID"])){
		$locationmanagerID = $_GET["locationmanagerID"];
	}
	if(isset($_GET["breweryID"])){
		$breweryID = $_GET["breweryID"];
	}

	if(isset($_SESSION["UserID"])){
		$userID = $_SESSION["UserID"];
	}


	Availability::addAvLoc($beerID,$breweryID,$locationID,$userID);
	Location::activty($locationID,date('Y-m-d H:i:s'));
	
	
	if(isset($_GET["locationID"])){
		// Send location update to followers
			
			$oLocation = new Location();
			$oLocation->load($_GET["locationID"]);
			
			$brew = new Beer();
			$brew->load($beerID);
			$title = $brew->title;
			$brewery = $brew->breweryname;
			
			$table = 'followLocationID';
			$aFollowersIDsList = FollowManager::getFollowersIDsList($locationID,$table);
			
			foreach($aFollowersIDsList as $userid){
			
			$oFollower = new User();
			$oFollower->load($userid);
			
				if($oFollower->settings != 1){
					// Compose email notifications to followers
					$to      = $oFollower->email;
					$subject = "Brewhound: Activity Notification";
							
					$message = '<html><body>';
					$message .= '<p><a href="'.$domain.'location/'.strip_tags($oLocation->slug).'">'.strip_tags($oLocation->locationname).'</a> tapped a new brew</p>';
					$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
					$message .= "<tr style='background: #eee;'><td><strong>Brew:</strong> </td><td>" . strip_tags($title) . "</td></tr>";
					$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($brewery)."</td></tr>";
					$message .= "</table>";
					$message .= '<p>You can customise your notification settings <a href="http://brewhound.nz/viewuseradmin">here</a></p>';
					$message .= "</body></html>";
		
								
					// In case any of our lines are larger than 70 characters, we should use wordwrap()
					$message = wordwrap($message, 70, "\r\n");
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					
					$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
					    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
	
					// Send email notification to follower
					//mail($to, $subject, $message, $headers);
				}
			} // end for each
		
	} // end if
	
	
	if(isset($_GET["quickadd"])){
		
		//echo $domain.$slug.'?addAvLoc=true';
		header("location:$domain$slug?addAvLoc=true");
		//header("location:viewbeer.php?beerID=$beerID&addAvLoc=true");
		exit;
	}
	

	// redirect 
	header("location:$domain$slug?addAvLoc=true");
	//header("location:index.php?addAvLoc=true"); 
	
	?>