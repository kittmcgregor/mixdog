 <?php

class View{

		static public function renderUser($oUser){
		
			$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '
					<div class="itemTitle"><h2>username: '.$oUser->username.'</h2></div>
					<div class="">First Name: '.$oUser->firstname.'</div>
					<div class=""><a href="#">Last Name: '.$oUser->lastname.'</a></div>';
					// <div class=""><a href="">Email: '.$oUser->email.'</a></div>'
			if($_GET["viewUserID"] == $_SESSION["UserID"]){
				$sHTML .= '<div class=""><a href="logout.php">log out</a></div>';
			}
			
			//$sHTML .= '<div class="">password: '.$oUser->password.'</div>';
					
/*
			$aLikes = $oUser->likes;
			for($iCount=0;$iCount<count($aLikes);$iCount++){
				$oLike = $aLikes[$iCount];
				$sHTML .= '<li class="">'.$oLike->beerID.'</li>';
				}
*/
			$sHTML .= '</ul>';
			$sHTML .= '</div>';
			return $sHTML;	
		}
		
				static public function renderUserAdmin($oUser){
		
			$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '
					<div class="itemTitle"><h2>username: '.$oUser->username.'</h2></div>
					<div class="">First Name: '.$oUser->firstname.'</div>
					<div class=""><a href="#">Last Name: '.$oUser->lastname.'</a></div>';
					// <div class=""><a href="">Email: '.$oUser->email.'</a></div>'

			$sHTML .= '<div class=""><a href="logout.php" class="btn btn-default">log out</a></div>';

			
			//$sHTML .= '<div class="">password: '.$oUser->password.'</div>';
					
/*
			$aLikes = $oUser->likes;
			for($iCount=0;$iCount<count($aLikes);$iCount++){
				$oLike = $aLikes[$iCount];
				$sHTML .= '<li class="">'.$oLike->beerID.'</li>';
				}
*/
			$sHTML .= '</ul>';
			$sHTML .= '</div>';
			return $sHTML;	
		}
		
		
		static public function renderBeer($oBeer,$likeStatus,$userID,$aBeerlocations){
			
/*
					echo "<pre>";
					print_r(Style::stylelist());
					print_r($oBeer);
					echo "</pre>";
*/

				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
			
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
			$sHTML = '<div class="wrapper clearfix">';
				$sHTML .= '
						<div class="image"><a href="#"><img src="'.$photo.'"/></a></div>
						<div class="itemBrewery"><h3>'.$oBeer->brewery.'</h3></div>
						<div class=""><h2>'.$oBeer->title.'</h2></div>
						<div class="itemDescription"><p>Description:<br/>'.$oBeer->description.'</p></div>';
				
				if($oBeer->styleID!=1){
					$sHTML .= '<div class=""><a href="">Style: '.$oStyle->stylename.'</a></div>';	
				}
				$sHTML .= '<div class="">ABV '.$oBeer->alcohol.'%</div>';	
/*
				if(empty($aBeerlocations)){
				$sHTML .= '<div class="">Locations: '.$oBeer->locations.'</div>';
				}
*/

				$sHTML .= '<p>';
				if(isset($_SESSION["UserID"])){
					// Like Status
					if($likeStatus->userID==$userID){
					$sHTML .= '<div class="liked">
								<a class="btn btn-default" href="likeupdate.php?removelike=true&beerID='.$oBeer->beerID.'&likeID='.$likeStatus->likeID.'">
								Unlike</a> <i class="fa fa-heart"> ('.count($oBeer->likes).')</i>';
					} else {
					$sHTML .= '<div class="">
								<a class="btn btn-default" href="likeupdate.php?addlike=true&beerID='.$oBeer->beerID.'&userID='.$userID.'">
								Like</a> <i class="fa fa-heart"> ('.count($oBeer->likes).')</i>';
					}
				} else {
						$sHTML .= '<i class="fa fa-heart"> ('.count($oBeer->likes).')</i>';
				}
				$sHTML .= '</p>';
				
				$sHTML .= '</div>';	
			$sHTML .= '</div>';
			return $sHTML;
		}
		
		static public function renderLike($oLike){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<div class="itemTitle"><h2>'.$oLike->likeID.'</h2></div>';
		$sHTML .= '<div class="itemTitle"><h2>'.$oLike->beerID.'</h2></div>';
		$sHTML .= '<div class="itemTitle"><h2>'.$oLike->userID.'</h2></div>';
		$sHTML .= '</div>';
		return $sHTML;
		}
		
		static public function renderSearchResults($aBeers,$loggedin){
			
			$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<ul id="listings">';
			for($iCount=0;$iCount<count($aBeers);$iCount++){
				$oBeer = $aBeers[$iCount];
				// Load stylenames
				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
				// check login
				if($loggedin==1) {
					$edit = '<a href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
				} else {
					$edit = "";
				}
				// Photo Path
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				$sHTML .= '<li>
								<div class="row">
										<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
										<div class="col itemTitleCol">
											<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>
										</div>';
				if($oBeer->styleID!=1){
				$sHTML .= 				'<div class=""><a href="">'.$oStyle->stylename.'</a></div>';	
				}				
				$sHTML .= 				'<div class="col itemLikes"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>
										<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>
										<div class="col item-edit">'.$edit.'</div>
								</div>
							</li>';
			}
			$sHTML .= '</ul>';
			$sHTML .= '</div>';
			return $sHTML;
		}
		
		static public function renderAllBeers($oAllBeers,$loggedin){



			$aBeers = $oAllBeers->allBeers;
			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<ul id="listings">';
			
			$iPage = 1;
			
			if(isset($_GET["page"])){
				$iPage	= $_GET["page"];
			}
			
			$iBeersPerPage = 10;
			$iTotalPages = ceil(count($aBeers) / $iBeersPerPage);
			$iStartIndex = ($iPage - 1) * $iBeersPerPage ;
			
			$iEndIndex = $iStartIndex + $iBeersPerPage;
			if($iEndIndex > count($aBeers)){
				$iEndIndex = count($aBeers);
			}
			
			for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
				$oBeer = $aBeers[$iCount];
				
				// Get Style Names
				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);

				// check login
				if($loggedin==1) {
					$edit = '<a href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
				} else {
					$edit = "";
				}
				// Photo Path
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				$sHTML .= '<li>
								<div class="row">
										<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
										<div class="col itemTitleCol">
											<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>
										</div>';
										
				if($oBeer->styleID!=1){
					$sHTML .= '<div class="desktopVisible"><a href="">'.$oStyle->stylename.'</a></div>';	
				}
										
				$sHTML .= 				'<div class="col itemLikes"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>
										<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>
										<div class="col item-edit">'.$edit.'</div>
								</div>
							</li>';
			}
			
			$sHTML .= '</ul>';
			
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
			$iNext =  $iPage + 1;
			$iPrev = $iPage - 1;
			$sHTML .= '<p>';
			$sHTML .= '<a href="'.'index.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';
			$sHTML .= ' <a href="'.'index.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			$sHTML .= '</p>';
			
			//$sHTML .= '</div>';
			return $sHTML;
		}
	
	
	static public function rendermostLiked($oAllBeers){
			
			$limit = 10;
			$aBeers = $oAllBeers->allBeers;
			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<ul id="listings">';
			for($iCount=0;$iCount<$limit;$iCount++){
				$oBeer = $aBeers[$iCount];
				// Photo Path
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				$sHTML .= '<li>
								<div class="row">
										<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
										<div class="col itemTitleColSmall">
											<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>
										</div>
										<div class="col"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>
								</div>
							</li>';
			}
			$sHTML .= '</ul>';
			//$sHTML .= '</div>';
			return $sHTML;
		}

	
	
	static public function renderAvailability($aBeerlocations){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Available from:</h3>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aBeerlocations);$iCount++){
			$oLocation = $aBeerlocations[$iCount];
			$sHTML .= '<li>
				<div class="row">
					<div class="col itemTitle desktopVisible"><a href="viewlocation.php?locationID='.$oLocation->locationID.'"><b>'.$oLocation->locationname.'</b></div>
					<div class="phoneFullWidth phoneVisible"><a href="viewlocation.php?locationID='.$oLocation->locationID.'"><b>'.$oLocation->locationname.'</b></div>
					<div class="col item-brewery desktopVisible">'.$oLocation->locationsuburb.', '.$oLocation->locationregion.'</a></div>
					
				</div>
				</li>';
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
	}
	
	static public function renderLocation($oLocation){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>'.$oLocation->locationname.'</h3>';
		$sHTML .= '<div class="">Address: '.$oLocation->locationaddress.'</div>';
		$sHTML .= '<div class="">Suburb: '.$oLocation->locationsuburb.'</div>';
		$sHTML .= '<div class="">Region: '.$oLocation->locationregion.'</div>';
		$sHTML .= '<div class="">Contact: '.$oLocation->locationcontact.'</div>';
		$sHTML .= '</div>';
		return $sHTML;
		}

static public function renderAllLocations($aLocations){
	
		$iPage = 1;
		
		if(isset($_GET["page"])){
			$iPage	= $_GET["page"];
		}
		
		$iLocationsPerPage = 10;
		$iTotalPages = ceil(count($aLocations) / $iLocationsPerPage);
		$iStartIndex = ($iPage - 1) * $iLocationsPerPage ;
		
		$iEndIndex = $iStartIndex + $iLocationsPerPage;
		if($iEndIndex > count($aLocations)){
			$iEndIndex = count($aLocations);
		}
	
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<ul id="listings" class="locations">';
		for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
			$location = $aLocations[$iCount];
			$oLocation = new Location();
			$oLocation->load($location->locationID);
			$sHTML .= '<a href="viewlocation.php?locationID='.$oLocation->locationID.'"><li><div class="row">';
// 				$sHTML .= '<div class="col item-logo"></div>';
				$sHTML .= '<div class="col itemTitle"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="col responsive">'.$oLocation->locationsuburb.'</div>';
				$sHTML .= '<div class="col responsive">'.$oLocation->locationregion.'</div>';
			$sHTML .= '</li></a>';	
		}
		
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
			$iNext =  $iPage + 1;
			$iPrev = $iPage - 1;
			$sHTML .= '<p>';
			$sHTML .= '<a href="'.'viewalllocations.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';
			$sHTML .= ' <a href="'.'viewalllocations.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			$sHTML .= '</p>';
			
		$sHTML .= '</ul></div></div>';
		return $sHTML;
	}

static public function renderAllUsers($aAllUsers){
	
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<ul id="listings" class="locations">';
		for($iCount=0;$iCount<count($aAllUsers);$iCount++){
			$user = $aAllUsers[$iCount];
			$oUser = new User();
			$oUser->load($user->userID);
			$sHTML .= '<a href="viewuser.php?viewUserID='.$oUser->userID.'"><li><div class="row">';
// 				$sHTML .= '<div class="col item-logo"></div>';
				$sHTML .= '<div class="col itemTitle"><h3>'.$oUser->username.'</h3></div>';
				$sHTML .= '<div class="col responsive">'.$oUser->firstname.'</div>';
				$sHTML .= '<div class="col responsive">'.$oUser->lastname.'</div>';
				$sHTML .= '<div class="col responsive">Beers Liked ('.count($oUser->likes).')</div>';
			$sHTML .= '</li></a>';	
		}
		$sHTML .= '</ul></div></div>';
		return $sHTML;
	}

static public function renderBeersLiked($aLikes){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Beers Liked:</h3>';
		$sHTML .= '<ul id="listings">';
		
		
		for($iCount=0;$iCount<count($aLikes);$iCount++){
			$oLike = $aLikes[$iCount];
			$oBeer = new Beer();
			$oBeer->load($oLike->beerID);

			// Get Style Names
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			
			// Photo Path
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
			$sHTML .= '<li>
				<div class="row">
					<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
					<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><b>'.$oBeer->title.'</b></div>';
					
			if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
			$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
			$sHTML .= '</div>';
			$sHTML .= '</li>';
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
}

static public function renderBeersAvailable($aBeersAvailable){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Available Brews:</h3>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aBeersAvailable);$iCount++){
			$oBeer = $aBeersAvailable[$iCount];
			// Get Style Names
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
		$sHTML .= '<li>
				<div class="row">
					<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
					<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
				$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
	}


	static public function renderLikers($aLikers){
		$sHTML = '<div class="wrapper clearfix">';
		if(count($aLikers)>0){
			$sHTML .= '<h5>Liked by:</h5>';
		}
		for($iCount=0;$iCount<count($aLikers);$iCount++){
			$oLiker = $aLikers[$iCount];
			$sHTML .= '<a href="viewuser.php?viewUserID='.$oLiker->userID.'"><b>'.$oLiker->username.'</b></a>';
				if($iCount<(count($aLikers) -1)){
					$sHTML .= ', ';
				}
			}
		$sHTML .= '</div>';
		return $sHTML;
	}
	
	static public function renderComments($aComments){
	
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h5>Comments:</h5>';
		$sHTML .= '<hr>';
		for($iCount=0;$iCount<count($aComments);$iCount++){
			$oComment = $aComments[$iCount];
			$oUser = new User();
			$oUser->load($oComment->userID);
			$sHTML .= '<p>'.$oComment->comment.'</p>';
			// display meta info
			$sHTML .= '<div class="meta">';
			$sHTML .= '<a href="viewuser.php?viewUserID='.$oComment->userID.'"><b>'.$oUser->username.'</b> <span>'.date('G:i:s d/m/y',strtotime($oComment->date)).'</span></a>';
			//$sHTML .= '<span>'.date('G:i:s d/m/y',strtotime($oComment->date)).'</span>';
			if($_SESSION["UserID"]==$oComment->userID){
				$sHTML .= '<span><a href="commentremove.php?beerID='.$oComment->beerID.'&removeCommentID='.$oComment->commentID.'"> | delete</a></span>';
			}
			$sHTML .= '</div>';
			// close meta
			
			$sHTML .= '<hr>';
			}
		$sHTML .= '</div>';
		return $sHTML;
	}

	static public function renderUserComments($aComments){
	
	
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h5>Comments:</h5>';
		$sHTML .= '<hr>';
		for($iCount=0;$iCount<count($aComments);$iCount++){
			$oComment = $aComments[$iCount];
			
			// Load user data
			$oUser = new User();
			$oUser->load($oComment->userID);
			
			// Load beer data
			$oBeer = new Beer();
			$oBeer->load($oComment->beerID);
			$beerID = $oBeer->beerID;
			// Photo Path
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
			$sHTML .= '<p>'.$oComment->comment.'</p>';
			$sHTML .= '<p class="commentFormat"><a href="viewbeer.php?beerID='.$beerID.'"><img src="'.$photo.'"/> '.$oBeer->title.'</a> | <a href="viewuser.php?viewUserID='.$oComment->userID.'">'.$oUser->username.'</a> <span>'.date('G:i:s d/m/y',strtotime($oComment->date)).'</span></p>';
			
			if($_SESSION["UserID"]==$oComment->userID){
				$sHTML .= '<p><a href="commentremove.php?beerID='.$beerID.'&removerCommentID='.$oComment->commentID.'">delete</a></p>';
			}
			$sHTML .= '<hr>';
			}
		$sHTML .= '</div>';
		return $sHTML;
	}

	static public function renderLoginRegister(){
		$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<div class="col-md-6">';
				$sHTML .= '<a href="login.php" class="btn btn-default">login</a> ';
				$sHTML .= '<a href="register.php" class="btn btn-default">register</a>';
				//$uri = $_SERVER['REQUEST_URI'];
			$sHTML .= '</div>';
		$sHTML .= '</div>';
		return $sHTML;
	}

	static public function renderCommentForm($oForm){
		
		$sFormHTML = $oForm->html;
		
		$sHTML = '<div class="wrapper clearfix">
				    <div class="col-md-6">
				        <div class="box">';
		$sHTML .= "$sFormHTML";
				
	     $sHTML .= '</div></div></div>';
	return $sHTML;
	}
	
	static public function renderLocationTabs(){
		$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<div class="filterTabs">';
			$sHTML .= '<h5>Filter</h5>';
			$sHTML .= '<a href="viewalllocations.php" class="btn btn-default">Reset</a>';
			$sHTML .= '</div>';
			
			$sHTML .= '<div class="filterTabs">';
			$sHTML .= '<h5>Auckland</h5>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Auckland%20CBD" class="btn btn-default">Auckland CBD</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Eden%20Terrace" class="btn btn-default">Eden Terrace</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Henderson" class="btn btn-default">Henderson</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Mt%20Eden" class="btn btn-default">Mt Eden</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Kingsland" class="btn btn-default">Kingsland</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Newmarket" class="btn btn-default">Newmarket</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Westmere" class="btn btn-default">Westmere</a>';
			$sHTML .= '</div>';
			
			$sHTML .= '<div class="filterTabs">';
			$sHTML .= '<h5>Rest of NZ</h5>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Leigh" class="btn btn-default">Leigh</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Mangawhai" class="btn btn-default">Mangawhai</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Waihi%20Beach" class="btn btn-default">Waihi Beach</a>';
			$sHTML .= '<a href="viewlocationsuburb.php?suburb=Wellington%20CBD" class="btn btn-default">Wellington CBD</a>';
			

			$sHTML .= '</div>';
		$sHTML .= '</div>';
	return $sHTML;
	}
	
	
}
?>