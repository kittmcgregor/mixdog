
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div class="center sliding">Brewery Details</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="brewery" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">
      <div class="content-block">
        <div class="content-block-inner">



<?php 
	ob_start(); // for redirection to work
	include_once'includes/brewery.php';
	include_once'includes/beer.php';
	include_once'includes/style.php';
	include_once'app_includes/view.php';
	$iBreweryID = 1;
	
/*
	if(isset($_GET["breweryID"])){
		$iBreweryID = $_GET["breweryID"];
	}
*/
	$domain = "http://brewhound.nz/";
	$slug = $_GET["slug"];
	
	$oBrewery = new Brewery();
	$oBrewery->loadBySlug($slug);
	$iBreweryID = $oBrewery->breweryID;
	
	
/*
	if(isset($_GET["userID"])){
	if($_GET["userID"]==1){
			echo View::renderClaimBrewery($iBreweryID);
		}
	}
*/

	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			echo '<div class="wrapper">';
			echo '<a class="btn btn-default" href="'.$domain.'editbrewery.php?breweryID='.$iBreweryID.'">superadmin edit</a>';
			echo '</div>';
		}
	}


	$oBrewery = new Brewery();
	$oBrewery->load($iBreweryID);
					
	echo View::renderBrewery($oBrewery,$domain);


	// Load Beers related to location


	$aBeers = Beer::loadIDsByBrewery($iBreweryID);
	
	//echo '<div class="wrapper clearfix">';
	
	echo '<p>';
		echo View::renderBeersByBrewery($aBeers,$domain);
	echo '</p>';
			
		echo '<div id="brewerymap" class="app_tallmap"></div>';
		echo '</div>';
	echo '</div>';
	
	
	
	//echo '</div>';
	

?>
        </div>
      </div>
    </div>
  </div>
</div>
