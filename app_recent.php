
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div class="center sliding">Latest</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="recent" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">
      <div class="content-block">
        <div class="content-block-inner">


<?php
					require_once 'includes/availabilitymanager.php';
					require_once 'app_includes/view.php';
								
					// LOCATION ACTIVITY
					$aAvIDs = Availability::all();
					

					echo "<h4>Recently Tapped</h4>";
					
					$limit = 5;
					echo View::renderActivity($aAvIDs,$limit);
?>



        </div>
      </div>
    </div>
  </div>
</div>


					
		
