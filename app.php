<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>brewhound App</title>
    <!-- Path to Framework7 Library CSS-->
    <link rel="stylesheet" href="app_css/framework7.ios.min.css">
    <link rel="stylesheet" href="app_css/framework7.ios.colors.min.css">
    <!-- Path to your custom app styles-->
    <link rel="stylesheet" href="app_css/my-app.css">
    
    <!-- get main site includes -->
<!--     <link rel='stylesheet' href="../assets/css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- 	<link rel="stylesheet" href="../assets/js/jquerymobile/jquery.mobile-1.4.5.min.css"> -->
	<link rel='stylesheet' href="../assets/css/style.css">
    <?php
	    session_start();
	    require_once 'phpThumb/phpThumb.config.php';
    	$domain = 'http://'.$_SERVER['HTTP_HOST'].'/';
    ?>
  </head>
  <body>
	  
	  <div class="login-screen">
      <!-- Default view-page layout -->
      <div class="view">
        <div class="page">
          <!-- page-content has additional login-screen content -->
          <div class="page-content login-screen-content">
            <div class="login-screen-title">Login</div>
            <!-- Login form -->
            <form id="login" action="app_loginrequest.php" method="GET" class="ajax-submit">
              <div class="list-block">
                <ul>
                  <li class="item-content">
                    <div class="item-inner">
                      <div class="item-title label">email</div>
                      <div class="item-input">
                        <input type="text" name="username" placeholder="email">
                      </div>
                    </div>
                  </li>
                  <li class="item-content">
                    <div class="item-inner">
                      <div class="item-title label">password</div>
                      <div class="item-input">
                        <input type="password" name="password" placeholder="password">
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="list-block">
                <ul>
                  <li>
                  <input type="submit" name="submit" class="button button-big button-fill color-green" value="submit"/>
                    <a href="#" id="login" class="item-link list-button">Sign In</a>
                  </li>
                </ul>
                <div class="list-block-label">Tip; you can login using username instead of email</div>
              </div>
              <div class="list-block">
                <ul>
                  <li>
                  <a href="#" class="item-link list-button close-login-screen">cancel</a>
                  </li>
                </ul>
               </div>
            </form>
          </div>
        </div>
      </div>
  </div>
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar-overlay"></div>
    <!-- Panels overlay-->
    <div class="panel-overlay"></div>
    <!-- Left panel with reveal effect-->
    <div class="panel panel-left panel-reveal">
      <div class="content-block">
        
      </div>
      <div class="list-block">
	      <ul>
		     <li>
		     	<a class="item-link close-panel" href="app.php">
				    <div class="item-content">
					    <div class="item-media">
						    <i class="icon icon-form-settings"></i>
						</div>
						<div class="item-inner">
						 	<div class="item-title">home</div>
					  	</div>
					</div>
				</a>
		     </li>
		     <li>
		     	<a class="item-link close-panel" href="app_infinite.php">
				    <div class="item-content">
					    <div class="item-media">
						    <i class="icon icon-form-settings"></i>
						</div>
							<div class="item-title">infinite</div>
					</div>
				</a>
		     </li>
		     <li>
		     <li>
		     	<a class="item-link close-panel open-preloader" href="app_locations.php">
				    <div class="item-content">
					    <div class="item-media">
						    <i class="icon icon-form-settings"></i>
						</div>
						<div class="item-inner">
						 	<div class="item-title">locations</div>
					  	</div>
					</div>
				</a>
		     </li>
		     <li>
		     	<a class="item-link close-panel" href="app.php">
				    <div class="item-content">
					    <div class="item-media">
						    <i class="icon icon-form-settings"></i>
						</div>
						<div class="item-inner">
						 	<div class="item-title">breweries</div>
					  	</div>
					</div>
				</a>
		     </li>
	      </ul>
      </div>  <!-- end list block -->
      
      <div class="content-block">
        	<div class="item-inner">
					<div class="item-title">map</div>
			</div>
      </div>
      					
    </div>
    <!-- Right panel with cover effect-->
    <div class="panel panel-right panel-cover">
      <div class="content-block">
        <p>Right panel content goes here</p>
      </div>
    </div>
    <!-- Views-->
    <div class="views">
      <!-- Your main view, should have "view-main" class-->
      <div class="view view-main">
        <!-- Top Navbar-->
        <div class="navbar">
          <div class="navbar-inner">
            <!-- We have home navbar without left link-->
            <div class="center sliding">brewhound</div>
            <div class="right">
              <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
            </div>
          </div>
        </div>
        <!-- Pages, because we need fixed-through navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-through toolbar-through">
          <!-- Page, data-page contains page name-->
          <div data-page="index" class="page">
            <!-- Scrollable page content-->
            <div class="page-content">
<!--               <div class="content-block-title">Find the freshest brews on tap</div> -->
              <div class="content-block">
                <div class="content-block-inner">
                  <p>New Zealand's craft beer tap guide</p>
                </div>
              </div>
<!--
              <a class="btn btn-default" href="app_map.php">Map</a>
              <a class="btn btn-default" href="app_recent.php">Recent</a>
-->
	            <div class="content-block">
			      <p><a href="app_checkin.php" class="button open-preloader">Checkin</a></p>
<!-- 			      <p><a href="#" class="button open-login-screen">Login</a></p> -->
			      
			      <p><a href="#" id="account" class="button">My Account</a></p>
			      
			    </div>
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
			
			<div id="map" class="smallmap"></div>
			
             	</div>
             </div>
            
              <div class="list-block">
                <ul>
                  <li><a href="form.html" class="item-link">
                      <div class="item-content"> 
                        <div class="item-inner">
                          <div class="item-title">Form</div>
                        </div>
                      </div></a></li>
                </ul>
              </div>
              <div class="content-block-title">Side panels</div>
              <div class="content-block">
                <div class="row">
                  <div class="col-50"><a href="#" data-panel="left" class="button open-panel">Left Panel</a></div>
                  <div class="col-50"><a href="#" data-panel="right" class="button open-panel">Right Panel</a></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Bottom Toolbar-->
        <div class="toolbar">
          <div class="toolbar-inner"><a href="#" class="link">Link 1</a><a href="#" class="link">Link 2</a></div>
        </div>
      </div>
    </div>
    
    <?php
	$url = 'http://brewhound.nz/listLocationMarkers.php';
	$lat_long = file_get_contents($url);
?>
<div id="markers"><?php 
		echo $lat_long;
		//echo json_encode($lat_long);?>
</div>
    
    <!-- Path to Framework7 Library JS-->
    <script type="text/javascript" src="app_js/framework7.min.js"></script>
	
    <!-- Path to your app js-->
    <script type="text/javascript" src="app_js/my-app.js"></script>
    
	<!-- get main site includes -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<!-- 	<script src="../assets/js/jquerymobile/jquery.mobile-1.4.5.min.js"></script> -->
    <script src="../assets/js/bootstrap.js"></script>
	<script src="../assets/js/scripts.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEwC6wpxW99D8hw95lEoBjCV1a_qVvcrs&callback=initFrontPageMap" async defer></script>
    
  </body>
</html>