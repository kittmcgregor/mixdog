<?php 
	ob_start(); // for redirection to work
	//session_start();
	include_once'includes/header.php';
	
/*
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
*/
	
	if(isset($_GET["viewUserID"])){
		$viewUserID = $_GET["viewUserID"];
	}
	
	$oUser = new User();
	$oUser->load($viewUserID);
					
	echo View::renderUser($oUser);


	echo '<div class="wrapper">';
	// Load Beers related to user

	//$aBeersLiked = Likes::loadBeers($iUserID);
	echo View::renderBeersLiked($oUser->likes);
	

	
// VIEW COMMENTS

	// Load commentID array
	$oCommentIDs = CommentIDs::loadCommentIDsByUser($viewUserID);
	
	// Load comments
	echo View::renderUserComments($oCommentIDs);

	echo '</div>';
	
	include_once'includes/footer.php';

?>