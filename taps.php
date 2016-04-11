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
						
						<?php	$totalTaps = Availability::totalTaps();
								$totalLoc = Location::all();
								$totalLocExcl = Beer::exclusive();
							echo '<p><a class="btn btn-default" href="viewlocations.php">View '.count($totalLoc).' Locations</a> <a class="btn btn-default" href="exclusive.php">View '.count($totalLocExcl).' tap only brews </a></p>'; ?>
						
						<?php
							

							
							
							
							//pagination();
							//echo View::renderAllBeers($oAllBeers,$loggedin);
							
/*
								echo "<pre>";
								print_r($oAllBeers);
								echo "</pre>";
*/
							
							$oAllBeers = new AllBeers();
							$oAllBeers->load();
							$loggedin = 0;
							$userID = 0;
							if(isset($_SESSION["UserID"])){
								$userID = $_SESSION["UserID"];
								$loggedin = 1;
							}
							
// 							echo '<h4>Fresh Taps</h4>';
/*
							$iBeersPerPage = 20;
							$currentpage = "brews";
							echo View::renderAllBeers($oAllBeers,$loggedin,$userID,$iBeersPerPage,$currentpage);
*/
							
							$aAvIDs = Availability::all();
							$limit = 30;
							echo View::renderActivity($aAvIDs,$limit);
							
							
/*
							// LOCATION ACTIVITY
					$aAvIDs = Availability::all();		
					
					$limit = 5;
					echo View::renderActivity($aAvIDs,$limit);
*/
	
						?>
						</div>
						<div class="col-md-4">
			
							<div class="promo">
								<h4>Owner?</h4>
								<p class="claimleft padtop1em">
								Breweries, Bars & Shops can claim ownership and manage their brews.</p>
								<a href="about.php" class="btn btn-default claimleft">claim</a>
								<a href="about.php"><img class="imgspace" src="<?php echo $imgpath; ?>promoadd.png"/></a>
								<a href="about.php"><img class="imgspace" src="<?php echo $imgpath; ?>promoremove.png"/></a>
							</div>
							
							<div>
							<?php
							$likestreamlimit = 5;
							$aLikeStream = Likes::getRecentLikeActivity();
							echo View::renderLikeStream($aLikeStream,$likestreamlimit);
							?>
							</div>

							<div class="mostliked">
							<h4>Most Liked</h4>
							<?php
							$show = 5;
							$oAllBeers = new AllBeers();
							$oAllBeers->loadMostLikes();
							echo View::rendermostLiked($oAllBeers,$show);
							?>
<!-- 						<a href="stats.php" class="">view all</a> -->
							</div>							
							
						</div>
					</div>
			</div>
		
		
<?php

	
	 include_once'includes/footer.php'; ?>