<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	if(isset($_SESSION["UserID"])){
		$userID = $_SESSION["UserID"];
	}

	
	echo "<pre>";
	echo "GET ";
	print_r($_GET);
	echo "</pre>";
	
	echo "<pre>";
	echo "SESSION ";
	print_r($_SESSION);
	echo "</pre>";

	$oUser = new User();
	$oUser->load($userID);
	$useremail = $oUser->email;
	$username = $oUser->username;
	$disableNotifications = $oUser->settings;

	echo "<pre>";
	echo $disableNotifications;
	echo "</pre>";
	
	if(isset($_GET["addfollow"])){
		$newFollow = new Follow();
		$newFollow->userID = $userID;

		if(isset($_GET["fb"])){
			$Bid = $_GET["fb"];
			$newFollow->followbreweryID = $Bid;
			$oBrewery = new Brewery();
			$oBrewery->load($Bid);
			$Bname = $oBrewery->breweryname;
			$Bslug = $oBrewery->slug;
			
			$oUser = new User();
			$oUser->loadByBrewery($Bid);
			$Bemail = $oUser->email;
			
		}
		if(isset($_GET["fl"])){
			$newFollow->followlocationID = $_GET["fl"];
			
			$Lid = $_GET["fl"];
			
			$oLoc = new Location();
			$oLoc->load($Lid);
			$Lname = $oLoc->locationname;
			$Lslug = $oLoc->slug;
			
			
			$oUser = new User();
			$oUser->loadByLocation($Lid);
			$Lemail = $oUser->email;
			
	
		}
		if(isset($_GET["fu"])){
			
			$newFollow->followuserID = $_GET["fu"];
			
			$Fid = new User();
			$Fid->load($_GET["fu"]);
			$Fusername = $Fid->username;
			$Fuserslug = $Fid->slug;
			$Femail = $Fid->email;
		}
		
		$newFollow->save();
	}



echo "redirect & send email";

	
	// Redirect accordingly
	
	
	if(isset($_GET["revb"])){
		
		if($disableNotifications==0&&$Bemail!=""){
			// email user notification of new follower	
			
			$to      = $Bemail;
			$subject = "Brewhound: New Follower Notification";
						
			$message = '<html><body>';
			$message .= '<p> <a href="'.$domain.'user/'.$username.'">'.$username.'</a> is now following <a href="'.$domain.'location/'.$Bslug.'">'.$Bname.'</a></p>';
			$message .= '<p>You can customise your notification settings <a href="http://brewhound.nz/viewuseradmin">here</a></p>';
			$message .= "</body></html>";
	
						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
				echo "<pre>";
				echo $to;
				echo $message;
				echo "</pre>";
			
			mail($to, $subject, $message, $headers);
			echo "email sent to ".$to;
			echo $message;
		}

		echo "redirect to back brewery id = ".$Bslug;

		header('location:'.$domain.'brewery/'.$Bslug);
		
	}
	

	if(isset($_GET["revu"])){
		
		if($disableNotifications==0){

			// email user notification of new follower	
			
			$to      = $Femail;
			$subject = "Brewhound: New Follower Notification";
						
			$message = '<html><body>';
			$message .= '<p> <a href="'.$domain.'user/'.$slug.'">'.$username.'</a> is now following you.</p>';
			$message .= '<p>You can customise your notification settings <a href="http://brewhound.nz/viewuseradmin">here</a></p>';
	/*
			$message .= '<p><a href="'.$domain.'user/'.strip_tags($username).'">'.strip_tags($username).'</a> added a checkin</p>';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Brew:</strong> </td><td>" . strip_tags($title) . "</td></tr>";
			$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($brewery)."</td></tr>";
			$message .= "<tr><td><strong>Rating:</strong> </td><td>".strip_tags($_POST["rating"])."/5</td></tr>";
			$message .= "</table>";
			$message .= "<p><b>Review:</b> ".strip_tags($_POST["review"])."</p>";
	*/
			
			$message .= "</body></html>";
	
						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
				echo "<pre>";
				echo $to;
				echo $message;
				echo "</pre>";
			
			mail($to, $subject, $message, $headers);
			
			echo "mail sent - redirect to back user = ".$_GET["fu"];
		}
		echo "redirect to back user = ".$Fuserslug;
		
		header('location:'.$domain.'user/'.$Fuserslug);
		exit;
	}


	if(isset($_GET["revl"])){
		
		if($disableNotifications==0){
			// email user notification of new follower	
			
			$to      = $Lemail;
			$subject = "Brewhound: New Follower Notification";
						
			$message = '<html><body>';
			$message .= '<p> <a href="'.$domain.'user/'.$username.'">'.$username.'</a> is now following <a href="'.$domain.'location/'.$Lslug.'">'.$Lname.'</p>';
			$message .= '<p>You can customise your notification settings <a href="http://brewhound.nz/viewuseradmin">here</a></p>';
	/*
			$message .= '<p><a href="'.$domain.'user/'.strip_tags($username).'">'.strip_tags($username).'</a> added a checkin</p>';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Brew:</strong> </td><td>" . strip_tags($title) . "</td></tr>";
			$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($brewery)."</td></tr>";
			$message .= "<tr><td><strong>Rating:</strong> </td><td>".strip_tags($_POST["rating"])."/5</td></tr>";
			$message .= "</table>";
			$message .= "<p><b>Review:</b> ".strip_tags($_POST["review"])."</p>";
	*/
			
			$message .= "</body></html>";
	
						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
				echo "<pre>";
				echo $to;
				echo $message;
				echo "</pre>";
			
			mail($to, $subject, $message, $headers);
	
		}
		
		echo "redirect to ".'location:'.$domain.'location/'.$_GET["slug"];

		header('location:'.$domain.'location/'.$_GET["slug"]);
		exit;
	}

/*
		$viewUserID = $newFollow->followuserID;
		header("location:viewuser.php?viewUserID=".$viewUserID);
*/
	
	
	//remove follows
	
	if(isset($_GET["removefollowL"])&&isset($_GET["slug"])){
		echo "remove follow & redirect to ".'location:'.$domain.'location/'.$_GET["slug"];
		Follow::remove($_GET["followID"]);
		header('location:'.$domain.'location/'.$_GET["slug"]);
	}
	
	if (isset($_GET["followID"])&&isset($_GET["removefollowB"])&&isset($_GET["slug"])) {
		echo "remove follow & redirect to ".'location:'.$domain.'location/'.$_GET["slug"];
		Follow::remove($_GET["followID"]);
		echo 'location:'.$domain.'brewery/'.$_GET["slug"];
		exit;
		header('location:'.$domain.'brewery/'.$_GET["slug"]);
	} 

	if (isset($_GET["removefollowU"])&&isset($_GET["slug"])) {
	Follow::remove($_GET["followID"]);
	echo "remove follow & redirect to admin";
	header('location:viewuseradmin.php');
	}
	
	if (isset($_GET["removefollow"])&&isset($_GET["slug"])) {
	Follow::remove($_GET["followID"]);
	echo "remove follow & redirect to admin";
	header('location:viewuseradmin.php');
	}
	
	// return to admin
	
	if (isset($_GET["removefollow"])) {
	Follow::remove($_GET["followID"]);
	echo "remove follow & redirect to admin";
	header('location:viewuseradmin.php');
	}
	
	// none of the above
	
	if(!isset($_GET["addfollow"])){
		$Bid = $_GET["fb"];
		$followID = FollowManager::getFollowBreweryID($userID,$Bid);
		
		Follow::remove($followID);

		header('location:'.$domain.'brewery/'.$_GET["slug"]);
	}

	echo 'no matches';




/*
	if(isset($_GET["removefollow"])&&$_GET["followID"]==true){
		echo "remove follow ".$_GET["followID"];
		Follow::remove($_GET["followID"]);
		header('location:'.$domain.'location/'.$_GET["slug"]);
	
	}
	if(isset($_GET["removefollowFromViewUser"])&&$_GET["followID"]==true){

		Follow::remove($_GET["followID"]);
		
		$redirect = $_GET["redirectViewUser"];
		header("location:viewuseradmin.php?viewUserID=".$redirect);
	
	}
*/

?>