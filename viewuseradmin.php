<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php");
	}

	
	$iUserID = $_SESSION["UserID"];

	$oUser = new User();
	$oUser->load($iUserID);
	
	echo View::renderUserAdmin($oUser);
	
	$aExistingData = array();
	$aExistingData["Settings"] = $oUser->settings;
	
	$oForm = new Form();
	$oForm->data = $aExistingData;
	
	
	if(isset($_POST["submit"])){
		
		$oForm->data = $_POST;
		
		if(isset($_POST["Settings"])){
					//Set values
			$oUser->settings = $_POST["Settings"];
	
		} else {
			$oUser->settings = "";
		}
		
		$oUser->update();
		
	}
	
	
	echo '<div class="wrapper clearfix">';
	echo '<div id="tabs">
	  <ul>
	    <li><a href="#tabs-1">Checkins</a></li>
	    <li><a href="#tabs-2">Tap Activity</a></li>
	    <li><a href="#tabs-3">Following</a></li>
	    <li><a href="#tabs-4">Followers</a></li>
	    <li><a href="#tabs-5">Likes</a></li>
	    <li><a href="#tabs-6">Settings</a></li>
	  </ul>';
	  
	echo '<div id="tabs-1" class="clearfix">';
	
	//echo '<p>Temporarily Unavailable</p>';
		// get all followIDs - each follow event
		$aFollowingIDsList = FollowManager::getFollowingIDsList($iUserID);
		$aFollowersIDsList = FollowManager::getFollowersIDsList($iUserID,'followUserID');
		
		// limit number of results
		$limit = 5;

		if(!empty ($aFollowingIDsList)){
			// get user ids - user ids of those you are following
			$aUserIDsOfFollowing = FollowManager::getFollowingUserIDsList($aFollowingIDsList);
			
			// get checkins of those you are following
			
			if(!empty ($aUserIDsOfFollowing)){
						
				$aStatusUpdates = Status::followinglatest($aUserIDsOfFollowing);
				//$aStatusUpdatesLatest = Status::latest();
						
				
				if(count($aStatusUpdates)<6){
					$limit = count($aStatusUpdates);
				}
				
				echo View::renderStatusUpdates($aStatusUpdates,$domain,$limit);
				
				// get location IDs
				$aLocationIDsOfFollowing = FollowManager::getFollowingLocationIDsList($aFollowingIDsList);
				
				// get location updates
				$aAvails = Availability::followinglatestNoDate($aLocationIDsOfFollowing);	
				//$aStatuses = Status::followinglatestKeys($aUserIDsOfFollowing);
			}
		}

/*
        if(count($aFollowingIDsList)!=0){

		//View::renderFollowingList($aFollowingIDsList);
		
		// list realted user to extract activity
		$aUserIDsOfFollowing = FollowManager::getFollowingUserIDsList($aFollowingIDsList);
		
		$aLikeStream = Likes::getUsersLikeActivity($aUserIDsOfFollowing);
	
		// Show followers like activity

		
		echo View::renderLikeStream($aLikeStream,5,$domain);


	}
	
	if(count($aFollowingIDsList)!=0){
		
		// list realted locations to extract activity
		$aFollowingLocationsIDsList = FollowManager::getFollowingLocationIDsList($aFollowingIDsList);
		
		$aLocationStream = Availability::getLocationActivity($aFollowingLocationsIDsList);
		
		
		//echo View::renderLocationActivityStream($aLocationStream);
		
	}
*/

// Location Activity
echo '</div>';

    echo '<div id="tabs-2" class="clearfix">';
    if(!empty($aAvails)){
	    echo View::renderLocationUpdates($aAvails,$domain,$limit);
    } else {
	    echo "<p>You're not following any locations yet</p>";
    }
	echo '</div>';


// List of those you are following
echo '<div id="tabs-3" class="clearfix">';


/*
	    if(count($aFollowingIDsList)!=0){
		View::renderFollowingList($aFollowingIDsList,$domain);
		}
*/


	    if(count($aFollowingIDsList)!=0){
		echo '<h4 class="solidline">Users<h4>';
		View::renderFollowingUsers($aFollowingIDsList,$domain);
		}
		
	    if(count($aFollowingIDsList)!=0){
		echo '<h4 class="solidline">Locations<h4>';
		View::renderFollowinglocation($aFollowingIDsList,$domain);
		}
			
	    if(count($aFollowingIDsList)!=0){
		echo '<h4 class="solidline">Breweries<h4>';
		View::renderFollowingbrewery($aFollowingIDsList,$domain);
		}

/*
	echo "<pre>";
	print_r($aFollowingIDsList);
	echo "</pre>";
*/
			
	echo '</div>';

// List of those who are following you
    echo '<div id="tabs-4" class="clearfix">';
	echo View::renderFollowersList($aFollowersIDsList,$domain);

	
	echo '</div>';
	
    echo '<div id="tabs-5" class="clearfix">';	
	// Load Beers related to user
	//$aBeersLiked = Likes::loadBeers($iUserID);
	echo View::renderBeersLiked($oUser->likes);
	echo '</div>';

    echo '<div id="tabs-6" class="clearfix">';	

	$oForm->makeCheckboxInput("Notifications","Settings","1","disable");
	$oForm->makeSubmitLeft("Save Settings","submit");
	echo $oForm->html;
	
	//echo '<p>Please <a class="btn btn-default" href="http://brewhound.nz/about.php">contact admin</a> for help</p>';
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

		echo View::renderBrewery($oBrewery,$domain);
			
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
		echo View::renderLocation($oLocation,$domain);
	
		// Load Beers related to location
/*
		$aBeersAvailable = Availability::loadBeers($iLocationID);
		echo View::renderManageAvailabilty($aBeersAvailable,$iLocationID,$iUserID);
*/
		
		$aAvailableIDs = Availability::loadIDs($locationID);
		echo View::renderAvailableData($aAvailableIDs,$locationID,$claimstatus,$domain);
		
		
	}
	

	
	include_once'includes/footer.php';

/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";	
*/

/*
	echo "<pre>";
	print_r($oForm->data);
	echo "</pre>";
*/

/*
	echo "<pre>";
	echo "user id ".$iUserID;
	echo "</pre>";

echo "<pre>user ids of those you are following ";
print_r($aUserIDsOfFollowing);
echo "</pre>";

	echo "<pre>status updates/checkins ";
	print_r($aStatusUpdates);
	echo "</pre>";
	
	echo "<pre>latest status updates/checkins ";
	print_r($aStatusUpdatesLatest);
	echo "</pre>";
	
echo "<pre>Following IDs ";
print_r($aFollowingIDsList);
echo "</pre>";

echo "<pre>Followers IDs ";
print_r($aFollowersIDsList);
echo "</pre>";
*/
	
/*
echo "<pre>";
print_r($aStatuses);
echo "</pre>";

echo "<pre>";
print_r($aAvails);
echo "</pre>";

$activity = $aStatuses + $aAvails;
ksort($activity);

$latestActivity = array();

foreach ($activity as $key => $val) {
    $latestActivity[$key] = $val;
}

echo "<pre>";
print_r($latestActivity);
echo "</pre>";
*/

?>