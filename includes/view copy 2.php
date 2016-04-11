 <?php


function time2str($ts) {
    if(!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }
    $diff = time() - $ts;
    if($diff == 0) {
        return 'now';
    } elseif($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) { return 'Yesterday'; }
        if($day_diff < 7) { return $day_diff . ' days ago'; }
        if($day_diff < 31) { return ceil($day_diff / 7) . ' weeks ago'; }
        if($day_diff < 60) { return 'last month'; }
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 120) { return 'in a minute'; }
            if($diff < 3600) { return 'in ' . floor($diff / 60) . ' minutes'; }
            if($diff < 7200) { return 'in an hour'; }
            if($diff < 86400) { return 'in ' . floor($diff / 3600) . ' hours'; }
        }
        if($day_diff == 1) { return 'Tomorrow'; }
        if($day_diff < 4) { return date('l', $ts); }
        if($day_diff < 7 + (7 - date('w'))) { return 'next week'; }
        if(ceil($day_diff / 7) < 4) { return 'in ' . ceil($day_diff / 7) . ' weeks'; }
        if(date('n', $ts) == date('n') + 1) { return 'next month'; }
        return date('F Y', $ts);
    }
}

class View{

	static public function renderLikeStream($aLikeStream,$getlimit){
		
			$sHTML = '<div>';
			$sHTML .= "<h4>Recently Liked</h4>";
			
			if(count($aLikeStream)<5){
				$limit = count($aLikeStream);
			} else {
				$limit = $getlimit;
			}
			
		for($iCount=0;$iCount<$limit;$iCount++){
			
			$oLike = new Like();
			$oLike->load($aLikeStream[$iCount]);
			$timedate = time2str($oLike->date);
			
			$oUser = new User();
			$oUser->load($oLike->userID);
						
			$oBeer = new Beer();
			$oBeer->load($oLike->beerID);

			$oBrewery = new Brewery();
			$oBrewery->load($oBeer->breweryID);
			
			$beerphoto = "";
			$breweryphoto = "assets/images/hound.png";

				if(isset($oBrewery->breweryphoto)){
					$breweryphoto = $oBrewery->breweryphoto;
				}
				
				if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID!=0){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}

			$sHTML .= '<p class="commentFormat">';
			
				//Show Beer Logo
				if($oBeer->photo!=""){
					$sHTML .= '<a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/> </a>';
				} else {
					$sHTML .= '<a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img class="breweryphoto" src="'.$breweryphoto.'"/> </a>';
				}
			
			$sHTML .= '<a href="viewuser.php?viewUserID='.$oUser->userID.'">'.$oUser->username.'</a>
			 liked <a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a>
			  <a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a><br/>'.$timedate.'';
			$sHTML .= '</p>';

		}
		$sHTML .= '</div>';
		return $sHTML;
	
	}
	
static public function renderActivity($aAvIDs,$getlimit){		
/*
		echo "<pre>";
		print_r($aAvIDs);
		echo "</pre>";
*/	
		$loggedin = 0;
		//$sHTML = '<div>';
		$sHTML = "<h4>Freshest Taps</h4>";
		
		$sHTML .= '<ul id="listings">';

					
		if(count($aAvIDs)<5){
			$limit = count($aAvIDs);
		} else {
			$limit = $getlimit;
		}
			
		for($iCount=0;$iCount<$limit;$iCount++){

			$oAvail = new Availability();
			$oAvail->load($aAvIDs[$iCount]);
			
			$timedate = time2str($oAvail->date);
			
			$oLocation = new Location();
			$oLocation->load($oAvail->locationID);
			
			$oBeer = new Beer();
			$oBeer->load($oAvail->beerID);

			// Get Style Names
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
				
			$oBrewery = new Brewery();
			$oBrewery->load($oAvail->breweryID);
			
/*
			$beerphoto = "";
			$breweryphoto = "assets/images/hound.png";

				if(isset($oBrewery->breweryphoto)){
					$breweryphoto = $oBrewery->breweryphoto;
				}
				
				if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID!=0){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
*/



					
/*
			for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
				$oBeer = $aBeers[$iCount];
				
				// Get Style Names
				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				
				$aBeersAvailable = Availability::loadBeerIDs($iLocationID);
				
				$iBeerID = $oBeer->beerID;
*/
				
				$beerphoto = "assets/images/hound.png";
				$breweryphoto = "";
				$breweryphoto = $oBrewery->breweryphoto;
				
				if($breweryphoto==""){
					// no brewery logo
				} 
				
				if ($oBeer->photo!=""){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$beerphoto = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID!=0){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
				
				
/*
				// show admin buttons
				if($loggedin==1) {
					$edit = "";
					//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
					if($iLocationID>1){
						if (in_array($iBeerID, $aBeersAvailable)) {
						$addremove = '<a class="btn btn-danger remove" href="removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
						} else {
						$addremove = '<a class="btn btn-success remove" href="addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'&breweryID='.$iBreweryID.'"><i class="fa fa-plus"></i></a>';	
						}
					} else {
					$add = "";
					}
				} else {
					$edit = "";
				}
*/
		$sHTML .= '<li>';
			$sHTML .= '<div class="row">';
						if($beerphoto=="assets/images/hound.png"){
						$sHTML .='<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
						} else {
						$sHTML .='<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
						}

						$sHTML .= '<div class="col itemTitleCol">';
						
						if($oBeer->breweryID<2){
						$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
						$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
						}
						if($oBeer->breweryID>1){
						$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
						$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
						}
						
						$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
						$sHTML .= '</div>';
										
						$sHTML .='<div class="col itemAlcohol">tapped '.$timedate.'</div>';
						$sHTML .= '<div class="col itemAlcohol"><a href="viewlocation.php?locationID='.$oLocation->locationID.'">'.$oLocation->locationname.'</a></div>';
										//<div class="col item-edit">'.$edit.'</div>
/*
				// Show +/-
				if($iLocationID>1){
						$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
				}
*/				
				
				$sHTML .= '</div>';
				$sHTML .= '</li>';
			}
			
			$sHTML .= '</ul>';




/*
			$sHTML .= '<p class="commentFormat">';
			
			
			
			
				//Show Beer Logo
				if($oBeer->photo!=""){
					$sHTML .= '<a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/> </a>';
				} else {
					$sHTML .= '<a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img class="breweryphoto" src="'.$breweryphoto.'"/> </a>';
				}
			
				$sHTML .= '<a href="viewlocation.php?locationID='.$oLocation->locationID.'">'.$oLocation->locationname.'</a> ';
			
			if($oBeer->breweryID=1){
				$sHTML .= 'added '.$oBeer->brewery.' ';
			} else {
				$sHTML .= 'added <a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a> ';
			}

			 
			
			 
			$sHTML .= '<a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a><br/>'.$timedate.'';
			$sHTML .= '</p>';
*/
			
			//$sHTML .= '<div>'.$oAvail->availabilityID.'</div>';

// 		}
		//$sHTML .= '</div>';
		return $sHTML;
	
	}


	static public function renderRecentActivity(){
	
			$oAvailability = new Availability();
		$oAvailability->load();
		
	}

	static public function renderFollowersLikedBeers($alikedbeers){

	
	$sHTML = '<div class="wrapper clearfix">';
	
	for($iCount=0;$iCount<count($alikedbeers);$iCount++){
		$oLikedBeer = new Beer();
		$oLikedBeer->load($alikedbeers[$iCount]);
		$sHTML .= '<p>'.$oLikedBeer->title.'</p>';
	}
	$sHTML .= '</div>';
	return $sHTML;
}


	static public function renderFollowingList($aFollowingIDsList){
		
/*
		echo "<pre>";
		print_r($aFollowingIDsList);
		echo "</pre>";
*/
		
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<div class="col-md-4">';
			
		$sHTML .= "<h4>following list</h4>";
		
		for($iCount=0;$iCount<count($aFollowingIDsList);$iCount++){
			$iFollowing = $aFollowingIDsList[$iCount];
			$oFollowing = new Follow();
			$oFollowing->load($iFollowing);
			
/*
			echo "<pre>";
			print_r($oFollowing);
			echo "</pre>";
*/
			
			if($oFollowing->followbreweryID!=0){
					$oBrewery = new Brewery();
					$oBrewery->load($oFollowing->followbreweryID);
					$sHTML .= '<p><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'" class="btn btn-default" >unfollow</a></p> ';
			}

			
			if($oFollowing->followlocationID!=0){
			$oLocation = new Location();
			$oLocation->load($oFollowing->followlocationID);
			$sHTML .= '<p><a href="viewlocation.php?locationID='.$oLocation->locationID.'">'.$oLocation->locationname.' </a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'" class="btn btn-default">unfollow</a></p> ';
			}
			
			if($oFollowing->followuserID!=0){
			$oUser = new User();
			$oUser->load($oFollowing->followuserID);
			$sHTML .= '<p><a href="viewuser.php?viewUserID='.$oUser->userID.'">'.$oUser->username.' <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'" class="btn btn-default" >unfollow</a></p> ';
			}			
			
		}

		$sHTML .= "</div>";

	echo $sHTML;
	
	}

		static public function renderBeer($oBeer,$likeStatus,$userID,$aBeerlocations){
			
/*
					echo "<pre>";
					print_r($oBeer->photo);
					echo "</pre>";
*/

				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				$beerphoto = "";
				$breweryphoto = "";

				if(isset($oBrewery->breweryphoto)){
					$breweryphoto = $oBrewery->breweryphoto;
				}
				
				if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID!=0){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
				
			//$sHTML = '<div class="wrapper clearfix">';
			
			
				$sHTML = '<div class="col-md-8 brewdetails">';
				$sHTML .= '<div class="col-md-8">';
					$sHTML .= '<div class="">';
					if($oBeer->breweryID>1){
						//$sHTML .= '<div class="breweryphoto"><a href="viewbrewery.php?breweryID='.$oBeer->breweryID.'"><img class="breweryphoto" src="'.$breweryphoto.'"/></a></div>';
						$sHTML .= '<div class="itemBrewery"><h4><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></h4></div>';
					} else {
						$sHTML .= '<div class="itemBrewery"><h4>'.$oBeer->brewery.'</h4></div>';
					}
							
					$sHTML .= 	'<div class=""><h2>'.$oBeer->title.'</h2></div>';
					$sHTML .= '<div class="specs">';
										if($oBeer->styleID!=1){
						$sHTML .= '<span><a href="">Style: '.$oStyle->stylename.'</a> </span>';	
					}
					$sHTML .= '<span class="">ABV: '.$oBeer->alcohol.'%</span>';	
					$sHTML .= '</div>';
					
					$sHTML .= 	'<div class="itemDescription"><p>'.$oBeer->description.'</p></div>';
					$sHTML .= '</div>';
					
											// SHOW EDIT IF LOGGED IN	
					if ($userID==true){
						$sHTML .= '<div class="btn btn-default"><a href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a></div>';
					}
					
					$sHTML .= '</div>';
					
					$sHTML .= '<div class="col-md-4 beerphotocontainer">';
					
					//Show Brewery Logo
					
					//Show Beer Logo
					if($oBeer->photo!=""){
						$sHTML .= '<div class="image beerphoto"><img src="'.$beerphoto.'"/></div>';
					} else {
						$sHTML .= '<div class=""><a href="viewbrewery.php?breweryID='.$oBeer->breweryID.'"><img class="breweryphoto" src="'.$breweryphoto.'"/></a></div>';

					}


					$sHTML .= '</div>';
					
					
				$sHTML .= 	'</div>';
						


	/*
					if(empty($aBeerlocations)){
					$sHTML .= '<div class="">Locations: '.$oBeer->locations.'</div>';
					}
	*/
					$sHTML .= '<div class="col-md-4">';
					$sHTML .= '<p>';
					if(isset($_SESSION["UserID"])){
						// Like Status
						if($likeStatus->userID==$userID){
						$sHTML .= '
									<a class="btn btn-default" href="likeupdate.php?removelike=true&beerID='.$oBeer->beerID.'&likeID='.$likeStatus->likeID.'">
									Unlike</a> <i class="fa fa-heart"> ('.count($oBeer->likes).')</i>';
						} else {
						$sHTML .= '
									<a class="btn btn-default" href="likeupdate.php?addlike=true&beerID='.$oBeer->beerID.'&userID='.$userID.'">
									Like</a> <i class="fa fa-heart"> ('.count($oBeer->likes).')</i>';
						}
					} else {
						$sHTML .= '<i class="fa fa-heart"> ('.count($oBeer->likes).')</i>';
						 //user not logged in 
				$sHTML .= '<a class="btn btn-default" role="button" data-toggle="collapse" href="#requireLoginMessage" aria-expanded="false" aria-controls="collapseExample">Like</a>';	
				
				$sHTML .= '<div class="collapse" id="requireLoginMessage">';
				$sHTML .= 'login required';
			
				$sHTML .= '</div>';	
							

							
					}
					$sHTML .= '</p>';
					//$sHTML .= '</div>';
				
				//$sHTML .= '</div>';
				
				
				//$sHTML .= '</div>';	
			//$sHTML .= '</div>';
			return $sHTML;
		}

	static public function renderAllBeers($oAllBeers,$loggedin,$userID){

				$oUser = new User();
				$oUser->load($userID);
				$iLocationID = $oUser->locationID;

			$aBeers = $oAllBeers->allBeers;
			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<ul id="listings">';
			
			$iPage = 1;
			
			if(isset($_GET["page"])){
				$iPage	= $_GET["page"];
			}
			
			$iBeersPerPage = 5;
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
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				
				$aBeersAvailable = Availability::loadBeerIDs($iLocationID);
				
				$iBeerID = $oBeer->beerID;
				
				$beerphoto = "assets/images/hound.png";
				$breweryphoto = "";
				$breweryphoto = $oBrewery->breweryphoto;
				
				if($breweryphoto==""){
					// no brewery logo
				} 
				
				if ($oBeer->photo!=""){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$beerphoto = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID!=0){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
				
/*
				// check login
				if($loggedin==1) {
					$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
				} else {
					$edit = "";
				}
*/
				
				// show admin buttons
				if($loggedin==1) {
					$edit = "";
					//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
					if($iLocationID>1){
						if (in_array($iBeerID, $aBeersAvailable)) {
						$addremove = '<a class="btn btn-danger remove" href="removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
						} else {
						$addremove = '<a class="btn btn-success remove" href="addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'&breweryID='.$iBreweryID.'"><i class="fa fa-plus"></i></a>';	
						}
					} else {
					$add = "";
					}
				} else {
					$edit = "";
				}
				
				// Photo Path
/*
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
*/
				$sHTML .= '<li>
								<div class="row">';
					
					
				if($beerphoto=="assets/images/hound.png"){
				$sHTML .='<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
				} else {
				$sHTML .='<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
				}

								$sHTML .= '<div class="col itemTitleCol">';
								
								if($oBeer->breweryID<2){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
								}
								if($oBeer->breweryID>1){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
								}
								
								$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
								$sHTML .= '</div>';
										
				if($oBeer->styleID!=1){
					$sHTML .= '<div class="desktopVisible"><a href="">'.$oStyle->stylename.'</a></div>';	
				}
										
				$sHTML .= 				'<div class="col itemLikes"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>
										<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>';
										//<div class="col item-edit">'.$edit.'</div>
				// Show +/-
				if($iLocationID>1){
						$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
				}						
				
				$sHTML .=				'</div>
							</li>';
			}
			
			$sHTML .= '</ul>';
			
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
			$iNext =  $iPage + 1;
			$iPrev = $iPage - 1;
			$sHTML .= '<p>';
			if($iPage>1){
			$sHTML .= '<a href="'.'index.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';	
			}
			if($iPage=$iTotalPages){
			$sHTML .= ' <a href="'.'index.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			}
			$sHTML .= '</p>';
			
			//$sHTML .= '</div>';
			return $sHTML;
		}

static public function renderRecentBeers($oAllBeers,$loggedin,$total){

			$oAllBeers = $oAllBeers->allBeers;
			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<ul id="listings">';
			
			for($iCount=0;$iCount<10;$iCount++){
				$oBeer = $oAllBeers[$iCount];
				
				// Get Style Names
				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				$beerphoto = "assets/images/hound.png";
				$breweryphoto = "";
				$breweryphoto = $oBrewery->breweryphoto;
				
				if($breweryphoto==""){
					// no brewery logo
				} 
				
				if ($oBeer->photo!=""){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$beerphoto = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID!=0){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
				
				// check login
				if($loggedin==1) {
					$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
				} else {
					$edit = "";
				}
				// Photo Path
/*
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
*/
				$sHTML .= '<li>
								<div class="row">';
					
					
				if($beerphoto=="assets/images/hound.png"){
				$sHTML .='<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
				} else {
				$sHTML .='<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
				}

								$sHTML .= '<div class="col itemTitleCol">';
								
								if($oBeer->breweryID<2){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
								}
								if($oBeer->breweryID>1){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
								}
								
								$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
								$sHTML .= '</div>';
										
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
			
			//$sHTML .= '</div>';
			return $sHTML;
		}


		static public function renderUser($oUser){

			$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<div class="itemTitle"><h2>User Profile</h2></div>';
			$sHTML .= '<div class="itemTitle"><h3>'.$oUser->username.'</h3></div>';
			
		// Follow Checks...
			
			$loggedin = 0;
			
			//Check logged in
			if(isset($_SESSION["UserID"])){
				//get logged in user
				$loggedin = 1;
				$iUserID = $_SESSION["UserID"];
				$iViewUserID = $_GET["viewUserID"];

				//get following list for logged in user
				$aFollowingIDsList = FollowManager::getFollowingIDsList($iUserID);

			} //user not logged in 
			else {
				//$sHTML .= '<div class=""><a class="btn btn-default" href="#">follow</a></div>';
				$sHTML .= '<div class=""><a class="btn btn-default" role="button" data-toggle="collapse" href="#requireLoginMessage" aria-expanded="false" aria-controls="collapseExample">follow</a></div>';	
				
				$sHTML .= '<div class="collapse" id="requireLoginMessage">';
				$sHTML .= 'login required';
				$sHTML .= '</div>';	
			}

		
			if($loggedin==1){
				//check list has items
				if(empty($aFollowingIDsList)){
					// not following anyone
					
					// show follow button
					$sHTML .= '<div class="">
					<a class="btn btn-default" href="followupdate.php?addfollow=true&revu=true&fu='.$oUser->userID.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingUserIDsList($aFollowingIDsList);
					
					//check if user viewed is already on follow list
					if(in_array($iViewUserID, $aUserIDsCurrentlyFollowing)){
						// match > show unfollow
						$sHTML .= '<div class="">
						<a class="btn btn-default" 
						href="viewuseradmin.php">
						unfollow</a></div>';
					} else {
						// no match show follow button
						$sHTML .= '<div class="">
						<a class="btn btn-default" href="followupdate.php?addfollow=true&revu=true&fu='.$oUser->userID.'">
						follow</a></div>';	
					}

				}
			}
			
			// END FOLLOW checks...
	
			$sHTML .= '</div>';
			return $sHTML;	
		}
		
	static public function renderUserAdmin($oUser){
		
			$sHTML = '<div class="wrapper clearfix">';
				$sHTML .= '<div class="col-md-8">';
				$sHTML .= '
					<div class="itemTitle"><h2>username: '.$oUser->username.'</h2></div>
					<div class="">First Name: '.$oUser->firstname.'</div>
					<div class="">Last Name: '.$oUser->lastname.'</div>';
					// <div class=""><a href="">Email: '.$oUser->email.'</a></div>'
			$sHTML .= '<div class=""><a href="edituser.php" class="btn btn-default">edit profile</a></div>';
			$sHTML .= '<div class=""><a href="viewuser.php?viewUserID='.$oUser->userID.'" class="btn btn-default">view public profile</a></div>';
			$sHTML .= '</div>';
/*
			if($_SESSION["BreweryManagerID"]){
		$sHTML .= '<a class="btn btn-default" href="viewbrewery.php?breweryID='.$iBreweryID.'">view public profile</a>';
			}
*/
			
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
		
		static public function renderLike($oLike){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<div class="itemTitle"><h2>'.$oLike->likeID.'</h2></div>';
		$sHTML .= '<div class="itemTitle"><h2>'.$oLike->beerID.'</h2></div>';
		$sHTML .= '<div class="itemTitle"><h2>'.$oLike->userID.'</h2></div>';
		$sHTML .= '</div>';
		return $sHTML;
		}
		
		static public function renderSearchResults($aBeers,$loggedin,$userID){
			
			
				$oUser = new User();
				$oUser->load($userID);
				$iLocationID = $oUser->locationID;
			
				
			$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<ul id="listings">';
			for($iCount=0;$iCount<count($aBeers);$iCount++){
				$oBeer = $aBeers[$iCount];
				
				$oUser = new User();
				$oUser->load($userID);
				$iLocationID = $oUser->locationID;
				
				$aBeersAvailable = Availability::loadBeerIDs($iLocationID);
				
				$iBeerID = $oBeer->beerID;
				
				// Load stylenames
				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
								
				$beerphoto = "assets/images/hound.png";
				$breweryphoto = "";
				$breweryphoto = $oBrewery->breweryphoto;
				$breweryphoto = "assets/images/".$breweryphoto;
			
/*
			echo "<pre>";
			echo $loggedin;
			echo "</pre>";
*/

				
				// show admin buttons
				if($loggedin==1) {
					$edit = "";
					//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
					if($iLocationID>1){
						if (in_array($iBeerID, $aBeersAvailable)) {
						$addremove = '<a class="btn btn-danger remove" href="removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
						} else {
						$addremove = '<a class="btn btn-success remove" href="addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'&breweryID='.$iBreweryID.'"><i class="fa fa-plus"></i></a>';	
						}
					} else {
					$add = "";
					}
				} else {
					$edit = "";
				}
				// Photo Path
				if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$beerphoto = "assets/images/hound.png";
				}
				
				$sHTML .= '<li>
								<div class="row">';
								
				if($oBeer->photo){
				$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
				}	else {
					$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
				}
										
								$sHTML .= '<div class="col itemTitleCol">';
																		
								if($oBeer->breweryID<2){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
								}
								if($oBeer->breweryID>1){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
								}

								//$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								//$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
									$sHTML .= '	</div>';
				if($oBeer->styleID!=1){
				$sHTML .= 				'<div class="desktopVisible"><a href="">'.$oStyle->stylename.'</a></div>';	
				}				
				$sHTML .= 				'<div class="col itemLikes"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>
										<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>';
				if($loggedin==1){
						//$sHTML .='<div class="col item-edit">'.$edit.'</div>';
						}
				if($iLocationID>1){
						$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
				}				
					$sHTML .= '				
								</div>
							</li>';
			}
			$sHTML .= '</ul>';
			$sHTML .= '</div>';
			return $sHTML;
		}
		
	
	
	static public function rendermostLiked($oAllBeers,$show){
			
			$limit = $show;
			$aBeers = $oAllBeers->allBeers;
			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<ul id="listings">';
			for($iCount=0;$iCount<$limit;$iCount++){
				$oBeer = $aBeers[$iCount];
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				// Photo Path
				$breweryphoto = $oBrewery->breweryphoto;
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$breweryphoto = "assets/images/$breweryphoto";
				}
				$sHTML .= '<li>
								<div class="row">';
										
					if($oBeer->photo){
						$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>';
					} else {
						$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
					}
					
										
										
					$sHTML .= '				<div class="col itemTitleColSmall">
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

	static public function renderAllLiked($oLikelist,$iBeersPerPage){
			
/*
			echo "<pre>";
			print_r($oLikelist);
			echo "</pre>";
			
			echo "<pre>";
			echo "total beers liked = ".count($oLikelist);
			echo "<br/>";
			echo "total beers per page = ".$iBeersPerPage;
			echo "</pre>";
*/
			
			$iPage = 1;
			
			if(isset($_GET["page"])){
				$iPage	= $_GET["page"];
			}
			
			$iTotalPages = ceil(count($oLikelist) / $iBeersPerPage);
			$iStartIndex = ($iPage - 1) * $iBeersPerPage ;
			
			$iEndIndex = $iStartIndex + $iBeersPerPage;
			if($iEndIndex > count($oLikelist)){
				$iEndIndex = count($oLikelist);
			}

			//$oLikelist = $oLikelist->allBeers;
			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<ul id="listings">';
			for($iCount=0;$iCount<$iBeersPerPage;$iCount++){
				$oBeer = $oLikelist[$iCount];
				$iBreweryID = $oLikelist[$iCount];
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				// Photo Path
				$breweryphoto = $oBrewery->breweryphoto;
				if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$breweryphoto = "assets/images/$breweryphoto";
				}
				$sHTML .= '<li>
								<div class="row">';
										
					if($oBeer->photo){
						$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>';
					} else {
						$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
					}
					
										
										
					$sHTML .= '				<div class="col itemTitleColSmall">
											<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
											<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>
										</div>
										<div class="col"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>
								</div>
							</li>';
			}
			$sHTML .= '</ul>';
			
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
			$iNext =  $iPage + 1;
			$iPrev = $iPage - 1;
			$sHTML .= '<p>';
			if($iPage>1){
			$sHTML .= '<a href="'.'stats.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';	
			}
			if($iPage=$iTotalPages){
			$sHTML .= ' <a href="'.'stats.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			}
			$sHTML .= '</p>';
			
			//$sHTML .= '</div>';
			return $sHTML;
		}

	
	static public function renderAvailability($aBeerlocations){
		//$sHTML = '<div class="wrapper clearfix">';
		
		$sHTML = '<h4>Available from:</h4>';
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
		//$sHTML .= '</div>';
		return $sHTML;
	}
	
	static public function renderLocation($oLocation){
		$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<div class="col-md-8">';
				$sHTML .= '<h3>'.$oLocation->locationname.'</h3>';
				$sHTML .= '<div class="contactDetails">'.$oLocation->locationaddress.'</div>';
				$sHTML .= '<div class="contactDetails">'.$oLocation->locationsuburb.' '.$oLocation->locationregion.'</div>';
				$sHTML .= '<div class="contactDetails">Phone: <a href="tel:'.$oLocation->locationcontact.'">'.$oLocation->locationcontact.'</a></div>';
				$sHTML .= '<div class="contactDetails"><a href="http://'.$oLocation->locationwebsite.'">'.$oLocation->locationwebsite.'</a></div>';
				
				// START FOLLOW Checks...
			
			$loggedin = 0;
			
			//Check logged in
			if(isset($_SESSION["UserID"])){
				//get logged in user
				$loggedin = 1;
				$iUserID = $_SESSION["UserID"];
				$iLocationID = $oLocation->locationID;
				//$iLocationID = $_GET["locationID"];

				//get following list for logged in user
				$aFollowingIDsList = FollowManager::getFollowingIDsList($iUserID);

			} //user not logged in 
			else {
				//$sHTML .= '<div class=""><a class="btn btn-default" href="#">follow</a></div>';
				$sHTML .= '<div class=""><a class="btn btn-default" role="button" data-toggle="collapse" href="#requireLoginMessage" aria-expanded="false" aria-controls="collapseExample">follow</a></div>';	
				
				$sHTML .= '<div class="collapse" id="requireLoginMessage">';
				$sHTML .= 'login required';
				$sHTML .= '</div>';	
			}
			
			if($loggedin==1){
				
				//Load user
				$oUser = new User();
				$oUser->load($_SESSION["UserID"]);
				
				//check list has items
				if(empty($aFollowingIDsList)){
					// not following anyone
					
					// show follow button
					$sHTML .= '<div class="">
					<a class="btn btn-default" href="followupdate.php?addfollow=true&revl=true&fl='.$oUser->userID.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingLocationIDsList($aFollowingIDsList);
					
					//check if user viewed is already on follow list
					if(in_array($iLocationID, $aUserIDsCurrentlyFollowing)){
						// match > show unfollow
						$sHTML .= '<div class="">
						<a class="btn btn-default" 
						href="viewuseradmin.php">
						unfollow</a></div>';
					} else {
						// no match show follow button
						$sHTML .= '<div class="">
						<a class="btn btn-default" href="followupdate.php?addfollow=true&revl=true&fl='.$oLocation->locationID.'">
						follow</a></div>';	
					}

				}
			}
			
			// END FOLLOW checks...

				
			$sHTML .= '</div>';
			
			
			$sHTML .= '<div class="col-md-4">';
						if($oLocation->claimstatus==0){
						$sHTML .= '<div class="claim">Are you the owner of this location?<br/>Claim your free management account now.</div>';
						$sHTML .= '<div class="claim"><a class="btn btn-default" href="about.php">Claim</a></div>';
			}
			$sHTML .= '</div>';

							
		$sHTML .= '</div>';
		return $sHTML;
		}

/*
	static public function renderClaimLocation($locationID){
			$oForm = new Form();
			$oForm->makeSelectInput("Brewery","BreweryID",User::userlist());
			echo $oForm->html;
	}
	static public function renderClaimBrewery($iBreweryID){
		
	}
*/
	
	static public function renderBrewery($oBrewery){
		
/*
		echo "<pre>";
		print_r($oBrewery);
		echo "</pre>";
*/

		$iBreweryID = $oBrewery->breweryID;
		
		$sHTML = '<div class="wrapper clearfix">';
			
			// LOGO
			$sHTML .= '<div class="col-md-2">';
			if ($oBrewery->breweryphoto){
				$photo = "assets/images/".$oBrewery->breweryphoto;
				} else {
				$photo = "assets/images/hound.png";
				}
			
			$sHTML .= '<div class="breweryimage"><img src="'.$photo.'"/></div>';


			$sHTML .= '</div>';
		
			// DETAILS
			$sHTML .= '<div class="col-md-6">';
				$sHTML .= '<h3>'.$oBrewery->breweryname.'</h3>';
				$sHTML .= '<h3>'.$oBrewery->fullbreweryname.'</h3>';
				$sHTML .= '<div class="">Address: '.$oBrewery->breweryaddress.'</div>';
				$sHTML .= '<div class="contactDetails">Website: <a href="'.$oBrewery->brewerywebsite.'">'.$oBrewery->brewerywebsite.'</a></div>';


if($_SERVER['REQUEST_URI']=="/viewuseradmin.php"){
	
} else {
	



		// START FOLLOW Checks...
			
			$loggedin = 0;
			
			//Check logged in
			if(isset($_SESSION["UserID"])){
				//get logged in user
				$loggedin = 1;
				$iUserID = $_SESSION["UserID"];
				$iBreweryID = $oBrewery->breweryID;

				//get following list for logged in user
				$aFollowingIDsList = FollowManager::getFollowingIDsList($iUserID);
				
			} //user not logged in 
			else {
				//$sHTML .= '<div class=""><a class="btn btn-default" href="#">follow</a></div>';
				$sHTML .= '<div class=""><a class="btn btn-default" role="button" data-toggle="collapse" href="#requireLoginMessage" aria-expanded="false" aria-controls="collapseExample">follow</a></div>';	
				
				$sHTML .= '<div class="collapse" id="requireLoginMessage">';
				$sHTML .= 'login required';
				$sHTML .= '</div>';	
			}

		
			if($loggedin==1){
				//check list has items
				if(empty($aFollowingIDsList)){
					// not following anyone
					
					// show follow button
					$sHTML .= '<div class="">
					<a class="btn btn-default" href="followupdate.php?addfollow=true&revu=true&fu='.$iUserID.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingBreweryIDsList($aFollowingIDsList);
					
						//check if user viewed is already on follow list
						if(in_array($iBreweryID, $aUserIDsCurrentlyFollowing)){
							// match > show unfollow
							$sHTML .= '<div class="">
							<a class="btn btn-default" 
							href="viewuseradmin.php">
							unfollow</a></div>';
						} else {
							// no match show follow button
							$sHTML .= '<div class="">
							<a class="btn btn-default" href="followupdate.php?addfollow=true&revb=true&fb='.$oBrewery->breweryID.'">
							follow</a></div>';	
						}

				}
			}
			
			// END FOLLOW checks...
}
				
			$sHTML .= '</div>';
			
			// CLAIM
			$sHTML .= '<div class="col-md-4 mt2">';
			if($oBrewery->claimstatus==0){
					$sHTML .= '<div class="claim">Are you the owner of this brewery?<br/>';
					$sHTML .= 'Claim your free management account now.</div>';
					$sHTML .= '<div class="claim"><a class="btn btn-default" href="about.php">Claim</a></div>';
			}
		$sHTML .= '</div>';

		$sHTML .= '</div>';
		return $sHTML;
		}

	static public function renderAllLocations($aLocations){
		
		// Pagination
		$iPage = 1;

		
		if(isset($_GET["page"])){
			$iPage	= $_GET["page"];
		}
		$iNext =  $iPage + 1;
		$iPrev = $iPage - 1;
		
		$iLocationsPerPage = 20;
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
		
			$aBeersAvailable = Availability::loadBeers($oLocation->locationID);
		
			$taps = count($aBeersAvailable);
			
			$sHTML .= '<a href="viewlocation.php?locationID='.$oLocation->locationID.'"><li><div class="row">';
// 				$sHTML .= '<div class="col item-logo"></div>';
				$sHTML .= '<div class="col itemTitle desktopVisible"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="phoneFullWidth phoneVisible"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="col responsive desktopVisible">'.$oLocation->locationsuburb.'</div>';
				$sHTML .= '<div class="col responsive desktopVisible">'.$oLocation->locationregion.'</div>';
				$sHTML .= '<div class="col responsive desktopVisible"><b>'.$taps.'</b> Taps listed</div>';
				
			$sHTML .= '</li></a>';	
		}

		if($iTotalPages>1){		
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';	
			$sHTML .= '<p>';
			if($iPage!=1){
			$sHTML .= '<a href="'.'viewlocations.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';
			}
			if($iPage!=$iTotalPages){
			$sHTML .= ' <a href="'.'viewlocations.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			}
			$sHTML .= '</p>';
		}
			
		$sHTML .= '</ul></div></div>';
		return $sHTML;
	}

	static public function renderAllBreweries($aBreweries){
		
		// Pagination
		$iPage = 1;

		
		if(isset($_GET["page"])){
			$iPage	= $_GET["page"];
		}
		$iNext =  $iPage + 1;
		$iPrev = $iPage - 1;
		
		$iBreweriesPerPage = 30;
		$iTotalPages = ceil(count($aBreweries) / $iBreweriesPerPage);
		//$iStartIndex = ($iPage - 1) * $iBreweriesPerPage ;
		
		$iStartIndex = 1; // offset for select placeholder
		
		$iEndIndex = $iStartIndex + $iBreweriesPerPage;
		if($iEndIndex > count($aBreweries)){
			$iEndIndex = count($aBreweries);
		}
	
		$sHTML = '<div class="wrapper clearfix">';
		
		$sHTML .= "<p>We are indexing more breweries every day, here's what we've got so far.</p>";
		$sHTML .= '<p>If you\'re the owner of a Brewery and want to manage your listings please contact us <a class="btn btn-default" href="about.php">contact</a></p>';
		
		$sHTML .= '<ul id="listings" class="locations">';
		for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
			$iBrewery = $aBreweries[$iCount];
			$oBrewery = new Brewery();
			$oBrewery->load($iBrewery->breweryID);
			$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
			//$aBeersAvailable = Availability::loadBeers($oLocation->locationID);
		
			//$taps = count($aBeersAvailable);
			
			$sHTML .= '<li><div class="row">';
// 				$sHTML .= '<div class="col item-logo"></div>';
				$sHTML .='<div class="col itemLogo"><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'"><img src="'.$breweryphoto.'"/></a></div>';

				$sHTML .= '<div class="col itemTitle desktopVisible"><h3><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></h3></div>';
				$sHTML .= '<div class="col itemTitleCol phoneFullWidth phoneVisible"><h3><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></h3></div>';
				//$sHTML .= '<div class="col responsive desktopVisible">'.$oBrewery->locationsuburb.'</div>';
				//$sHTML .= '<div class="col responsive desktopVisible">'.$oBrewery->locationregion.'</div>';
				//$sHTML .= '<div class="col responsive desktopVisible"><b>'.$taps.'</b> Taps listed</div>';
				
			$sHTML .= '</li>';	
		}

		if($iTotalPages>1){		
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';	
			$sHTML .= '<p>';
			if($iPage!=1){
			$sHTML .= '<a href="'.'viewbreweries.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';
			}
			if($iPage!=$iTotalPages){
			$sHTML .= ' <a href="'.'viewbreweries.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			}
			$sHTML .= '</p>';
		}
			
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
				//$sHTML .= '<div class="col responsive">'.$oUser->firstname.'</div>';
				//$sHTML .= '<div class="col responsive">'.$oUser->lastname.'</div>';
				$sHTML .= '<div class="col responsive">Beers Liked ('.count($oUser->likes).')</div>';
			$sHTML .= '</li></a>';	
		}
		$sHTML .= '</ul></div></div>';
		return $sHTML;
	}

static public function renderBeersLiked($aLikes){
		$sHTML = '<div class="wrapper clearfix">';
		
		if(count($aLikes>0)){
		$sHTML .= '<h4>Beers Liked:</h4>';
		$sHTML .= '<ul id="listings">';
		}
		
		for($iCount=0;$iCount<count($aLikes);$iCount++){
			$oLike = $aLikes[$iCount];
			$oBeer = new Beer();
			$oBeer->load($oLike->beerID);

			// Get Style Names
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			
			$oBrewery = new Brewery();
			$oBrewery->load($oBeer->breweryID);

			// init photo vars
			$beerphoto = "";
			$breweryphoto = "";
			$placeholder = "assets/images/hound.png";
			
			// Get Beer Photo
			if ($oBeer->photo!=""){
				$beerphoto = "assets/images/".$oBeer->photo;
			}
			// Get Brewery Photo
			if($oBeer->breweryID>1){
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
			}

			$sHTML .= '<li>
				<div class="row">';
				
				//Show Logo
				if($beerphoto==""){
					$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
				} elseif ($breweryphoto=="") {
					$sHTML .= '<div class="col itemLogo"><img src="'.$beerphoto.'"/></div>';
				} elseif ($beerphoto!="") {
					$sHTML .= '<div class="col itemLogo"><img src="'.$beerphoto.'"/></div>';
				} else {
					$sHTML .= '<div class="col itemLogo"><img src="'.$placeholder.'"/></div>';
				}
				//<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
				$sHTML .='<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><b>'.$oBeer->title.'</b></div>';
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
	
/*
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
*/
	
		$iLocationID=0;
		$iLocationManagerID=0;
			$userID = 0;
		if (isset($_SESSION["UserID"])==false){
			$loggedin = 0;
		} else {
			$loggedin = 1;
			$userID=$_SESSION["UserID"];
			$iLocationManagerID = $_SESSION["LocationManagerID"];
			$iBreweryManagerID = $_SESSION["BreweryManagerID"];
		}
		
		$iLocationID = $iLocationManagerID;
					
		$aBeersAvLoc = Availability::loadBeerIDs($iLocationID);
		

		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Available Brews:</h3>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aBeersAvailable);$iCount++){
			$oBeer = $aBeersAvailable[$iCount];
			// Get Style Names
			
			if($oBeer->breweryID!=1){
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
			}

			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			$iBeerID = $oBeer->beerID;
			if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
		$sHTML .= '<li>
				<div class="row">';
		
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>';
		} else {
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
		}

		$sHTML .= '<div class="col itemTitleCol">';
		if($oBeer->breweryID!=1){
			$sHTML .= '								<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '							<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
		} else	{
			$sHTML .= '								<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '							<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
		}								
											
		$sHTML .= '								<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '							</div>';
		
				
// 		$sHTML .= '<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
				$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
			
				
				// show admin buttons
				if($loggedin==1) {
					$edit = "";
					//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
					if($iLocationManagerID>1){
						if (in_array($iBeerID, $aBeersAvLoc)) {
						$addremove = '<a class="btn btn-danger remove" href="removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
						} else {
						$addremove = '<a class="btn btn-success remove" href="addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'&breweryID='.$iBreweryID.'"><i class="fa fa-plus"></i></a>';	
						}
					} else {
					$add = "";
					}
				} else {
					$edit = "";
				}
				

				if($iLocationID>1){
						$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
				}	
				
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
	}

static public function renderAvailableData($aAvailableIDs){
	
/*
	echo "<pre>";
	print_r($aAvailableIDs);
	echo "</pre>";
*/
		
		
		$iLocationID=0;
		$iLocationManagerID=0;
			$userID = 0;
		if (isset($_SESSION["UserID"])==false){
			$loggedin = 0;
		} else {
			$loggedin = 1;
			$userID=$_SESSION["UserID"];
			$iLocationManagerID = $_SESSION["LocationManagerID"];
			$iBreweryManagerID = $_SESSION["BreweryManagerID"];
		}
		
		$iLocationID = $iLocationManagerID;
					
		$aBeersAvLoc = Availability::loadBeerIDs($iLocationID);
		

		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Available Brews:</h3>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aAvailableIDs);$iCount++){
			$iAvailableID = $aAvailableIDs[$iCount];
	
			$oAvailability = new Availability();
			$oAvailability->load($iAvailableID);
			
			$oBeer = new Beer();
			$oBeer->load($oAvailability->beerID);
			
			$timedate = time2str($oAvailability->date);
			
			// Get Style Names
			
			if($oBeer->breweryID!=1){
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
			}

			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			$iBeerID = $oBeer->beerID;
			if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
		$sHTML .= '<li>
				<div class="row">';
		
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>';
		} else {
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
		}

		$sHTML .= '<div class="col itemTitleCol">';
		if($oBeer->breweryID!=1){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
		} else	{
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
		}								
											
		$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '</div>';
		$sHTML .= ''.$timedate.'';
				
// 		$sHTML .= '<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
				$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
			
				
				// show admin buttons
				if($loggedin==1) {
					$edit = "";
					//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
					if($iLocationManagerID>1){
						if (in_array($iBeerID, $aBeersAvLoc)) {
						$addremove = '<a class="btn btn-danger remove" href="removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
						} else {
						$addremove = '<a class="btn btn-success remove" href="addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'"><i class="fa fa-plus"></i></a>';	
						}
					} else {
					$add = "";
					}
				} else {
					$edit = "";
				}
				

				if($iLocationID>1){
						$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
				}	
				
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
	}


static public function renderManageAvailabilty($aBeersAvailable,$iLocationID,$iUserID){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Available Brews:</h3>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aBeersAvailable);$iCount++){
			$oBeer = $aBeersAvailable[$iCount];
			// Get Style Names
			$iBeerID = $oBeer->beerID;
			
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			
			$iBreweryID = $oBeer->breweryID;
			$oBrewery = new Brewery();
			$oBrewery->load($iBreweryID);
			
			$beerphoto = "assets/images/hound.png";
			$breweryphoto = "";
			$breweryphoto = $oBrewery->breweryphoto;
			if ($oBrewery->breweryphoto){
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
			
			if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$beerphoto = "assets/images/hound.png";
				}
		$sHTML .= '<li>
				<div class="row">';
				
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
		}	else {
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
		}
				
		
		$sHTML .= '<div class="col itemTitleCol">';
		
		if($oBeer->breweryID<2){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
			}
			if($oBeer->breweryID>1){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
			}
		
			//<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
			//<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
		$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '</div>';
		$sHTML .= '<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
/*
		$sHTML .= '<div class="col itemTitleCol"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
					$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
*/
					
					$sHTML .='<div class="col item-edit"><a class="btn btn-danger remove" href="removeLocAv.php?userID='.$iUserID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a></div>';
				
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
	}

static public function renderManageBreweryAvailabilty($aBeersAvailable,$iBreweryID,$iUserID){
		$sHTML = '<div class="wrapper clearfix">';
		$sHTML .= '<h3>Available Brews:</h3>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aBeersAvailable);$iCount++){
			$oBeer = $aBeersAvailable[$iCount];
			// Get Style Names
			$iBeerID = $oBeer->beerID;
			
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			
			$iBreweryID = $oBeer->breweryID;
			$oBrewery = new Brewery();
			$oBrewery->load($iBreweryID);
			
			$beerphoto = "assets/images/hound.png";
			$breweryphoto = "";
			$breweryphoto = $oBrewery->breweryphoto;
			if ($oBrewery->breweryphoto){
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
			
			if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$beerphoto = "assets/images/hound.png";
				}
		$sHTML .= '<li>
				<div class="row">';
				
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
		}	else {
			$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
		}
				
		
		$sHTML .= '<div class="col itemTitleCol">';
		
		if($oBeer->breweryID<2){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
			}
			if($oBeer->breweryID>1){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
			}
		
			//<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
			//<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>
		$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '</div>';
		$sHTML .= '<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
/*
		$sHTML .= '<div class="col itemTitleCol"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
					$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
*/
					
					$sHTML .='<div class="col item-edit"><a class="btn btn-danger remove" href="removeLocAv.php?userID='.$iUserID.'&locationID='.$iBreweryID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a></div>';
				
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		$sHTML .= '</div>';
		return $sHTML;
	}


	static public function renderLikers($aLikers){
		//$sHTML = '<div class="wrapper clearfix">';
		//$sHTML = '<div class="col-md-6">';
		$sHTML = '<div class="">';	
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
		//$sHTML .= '</div>';
		return $sHTML;
	}
	
	static public function renderComments($aComments){

		//$sHTML = '<div class="wrapper clearfix">';
		$sHTML = '<h4>Comments:</h4>';
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
			if(isset($_SESSION["UserID"])){
				if($_SESSION["UserID"]==$oComment->userID){
				$sHTML .= '<span><a href="commentremove.php?beerID='.$oComment->beerID.'&removeCommentID='.$oComment->commentID.'"> | delete</a></span>';
				}
			}

			$sHTML .= '</div>';
			// close meta
			
			$sHTML .= '<hr>';
			}
		//$sHTML .= '</div>';
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
			
				if(isset($_SESSION["UserID"])){
					if($_SESSION["UserID"]==$oComment->userID){
					$sHTML .= '<p><a href="commentremove.php?beerID='.$beerID.'&removerCommentID='.$oComment->commentID.'">delete</a></p>';
				}
			}

			$sHTML .= '<hr>';
			}
		$sHTML .= '</div>';
		return $sHTML;
	}

	static public function renderLoginRegister(){
		//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<div class="col-md-6">';
				$sHTML .= '<a href="login.php" class="btn btn-default">login</a> ';
				$sHTML .= '<a href="register.php" class="btn btn-default">register</a>';
				//$uri = $_SERVER['REQUEST_URI'];
			$sHTML .= '</div>';
		//$sHTML .= '</div>';
		return $sHTML;
	}

	static public function renderCommentForm($oForm){
		
		$sFormHTML = $oForm->html;
		
		//$sHTML = '<div class="wrapper clearfix">';
		$sHTML = 	'<div class="col-md-12">
				        <div class="box">';
		$sHTML .= "$sFormHTML";
				
	    $sHTML .= '</div></div>';
	    $sHTML .= '</div>';
	return $sHTML;
	}
	
	static public function renderLocationTabs($suburb){
		
		$sHTML = '<div class="wrapper clearfix">';
			$sHTML .= '<a class="btn btn-primary" role="button" data-toggle="collapse" href="#filters" aria-expanded="false" aria-controls="collapseExample">Filter Locations</a>';
			if ($suburb!="all"){
				$sHTML .= '<a href="viewlocations.php" class="btn btn-default">Show all</a>';
			}
				$sHTML .= '<div class="collapse" id="filters">';

				$sHTML .= '<h5>Auckland</h5>';
				$sHTML .= '<a href="viewlocations.php?suburb=Auckland%20Central" class="btn btn-default">Auckland Central</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Birkenhead" class="btn btn-default">Birkenhead</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Browns%20Bay" class="btn btn-default">Browns Bay</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Eden%20Terrace" class="btn btn-default">Eden Terrace</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Henderson" class="btn btn-default">Henderson</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Kingsland" class="btn btn-default">Kingsland</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Morningside" class="btn btn-default">Morningside</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mt%20Eden" class="btn btn-default">Mt Eden</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Newmarket" class="btn btn-default">Newmarket</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Ponsonby" class="btn btn-default">Ponsonby GreyLynn</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Westmere" class="btn btn-default">Westmere</a>';
				
				$sHTML .= '<h5>Rest of NZ</h5>';
				$sHTML .= '<a href="viewlocations.php?suburb=Christchurch%20Central" class="btn btn-default">Christchurch Central</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Leigh" class="btn btn-default">Leigh</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mangawhai" class="btn btn-default">Mangawhai</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mt%20Maunganui" class="btn btn-default">Mt Maunganui</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Waihi%20Beach" class="btn btn-default">Waihi Beach</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Wellington%20Central" class="btn btn-default">Wellington Central</a>';

			$sHTML .= '</div>';
			
		$sHTML .= '</div>';
	return $sHTML;
	}
	
	
}
?>