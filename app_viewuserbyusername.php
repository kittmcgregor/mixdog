
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div id="title" class="center sliding">User Profile</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="brew" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">


<?php 
	ob_start(); // for redirection to work
	//session_start();
	require_once 'includes/user.php';
	require_once 'app_includes/view.php';
	require_once 'includes/beer.php';
	require_once 'includes/statusupdates.php';
	require_once 'includes/FollowManager.php';
	
	$domain = 'http://'.$_SERVER['HTTP_HOST'].'/';
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
	
	
	echo '<div class="content-block">';
    	echo '<div class="content-block-inner">';
			echo View::renderUser($oUser,$domain);

	//echo '<div class="wrapper clearfix">';
	
	
	
	$aUserIDsOfFollowing = array('0'=>$id);
	
	$aStatusUpdates = Status::followinglatest($aUserIDsOfFollowing);
	$aFollowingIDsList = FollowManager::getFollowingIDsList($id);
	
	$table = 'followUserID';
	$aFollowersIDsList = FollowManager::getFollowersIDsList($id,$table);
	
	$limit = 10;



		echo '<p>';
			echo '<div class="buttons-row">';
				echo '<a href="#tab1'.$id.'" class="tab-link active button">Activity</a>
				 
				<a href="#tab2'.$id.'" class="tab-link button">Followers</a>
				 
				<a href="#tab3'.$id.'" class="tab-link button">Following</a> ';
			echo '</div>';
		echo '</p>';
			
	echo '<div class="tabs-swipeable-wrap">';
	echo '<div class="tabs">';
	
		echo '<div class="tab active" id="tab1'.$id.'">';
			echo '<div class="content-block">';
			if(!empty($aStatusUpdates)){echo View::renderStatusUpdates($aStatusUpdates,$domain,$limit);} else {echo '<p>nothing to see here folks</p>';}
			echo '</div>';
		echo '</div>';

		echo '<div class="tab" id="tab2'.$id.'">';
		echo View::renderFollowersList($aFollowersIDsList,$domain,$limit);
		echo '</div>';
		
		echo '<div class="tab" id="tab3'.$id.'">';
		echo View::renderFollowingListBasic($aFollowingIDsList,$domain,$limit);
		echo '</div>';
		
	echo '</div> ';
	echo '</div> ';


?>

        </div>
      </div>
    </div>
  </div>
</div>
