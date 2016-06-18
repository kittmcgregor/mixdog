
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div class="center sliding">Map</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="map" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">
      <div class="content-block">
        <div class="content-block-inner">
          <p>Map</p>

  				<div id="mapfilters" class="marginbottom">
					<a href"#" class="btn btn-default" id="auckland">AKL</a> <a href"#" class="btn btn-default" id="wellington">WGTN</a> <a href"#" class="btn btn-default" id="chch">CHC</a> 							<a href"#" class="btn btn-default" id="reset">ALL</a>
				</div>

				<div id="map2" class="fullheightmap"></div>

				<script>
				
						console.log("reloading map");
				
				</script>
        </div>
      </div>
    </div>
  </div>
</div>