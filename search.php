<?php include_once'includes/header.php';
	$sKeyword="";
	$sRawKeyword="";
		$userID = 0;
		if (isset($_SESSION["UserID"])==false){
			$loggedin = 0;
		} else {
			$loggedin = 1;
			$userID=$_SESSION["UserID"];
		}

if(isset($_POST["keyword"])){
	$sRawKeyword = $_POST["keyword"];
	$sKeyword = mysql_escape_string($sRawKeyword);
}


/*
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
*/

						//$aBeers = Beer::search($_POST["keyword"]);
						$aBeers = Beer::search($sKeyword);
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
						<?php if($sRawKeyword==""){
							echo '<h3>All active brews 1-9, A-Z:</h3>';
						} else {
							echo '<h3>Search for: "'.$sRawKeyword.'"</h3>';
						}
						$totalsearchresults = count($aBeers);
						echo '<h4>'.$totalsearchresults.' results</h4>';
						?>
						
						
					</div>
					
					<div class="col-md-4 sort-col">
							<div class="sort">
<!--
								<label>Sort by:</label>
								<select form="bookingForm" id="serviceSelect" class"formValue">
								  <option value="category">Date</option>
								  <option value="category">Alphabetical</option>
								  <option value="category">Popular</option>
								  <option value="category">Rating</option>
								</select>
-->
							</div>
						</div>
					</div>



					<?php
						

						
						
/*
							echo "<pre>";
							print_r(Beer::search($_POST["keyword"]));
							echo "</pre>";
*/
							
							echo View::renderSearchResults($aBeers,$loggedin,$userID,$domain);
							
					?>

			</div>
		
		
<?php
	
	
	 include_once'includes/footer.php'; ?>