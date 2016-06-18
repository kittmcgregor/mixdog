
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div class="center sliding">Location Details</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="location" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">


<?php
	session_start();
	include_once'includes/location.php';
	include_once'includes/style.php';
	include_once'includes/FollowManager.php';
	include_once'includes/user.php';
	include_once'app_includes/view.php';
	
/*
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
*/
	
	$slug = $_GET["slug"];
	//$name = $_GET["name"];
	
	$oLocation = new Location();
	$oLocation->loadBySlug($slug);
	$locationID = $oLocation->locationID;
	
	$claimstatus = $oLocation->claimstatus;
	$domain = 'http://brewhound.nz/';
	echo View::renderLocation($oLocation,$domain);




// superadmin edit
	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			echo '<div class="wrapper">';
			echo '<a class="btn btn-default" href="'.$domain.'editlocation.php?locationID='.$locationID.'">superadmin edit</a>';
			echo '</div>';
		}
	}
	

	
	// Load Beers related to location
	$aAvailableIDs = Availability::loadIDs($locationID);
	
	if(isset($_GET['orderbybrewery'])){
		if($_GET['orderbybrewery']=true){
			$aAvailableIDs = Availability::loadIDsBrewery($locationID);
		}
	}
		echo '<div class="content-block">';
        echo '<div class="content-block-inner">';
	
	//$aBeersAvailable = Availability::loadBeers($locationID);
	//echo View::renderBeersAvailable($aBeersAvailable);
	echo View::renderAvailableData($aAvailableIDs,$locationID,$claimstatus,$domain);
	
			echo '</div>';
			echo '</div>';
?>




        </div>
      </div>
    </div>
  </div>
</div>
