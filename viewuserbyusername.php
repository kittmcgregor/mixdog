<?php 
	ob_start(); // for redirection to work
	//session_start();
	include_once'includes/header.php';
	
/*
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
*/
	
	if(isset($_GET["username"])){
		$username = $_GET["username"];
	}
	
	$oUser = new User();
	$oUser->loadUserNameProfile($username);
	$id = $oUser->userID;
	
	echo View::renderUser($oUser,$domain);

	echo '<div class="wrapper">';
	
	
	
	$aUserIDsOfFollowing = array('0'=>$id);
	
	$aStatusUpdates = Status::followinglatest($aUserIDsOfFollowing);
	$limit = 10;
	echo View::renderStatusUpdates($aStatusUpdates,$domain,$limit);
	
	// Load Beers related to user

	//$aBeersLiked = Likes::loadBeers($iUserID);
	//echo View::renderBeersLiked($oUser->likes);
	

	
// VIEW COMMENTS

	// Load commentID array
/*
	$oCommentIDs = CommentIDs::loadCommentIDsByUser($viewUserID);
	
	// Load comments
	echo View::renderUserComments($oCommentIDs);
*/

	echo '</div>';
	
	include_once'includes/footer.php';

?>