 <?php

function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}

function convertPass($id){
	
	$oUser = new User();
	$oUser->load($id);
	$password = $oUser->password;
	$encrypted = password_hash($password,PASSWORD_DEFAULT);
	$oUser->password = $encrypted;
	$oUser->updatepassword();
	
	return 'done';
}

function createUserSlugs($users) {
	$i = 1;
	
	foreach($users as $user) {
		
		$oUser = new User();
		$oUser->load($user);
		
		$slug = strtolower(str_replace(array(':','+','&','!','#','.',"'",'(',')'),"",str_replace(' ','-',$oUser->username)));
		$oUser->slug = $slug;
		
		echo "<pre>";
		echo $slug;
		echo "</pre>";
		
		$oUser->updateUserSlugs();
		$i++;
	}
}


function createslugs($beers) {
	$i = 1;
	
	foreach($beers as $beer) {
		
		$oBeer = new Beer();
		$oBeer->load($beer);
		
		
		if($oBeer->breweryID<2){
			$brewery = strtolower(str_replace(array('&','+','.',"'",'(',')'),"",str_replace(' ','-',$oBeer->brewery)));
		} else {
			$breweryID = new Brewery();
			$breweryID->load($oBeer->breweryID);
			
			$brewery = strtolower(str_replace(array('&','+','.',"'",'(',')'),"",str_replace(' ','-',$breweryID->breweryname)));
			//$brewery = strtolower(str_replace(' ','-',$brew->brewery()->BreweryName));
		}
		$title = strtolower(str_replace(array(':','+','&','!','#','.',"'",'(',')'),"",str_replace(' ','-',$oBeer->title)));
		$createslug = $brewery.'-'.$title;
		//echo $createslug.' '.$oBeer->beerid.'<br/>';
		$oBeer->slug = $createslug;
		$oBeer->updateslugs();
		$i++;
	}
}

function createLocationSlugs($locations) {
	$i = 1;
	
	foreach($locations as $location) {
		
		$oLocation = new Location();
		$oLocation->load($location);

		$locationSlug = strtolower(str_replace(array('/','.',",",'&','!',"'",'(',')'),"",str_replace(' ','-',$oLocation->locationname)));

		//echo $locationSlug.' '.$oLocation->locationID.'<br/>';
		$oLocation->slug = $locationSlug;
		$oLocation->updateLocationSlugs();
		$i++;
	}
}

function createBrewerySlugs($breweries) {
	$i = 1;
	
	foreach($breweries as $brewery) {
		
		$oBrewery = new Brewery();
		$oBrewery->load($brewery);

		$brewerySlug = strtolower(str_replace(array('.',",",'&','!',"'",'(',')'),"",str_replace(' ','-',$oBrewery->breweryname)));

		//echo $locationSlug.' '.$oLocation->locationID.'<br/>';
		$oBrewery->slug = $brewerySlug;
		$oBrewery->updateBrewerySlugs();
		$i++;
	}
}

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
            if($diff < 3600) return floor($diff / 60) . ' mins ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hrs ago';
        }
        if($day_diff == 1) { return 'Yesterday'; }
        if($day_diff < 14) { return $day_diff . ' days ago'; }
        if($day_diff < 31) { return ceil($day_diff / 7) . ' weeks ago'; }
        if($day_diff < 60) { return 'a few weeks ago'; }
                //if($day_diff < 60) { return 'last month'; }
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
	
	
	static public function renderCheckins($aStatusUpdates,$beerID,$domain,$oForm){
		
		$sFormHTML = $oForm->html;
		
		$averageRating = 0;
		$averageRating = Status::averageRating($beerID);
		
		$sHTML = '';
		
		if(count($aStatusUpdates)!=0&&$averageRating!=0){
			$sHTML .= '<h4>Average Rating '.$averageRating.'</h4>';
		}
		
				
		for($iCount=0;$iCount<count($aStatusUpdates);$iCount++){
			$oStatus = new Status();
			$oStatus->load($aStatusUpdates[$iCount]);
			
			$photo = "";
			if($oStatus->photo){
				$photo = $oStatus->photo;
			}
			
			$rating = $oStatus->rating;
			$starsHTML = "";
			
			for($i=0;$i<$rating;$i++){
				$starsHTML .= '<i class="fa fa-star" aria-hidden="true"></i> ';
			}
			$blankstarsHTML = "";
			$addblank = 5-$rating;
			for($i=0;$i<$addblank;$i++){
				$blankstarsHTML .= '<i class="fa fa-star-o" aria-hidden="true"></i> ';
			}
			
			
			
			$oUser = new User();
			$oUser->load($oStatus->userid);
			
			$oLocation = new Location();
			$oLocation->load($oStatus->locationid);
			
			$sHTML .= '<div class="sideitem">';
				$sHTML .= '<div class="sideitemText">';
				if($rating!=0){
					$sHTML .=	'<span class="stars">'.$starsHTML.$blankstarsHTML.'</span>';
				}
				

				$sHTML .= ' by <a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a> at <a href="'.$domain.'location/'.$oLocation->slug.'">'.$oLocation->locationname.'</a>';
				$sHTML .= ' '.time2str($oStatus->created_at).' ';
				
				if($oStatus->photo||$oStatus->review){
					$sHTML .= '<a role="button" data-toggle="collapse" href="#details'.$iCount.'" aria-expanded="false" aria-controls="collapseExample">';
					//icons
						if($oStatus->photo){
							$sHTML .= ' <i class="fa fa-picture-o fa-2x" aria-hidden="true"></i> ';
						}
						if($oStatus->review){
							$sHTML .= ' <i class="fa fa-pencil fa-2x" aria-hidden="true"></i> ';
						}
					$sHTML .= '</a>';
					}
					
				
							
				//$sHTML .= '<div class="clearfix"></div>';
				

				
				
					$sHTML .= '<div class="collapse" id="details'.$iCount.'">';
					$sHTML .= '<h4>Review/Comments</h4>';			
					$sHTML .= '<p>'.$oStatus->review.'</p>';
					if($oStatus->photo){
						$sHTML .= '<img style="max-width:100%" src="http://brewhound.nz/assets/images/'.$photo.'"/>';	
					}
					$sHTML .= '<div class="comments">';
					// render comment form
					//$sHTML .= "$sFormHTML";
					$sHTML .= '</div>';
					$sHTML .= '</div>';
				
				$sHTML .= '</div>';	
				
				
			$sHTML .= '</div>';
			
			
			}
		
		$sHTML .= '';
		return $sHTML;	
	}
	
	static public function renderLocationUpdates($aAvails,$domain,$limit){
		$sHTML = '';
				
		for($iCount=0;$iCount<count($aAvails);$iCount++){
			$oAvail = new Availability();
			$oAvail->load($aAvails[$iCount]);
			$beerID = $oAvail->beerID;
			$date = time2str($oAvail->date);
			
			$locationID = $oAvail->locationID;
			
			$oBeer = new Beer();
			$oBeer->load($beerID);
			$brewslug = $oBeer->slug;
			
			$oLocation = new Location();
			$oLocation->load($locationID);
			$location = $oLocation->locationname;
			$locationslug = $oLocation->slug;
			
			$sHTML .= '<p><a href="'.$domain.$brewslug.'">'.$oBeer->breweryname.' - '.$oBeer->title.'</a> @ <a href="'.$domain.'location/'.$locationslug.'">'.$location.'</a> '.$date.'</p>';
		}
		$sHTML .= '';
		return $sHTML;	
	}

	static public function renderStatusUpdates($aStatusUpdates,$domain,$limit){
		
		$sHTML = '';
		
		for($iCount=0;$iCount<$limit;$iCount++){
			$oStatus = new Status();
			$oStatus->load($aStatusUpdates[$iCount]);
			
			$rating = $oStatus->rating;
			$starsHTML = "";
			
			for($i=0;$i<$rating;$i++){
				$starsHTML .= '<i class="fa fa-star" aria-hidden="true"></i> ';
			}
			
			$blankstarsHTML = "";
			$addblank = 5-$rating;
			
			for($i=0;$i<$addblank;$i++){
				$blankstarsHTML .= '<i class="fa fa-star-o" aria-hidden="true"></i> ';
			}
			
			
			$oBeer = new Beer();
			$oBeer->load($oStatus->beerid);
			
			$oBrewery = new Brewery();
			$oBrewery->load($oBeer->breweryID);
			
			if($oBeer->photo){
				$logo = $oBeer->photo;
			} else {
				$logo = $oBrewery->breweryphoto;
			}

			$oUser = new User();
			$oUser->load($oStatus->userid);
			
			$oLocation = new Location();
			$oLocation->load($oStatus->locationid);
			
			$sHTML .= '<div class="sideitem">';
				$sHTML .= '<div class="sideitemLogo">
							<a href="'.$domain.$oBeer->slug.'">
							<img class="minilogo" src="'.$domain.'assets/images/'.$logo.'"/>
							</a></div>';
				$sHTML .= '<div class="sideitemText">';
							if($rating!=0){
				$sHTML .=	'<span class="stars">'.$starsHTML.$blankstarsHTML.'</span>';
							}
				$sHTML .= '<a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a> 
							checked in <a href="'.$domain.$oBeer->slug.'">'.$oBeer->breweryname.' '.$oBeer->title.'</a> 
							at <a href="'.$domain.'location/'.$oLocation->slug.'">'.$oLocation->locationname.'</a> '.time2str($oStatus->created_at).' 
							<a href="'.$domain.$oBeer->slug.'">details <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
							</div>';
							

				//$sHTML .= '<div class="clearfix"></div>';
			$sHTML .= '</div>';
			}
		
		$sHTML .= '';
		
		return $sHTML;	
	}
	
	static public function renderActivityStream($aLikeStream,$getlimit,$domain){
			$sHTML = '<div>';
			
			
		}
	
	static public function renderLikeStream($aLikeStream,$getlimit,$domain){
		
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
			$breweryphoto = $domain."assets/images/hound.png";

				if(isset($oBrewery->breweryphoto)){
					$breweryphoto = $oBrewery->breweryphoto;
				}
				
				if ($oBeer->photo){
					$beerphoto = $domain."assets/images/".$oBeer->photo;
				} else {
					$photo = $domain."assets/images/hound.png";
				}
				
				if($oBeer->breweryID>1){
					$breweryphoto = $domain."assets/images/".$oBrewery->breweryphoto;
				}

			$sHTML .= '<p class="commentFormat">';
			
				//Show Beer Logo
				if($oBeer->photo!=""){
					$sHTML .= '<a href="'.$domain.$oBeer->slug.'"><img src="'.$beerphoto.'"/> </a>';
				} else {
					$sHTML .= '<a href="'.$domain.$oBeer->slug.'"><img class="breweryphoto" src="'.$breweryphoto.'"/> </a>';
				}
			
			$sHTML .= '<a href="viewuser.php?viewUserID='.$oUser->userID.'">'.$oUser->username.'</a>
			 liked ';
			
			if($oBeer->breweryID>1){
				$sHTML .= ' <a href="'.$domain.$oBrewery->slug.'">'.$oBrewery->breweryname.' </a>';
			} else {
				$sHTML .= ' '.$oBeer->brewery.' ';
			}
			
			
			$sHTML .= '<a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a><br/>'.$timedate.'';
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

		
		$sHTML = '<ul id="listings">';

					
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

			
/*
			// Get Style Names
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
*/
				
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
				$breweryphoto = "assets/images/hound.png";
				
				if($oBrewery->breweryphoto){
					//$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
					$breweryphoto = "thumbs/50x50/images/".$oBrewery->breweryphoto;
					$beerphoto = $breweryphoto;
				}
				
				if ($oBeer->photo){
					//$beerphoto = "assets/images/".$oBeer->photo;
					$beerphoto = "thumbs/50x50/images/".$oBeer->photo;
					// 'http://brewhound.nz/thumbs/' + size + '/images/' + image;
				}
				

				
/*
							echo "<pre>";
							echo "beerphoto ".$beerphoto;
							echo "</pre>";
*/
							
/*
							echo "<pre>";
							echo "breweryphoto ".$breweryphoto;
							echo "</pre>";
*/
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

						$sHTML .='<div class="col itemLogo default"><a href="'.$oBeer->slug.'"><img src="'.$beerphoto.'"/></a></div>';

						//$sHTML .='<div class="col itemLogo default"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';


						$sHTML .= '<div class="col itemTitleCol">';
						
						if($oBeer->breweryID<2){
						$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
						$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';	
						}
						if($oBeer->breweryID>1){
						$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
						$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';	
						}
						
						$sHTML .= '<div class="itemTitle"><a href="'.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
						$sHTML .= '</div>';
										
						$sHTML .='<div class="col itemAlcohol">tapped '.$timedate.'</div>';
						
						$sHTML .= '<div class="col itemLocationCol itemBrewery desktopVisible"><a href="location/'.$oLocation->slug.'">'.$oLocation->locationname.'</a></div>';
						$sHTML .= '<div class="col phoneCol phoneVisible"><a href="location/'.$oLocation->slug.'">'.$oLocation->locationname.'</a></div>';
						//$sHTML .= '<div class="col itemAlcohol"><a href="viewlocation.php?locationID='.$oLocation->locationID.'">'.$oLocation->locationname.'</a></div>';
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


static public function renderActivitySidebar($aAvIDs,$getlimit){		
/*
		echo "<pre>";
		print_r($aAvIDs);
		echo "</pre>";
*/	
		$loggedin = 0;

		$sHTML = '<ul id="listings">';
					
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
	
			$oBrewery = new Brewery();
			$oBrewery->load($oAvail->breweryID);
	
				$beerphoto = "assets/images/hound.png";
				$breweryphoto = "assets/images/hound.png";
				
				if($oBrewery->breweryphoto){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
					$beerphoto = $breweryphoto;
				}
				
				if ($oBeer->photo){
					$beerphoto = "assets/images/".$oBeer->photo;
				}


				$sHTML .= '<li>';
					$sHTML .= '<div class="row">';
		
					$sHTML .='<div class="col itemLogo default"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';


					$sHTML .= '<div class="col itemTitleCol">';
					
					if($oBeer->breweryID<2){
					$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
					$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
					}
					if($oBeer->breweryID>1){
					$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></div>';
					$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></div>';	
					}
					
					$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					$sHTML .= '</div>';
									
					//$sHTML .='<div class="col itemAlcohol">tapped '.$timedate.'</div>';
					
					$sHTML .= '<div class="col itemLocationCol itemBrewery desktopVisible"><a href="viewlocation.php?locationID='.$oLocation->locationID.'">'.$oLocation->locationname.'</a></div>';
					$sHTML .= '<div class="col phoneCol phoneVisible"><a href="viewlocation.php?locationID='.$oLocation->locationID.'">'.$oLocation->locationname.'</a></div>';

				
				$sHTML .= '</div>';
				$sHTML .= '</li>';
			}
			
			$sHTML .= '</ul>';

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
	
	static public function renderFollowersList($aFollowersIDsList,$domain){
	
		$sHTML = "";
		for($iCount=0;$iCount<count($aFollowersIDsList);$iCount++){
			$iFollower = $aFollowersIDsList[$iCount];
			$oUser = new User();
			$oUser->load($iFollower);
			$sHTML .= '<p><a href="'.$domain.'user/'.$oUser->slug.'">'.$oUser->username.'</a></p>';
		}
		echo $sHTML;
	}


	static public function renderFollowingUsers($aFollowingIDsList,$domain){
		$sHTML = "";
		for($iCount=0;$iCount<count($aFollowingIDsList);$iCount++){
			$iFollowing = $aFollowingIDsList[$iCount];
			$oFollowing = new Follow();
			$oFollowing->load($iFollowing);
			//Find correct table to get user from
			if($oFollowing->followuserID!=0){
			$oUser = new User();
			$oUser->load($oFollowing->followuserID);
			$sHTML .= '<p><a href="'.$domain.'user/'.$oUser->slug.'">'.$oUser->username.'</a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'" class="btn btn-default" >unfollow</a></p> ';
			}	
		}
	echo $sHTML;
	}


	static public function renderFollowinglocation($aFollowingIDsList,$domain){
		$sHTML = "";
		for($iCount=0;$iCount<count($aFollowingIDsList);$iCount++){
			$iFollowing = $aFollowingIDsList[$iCount];
			$oFollowing = new Follow();
			$oFollowing->load($iFollowing);
			//Find correct table to get user from
			if($oFollowing->followlocationID!=0){
			$oLocation = new Location();
			$oLocation->load($oFollowing->followlocationID);
			$sHTML .= '<p><a href="'.$domain.'location/'.$oLocation->slug.'">'.$oLocation->locationname.' </a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'&slug='.$oLocation->slug.'" class="btn btn-default">unfollow</a></p> ';
			}
		}
	echo $sHTML;
	}

	static public function renderFollowingbrewery($aFollowingIDsList,$domain){
		$sHTML = "";
		for($iCount=0;$iCount<count($aFollowingIDsList);$iCount++){
			$iFollowing = $aFollowingIDsList[$iCount];
			$oFollowing = new Follow();
			$oFollowing->load($iFollowing);
			//Find correct table to get user from
			if($oFollowing->followbreweryID!=0){
					$oBrewery = new Brewery();
					$oBrewery->load($oFollowing->followbreweryID);
					$sHTML .= '<p><a href="'.$domain.'brewery/'.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'&slug='.$oBrewery->slug.'" class="btn btn-default" >unfollow</a></p> ';
			}
		}
	echo $sHTML;
	}

	static public function renderFollowingList($aFollowingIDsList,$domain){
		
/*
		echo "<pre>";
		print_r($aFollowingIDsList);
		echo "</pre>";
*/
		
			
		$sHTML = "";
		
		for($iCount=0;$iCount<count($aFollowingIDsList);$iCount++){
			$iFollowing = $aFollowingIDsList[$iCount];
			$oFollowing = new Follow();
			$oFollowing->load($iFollowing);
			
			//Find correct table to get user from
			
			if($oFollowing->followbreweryID!=0){
					$oBrewery = new Brewery();
					$oBrewery->load($oFollowing->followbreweryID);
					$sHTML .= '<p><a href="'.$domain.'brewery/'.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'&slug='.$oBrewery->slug.'" class="btn btn-default" >unfollow</a></p> ';
			}

			
			if($oFollowing->followlocationID!=0){
			$oLocation = new Location();
			$oLocation->load($oFollowing->followlocationID);
			$sHTML .= '<p><a href="'.$domain.'location/'.$oLocation->slug.'">'.$oLocation->locationname.' </a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'&slug='.$oLocation->slug.'" class="btn btn-default">unfollow</a></p> ';
			}
			
			if($oFollowing->followuserID!=0){
			$oUser = new User();
			$oUser->load($oFollowing->followuserID);
			$sHTML .= '<p><a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a> <a href="followupdate.php?removefollow=true&followID='.$oFollowing->followID.'" class="btn btn-default" >unfollow</a></p> ';
			}			
			
		}



	echo $sHTML;
	
	}

	static public function renderFollowingListBasic($aFollowingIDsList,$domain){
		
/*
		echo "<pre>";
		print_r($aFollowingIDsList);
		echo "</pre>";
*/
		
			
		$sHTML = "";
		
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
					$sHTML .= '<p><a href="'.$domain.'brewery/'.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></p> ';
			}

			
			if($oFollowing->followlocationID!=0){
			$oLocation = new Location();
			$oLocation->load($oFollowing->followlocationID);
			$sHTML .= '<p><a href="'.$domain.'location/'.$oLocation->slug.'">'.$oLocation->locationname.' </a></p> ';
			}
			
			if($oFollowing->followuserID!=0){
			$oUser = new User();
			$oUser->load($oFollowing->followuserID);
			$sHTML .= '<p><a href="'.$domain.'user/'.$oUser->slug.'">'.$oUser->username.' </a></p> ';
			}			
			
		}



	echo $sHTML;
	
	}

		static public function renderBeerDebug($oBeer,$likeStatus,$userID,$aBeerlocations,$aExistingData,$domain){

		}
		
		static public function renderBeer($oBeer,$likeStatus,$userID,$aBeerlocations,$aExistingData,$domain){
				$iBeerID = $oBeer->beerid;
				

				
				if(isset($_SESSION["UserID"])){
					$userID=$_SESSION["UserID"];
					$iLocationManagerID = $_SESSION["LocationManagerID"];
					$iLocationID = $iLocationManagerID;		
					$aBeersAvLoc = Availability::loadBeerIDs($iLocationID);
					
					$avid = Availability::findAvID($iBeerID,$iLocationID);
					
				}
							
			$slug = $oBeer->slug;

/*
				echo "<pre>";
				print_r($aBeersAvLoc);
				echo "</pre>";
*/
				
				$oStyle = new Style();
				$oStyle->load($oBeer->styleID);
				
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				
				$beerphoto = "assets/images/hound.png";
				$breweryphoto = "assets/images/hound.png";

				if(isset($oBrewery->breweryphoto)){
					$breweryphoto = $oBrewery->breweryphoto;
				}
				
				if ($oBeer->photo){
					$beerphoto = "thumbs/300x300/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID>1 && $oBrewery->breweryphoto!=''){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
				
			//$sHTML = '<div class="wrapper clearfix">';
			
							$sHTML = '<div class="col-md-8 brewdetails">';
				$sHTML .= '<div class="col-md-12">';
						$sHTML .= '<div class="brewinfo">';
						if($oBeer->breweryID>1){
							//$sHTML .= '<div class="breweryphoto"><a href="viewbrewery.php?breweryID='.$oBeer->breweryID.'"><img class="breweryphoto" src="'.$breweryphoto.'"/></a></div>';
							$sHTML .= '<div class="itemBrewery"><h4><a href="brewery/'.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></h4></div>';
						} else {
							$sHTML .= '<div class="itemBrewery"><h4>'.$oBeer->brewery.'</h4></div>';
						}
								
						$sHTML .= 	'<div class="brewTitle"><h2>'.$oBeer->title.'</h2></div>';
							$sHTML .= '<ul class="specs">';
							if($oBeer->exclusive==1){
								$sHTML .= '<li class="tapOnly"><a href="/exclusive.php">Tap only</a></li>';
							}
							if($oBeer->freshhop==1){
								$sHTML .= '<li class="tapOnly"><a href="/freshhop">Fresh Hop</a></li>';
							}
							if($oBeer->styleID!=1){
								$sHTML .= '<li> Style: '.$oStyle->stylename.' </li>';	
							}
							if($oBeer->alcohol!=""){
							$sHTML .= '<li class="">ABV: '.$oBeer->alcohol.'%</li>';
							}
							if($oBeer->ibu!=""){
							$sHTML .= '<li class="">IBU: '.$oBeer->ibu.'</li>';
							}
							$sHTML .= '</ul>';
						$sHTML .= '</div>';
						
						$sHTML .= '<div class="beerphotocontainer">';
							if($oBeer->photo!=""){
								$sHTML .= '<img class="image" src="'.$beerphoto.'"/>';
							} else {
								$sHTML .= '<img class="breweryphoto" src="'.$breweryphoto.'"/>';
							}
						$sHTML .= '</div>';
						$sHTML .= '<div class="clearfix"></div>';					
					$sHTML .= '</div>';
			
			
			$sHTML .= '<div class="col-md-12">';
						
					$sHTML .= 	'<div class="itemDescription"><p>'.$oBeer->description.'</p></div>';	
						
						// SHOW EDIT & ADD IF LOGGED IN	
						
						$oFormLocAv = new Form();
						
						$oFormLocAv->makeLocAvButtons("Available at:","Locations",Location::lists(),$aBeerlocations,$iBreweryID,$oBeer->beerid,$slug);
						
						//$oFormLocAv->makeSubmit("Add Location","submit");
						
						
						if ($userID==true){
							$sHTML .= '<div class="adminButtons">';
							$sHTML .= '<a class="btn btn-primary" role="button" data-toggle="collapse" href="#addAvailList" aria-expanded="false" aria-controls="collapseExample">Add/Remove Location <i class="fa fa-bars"></i></a>';
							$sHTML .= '<div class="collapse" id="addAvailList">';
							$sHTML .= $oFormLocAv->html;
							$sHTML .= '</div>';
							$sHTML .= '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit details</a>';
							
							if($iLocationManagerID>1) {
								$edit = "";
								//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
								if($iLocationManagerID>1){
									if (in_array($iBeerID, $aBeersAvLoc)) {
									//$addremove = '<a class="btn btn-danger remove" href="'.$domain.'removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
									$addremove = '<a class="btn btn-danger remove" href="'.$domain.'removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'&slug='.$slug.'&availableID='.$avid.'&quickremove=true"><i class="fa fa-times"></i></a>';
									} else {
									$addremove = '<a class="btn btn-success remove" href="'.$domain.'addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'&breweryID='.$iBreweryID.'&slug='.$slug.'&quickadd=true"><i class="fa fa-plus"></i></a>';	
									}
								} else {
								$add = "";
								}
							} else {
								$edit = "";
							}

						if($iLocationID>1){
							$sHTML .= $addremove;
						}
				
							$sHTML .= '</div>';
						}

					
					$sHTML .= '</div>';
					
				$sHTML .= 	'</div>';
			
			
			
			
			
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

	static public function renderAllBeers($oAllBeers,$loggedin,$userID,$iBeersPerPage,$currentpage,$domain){

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
				$breweryphoto = "assets/images/hound.png";

				
				if ($oBeer->photo){
					$beerphoto = $domain."assets/images/".$oBeer->photo;
				} elseif ($oBrewery->breweryphoto){
					$breweryphoto = $domain."assets/images/".$oBrewery->breweryphoto;
				} else {
					$beerphoto = $domain."assets/images/hound.png";
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
				$sHTML .='<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
				} else {
				$sHTML .='<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$beerphoto.'"/></a></div>';
				}

								$sHTML .= '<div class="col itemTitleCol">';
								
								if($oBeer->breweryID<2){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';	
								}
								if($oBeer->breweryID>1){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';	
								}
								
								$sHTML .= '<div class="itemTitle"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
								$sHTML .= '</div>';
										
				if($oBeer->styleID!=1){
					//$sHTML .= '<div class="desktopVisible"><a href="">'.$oStyle->stylename.'</a></div>';	
					$sHTML .= '<div class="itemBrewery desktopVisible">'.$oStyle->stylename.'</div>';
					$sHTML .= '<div class="col phoneCol phoneVisible">'.$oStyle->stylename.'</div>';
					
				}
				
				

												
				//$sHTML .= 				'<div class="col itemLikes"><i class="fa fa-heart"> ('.count($oBeer->likes).')</i></div>';
				$sHTML .= 				'<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>';
										//<div class="col item-edit">'.$edit.'</div>
				// Show +/-
				if($iLocationID>1){
						$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
				}						
				
				$sHTML .=				'</div>
							</li>';
			}
			
			$sHTML .= '</ul>';
			
			if($currentpage=="home"){
				// dont show pagination
				$sHTML .= '<a href="brews.php" class="btn btn-default">View all brews</a>';
			} else {
				$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
				$iNext =  $iPage + 1;
				$iPrev = $iPage - 1;
				$sHTML .= '<p>';
				if($iPage>1){
				$sHTML .= '<a href="'.'brews.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';	
				}
				if($iPage=$iTotalPages){
				$sHTML .= ' <a href="'.'brews.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
				}
			}

			$sHTML .= '</p>';
			
			//$sHTML .= '</div>';
			return $sHTML;
		}


static public function renderFreshHopBeers($aFreshHop,$iBeersPerPage,$pagination,$domain){
	
	if ($pagination==1){
				$iPage = 1;
				
				if(isset($_GET["page"])){
					$iPage	= $_GET["page"];
				}
				
				$iTotalPages = ceil(count($aFreshHop) / $iBeersPerPage);
				$iStartIndex = ($iPage - 1) * $iBeersPerPage ;
				
				$iEndIndex = $iStartIndex + $iBeersPerPage;
				if($iEndIndex > count($aFreshHop)){
					$iEndIndex = count($aFreshHop);
				}
	} else {
		$iStartIndex = 0;
		$iEndIndex = $iBeersPerPage;
	}
	
	$sHTML = '<div id="Exclusive">';
	for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
		$oBeerID = $aFreshHop[$iCount];
		$oBeer = new Beer();
		$oBeer->load($oBeerID);
		
		// Get Meta
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
					$beerphoto = "assets/images/hound.png";
					$breweryphoto = "";
					$breweryphoto = $oBrewery->breweryphoto;
					if($breweryphoto==""){} 
					if ($oBeer->photo!=""){$beerphoto = "assets/images/".$oBeer->photo;} else {$beerphoto = "assets/images/hound.png";}
					if($oBeer->breweryID!=0){$breweryphoto = "assets/images/".$oBrewery->breweryphoto;}
				
				
				$av = count(Availability::loadLocationIDs($oBeerID));
				
				
				$sHTML .= '<li>
								<div class="row">';
					
					
				if($beerphoto=="assets/images/hound.png"){
				$sHTML .='<div class="col itemLogo"><a href="'.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
				} else {
				$sHTML .='<div class="col itemLogo"><a href="'.$oBeer->slug.'"><img src="'.$beerphoto.'"/></a></div>';
				}

								$sHTML .= '<div class="col itemTitleCol desktopVisible">';
								
									if($oBeer->breweryID<2){
									$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
									}
									if($oBeer->breweryID>1){
									$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
									}
									
									$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
								$sHTML .= '</div>';
								
								$sHTML .= '<div class="col itemTitleColWide phoneVisible">';
								
									if($oBeer->breweryID<2){
									$sHTML .= '<div class="itemBrewery"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
									}
									if($oBeer->breweryID>1){
									$sHTML .= '<div class="itemBrewery"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
									}
									
									$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
									
								$sHTML .= '</div>';
								
								$sHTML .= '<div class="col item phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$av.' Taps';
								$sHTML .= '</a></div>';
										
				if($oBeer->styleID!=1){
					$sHTML .= '<div class="desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oStyle->stylename.'</a></div>';	
				}
										
				$sHTML .= 				'<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>
								</div>
							</li>';
		
		}
		if ($pagination==1){
				$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
				$iNext =  $iPage + 1;
				$iPrev = $iPage - 1;
				$sHTML .= '<p>';
				if($iPage>1){
				$sHTML .= '<a href="'.'freshhop.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';	
				}
				if($iPage<$iTotalPages){
				$sHTML .= ' <a href="'.'freshhop.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
				}
				$sHTML .= '</p>';
		}
	$sHTML .= '</div>';
	return $sHTML;
	
	}




static public function renderExclusiveBeers($aExclusive,$iBeersPerPage,$pagination,$domain){
	
	if ($pagination==1){
				$iPage = 1;
				
				if(isset($_GET["page"])){
					$iPage	= $_GET["page"];
				}
				
				$iTotalPages = ceil(count($aExclusive) / $iBeersPerPage);
				$iStartIndex = ($iPage - 1) * $iBeersPerPage ;
				
				$iEndIndex = $iStartIndex + $iBeersPerPage;
				if($iEndIndex > count($aExclusive)){
					$iEndIndex = count($aExclusive);
				}
	} else {
		$iStartIndex = 0;
		$iEndIndex = $iBeersPerPage;
	}
	
	$sHTML = '<div id="Exclusive">';
	for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
		$oBeerID = $aExclusive[$iCount];
		$oBeer = new Beer();
		$oBeer->load($oBeerID);
		
		// Get Meta
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
					$beerphoto = "assets/images/hound.png";
					$breweryphoto = "";
					$breweryphoto = $oBrewery->breweryphoto;
					if($breweryphoto==""){} 
					if ($oBeer->photo!=""){$beerphoto = "assets/images/".$oBeer->photo;} else {$beerphoto = "assets/images/hound.png";}
					if($oBeer->breweryID!=0){$breweryphoto = "assets/images/".$oBrewery->breweryphoto;}
				
				
				$av = count(Availability::loadLocationIDs($oBeerID));
				
				
				$sHTML .= '<li>
								<div class="row">';
					
					
				if($beerphoto=="assets/images/hound.png"){
				$sHTML .='<div class="col itemLogo"><a href="'.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
				} else {
				$sHTML .='<div class="col itemLogo"><a href="'.$oBeer->slug.'"><img src="'.$beerphoto.'"/></a></div>';
				}

								$sHTML .= '<div class="col itemTitleCol desktopVisible">';
								
									if($oBeer->breweryID<2){
									$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
									}
									if($oBeer->breweryID>1){
									$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
									}
									
									$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
								$sHTML .= '</div>';
								
								$sHTML .= '<div class="col itemTitleColWide phoneVisible">';
								
									if($oBeer->breweryID<2){
									$sHTML .= '<div class="itemBrewery"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
									}
									if($oBeer->breweryID>1){
									$sHTML .= '<div class="itemBrewery"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
									}
									
									$sHTML .= '<div class="itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
									
								$sHTML .= '</div>';
								
								$sHTML .= '<div class="col item phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$av.' Taps';
								$sHTML .= '</a></div>';
										
				if($oBeer->styleID!=1){
					$sHTML .= '<div class="desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oStyle->stylename.'</a></div>';	
				}
										
				$sHTML .= 				'<div class="col itemAlcohol">'.$oBeer->alcohol.'% ABV</div>
								</div>
							</li>';
		
		}
		if ($pagination==1){
				$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';
				$iNext =  $iPage + 1;
				$iPrev = $iPage - 1;
				$sHTML .= '<p>';
				if($iPage>1){
				$sHTML .= '<a href="'.'exclusive.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';	
				}
				if($iPage<$iTotalPages){
				$sHTML .= ' <a href="'.'exclusive.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
				}
				$sHTML .= '</p>';
		}
	$sHTML .= '</div>';
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


		static public function renderUser($oUser,$domain){

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
				$iViewUserID = $oUser->userID;

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
					<a class="btn btn-default" href="'.$domain.'followupdate.php?addfollow=true&revu=true&fu='.$oUser->userID.'&slug='.$oUser->username.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingUserIDsList($aFollowingIDsList);
					
					//check if user viewed is already on follow list
					if(in_array($iViewUserID, $aUserIDsCurrentlyFollowing)){
						// match > show unfollow
						$sHTML .= '<div class="">
						<a class="btn btn-default" 
						href="'.$domain.'viewuseradmin.php">
						unfollow</a></div>';
					} else {
						// no match show follow button
						$sHTML .= '<div class="">
						<a class="btn btn-default" href="'.$domain.'followupdate.php?addfollow=true&revu=true&fu='.$oUser->userID.'">
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
					<p class="">First Name: '.$oUser->firstname.'</p>
					<p class="">Last Name: '.$oUser->lastname.'</p>';
					// <div class=""><a href="">Email: '.$oUser->email.'</a></div>'
			$sHTML .= '<p class=""><a href="edituser.php" class="btn btn-default">edit profile</a></p>';
			$sHTML .= '<p class="quicklinks">
<a class="btn btn-success" href="addstatusupdate">Check In <i class="fa fa-check-square" aria-hidden="true"></i></a> <a class="btn btn-info" href="addstatusupdate">Rate <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></a> <a class="btn btn-warning" href="addstatusupdate">Review <i class="fa fa-pencil" aria-hidden="true"></i></a> <a type="button" class="btn btn-primary" href="addstatusupdate"><i class="fa fa-picture-o" aria-hidden="true"></i> Upload Photo</a>
</p>';
			
			//$sHTML .= '<div class=""><a href="viewuser.php?viewUserID='.$oUser->userID.'" class="btn btn-default">view public profile</a></div>';
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
		
		static public function renderSearchResults($aBeers,$loggedin,$userID,$domain){
			
			
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
				$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$beerphoto.'"/></a></div>';
				}	else {
					$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
				}
										
								$sHTML .= '<div class="col itemTitleCol">';
																		
								if($oBeer->breweryID<2){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';	
								}
								if($oBeer->breweryID>1){
								$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
								$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';	
								}

								//$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								//$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
								$sHTML .= '<div class="itemTitle"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
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
		
	
	
	static public function rendermostLiked($oAllBeers,$show,$domain){
			
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
					$photo = $domain."assets/images/".$oBeer->photo;
				} else {
					$breweryphoto = $domain."assets/images/$breweryphoto";
				}
				$sHTML .= '<li>
								<div class="row">';
										
					if($oBeer->photo){
						$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$photo.'"/></a></div>';
					} else {
						$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
					}
					
										
										
					$sHTML .= '				<div class="col itemTitleColSmall">
											<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>
											<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>
											<div class="itemTitle"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a></div>
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

	
	static public function renderAvailability($aExistinglocations,$domain){
		//$sHTML = '<div class="wrapper clearfix">';
		
		
		
		$sHTML = '<h4>Available on tap from:</h4>';
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aExistinglocations);$iCount++){
			$oLocation = new Location();
			$oLocation->load($aExistinglocations[$iCount]);
			//$oLocation = $aExistinglocations[$iCount];
			$sHTML .= '<li>';
				$sHTML .= '<div class="row">';
					if($oLocation->fills==1){
						$sHTML .=  '<div class="col-icon"><i class="fa fa-shopping-cart"></i></div>';
					}
					$sHTML .=  '<div class="col itemTitle desktopVisible"><a href="'.$domain.'location/'.$oLocation->slug.'"><b>'.$oLocation->locationname.'</b></div>';
					$sHTML .=  '<div class="phoneFullWidth phoneVisible"><a href="'.$domain.'location/'.$oLocation->slug.'"><b>'.$oLocation->locationname.'</b></div>';
					$sHTML .=  '<div class="col item-brewery desktopVisible">'.$oLocation->locationsuburb.', '.$oLocation->locationregion.'</a></div>';
				$sHTML .= '</div>';
				$sHTML .= '</li>';
			}
		$sHTML .= '</ul>';
		//$sHTML .= '</div>';
		return $sHTML;
	}
	
	static public function renderLocation($oLocation,$domain){
		
		$sHTML = '<div class="wrapper clearfix locationDetails">';
		$sHTML .= '<h3>'.$oLocation->locationname.'</h3>';
		$sHTML .= '<div class="solidline"></div>';
			$sHTML .= '<div class="col-md-6">';
				
/*
				if($_SERVER['REQUEST_URI']=="/viewuseradmin.php"){

				} else {
*/
				
				$sHTML .= '<div class="contactDetails">'.$oLocation->locationaddress.'</div>';
				$sHTML .= '<div class="contactDetails">'.$oLocation->locationsuburb.' '.$oLocation->locationregion.'</div>';
				$sHTML .= '<div class="contactDetails">Phone: <a href="tel:'.$oLocation->locationcontact.'">'.$oLocation->locationcontact.'</a></div>';
				$sHTML .= '<div class="contactDetails"><a target="_blank" href="http://'.$oLocation->locationwebsite.'">'.$oLocation->locationwebsite.'</a></div>';
				if($oLocation->fills==1){
					$sHTML .=  '<div class="contactDetails"><i class="fa fa-shopping-cart locationspec"> Off-license</i></div>';
				}
				
				
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
					<a class="btn btn-default" href="'.$domain.'followupdate.php?addfollow=true&revl=true&fl='.$oUser->userID.'&slug='.$oLocation->slug.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingLocationIDsList($aFollowingIDsList);
					
					//check if user viewed is already on follow list
					if(in_array($iLocationID, $aUserIDsCurrentlyFollowing)){
						// match > show unfollow - get follow id
						
						$followID = FollowManager::getFollowingLocationIDByUSer($oUser->userID,$oLocation->locationID);

						$sHTML .= '<div class="">
						<a class="btn btn-default" 
						href="'.$domain.'followupdate.php?removefollow=true&followID='.$followID.'&slug='.$oLocation->slug.'">
						unfollow</a></div>';
					} else {
						// no match show follow button
						$sHTML .= '<div class="">
						<a class="btn btn-default" href="'.$domain.'followupdate.php?addfollow=true&revl=true&fl='.$oLocation->locationID.'&slug='.$oLocation->slug.'">
						follow</a></div>';	
					}

				}
			}
			
			// END FOLLOW checks...
			
// 			} 
			// close if on admin page
			
			
				$sHTML .= '<h4>Followed by</h4>';
						
				$aFollowingIDsList = FollowManager::getFollowersIDsList($oLocation->locationID,'followLocationID');
				
				foreach($aFollowingIDsList as $follower){
					$oUser = new User();
					$oUser->load($follower);
					$sHTML .= '<span><a class="pil" href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a></span> ';
				}
	
			
			
			$sHTML .= '</div>';
			
			
			$sHTML .= '<div class="col-md-6">';
			if($oLocation->locationinfo!=""){
				$sHTML .= '<div class="">';
					$sHTML .= '<h5>Info</h5>';
					$sHTML .= '<p>';
					$sHTML .= $oLocation->locationinfo;
					$sHTML .= '<p>';
				$sHTML .= '</div>';
			}
			if($oLocation->locationevents!=""){
				$sHTML .= '<div class="locationEvents">';
					$sHTML .= '<h5>Events</h5>';
					$sHTML .= '<p>';
					$sHTML .= $oLocation->locationevents;
					$sHTML .= '<p>';
				$sHTML .= '</div>';
			}
						if($oLocation->claimstatus==0){
						$sHTML .= '<div class="spacer"></div>';
						$sHTML .= '<div class="claim">Are you the owner of this location?<br/>Claim your free management account now.</div>';
						$sHTML .= '<div class="claim"><a class="btn btn-default" href="'.$domain.'about.php">Claim</a></div>';
			}
			$sHTML .= '<div id="map" class="smallmap"></div>';
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
	
	static public function renderBrewery($oBrewery,$domain){
		
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
				$photo = $domain."thumbs/300x300/images/".$oBrewery->breweryphoto;
				} else {
				$photo = $domain."assets/images/hound.png";
				}
			
			$sHTML .= '<div class="breweryimage"><img src="'.$photo.'"/></div>';


			$sHTML .= '</div>';
		
			// DETAILS
			$sHTML .= '<div class="col-md-6">';
				//$sHTML .= '<h3>'.$oBrewery->breweryname.'</h3>';
				$sHTML .= '<h3>'.$oBrewery->fullbreweryname.'</h3>';
				$sHTML .= '<div class="">Address: '.$oBrewery->breweryaddress.'</div>';
				$sHTML .= '<div class="contactDetails">Website: <a href="http://'.$oBrewery->brewerywebsite.'">'.$oBrewery->brewerywebsite.'</a></div>';


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

			$aFollowingIDsList = FollowManager::getFollowersIDsList($oBrewery->breweryID,'followBreweryID');
		
			if($loggedin==1){
				//check list has items
				if(empty($aFollowingIDsList)){
					// not following anyone
					
					// show follow button
					$sHTML .= '<div class="">
					<a class="btn btn-default" href="'.$domain.'followupdate.php?addfollow=true&revb=true&fb='.$iBreweryID.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					//$aUserIDsCurrentlyFollowing = FollowManager::getFollowingBreweryIDsList($aFollowingIDsList);
					

						//check if user viewed is already on follow list
						if(in_array($iUserID, $aFollowingIDsList)){
							// match > show unfollow
							$sHTML .= '<div class="">
							<a class="btn btn-default" 
							href="'.$domain.'followupdate.php?removefollowB=true&fb='.$iBreweryID.'&slug='.$oBrewery->slug.'">
							unfollow</a></div>';
						} else {
							// no match show follow button
							$sHTML .= '<div class="">
							<a class="btn btn-default" href="'.$domain.'followupdate.php?addfollow=true&revb=true&fb='.$iBreweryID.'">
							follow</a></div>';	
						}

				}
			}
	
			$sHTML .= '<h4>Followed by</h4>';
			
			foreach($aFollowingIDsList as $follower){
				$oUser = new User();
				$oUser->load($follower);
				$sHTML .= '<span><a class="pil" href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a></span> ';
			}
	
			
			// END FOLLOW checks...
}
				
			$sHTML .= '</div>';
			
			// CLAIM
			$sHTML .= '<div class="col-md-4 mt2">';
			if($oBrewery->claimstatus==0){
					$sHTML .= '<div class="claim">Are you the owner of this brewery?<br/>';
					$sHTML .= 'Claim your free management account now.</div>';
					$sHTML .= '<div class="claim"><a class="btn btn-default" href="'.$domain.'about.php">Claim</a></div>';
			}
		$sHTML .= '</div>';

		$sHTML .= '</div>';
		return $sHTML;
		}

	static public function renderAllLocations($aLocations,$domain){
		
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
	
		//$sHTML = '<div class="wrapper clearfix">';
		
			$totalTaps = Availability::totalTaps();
			$totalLoc = Location::all();
			//$sHTML .= '<p><a class="btn btn-default" href="brews.php">'.$totalTaps.' taps</a></p>'; 


		$sHTML = '<ul id="listings" class="locations">';
		for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
			$location = $aLocations[$iCount];
			$oLocation = new Location();
			$oLocation->load($location->locationID);
		
			//$aBeersAvailable = Availability::loadBeers($oLocation->locationID);
		
			//$taps = count($aBeersAvailable);
			
			$sHTML .= '<a href="'.$domain.'location/'.$oLocation->slug.'"><li><div class="row">';
// 				$sHTML .= '<div class="col item-logo"></div>';

				if($oLocation->fills==1){
					$sHTML .=  '<div class="contactDetails"><i class="fa fa-shopping-cart locationspec"></i></div>';
				}
				

				$sHTML .= '<div class="col itemTitle desktopVisible"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="col itemTitleCol phoneVisible"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="col phoneCol phoneVisible">'.$oLocation->locationsuburb.'</div>';
				
				$sHTML .= '<div class="col responsive desktopVisible">'.$oLocation->locationsuburb.'</div>';
				$sHTML .= '<div class="col responsive desktopVisible">'.$oLocation->locationregion.'</div>';
				
				//$sHTML .= '<div class="col responsive desktopVisible"><b>'.$taps.'</b> Taps listed</div>';
				//$sHTML .= '<div class="col phoneCol phoneVisible"><b>'.$taps.'</b> Taps</div>';
				
			$sHTML .= '</li></a>';	
		}

		if($iTotalPages>1){		
			$sHTML .= '<p>Page '.$iPage.' of '.$iTotalPages.'<p>';	
			$sHTML .= '<p>';
			if($iPage!=1){
			$sHTML .= '<a href="'.$domain.'viewlocations.php?page='.$iPrev.'"'.' class="btn btn-default">prev</a> ';
			}
			if($iPage!=$iTotalPages){
			$sHTML .= ' <a href="'.$domain.'viewlocations.php?page='.$iNext.'"'.' class="btn btn-default">next</a>';
			}
			$sHTML .= '</p>';
		}
			
		$sHTML .= '</ul>';
		return $sHTML;
	}

	static public function renderAllBreweries($aBreweries,$domain){
		
		$iTotalBreweries = count($aBreweries);
		// Pagination
/*
		$iPage = 1;

		
		if(isset($_GET["page"])){
			$iPage	= $_GET["page"];
		}
		$iNext =  $iPage + 1;
		$iPrev = $iPage - 1;
		
		$iBreweriesPerPage = 20;
		$iTotalPages = ceil(count($aBreweries) / $iBreweriesPerPage);
		
		$iStartIndex = ($iPage - 1) * $iBreweriesPerPage ;
		
		$iEndIndex = $iStartIndex + $iBreweriesPerPage;
		if($iEndIndex > count($aBreweries)){
			$iEndIndex = count($aBreweries);
		}
*/
	
		$sHTML = '<div class="wrapper clearfix">';
		
		$sHTML .= "<p>We're indexing more breweries every day - so far we've got $iTotalBreweries</p>";
		$sHTML .= '<p>If you\'re the owner of a Brewery and want to manage your listings please contact us <a class="btn btn-default" href="../about.php">contact</a></p>';
		
		$sHTML .= '<ul id="listings" class="locations">';
		for($iCount=0;$iCount<count($aBreweries);$iCount++){
		//for($iCount=$iStartIndex;$iCount<$iEndIndex;$iCount++){
			// do nothing on first loop > select=>0 placeholder
			if($iCount>0){
			
				$iBrewery = $aBreweries[$iCount];
				$oBrewery = new Brewery();
				$oBrewery->load($iBrewery->breweryID);
				
				//$iTotalAvail = Availability::loadBrewery($iBrewery->breweryID);
				//$iTotalAvail = count($iTotalAvail);
				
				$iTotalAvail = Availability::getTapCount($iBrewery->breweryID);
				
				$breweryphoto = "thumbs/50x50/images/".$oBrewery->breweryphoto;
				//$aBeersAvailable = Availability::loadBeers($oLocation->locationID);
			
				//$taps = count($aBeersAvailable);
				
				$sHTML .= '<li><div class="row">';
					$sHTML .='<div class="col itemLogo"><a href="'.$domain.'brewery/'.$oBrewery->slug.'"><img src="'.$domain.$breweryphoto.'"/></a></div>';
	
					$sHTML .= '<div class="col itemTitle desktopVisible"><h3><a href="'.$domain.'brewery/'.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></h3></div>';
					$sHTML .= '<div class="col itemTitleCol phoneFullWidth phoneVisible"><h3><a href="'.$domain.'brewery/'.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></h3></div>';
					//$sHTML .= '<div class="col responsive desktopVisible">'.$oBrewery->locationsuburb.'</div>';
					//$sHTML .= '<div class="col responsive desktopVisible">'.$oBrewery->locationregion.'</div>';
					$sHTML .= '<div class="col itemTitle desktopVisible">'.$iTotalAvail.' Brews Available</div>';
					$sHTML .= '<div class="col item-edit  phoneVisible">'.$iTotalAvail.'</div>';
					
				$sHTML .= '</li>';
			}
				
		}

/*
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
*/
			
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
		//$sHTML = '<div class="wrapper clearfix">';
		
		if(count($aLikes>0)){
		$sHTML = '<h4>Beers Liked:</h4>';
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
					$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
				} elseif ($beerphoto!="") {
					$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';
				} else {
					$sHTML .= '<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$placeholder.'"/></a></div>';
				}
				//<div class="col itemLogo"><a href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>
				$sHTML .='<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</div>';
			if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol"><a href="">'.$oStyle->stylename.'</a></div>';}
			$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
			$sHTML .= '</div>';
			$sHTML .= '</li>';
			}
		$sHTML .= '</ul>';
		//$sHTML .= '</div>';
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

static public function renderBeersByBrewery($aBeersAvailable,$domain){
	
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


		$sHTML = '<ul id="listings">';
		for($iCount=0;$iCount<count($aBeersAvailable);$iCount++){
			$oBeer = $aBeersAvailable[$iCount];
			// Get Style Names
			
			if($oBeer->breweryID!=1){
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
				$breweryphoto = $domain."thumbs/50x50/images/".$oBrewery->breweryphoto;
				//$breweryphoto = $domain."assets/images/".$oBrewery->breweryphoto;
			}


			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			$iBeerID = $oBeer->beerID;
			
			$iTotalTaps = Availability::totalBreweryTaps($iBeerID);
			
			if($iTotalTaps==1){
				$taps = 'tap';
			} else {
				$taps = 'taps';
			}
			
			if ($oBeer->photo){
					$photo = $domain."thumbs/50x50/images/".$oBeer->photo;
				} else {
					$photo = $domain."assets/images/hound.png";
				}
		$sHTML .= '<li>
				<div class="row">';
		
		if($oBeer->photo!=''){
			$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$photo.'"/></a></div>';
		} elseif($oBrewery->breweryphoto!='') {
			$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
		} else {
			$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$photo.'"/></a></div>';
		}

		$sHTML .= '<div class="col itemTitleCol">';
		if($oBeer->breweryID!=1){
			$sHTML .= '								<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '							<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
		} else	{
			$sHTML .= '								<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '							<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
		}								
											
		$sHTML .= '								<div class="itemTitle"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '							</div>';
		
				
// 		$sHTML .= '<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
					if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol">'.$oStyle->stylename.'</div>';}
				$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
				$sHTML .='<div class="col desktopVisible">'.$iTotalTaps.' '.$taps.'</a></div>';
			
				if($iLocationManagerID>1){}else{
				$sHTML .='<div class="col phoneVisible">'.$iTotalTaps.' '.$taps.'</a></div>';}
				// show admin buttons
				if($loggedin==1) {
					$edit = "";
					//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
					if($iLocationManagerID>1){
						if (in_array($iBeerID, $aBeersAvLoc)) {
						$addremove = '<a class="btn btn-danger remove" href="'.$domain.'removeLocAv.php?userID='.$userID.'&locationID='.$iLocationID.'&beerID='.$iBeerID.'"><i class="fa fa-times"></i></a>';
						} else {
						$addremove = '<a class="btn btn-success remove" href="'.$domain.'addLocAv.php?beerID='.$oBeer->beerID.'&locationID='.$iLocationID.'&userID='.$userID.'&breweryID='.$iBreweryID.'"><i class="fa fa-plus"></i></a>';	
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
		
		return $sHTML;
	}




static public function renderAvailableData($aAvailableIDs,$iLocationID,$claimstatus,$domain){

/*
	echo "<pre>";
	print_r($aAvailableIDs);
	echo "</pre>";
*/

		// $oLocation->claimstatus==0
		//$iLocationID=0;
		$iLocationManagerID=0;
		$userID = 0;
		
		if (isset($_SESSION["UserID"])==false){
			$loggedin = 0;
		} else {
			$loggedin = 1;
			$userID=$_SESSION["UserID"];
			$iLocationManagerID = $_SESSION["LocationManagerID"];
			$iBreweryManagerID = $_SESSION["BreweryManagerID"];
			// set for getting $aBeersAvLoc
			//$iLocationID = $iLocationManagerID;
		}
			
		$aBeersAvLoc = Availability::loadBeerIDs($iLocationID);
		$taps = count($aAvailableIDs);
		
		$sHTML = '<div class="wrapper clearfix">';
		
			if(isset($_GET['orderbybrewery'])){
	$sHTML .= 'Sorted by Brewery <a class="btn btn-default right" href="'.$domain.'viewlocation.php?locationID='.$iLocationID.'">Sort by Freshness</a>  * these breweries are not yet indexed';
	} else {
	$sHTML .= 'Sorted by Freshness <a class="btn btn-default" href="'.$domain.'viewlocation.php?locationID='.$iLocationID.'&orderbybrewery=1">Sort by Brewery</a>';
	}

			
		//$sHTML .= $iLocationID;
		//$sHTML .= "logged in = ".$loggedin;
		if($taps>1){
			$sHTML .= '<h3>'.$taps.' Brews:</h3>';
		} elseif ($taps<1){
			// do nothing
		} else {
			$sHTML .= '<h3>'.$taps.' Brew:</h3>';
		}
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aAvailableIDs);$iCount++){

			$iAvailableID = $aAvailableIDs[$iCount];
	
			$oAvailability = new Availability();
			$oAvailability->load($iAvailableID);
			
			// Resident Status		
			$residentStatus = $oAvailability->residentstatus;
			
			$oBeer = new Beer();
			$oBeer->load($oAvailability->beerID);

			$timedate = time2str($oAvailability->date);
			
			// Get Style Names
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
	
			// default
			$breweryphoto = "assets/images/hound.png";
			// if beer photo
			if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
			}
			
			if($oBrewery->breweryphoto){
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
			} 
			
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			
			$iBeerID = $oBeer->beerID;

			
		$sHTML .= '<li>
				<div class="row">';
		
		if($oAvailability->breweryID==1){
			$sHTML .= '*';	
		}
			
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$domain.$photo.'"/></a></div>';
		} else {
			$sHTML .= '<div class="col itemLogo"><a href="'.$domain.$oBeer->slug.'"><img src="'.$domain.$breweryphoto.'"/></a></div>';
		} 

		$sHTML .= '<div class="col itemTitleCol locationData">';
		if($oBeer->breweryID<2){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
		} else	{
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
		}								
											
		$sHTML .= '<div class="itemTitle desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '<div class="itemTitle phoneVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
		
		$sHTML .= '</div>';
/*
		if($iLocationManagerID==70){
			$sHTML .= '<div class="itemBrewery phoneVisible">'.$timedate.'</a></div>';
				
			}else{
				$sHTML .='';
				
			}
*/
		if($residentStatus==0){
			$sHTML .='<div class="col desktopVisible">'.$timedate.'</div>';
			$sHTML .='<div class="col phoneVisible">'.$timedate.'</div>';
		} else {
			$sHTML .='<div class="col desktopVisible">always on tap</div>';
			$sHTML .='<div class="col phoneVisible phoneCol">always on tap</div>';
		}
		
		
		if($loggedin==1) {
			if($residentStatus==0){
				$sHTML .='<div class="col desktopVisible"><a href="'.$domain.'addResident.php?availableID='.$iAvailableID.'&locationID='.$iLocationID.'" class="btn btn-success"><i class="fa fa-home"></i></a></div>';
				$sHTML .='<div class="col phoneVisible phoneCol adminButton"><a href="'.$domain.'addResident.php?beerID='.$iAvailableID.'&locationID='.$iLocationID.'" class="btn btn-success"><i class="fa fa-home"></i></a></div>';
			} else {
				$sHTML .='<div class="col desktopVisible"><a href="'.$domain.'removeResident.php?availableID='.$iAvailableID.'&locationID='.$iLocationID.'" class="btn btn-danger"><i class="fa fa-home"></i></a></div>';
				$sHTML .='<div class="col phoneVisible phoneCol adminButton"><a href="'.$domain.'removeResident.php?availableID='.$iAvailableID.'&locationID='.$iLocationID.'" class="btn btn-danger"><i class="fa fa-home"></i></a></div>';
			}
		}
		
// 		$sHTML .= '<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		if($oBeer->styleID!=1){$sHTML .= '<div class="col itemAlcohol">'.$oStyle->stylename.'</div>';}
			$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</a></div>';
		
		
/*
	if(isset($_GET['locationID'])){
		if($_GET['locationID']==5){
				echo "<pre>";
				echo $iAvailableID;
				echo "</pre>";
		}
	}
*/
		
				
		// show admin buttons
		if($loggedin==1) {
			$edit = "";
				//$edit = '<a class="btn btn-default" href="editbeer.php?beerID='.$oBeer->beerID.'">edit</a>';
				if($iLocationManagerID>1){
					if (in_array($iBeerID, $aBeersAvLoc)) {
						$addremove = '<a class="btn btn-danger remove" href="'.$domain.'removeLocAv.php?userID='.$userID.'&availableID='.$iAvailableID.'"><i class="fa fa-times"></i></a>';		
							} else {
							$addremove = '<a class="btn btn-success remove" href="'.$domain.'addLocAv.php?userID='.$userID.'&availableID='.$iAvailableID.'"><i class="fa fa-plus"></i></a>';	
					}
				} else {
				$add = "";
				}
			} else {
				$edit = "";
			}
			
/*
			$sHTML .='LocationManagerID = '.$iLocationManagerID;
			$sHTML .='BeerID = '.$iBeerID;
*/
			if($iLocationManagerID>1){
					$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
			}	
				
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		
		
////////////////// ERRRRRRRORRRRR
		
		
		//$sHTML .= "claimstatus = ".$claimstatus;
		if($claimstatus==0){
		$sHTML .= '<p>Please note: Freshness is a good indication of availability, although popular kegs often only last a matter of days. <br/>
		If a location is unclaimed then listings are crowd sourced and may not be current. If you\'d like to contribute then please <a href="/register.php" class="btn btn-default nomargin">sign up</a> and do so - cheers! </p>';
		}
		$sHTML .= '</div>';
		return $sHTML;
	}


static public function embedAvailableData($aAvailableIDs,$iLocationID,$claimstatus){
	
/*
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";
*/
	
	
		
	// http://brewhound.nz/embedlocation.php?locationID=81&date=hide&style=hide&ABV=hide
	
	// Default to show
	$date = 'show';
	$style = 'show';
	$ABV = 'show';
	$cols=1;
	
	if(isset($_GET["cols"])){
		if($cols = 2){
			echo '<style> @media (min-width: 600px) {
			#listings li {  
							width: 49%;
							float: left;
							margin-right: 1%;
							line-height: 1em;
			}
			#listings .row {
							height:5em;
			}
			#listings .itemTitleCol {
				margin-left: 0;
				}
			}
			</style>';
		}
	}
	
		if(isset($_GET["date"])){
			$date = 'hide';
		}

		if(isset($_GET["style"])){
		$style = 'hide';
		}
		
		if(isset($_GET["ABV"])){
		$ABV = 'hide';
		}
		
		// $oLocation->claimstatus==0
		//$iLocationID=0;
		$iLocationManagerID=0;
			$userID = 0;
		if (isset($_SESSION["UserID"])==false){
			$loggedin = 0;
		} else {
			$loggedin = 1;
			$userID=$_SESSION["UserID"];
			$iLocationManagerID = $_SESSION["LocationManagerID"];
			$iBreweryManagerID = $_SESSION["BreweryManagerID"];
			// set for getting $aBeersAvLoc
			//$iLocationID = $iLocationManagerID;
		}
			
		$aBeersAvLoc = Availability::loadBeerIDs($iLocationID);
		$taps = count($aAvailableIDs);

		$sHTML = '<div class="wrapper clearfix">';
		
		//$sHTML .= $iLocationID;
		//$sHTML .= "logged in = ".$loggedin;
		if($taps>1){
			$sHTML .= '<h3>'.$taps.' Brews:</h3>';
		} elseif ($taps<1){
			// do nothing
		} else {
			$sHTML .= '<h3>'.$taps.' Brew:</h3>';
		}
		$sHTML .= '<ul id="listings">';
		for($iCount=0;$iCount<count($aAvailableIDs);$iCount++){
			$iAvailableID = $aAvailableIDs[$iCount];
	
			$oAvailability = new Availability();
			$oAvailability->load($iAvailableID);
			
			$oBeer = new Beer();
			$oBeer->load($oAvailability->beerID);
			
			$timedate = time2str($oAvailability->date);
			
			// Get Style Names
				$iBreweryID = $oBeer->breweryID;
				$oBrewery = new Brewery();
				$oBrewery->load($iBreweryID);
			
			// default
			$breweryphoto = "assets/images/hound.png";
			// if beer photo
			if ($oBeer->photo){
					$photo = "assets/images/".$oBeer->photo;
			}
			
			if($oBrewery->breweryphoto){
				$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
			} 
			
			$oStyle = new Style();
			$oStyle->load($oBeer->styleID);
			$iBeerID = $oBeer->beerID;

		$sHTML .= '<li>
				<div class="row">';
		
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a target="_blank" href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$photo.'"/></a></div>';
		} else {
			$sHTML .= '<div class="col itemLogo"><a target="_blank" href="viewbeer.php?beerID='.$oBeer->beerID.'"><img src="'.$breweryphoto.'"/></a></div>';
		} 

		$sHTML .= '<div class="col itemTitleCol">';
		if($oBeer->breweryID<2){
			$sHTML .= '<div class="itemBrewery desktopVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';
		} else	{
			$sHTML .= '<div class="itemBrewery desktopVisible"><a target="_blank" href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '<div class="itemBrewery phoneVisible"><a target="_blank" href="viewbrewery.php?breweryID='.$oBrewery->breweryID.'">'.$oBrewery->breweryname.'</a></div>';
		}								
											
		$sHTML .= '<div class="itemTitle"><a target="_blank" href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		$sHTML .= '</div>';
/*
		if($iLocationManagerID==70){
			$sHTML .= '<div class="itemBrewery phoneVisible">'.$timedate.'</a></div>';
				
			}else{
				$sHTML .='';
				
			}
*/

		if($date=='show'){
			$sHTML .='<div class="col desktopVisible">'.$timedate.'</a></div>';
		}
// 		$sHTML .= '<div class="col itemTitle"><a href="viewbeer.php?beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
		if($style=='show'){
			if($oBeer->styleID!=1){
				$sHTML .= '<div class="col itemAlcohol">'.$oStyle->stylename.'</div>';
			}
		}
		if($ABV=='show'){	
		$sHTML .='<div class="col itemAlcohol">'.$oBeer->alcohol.'%</div>';
		}
					
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
			
/*
			$sHTML .='LocationManagerID = '.$iLocationManagerID;
			$sHTML .='BeerID = '.$iBeerID;
*/
			if($iLocationManagerID>1){
					$sHTML .='<div class="col item-edit">'.$addremove.'</div>';
			}	
				
				$sHTML .= '</div>';		
			}
		$sHTML .= '</ul>';
		
		//$sHTML .= "claimstatus = ".$claimstatus;
		if($claimstatus==0){
		$sHTML .= '<p>Please note: Freshness is a good indication of availability, although popular kegs often only last a matter of days. <br/>
		If a location is unclaimed then listings are crowd sourced and may not be current. If you\'d like to contribute then please <a href="/register.php" class="btn btn-default nomargin">sign up</a> and do so - cheers! </p>';
		}
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
		$sHTML = '<h4>Reviews:</h4>';
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
	
	
		//$sHTML = '<div class="wrapper clearfix">';
		$sHTML = '<h5>Reviews:</h5>';
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
		//$sHTML .= '</div>';
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
	
	
	static public function renderNews($oNews){

			$sHTML = '<h3>'.$oNews->newstitle.'</h3>';
			
			$sHTML .= '<article class="newsArticle">';
				$sHTML .= $oNews->newscontent;
				$sHTML .= '<div class="imageContainer"><a href="'.$oNews->newsimagelinkaddress.'"><img src="'.$oNews->newsimagelink.'"/></a></div>';
			$sHTML .= '</article>';
			

		

		return $sHTML;
	}
	
	static public function rendernewsfeed($aNews){
	$sHTML = '<div class="">';
	for($iCount=0;$iCount<count($aNews);$iCount++){
			// Load user data
			$oNews = new News();
			$oNews->load($aNews[$iCount]);
			$sHTML .= '<h5><a href="viewnews.php?newsID='.$oNews->newsID.'">'.$oNews->newstitle.'</a></h5>';
	}
	
	
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
				$sHTML .= '<a href="viewlocations.php?suburb=Westlynn" class="btn btn-default">Westlynn</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Westmere" class="btn btn-default">Westmere</a>';
				
				$sHTML .= '<h5>Rest of NZ</h5>';
				$sHTML .= '<a href="viewlocations.php?suburb=Christchurch" class="btn btn-default">Christchurch</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Hamilton" class="btn btn-default">Hamilton</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Leigh" class="btn btn-default">Leigh</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mangawhai" class="btn btn-default">Mangawhai</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Matakana" class="btn btn-default">Matakana</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Mt%20Maunganui" class="btn btn-default">Mt Maunganui</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Rotorua" class="btn btn-default">Rotorua</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Tauranga" class="btn btn-default">Tauranga</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Waihi%20Beach" class="btn btn-default">Waihi Beach</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Waipu" class="btn btn-default">Waipu</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Warkworth" class="btn btn-default">Warkworth</a>';
				$sHTML .= '<a href="viewlocations.php?suburb=Wellington" class="btn btn-default">Wellington</a>';

			$sHTML .= '</div>';
			
		$sHTML .= '</div>';
	return $sHTML;
	}
	
	
}
?>