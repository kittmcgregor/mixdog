<?php 
	ob_start(); // for redirection to work
	//session_start();
	include_once'includes/header.php';
	
	$newsID = 0;
	
	if(isset($_GET["newsID"])){
		$newsID = $_GET["newsID"];
	}
	
	$oNews = new News();
	$oNews->load($newsID);
?>	


<div class="wrapper clearfix">
	<div class="col-md-8">
		<?php echo View::renderNews($oNews); 
	
	if (isset($_SESSION["UserID"])==1){
		echo '<p><a class="btn btn-default" href="http://brewhound.nz/editnews.php?newsID='.$newsID.'">edit</a></p>';
	} ?>
	</div>
	
	<div class="col-md-4 sidebar">
		<aside>
	<h4>News</h4>
	<?php $aNews = News::all();
	echo View::rendernewsfeed($aNews);?>
			</aside>					 
<!--
		<?php	$totalTaps = Availability::totalTaps();
				$totalLoc = Location::all();
				$totalLocExcl = Beer::exclusive();
			echo '<p class="quicklinks"><a class="btn btn-default" href="brews.php">View '.$totalTaps.' taps </a> <a class="btn btn-default" href="viewlocations.php">View '.count($totalLoc).' Locations</a> <a class="btn btn-default" href="viewbreweries.php">View '.count(Brewery::all()).' Breweries </a> <a class="btn btn-default" href="exclusive.php">View '.count($totalLocExcl).' tap only brews </a></p>'; 
		?>
-->
		
<!-- 		<div class="solidline"></div> -->

		<?php
			// LOCATION ACTIVITY
			$aAvIDs = Availability::all();
		
			$limit = 5;
			echo "<h4>Fresh Taps</h4>";
			echo View::renderActivitySidebar($aAvIDs,$limit);
		?>
		
<!--
		<h4>Exclusively on Tap</h4>
			<ul id="listings">
				<?php
				$aExclusive = Beer::exclusive();
				$xpp = 5;
				$paginate = 0;
				echo View::renderExclusiveBeers($aExclusive,$xpp,$paginate);
				?>
			</ul>
			<p><a class="btn btn-default" href="exclusive.php">View all exclusive taps </a></p>
-->
	</div>
											
</div>



<?php

	include_once'includes/footer.php';

?>