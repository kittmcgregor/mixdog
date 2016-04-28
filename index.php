<?php include_once'includes/header.php'; 
?>
	
	
	
	
	<div id="banner"></div>

		<div id="content" class="wrapper clearfix">

				<div id="intro">Find the freshest brews on tap</div>

			
			<div id="searchForm">
				<form id="homeSearchForm" action="search.php" method="post" enctype="multipart/form-data">
					<div class="form-group clearfix">
						<div class="col-md-8"><input type="text" class="form-control" id="searchKeywords" name="keyword" placeholder="search keywords..." value=""/></div>
						<div class="col-md-4"><input type="submit" id="submit" value="release the hounds" class="btn"></div>
					</div>	
				</form>
			</div> <!-- end search form -->
			
			
		</div> <!-- end content container -->
			
			<div id="searchResults">

					<div class="wrapper clearfix">
						<div class="col-md-8">
						
						<p>New Zealand's craft beer tap guide <a class="btn btn-link" href="about.php">read more...</a></p>
<!-- 						<div class="solidline"></div> -->

						<?php	$totalTaps = Availability::totalTaps();
								$totalBrews = Beer::allBeers();
								$totalLoc = Location::all();
								$totalLocExcl = Beer::exclusive();
								$aFreshHop = Beer::FreshHop();
							echo '<p class="quicklinks"><a class="btn btn-default" href="brews">'.$totalTaps.' taps </a> <a class="btn btn-default" href="locations">'.count($totalLoc).' Locations</a> <a class="btn btn-default" href="breweries">'.count(Brewery::all()).' Breweries </a> <a class="btn btn-default" href="exclusive">'.count($totalLocExcl).' tap exclusives </a> <a class="btn btn-default" href="freshhop"> '.count($aFreshHop).' Fresh Hop</a> </p>'; 
						

						?>
						<p class="quicklinks">
<a class="btn btn-success" href="addstatusupdate">Check In <i class="fa fa-check-square" aria-hidden="true"></i></a> <a class="btn btn-info" href="addstatusupdate">Rate <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></a> <a class="btn btn-warning" href="addstatusupdate">Review <i class="fa fa-pencil" aria-hidden="true"></i></a> <a type="button" class="btn btn-primary" href="addstatusupdate">Upload Photo <i class="fa fa-picture-o" aria-hidden="true"></i></a>
</p>
						<?php
							
							// LOCATION ACTIVITY
					$aAvIDs = Availability::all();
					

					echo "<h4>Recently Tapped</h4>";
					
					$limit = 5;
					echo View::renderActivity($aAvIDs,$limit);
							
							
									
							echo '<p class="quicklinks"><a class="btn btn-default" href="taps.php"> more fresh taps</a></p>';
							
							//pagination();
							//echo View::renderAllBeers($oAllBeers,$loggedin);
							
/*
								echo "<pre>";
								print_r($oAllBeers);
								echo "</pre>";
*/
							
/*
							$oAllBeers = new AllBeers();
							$oAllBeers->load();
							$loggedin = 0;
							$userID = 0;
							if(isset($_SESSION["UserID"])){
								$userID = $_SESSION["UserID"];
								$loggedin = 1;
							}
							
							echo '<h4>Newly Listed</h4>';
							$iBeersPerPage = 5;
							$currentpage = "home";
							echo View::renderAllBeers($oAllBeers,$loggedin,$userID,$iBeersPerPage,$currentpage);
*/
							

	
						?>
						
						<h4>Fresh Hop Brews</h4>
							<ul id="listings">
								<?php
								
								if(count($aFreshHop)<6){
									$xpp = count($aFreshHop);
								} else {
									$xpp = 5;
								}
								$paginate = 0;
								echo View::renderFreshHopBeers($aFreshHop,$xpp,$paginate,$domain);
								?>
							</ul>
							<?php echo '<p><a class="btn btn-default" href="freshhop">View all Fresh Hop Brews </a></p>'; ?>
						

						<div class="spacer clearfix"></div>
						<h4>Exclusively on Tap</h4>
							<ul id="listings">
								<?php
								$aExclusive = Beer::exclusive();
								$xpp = 5;
								$paginate = 0;
								echo View::renderExclusiveBeers($aExclusive,$xpp,$paginate,$domain);
								?>
							</ul>
							<?php echo '<p><a class="btn btn-default" href="exclusive.php">View all exclusive taps </a></p>'; ?>
							
						
						</div>
						<div class="col-md-4">
							<div class="aside">
								<h4>News</h4>
								
								
										
								<?php $aNews = News::all();
								 echo View::rendernewsfeed($aNews);?>
								
								
<!--
								<h5><a href="http://brewhound.nz/viewlocation.php?locationID=82">
									<i class="fa fa-external-link"></i>Behemoth Tap Takeover at Pomeroy's ChCh Friday 26th Feb</a></h5>
								
									<h5><a href="http://brewhound.nz/viewlocation.php?locationID=67">
									<i class="fa fa-external-link"></i>Mighty Mainland takeover at Brothers AK Friday 26th Feb</a></h5>
								
									<h5><a href="http://blanc.kiwi/brewfest" target="_blank">
									<i class="fa fa-external-link"></i> 
									The Great Brewfest Out West is back Saturday February 20th 2016!</a></h5>
									
									<h5><a href="http://www.luke.co.nz/2016/02/brooklyn-thunder-two-new-epic-beers/" target="_blank">
									<i class="fa fa-external-link"></i> 
									Brooklyn & Thunder – Two New EPIC Beers</a></h5>
-->
							</div>
							
							<div class="aside">
								<h4>Social Media</h4>
								<h5><a href="https://www.facebook.com/brewhoundnz/" target="_blank"><i class="fa fa-facebook-square"></i> facebook</a> &nbsp;<a href="https://twitter.com/brewhoundnz" target="_blank"><i class="fa fa-twitter-square"></i> twitter</a>
							</div>
							
							<div class="promo">
								<h4>Owner?</h4>
								<p>
								Breweries, Bars & Shops can claim ownership and manage their brews exclusivly.</p>
								<a href="about.php" class="btn btn-default claimleft">claim</a>
								<a href="about.php"><img class="imgspace" src="<?php echo $imgpath; ?>promoadd.png"/></a>
								<a href="about.php"><img class="imgspace" src="<?php echo $imgpath; ?>promoremove.png"/></a>
							</div>
							
							<div class="mostliked">
								<h4>Activity</h4>
								<?php
									$aStatusUpdates = Status::latest();
									echo view::renderStatusUpdates($aStatusUpdates,$domain);
								?>
							
							</div>
								
							<div class="mostliked">
								<h4>Most Likes</h4>
								<?php
								$show = 5;
								$oAllBeers = new AllBeers();
								$oAllBeers->loadMostLikes();
								echo View::rendermostLiked($oAllBeers,$show,$domain);
								?>
							
							<div>
								<?php
								$likestreamlimit = 5;
								$aLikeStream = Likes::getRecentLikeActivity();
								echo View::renderLikeStream($aLikeStream,$likestreamlimit,$domain);
								?>
							</div>


<!-- 						<a href="stats.php" class="">view all</a> -->
							</div>							
							
						</div>
					</div>
			</div>
		
		
<?php
/*
						echo "<pre>";
						print_r($aAvIDs);
						echo "</pre>";
*/
	
	 include_once'includes/footer.php'; ?>