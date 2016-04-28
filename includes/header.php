<?php
	session_start();
	$imgpath = "assets/images/";
	if(isset($_SESSION["UserID"])){
		//logged in
	} else {
	// not logged in
	}
	$domain = 'http://'.$_SERVER['HTTP_HOST'].'/';
	// Report simple running errors
	
/*
	if($_SESSION["previous_previous_page"]){
	$_SESSION["previous_previous_page"] = $_SESSION["previous_page"];
	}
	if($_SESSION["previous_page"]){
		$_SESSION["previous_page"] = $_SERVER['HTTP_REFERER'];
	}
*/

/*
	unset($_SESSION["last_page"]);
	unset($_SESSION["current_page"]);
*/
	
	require_once 'includes/view.php';
	require_once 'includes/form.php';
	require_once 'includes/beer.php';
	require_once 'includes/allBeers.php';
	require_once 'includes/user.php';
	
	require_once 'includes/statusupdates.php';
	require_once 'includes/like.php';
	require_once 'includes/likemanager.php';

	require_once 'includes/comment.php';
	require_once 'includes/commentmanager.php';
	
	require_once 'includes/follow.php';
	require_once 'includes/FollowManager.php';
	
	include_once'includes/brewery.php';
	include_once'includes/location.php';
	include_once'includes/style.php';
	include_once'includes/news.php';
	include_once'includes/availabilitymanager.php';

?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>brewhound - New Zealand's craft beer tap guide</title>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<?php if(isset($_GET["newsID"])){
			$iNewsID = $_GET["newsID"];
			$oNews = new News();
			$oNews->load($iNewsID);
			$newsurl = 'http://brewhound.nz/viewnews.php?newsID='.$oNews->newsID;
			echo '<meta property="og:site_name" content="Brewhound" />';
			echo '<meta property="og:image" content="'.$oNews->newsimagelink.'" />';
			echo '<meta property="og:type" content="article" />';
			echo '<meta property="og:url" content="'.$newsurl.'" />';
			echo '<meta property="og:title" content="'.$oNews->newstitle.'" />';
			echo '<meta property="og:description" content="'.$oNews->newsexcerpt.'" />';
			//echo '<meta property="og:description" content="Brewhound - An independent Craft Beer Directory based in Auckland, New Zealand. List your bar, brewery or shop for free."/>';
			} elseif(isset($_GET["name"])){
			$oBeer = new Beer();
			$oBeer->loadByName($_GET["name"]);
			echo '<meta property="og:site_name" content="Brewhound" />';
			echo '<meta property="og:image" content="http://brewhound.nz/assets/images/'.$oBeer->photo.'" />';
				if($oBeer->breweryID<2){
					$brewery = $oBeer->brewery;
				} else {
					$breweryID = new Brewery();
					$breweryID->load($oBeer->breweryID);
					$brewery = $breweryID->breweryname;
				}
			echo '<meta property="og:title" content="'.$brewery.' '.$oBeer->title.' - Brewhound" />';
			echo '<meta property="og:url" content="http://brewhound.nz/'.$oBeer->slug.'" />';
			echo '<meta property="og:description" content="'.$oBeer->description.'"/>';
			}	else {
			echo '<meta property="og:image" content="http://brewhound.nz/assets/images/brewhound-og.png" />';
			echo '<meta property="og:image" content="http://brewhound.nz/assets/images/brewhound-og.png" />';
			echo '<meta property="og:image" content="http://brewhound.nz/assets/images/brewhound-og-hound-solo.png" />';
			echo '<meta property="og:title" content="Brewhound" />';
			echo '<meta property="og:type" content="website" />';
			echo '<meta property="og:url" content="http://brewhound.nz" />';
			echo '<meta property="og:description" content="An independent Craft Beer Tap Directory based in Auckland, New Zealand. List your bar, brewery or shop for free."/>';
			}
		?>
		
	<link rel='stylesheet' href="<?php echo $domain ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel='stylesheet' href="<?php echo $domain ?>assets/css/style.css">
	</head>
	
	<body>
	
		<header>
			<nav class="clearfix">
				<div id="branding" style="cursor: pointer;" onclick="window.location='http://brewhound.nz/';"><span class="beta phoneVisible">beta</span> <span class="logotext">brew hound</span> <span class="beta hidePhone">beta</span></div> 
		
				<ul id="mainNav">
<!-- 					<li><a href=""><i class="fa fa-search fa-lg"></i></a></li> -->
					<li><a href="<?php echo $domain ?>search.php"><i class="fa fa-search fa-lg"></i></a></li>
					<li class="dropdown">
				        <a href="#"><i class="fa fa-bars dropdown-toggle"></i>browse</a>
				        <ul class="dropdown-menu">
							<li><a href="<?php echo $domain ?>locations/">locations</a></li>
							<li><a href="<?php echo $domain ?>breweries/">breweries</a></li>
							<li><a href="<?php echo $domain ?>brews/">brews by name</a></li>
				    	</ul>
				    </li>
					<li class="dropdown">
				        <a href="#"><i class="fa fa-bars dropdown-toggle"></i>add listing</a>
				        <ul class="dropdown-menu">
							<li><a href="<?php echo $domain ?>addbeer.php">new brew</a></li>
							<li><a href="<?php echo $domain ?>viewbreweries.php">select from brewery</a></li>
							<li><a href="<?php echo $domain ?>search.php">find/search</a></li>
							<li><a href="<?php echo $domain ?>addstyle.php">new style</a></li>
							<li><a href="<?php echo $domain ?>addlocation.php">new location</a></li>
							<li><a href="<?php echo $domain ?>addbrewery.php">new brewery</a></li>
				    	</ul>
				    </li>

<!-- 					<li><a href="#">how it works</a></li> -->
					<!-- <li><a href="#">plans & pricing</a></li> -->
					<?php 
					if(isset($_SESSION["UserID"])){
						echo '<li><a href="'.$domain.'logout">logout <i class="fa fa-sign-out"></i></a></li>';
					} else {
						echo '<li><a href="'.$domain.'login">login <i class="fa fa-sign-in"></i></a></li>';
					}
					?>
					<li><a href="<?php echo $domain ?>register">sign up <i class="fa fa-pencil-square-o"></i></a></li>
					<li><a href="<?php echo $domain ?>viewuseradmin"><img src="<?php echo $domain ?>assets/images/dogbowl-20.png"/></a></li>
				</ul>
				
				<div id="mobileNavContainer">
				        <a class="btn hamburger" role="button" data-toggle="collapse" href="#mobileToggle" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-bars dropdown-toggle"></i></a>
				        <div class="collapse" id="mobileToggle">
					        
					        <ul class="mobileNav">
						        <li class="navTitle"><i class="fa fa-eye"></i></li>
								<li><a href="<?php echo $domain ?>viewlocations.php">browse locations</a></li>
								<li><a href="<?php echo $domain ?>viewbreweries.php">browse breweries</a></li>
								<li><a href="<?php echo $domain ?>searchmobile.php"><i class="fa fa-search fa-lg"></i></a></li>
								<div class="clear"></div>
							<div class="line"></div>
							<li class="navTitle"><i class="fa fa-plus"></i></li>
								<li><a href="<?php echo $domain ?>addbeer.php">new brew</a></li>
								<li><a href="<?php echo $domain ?>viewbreweries.php">from brewery</a></li>
								<li><a href="<?php echo $domain ?>searchmobile.php">find/search</a></li>
								<li><a href="<?php echo $domain ?>addstyle.php">new style</a></li>
								<li><a href="<?php echo $domain ?>addlocation.php">new location</a></li>
								<li><a href="<?php echo $domain ?>addbrewery.php">new brewery</a></li>
								<div class="clear"></div>
							<div class="line"></div>
							<li class="navTitle"><i class="fa fa-user"></i></li>
								<?php 
								if(isset($_SESSION["UserID"])){
									echo '<li><a href="'.$domain.'logout">logout <i class="fa fa-sign-out"></i></a></li>';
								} else {
									echo '<li><a href="'.$domain.'login">login <i class="fa fa-sign-in"></i></a></li>';
								}
								?>
								<li><a href="<?php echo $domain ?>register">sign up <i class="fa fa-pencil-square-o"></i></a></li>
								<li><a href="<?php echo $domain ?>viewuseradmin"><img src="<?php echo $domain ?>assets/images/dogbowl-20.png"/></a></li>
								<div class="clear"></div>
					    	</ul>
					    </div>
				</div>
			</nav>
		</header>

<?php

//login/out	
	if(isset($_GET["requirelogin"])){
		echo '<div class="wrapper label-success">';
		echo "Login required to add content<br/>";
		echo '</div>';
	}
	if(isset($_GET["loginsuccess"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "Logged in successfully<br/>";
		echo '</div>';
	}
	if(isset($_GET["logoutsuccess"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "Logged out successfully<br/>";
		echo '</div>';
	}

// beer
	if(isset($_GET["beerAddedSuccess"])){
		echo '<div class="wrapper label-success">';
		echo "Beer added successfully<br/>";
		echo 'View it <a href="viewbeer.php?beerID='.$_GET["newBeerID"].'">here</a> or add another beer below';
		echo '</div>';
	}		
	if(isset($_GET["beerUpdatedSuccess"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "Beer updated successfully<br/>";
		echo '</div>';
	}
// style
	 if(isset($_GET["stylesuccess"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "Style added successfully<br/>";
		echo '</div>';
		}
// news
	 if(isset($_GET["newssuccess"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "News added successfully<br/>";
		echo '</div>';
		}
// comments
	if(isset($_GET["newcomment"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "New comment added successfully";
		echo '</div>';
	}
	
	if(isset($_GET["commentremoved"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "Comment removed successfully";
		echo '</div>';
	}
	
	if(isset($_GET["feedback"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "Feedback sent successfully";
		echo '</div>';
	}
	if(isset($_GET["claim"])){
		echo '<div class="wrapper label-success">';
		echo "<p>Claim sent successfully</p>";
		echo "<p>We'll verify your claim, then grant you access to manage your brewery or location.</p>";
		echo "<p>Please <a class='btn btn-default' href='/register.php'>sign up/register</a> then we'll upgrade/link your account.</p>";
		echo '</div>';
	}
	if(isset($_GET["registersuccess"])){
		$sNewUseremail = $_SESSION["NewUserEmail"];
		echo '<div class="wrapper label-success">';
		echo "User account registered successfully<br/>";
		echo "A confirmation email has been sent to $sNewUseremail (maybe check your spam folder?)<br/>";
		echo 'You can view your account here <a href="viewuseradmin.php?userID='.$_GET["newUserID"].'">here</a>';
		$_GET["newUserID"] = $_SESSION["UserID"];
		echo '</div>';
		}
	if(isset($_GET["removedAvLoc"])){
		echo '<div class="wrapper label-success">';
		echo "<p>Removed listing successfully</p>";
		echo '</div>';
		}
	if(isset($_GET["addAvLoc"])){
		echo '<div class="wrapper label-success">';
		echo "<p>Added listing successfully</p>";
		echo '</div>';
		}
		if(isset($_GET["usersuccess"])){
		echo '<div class="wrapper label-success fadeOut">';
		echo "<p>Updated successfully</p>";
		echo '</div>';
		}
	if(isset($_GET["addResidency"])){
		echo '<div class="wrapper label-success">';
		echo "<p>Added residency successfully</p>";
		echo '</div>';
		}
	if(isset($_GET["removeResidency"])){
		echo '<div class="wrapper label-success">';
		echo "<p>Removed residency successfully</p>";
		echo '</div>';
		}	
/*
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
*/
	?>