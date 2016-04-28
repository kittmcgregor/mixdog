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
							
							<div class="mostliked">
							<h3>Most Liked</h3>
							
							<?php
							$iBeersPerPage = 5;
							$aBeerLikelist = Beer::beerLikelist();
							
/*
							echo "<pre>";
							print_r($aBeerLikelist);
							echo "</pre>";
*/
							
							echo View::renderAllLiked($aBeerLikelist,$iBeersPerPage);
													
							?>
							
							</div>
							
							

						</div>
						<div class="col-md-4">


							
							<div class="promo">
								<h3>Owner Operator?</h3>
								<p class="claimleft">
								Claim your admin account. It's free! <a href="about.php" class="btn btn-default">claim</a></p>
								<img class="imgspace" src="<?php echo $imgpath; ?>promoadd.png"/>
								<img class="imgspace" src="<?php echo $imgpath; ?>promoremove.png"/>
							</div>
							
						</div>
					</div>
			</div>
		
		
<?php
	
	
	 include_once'includes/footer.php'; ?>