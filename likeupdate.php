<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 
	
	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
	
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";
	
	if($_GET["addlike"]==true){
		$newLike = new Like();
		$newLike->beerID = $beerID;
		$newLike->userID = $_GET["userID"];
		$newLike->save();
	
	
			$oUser = new User();
			$oUser->load($_GET["userID"]);
			
			$oBeer = new Beer();
			$oBeer->load($beerID);
			
			
	// Send email notification to admin
/*
			$to      = 'admin@version.nz';
			$subject = 'Brewhound: like ';
			
			$message = '<html><body>';
			$message = '<p> </p>';
			$message .= '<a href="http://beta.brewhound.nz/viewuser.php?viewUserID='.strip_tags($oUser->userID).'">'.strip_tags($oUser->username).'</a> liked
						<a href="http://beta.brewhound.nz/viewuser.php?viewbeer='.$beerID.'">'.strip_tags($oBeer->title).'</a>
						';
			$message .= "</body></html>";
						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
*/

	
	}
	if($_GET["removelike"]==true){
		echo 'remove like';
		Like::remove($_GET["likeID"]);
	}
	
	
	// redirect 
	header("location:viewbeer.php?beerID=".$beerID); 
	
	?>