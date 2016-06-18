
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
  <div data-page="account" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">
      <div class="content-block">
        <div class="content-block-inner">
	        


<?php
	session_start();
	include_once'includes/user.php';
	include_once'app_includes/view.php';
	$iUserID = $_SESSION["UserID"];

	$oUser = new User();
	$oUser->load($iUserID);
	
	echo View::renderUserAdmin($oUser);

?>

	        

        </div>
      </div>
    </div>
  </div>
</div>