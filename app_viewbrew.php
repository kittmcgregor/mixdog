
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div id="title" class="center sliding">Brew Details</div>
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
      <div class="content-block">
        <div class="content-block-inner">


<?php
	ob_start(); // for redirection to work
	require_once 'includes/beer.php';
	require_once 'includes/style.php';
	require_once 'app_includes/view.php';
	require_once 'includes/form.php';
	require_once 'includes/likemanager.php';
	require_once 'includes/statusupdates.php';
	
	$uri = $_SERVER['REQUEST_URI'];
	$domain = 'http://'.$_SERVER['HTTP_HOST'].'/';
/*
echo "<pre>";
print_r($_GET);
echo "</pre>";
*/
	
	$beerName = $_GET["slug"];
	//$beerID = $_GET["beerID"];
	
	$oBeer = new Beer();
	$oBeer->loadByName($beerName);
	$beerID = $oBeer->beerid;
	
	
	$userID = 0;
	$likeStatus = "";
	if(isset($_SESSION["UserID"])){
		$userID = $_SESSION["UserID"];
		// GET LIKE STATUS
	$likeStatus = new Like();
	$likeStatus->loadIfLiked($beerID,$userID);
	}

	$aBeerlocations = Availability::loadLocationIDs($beerID);

	
	//echo '<div class="wrapper clearfix">';
	
	$aExistingLocations = array();
	foreach($oBeer->locations as $location){
		$aExistingLocations[] = $location->locationID;
	}
	
	$aExistingData = array();
	$aExistingData["Locations"] = $aExistingLocations;
	
	echo View::renderBeer($oBeer,$likeStatus,$userID,$aBeerlocations,$aExistingData,$domain);

// Show list of users who like this beer 
	$oLikers = Likes::loadLikers($beerID);
	
	echo View::renderLikers($oLikers);
	
	//show claim
/*
	echo '<div class="claimbox">';
		echo '<div class="claimleft">Are you a stockist of this brew?<br/>Claim your free management account now.</div>';
		echo '<div class="claimleft"><a class="button" href="#">Claim</a></div>';
	echo '</div>';

	echo '</div>';
*/
	
		echo '<div id="brewmap" class="smallmap"></div>';
	//echo '</div>';
	


	//echo '<div class="wrapper clearfix">';
		echo '<div class="col-md-6">';
		
	// VIEW LOCATIONS
	if(!empty($aBeerlocations)){
		echo View::renderAvailability($aBeerlocations,$domain);
	}
		
		//echo '<p>Please note: Freshness is a good indication of availability, although popular kegs often only last a matter of days. If a location is unclaimed then listings are crowd sourced and may not be current. If you\'d like to contribute then please <a href="/register.php" class="btn btn-default nomargin">sign up</a> and do so - cheers! </p>';

		echo "</div>";
	
	echo '<div class="col-md-6">';
			
	// VIEW CHECKINS
	
	echo '<h4>Checkins / Reviews / Ratings / Photos</h4>';
	
	echo '<p class="quicklinks">
<a class="btn btn-success" href="addstatusupdate?brew='.$beerID.'&name='.$beerName.'">Check In <i class="fa fa-check-square" aria-hidden="true"></i></a> <a class="btn btn-info" href="addstatusupdate">Rate <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></a> <a class="btn btn-warning" href="addstatusupdate">Review <i class="fa fa-pencil" aria-hidden="true"></i></a> <a type="button" class="btn btn-primary" href="addstatusupdate">Photo <i class="fa fa-picture-o" aria-hidden="true"></i></a>
</p>';
	
	
	$checkins = Status::loadbybeer($beerID);
	echo view::renderCheckins($checkins,$beerID,$domain);
	
		echo '</div>';
	
	//echo '</div>';

?>

        </div>
      </div>
    </div>
  </div>
</div>

