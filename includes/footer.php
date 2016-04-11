		<footer>
			<div id="footer-container" class="clearfix">
				<div class="col-md-3">
					<h5>add listing</h5>
					<ul id="footerNav">
							<li><a href="addbeer.php">brew</a></li>
							<li><a href="addstyle.php">style</a></li>
							<li><a href="addlocation.php">location</a></li>
							<li><a href="addbrewery.php">brewery</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<h5>browse</h5>
					<ul id="footerNav">
							<li><a href="viewlocations.php">locations</a></li>
							<li><a href="viewbreweries.php">breweries</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<h5>Info</h5>
					<ul id="footerNav">
						<li><a href="about.php">About</a></li>
						<li><a href="about.php">Feedback</a></li>
						<li><a href="about.php">Claim Brewery, Bar or Shop</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<h5>My Profile</h5>
					<ul id="footerNav">
						<?php 
						if(isset($_SESSION["UserID"])){
							echo '<li><a href="logout.php">logout <i class="fa fa-sign-out"></i></a></li>';
						} else {
							echo '<li><a href="login.php">login <i class="fa fa-sign-in"></i></a></li>';
						}
						?>
						<li><a href="viewuseradmin.php">View Account</a></li>
						<li><a href="register.php">sign up <i class="fa fa-pencil-square-o"></i></a></li>
					</ul>
				</div>
			</div>
		</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/scripts.js"></script>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-72835030-1', 'auto');
  ga('send', 'pageview');

</script>
	</body>
</html>