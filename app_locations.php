
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div class="center sliding">locations</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="locations" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">
	    <div class="content-block">
	        <div class="content-block-inner">
				<form>
					<div class="item-content">
						<div class="item-inner">
					        <div class="item-title label">Find</div>
					        <div class="item-input">
					          <input type="text" id="autocomplete-dropdown-all"  placeholder="Location" value=""/>
					          <input id="locationid" type="hidden" name="locationid" value="">
					        </div>
					    </div>
					</div>
				</form>
		    </div>
		</div>
				
		<div class="content-block ">
			<div class="content-block-inner">
			<?php
				require_once 'includes/location.php';
				require_once 'app_includes/view.php';
				$domain = 'http://'.$_SERVER['HTTP_HOST'].'/';
				$aLocations = Location::all();			
				echo View::renderAllLocations($aLocations,$domain); 
			?>
			
			  <!-- Preloader -->
			  <div class="infinite-scroll-preloader">
			    <div class="preloader"></div>
			  </div>
		  </div>
		</div>


        </div>
      </div>
    </div>
  </div>
</div>


					
		
