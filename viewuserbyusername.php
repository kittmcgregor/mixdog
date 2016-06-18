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

	echo '<div class="wrapper clearfix">';
	
	
	
	$aUserIDsOfFollowing = array('0'=>$id);
	
	$aStatusUpdates = Status::followinglatest($aUserIDsOfFollowing);
	$aFollowingIDsList = FollowManager::getFollowingIDsList($id);
	
	$table = 'followUserID';
	$aFollowersIDsList = FollowManager::getFollowersIDsList($id,$table);
	
	$limit = 5;
	
	if(count($aStatusUpdates)<6){
		$limit = count($aStatusUpdates);
	}
	
	echo '<div class="col-md-8">';
	echo '<h4>Activity</h4>';
	if(!empty($aStatusUpdates)){
		echo View::renderStatusUpdates($aStatusUpdates,$domain,$limit);
	} else {
		echo '<p>nothing to see here folks</p>';
	}
	
	echo '</div>';
	echo '<div class="col-md-4">';;
		echo '<h4>Followers</h4>';
		echo View::renderFollowersList($aFollowersIDsList,$domain,$limit);


		echo '<h4>Following</h4>';
		echo View::renderFollowingListBasic($aFollowingIDsList,$domain,$limit);
		echo '</div>';
	echo '</div>';	
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