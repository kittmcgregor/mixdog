<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php");
	}
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/	
	
	$iUserID = $_SESSION["UserID"];

	$oUser = new User();
	$oUser->load($iUserID);
			
	echo View::renderUserAdmin($oUser);

	//echo View::renderActivity($iUserID);

	// list follow instances
	
	$aFollowingIDsList = FollowManager::getFollowingIDsList($iUserID);

echo '<div class="wrapper clearfix">';
echo '<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Following</a></li>
    <li><a href="#tabs-2">Activity</a></li>
    <li><a href="#tabs-3">Your Likes</a></li>
    <li><a href="#tabs-4">Your Comments</a></li>
  </ul>';
  
echo '<div id="tabs-1" class="clearfix">';
    
    	if(count($aFollowingIDsList)!=0){

		View::renderFollowingList($aFollowingIDsList);
		}
    
echo '</div>';
  
echo '<div id="tabs-2" class="clearfix">';
    if(count($aFollowingIDsList)!=0){

		//View::renderFollowingList($aFollowingIDsList);
		
		// list realted user to extract activity
		$aUserIDsOfFollowing = FollowManager::getFollowingUserIDsList($aFollowingIDsList);
		
		$aLikeStream = Likes::getUsersLikeActivity($aUserIDsOfFollowing);
	
		// Show followers like activity

		echo View::renderLikeStream($aLikeStream,5);


	}
	
	if(count($aFollowingIDsList)!=0){
		
		// list realted locations to extract activity
		$aFollowingLocationsIDsList = FollowManager::getFollowingLocationIDsList($aFollowingIDsList);
		
		$aLocationStream = Availability::getLocationActivity($aFollowingLocationsIDsList);
		
		
		//echo View::renderLocationActivityStream($aLocationStream);
		
	}
	echo '</div>';

    echo '<div id="tabs-3" class="clearfix">';
	
	// Load Beers related to user


	//$aBeersLiked = Likes::loadBeers($iUserID);
	echo View::renderBeersLiked($oUser->likes);


	echo '</div>';
		
    echo '<div id="tabs-4" class="clearfix">';
	
// VIEW COMMENTS

	// Load commentID array
	$oCommentIDs = CommentIDs::loadCommentIDsByUser($iUserID);
	

	// Load comments
	echo View::renderUserComments($oCommentIDs);

	echo '</div>';


	echo '</div>'; // close tabs

echo '</div>'; // close wrapper
	
/*
	$iFollowUserID = 20;
	$alikedbeers = Likes::loadList($iFollowUserID);
*/
	
	//echo View::renderFollowersLikedBeers($alikedbeers);
	
	
	if($oUser->breweryID!=0){
		$oBrewery = new Brewery();
		$oBrewery->load($oUser->breweryID);
		$iBreweryID = $oBrewery->breweryID;
		 
		// Load Beers related to location
		$aBeersAvailable = Availability::loadBrewery($iBreweryID);				
	

		echo '<div class="wrapper clearfix">';
			echo '<div class="col-md-12">';
			echo '<h4>Brewery Admin</h4>';
			echo '<div class="solidline"></div>';
			echo '<div class=""><a class="btn btn-default" href="addbeer.php">add brew</a></div>';
			echo '</div>';
		echo '</div>';

		echo View::renderBrewery($oBrewery);
			
		//echo View::renderBeersAvailable($aBeersAvailable);	
		
		echo View::renderManageBreweryAvailabilty($aBeersAvailable,$iBreweryID,$iUserID);
	}
	
/*
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
*/
	if($oUser->locationID!=0){
	echo '<div class="wrapper clearfix">';
		echo '<div class="col-md-12">';
		echo '<h4>Location Admin</h4>';
		echo '<div class="solidline"></div>';
		
			echo '<div class="col-md-10">';
				echo '<h5>Stats - Advanced stats coming soon</h5>';
				$TappedAllTime = count(Availability::loadAllIDs($oUser->locationID));
				echo '<p><span class="stat">'.$TappedAllTime.'</span> Brews tapped since stats activated on Mar 7th 2016 (includes what was current at the time).</p>';
				
				$TappedLastMonth = count(Availability::lastMonth($oUser->locationID));
				echo '<p><span class="stat">'.$TappedLastMonth.'</span> Brews tapped in last 30 days</p>';
				
				$TappedLastWeek = count(Availability::lastWeek($oUser->locationID));
				echo '<p><span class="stat">'.$TappedLastWeek.'</span> Brews tapped in the last 7 days</p>';
			
			echo '</div>';
			
			echo '<div class="col-md-2">';
			echo '<div class=""><a class="btn btn-default" href="editlocation.php?locationID='.$oUser->locationID.'">edit location</a></div>';
			echo '</div>';
		
		echo '</div>';
	echo '</div>';
	}
			
	if($oUser->locationID!=0){
		$oLocation = new Location();
		$oLocation->load($oUser->locationID);
		$locationID = $oLocation->locationID;
		$claimstatus = $oLocation->claimstatus;			
		echo View::renderLocation($oLocation);
	
		// Load Beers related to location
/*
		$aBeersAvailable = Availability::loadBeers($iLocationID);
		echo View::renderManageAvailabilty($aBeersAvailable,$iLocationID,$iUserID);
*/
		
		$aAvailableIDs = Availability::loadIDs($locationID);
		echo View::renderAvailableData($aAvailableIDs,$locationID,$claimstatus);
		
		
	}
	

	
	include_once'includes/footer.php';

?>