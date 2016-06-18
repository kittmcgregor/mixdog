 <?php
class View{

	static public function renderUserAdmin($oUser){
	
				$sHTML = '<div class="itemTitle"><h2>username: '.$oUser->username.'</h2></div>
					<p class="">First Name: '.$oUser->firstname.'</p>
					<p class="">Last Name: '.$oUser->lastname.'</p>';
					// <div class=""><a href="">Email: '.$oUser->email.'</a></div>'
			$sHTML .= '<p class=""><a href="edituser.php" class="btn btn-default">edit profile</a></p>';
						
			//$sHTML .= '<div class=""><a href="viewuser.php?viewUserID='.$oUser->userID.'" class="btn btn-default">view public profile</a></div>';

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
			$sHTML .= '<a class="button" id="logout">logout</a>';
			//$sHTML .= '</div>';
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
	
							$sHTML .='<div class="col itemLogo default"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'&beerID='.$oBeer->beerID.'"><img src="'.$beerphoto.'"/></a></div>';	
	
							$sHTML .= '<div class="col itemTitleCol">';
							
							if($oBeer->breweryID<2){
							$sHTML .= '<div class="itemBrewery phoneVisible"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'&beerID='.$oBeer->beerID.'">'.$oBeer->brewery.'</a></div>';	
							}
							if($oBeer->breweryID>1){
							$sHTML .= '<div class="itemBrewery phoneVisible"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'&beerID='.$oBeer->beerID.'">'.$oBrewery->breweryname.'</a></div>';	
							}
							
							$sHTML .= '<div class="itemTitle"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'&beerID='.$oBeer->beerID.'">'.$oBeer->title.'</a></div>';
							$sHTML .= '</div>';
											
							$sHTML .='<div class="col itemAlcohol">tapped '.$timedate.'</div>';
							
							$sHTML .= '<div class="col itemLocationCol itemBrewery desktopVisible"><a href="app_viewlocation.php?slug='.$oLocation->slug.'&beerID='.$oBeer->beerID.'">'.$oLocation->locationname.'</a></div>';
							$sHTML .= '<div class="col phoneCol phoneVisible"><a class="open-preloader" href="app_viewlocation.php?slug='.$oLocation->slug.'&beerID='.$oBeer->beerID.'">'.$oLocation->locationname.'</a></div>';
			
					
					$sHTML .= '</div>';
					$sHTML .= '</li>';
				}
				
				$sHTML .= '</ul>';
	

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
			$sHTML .= '<li>
				<div class="row">
					<div class="col itemTitle desktopVisible"><a href="app_viewlocation.php?slug='.$oLocation->slug.'"><b>'.$oLocation->locationname.'</b></div>
					<div class="phoneFullWidth phoneVisible"><a class="open-preloader" href="app_viewlocation.php?slug='.$oLocation->slug.'"><b>'.$oLocation->locationname.'</b></div>
					<div class="col item-brewery desktopVisible">'.$oLocation->locationsuburb.', '.$oLocation->locationregion.'</a></div>
					
				</div>
				</li>';
			}
		$sHTML .= '</ul>';
		//$sHTML .= '</div>';
		return $sHTML;
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
					$beerphoto = "assets/images/".$oBeer->photo;
				} else {
					$photo = "assets/images/hound.png";
				}
				
				if($oBeer->breweryID>1){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				}
				
			//$sHTML = '<div class="wrapper clearfix">';
			
							//$sHTML = '<div class="col-md-8 brewdetails">';
				$sHTML = '<div class=".col-100">';
						$sHTML .= '<div class="brewinfo">';
						if($oBeer->breweryID>1){
							//$sHTML .= '<div class="breweryphoto"><a href="viewbrewery.php?breweryID='.$oBeer->breweryID.'"><img class="breweryphoto" src="'.$breweryphoto.'"/></a></div>';
							$sHTML .= '<div class="itemBrewery"><h4><a class="open-preloader" href="app_viewbrewery.php?slug='.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></h4></div>';
						} else {
							$sHTML .= '<div class="itemBrewery"><h4>'.$oBeer->brewery.'</h4></div>';
						}
								
						$sHTML .= 	'<div class="brewTitle"><h2>'.$oBeer->title.'</h2></div>';
							$sHTML .= '<ul class="specs">';
							if($oBeer->exclusive==1){
								$sHTML .= '<li class="tapOnly">';
								//$sHTML .= '<a href="/exclusive.php">';
								$sHTML .= 'Tap only';
								//$sHTML .= '</a>';
								$sHTML .= '</li>';
							}
							if($oBeer->freshhop==1){
								$sHTML .= '<li class="tapOnly">';
								//$sHTML .= '<a href="/freshhop">';
								$sHTML .= 'Fresh Hop';
								//$sHTML .= '</a>';
								$sHTML .= '</li>';
							}
							if($oBeer->styleID!=1){
								$sHTML .= '<li> Style: '.$oStyle->stylename.' </li>';	
							}
							if($oBeer->alcohol!=""){
							$sHTML .= '<li class="">ABV: '.$oBeer->alcohol.'%</li>';
							}
							$sHTML .= '</ul>';
						$sHTML .= '</div>';
						
						$sHTML .= '<div class="beerphotocontainer">';
							if($oBeer->photo!=""){
								$sHTML .= '<img class="image" src="'.$beerphoto.'"/>';
							} else {
								$sHTML .= '<img class="breweryphoto" src="'.$breweryphoto.'"/>';
							}
						//$sHTML .= '</div>';
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
			
			
			
			
			
/*
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
				$sHTML .= '<a class="button" role="button" data-toggle="collapse" href="#">Like</a>';	
				
			
				$sHTML .= '</div>';	
							

							
					}
					$sHTML .= '</p>';
*/


					//$sHTML .= '</div>';
				
				//$sHTML .= '</div>';
				
				
				//$sHTML .= '</div>';	
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

	static public function renderCheckins($aStatusUpdates,$beerID,$domain){
		
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
				

				$sHTML .= '</div>';
				
				$sHTML .= '<div class="collapse" id="details'.$iCount.'">';
				$sHTML .= '<h4>Review/Comments</h4>';			
				$sHTML .= '<p>'.$oStatus->review.'</p>';
				if($oStatus->photo){
					$sHTML .= '<img style="max-width:100%" src="http://brewhound.nz/assets/images/'.$photo.'"/>';	
				}
			
				$sHTML .= '</div>';	
				
				
			$sHTML .= '</div>';
			
			
			}
		
		$sHTML .= '';
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
			
			$sHTML = '';
			//$sHTML = '<div class="wrapper clearfix">';
			
	
				
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
				
				$oBrew = new Beer();
				$oBrew->load($oAvailability->beerID);
	
				$timedate = time2str($oAvailability->date);
				
				// Get Style Names
					$iBreweryID = $oBrew->breweryID;
					$oBrewery = new Brewery();
					$oBrewery->load($iBreweryID);
		
				// default
				$breweryphoto = "assets/images/hound.png";
				// if beer photo
				if ($oBrew->photo){
						$photo = "assets/images/".$oBrew->photo;
				}
				
				if($oBrewery->breweryphoto){
					$breweryphoto = "assets/images/".$oBrewery->breweryphoto;
				} 
				
				$oStyle = new Style();
				$oStyle->load($oBrew->styleID);
				
				$iBeerID = $oBrew->beerID;
	
				
			$sHTML .= '<li>
					<div class="row">';
			
			if($oAvailability->breweryID==1){
				$sHTML .= '*';	
			}
				
			if($oBrew->photo){
				$sHTML .= '<div class="col itemLogo"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBrew->slug.'"><img src="'.$domain.$photo.'"/></a></div>';
			} else {
				$sHTML .= '<div class="col itemLogo"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBrew->slug.'"><img src="'.$domain.$breweryphoto.'"/></a></div>';
			} 
	
			$sHTML .= '<div class="col itemTitleCol locationData">';
			if($oBrew->breweryID<2){
				$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBrew->slug.'">'.$oBrew->brewery.'</a></div>';
				$sHTML .= '<div class="itemBrewery phoneVisible"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBrew->slug.'">'.$oBrew->brewery.'</a></div>';
			} else	{
				$sHTML .= '<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBrew->slug.'">'.$oBrewery->breweryname.'</a></div>';
				$sHTML .= '<div class="itemBrewery phoneVisible"><a class="open-preloader" href="app_viewbrewery.php?slug='.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></div>';
			}								
												
			$sHTML .= '<div class="itemTitle desktopVisible"><a href="'.$domain.$oBrew->slug.'">'.$oBrew->title.'</a></div>';
			$sHTML .= '<div class="itemTitle phoneVisible"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBrew->slug.'">'.$oBrew->title.'</a></div>';
			
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
			if($oBrew->styleID!=1){$sHTML .= '<div class="col itemAlcohol">'.$oStyle->stylename.'</div>';}
				$sHTML .='<div class="col itemAlcohol">'.$oBrew->alcohol.'%</a></div>';
			
			
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
			//$sHTML .= '</div>';
			return $sHTML;
		}

	static public function renderLocation($oLocation,$domain){
		
		$sHTML = '<div class="content-block">';
        $sHTML .= '<div class="content-block-inner">';
        
		//$sHTML = '<div class="wrapper clearfix locationDetails">';
		$sHTML .= '<h3>'.$oLocation->locationname.'</h3>';
		$sHTML .= '<div class="solidline"></div>';
			//$sHTML .= '<div class="col-md-6">';
				
/*
				if($_SERVER['REQUEST_URI']=="/viewuseradmin.php"){

				} else {
*/
				
				$sHTML .= '<div class="contactDetails">'.$oLocation->locationaddress.'</div>';
				$sHTML .= '<div class="contactDetails">'.$oLocation->locationsuburb.' '.$oLocation->locationregion.'</div>';
				$sHTML .= '<div class="contactDetails">Phone: <a href="tel:'.$oLocation->locationcontact.'">'.$oLocation->locationcontact.'</a></div>';
				$sHTML .= '<div class="contactDetails"><a target="_blank" href="http://'.$oLocation->locationwebsite.'">'.$oLocation->locationwebsite.'</a></div>';
				
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
				$sHTML .= '<div class=""><a class="button" href="#">follow</a></div>';
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
					<a class="button" href="'.$domain.'followupdate.php?addfollow=true&revl=true&fl='.$oUser->userID.'$slug='.$oLocation->slug.'">
					follow</a></div>';	
					
					} else {
					//get user IDs from list
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingLocationIDsList($aFollowingIDsList);
					
					//check if user viewed is already on follow list
					if(in_array($iLocationID, $aUserIDsCurrentlyFollowing)){
						// match > show unfollow - get follow id
						
						$followID = FollowManager::getFollowingLocationIDByUSer($oUser->userID,$oLocation->locationID);

						$sHTML .= '<div class="">
						<a class="button" 
						href="'.$domain.'followupdate.php?removefollow=true&followID='.$followID.'&slug='.$oLocation->slug.'">
						unfollow</a></div>';
					} else {
						// no match show follow button
						$sHTML .= '<div class="">
						<a class="button" href="'.$domain.'followupdate.php?addfollow=true&revl=true&fl='.$oLocation->locationID.'&slug='.$oLocation->slug.'">
						follow</a></div>';	
					}

				}
			}
			
			// END FOLLOW checks...
			
// 			} 
			// close if on admin page
			
			

						
				$aFollowingIDsList = FollowManager::getFollowersIDsList($oLocation->locationID,'followLocationID');
				


				$sHTML .= '<h4>Followed by</h4>';
				foreach($aFollowingIDsList as $follower){
					$oUser = new User();
					$oUser->load($follower);
					//$sHTML .= '<span><a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a></span> ';
					$sHTML .= '<span><a class="pil" href="'.$domain.'app_viewuserbyusername.php?username='.$oUser->username.'">'.$oUser->username.'</a></span> ';
					
					//$sHTML .= '<div class="chip"><div class="chip-label">Name</div></div>';
					
				}



// collapse accordian				
/*
	$sHTML .= '<div class="content-block accordion-list custom-accordion">';
    $sHTML .= '<div class="accordion-item">';
	    $sHTML .= '<div class="accordion-item-toggle">';
	    	$sHTML .= '<i class="icon icon-plus">+</i>';
			$sHTML .= '<i class="icon icon-minus">-</i>';
			$sHTML .= '<span>Followers</span>';
	    $sHTML .= '</div>';
	   $sHTML .= '<div class="accordion-item-content">';

	    $sHTML .= '</div>';
   $sHTML .= '</div>';
   $sHTML .= '</div>';
*/
	
	
	
			
			//$sHTML .= '</div>';
			
			
			//$sHTML .= '<div class="col-md-6">';
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
/*
						if($oLocation->claimstatus==0){
						$sHTML .= '<div class="spacer"></div>';
						$sHTML .= '<div class="claim">Are you the owner of this location?<br/>Claim your free management account now.</div>';
						$sHTML .= '<div class="claim"><a class="button" href="'.$domain.'about.php">Claim</a></div>';
			}
*/


				$sHTML .= '</div>';
				$sHTML .= '</div>';
				
			$sHTML .= '<div id="map" class="app_smallmap"></div>';
			//$sHTML .= '</div>';
			
			

							
		//$sHTML .= '</div>';
		return $sHTML;
		}

	static public function renderBrewery($oBrewery,$domain){
		
/*
		echo "<pre>";
		print_r($oBrewery);
		echo "</pre>";
*/

		$iBreweryID = $oBrewery->breweryID;
		
		$sHTML = '';
		//$sHTML = '<div class="wrapper clearfix">';
			
			// LOGO
			$sHTML .= '<div class="col-md-2">';
			if ($oBrewery->breweryphoto){
				$photo = $domain."assets/images/".$oBrewery->breweryphoto;
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
				$sHTML .= '<div class="contactDetails">Website: <a class="external link" href="http://'.$oBrewery->brewerywebsite.'">'.$oBrewery->brewerywebsite.'</a></div>';


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
				$sHTML .= '<div class="">
				<a class="button" role="button" href="#" aria-expanded="false" >follow</a>
				</div>';	
				
			}

		
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
					$aUserIDsCurrentlyFollowing = FollowManager::getFollowingBreweryIDsList($aFollowingIDsList);
					
						//check if user viewed is already on follow list
						if(in_array($iBreweryID, $aUserIDsCurrentlyFollowing)){
							// match > show unfollow
							$sHTML .= '<div class="">
							<a class="button" 
							href="'.$domain.'followupdate.php?removefollowB=true&fb='.$iBreweryID.'&slug='.$oBrewery->slug.'">
							unfollow</a></div>';
						} else {
							// no match show follow button
							$sHTML .= '<div class="">
							<a class="button" href="'.$domain.'followupdate.php?addfollow=true&revb=true&fb='.$iBreweryID.'">
							follow</a></div>';	
						}

				}
			}
			
			// END FOLLOW checks...
}
				
			$sHTML .= '</div>';
			
			// CLAIM
/*
			$sHTML .= '<div class="col-md-4 mt2">';
			if($oBrewery->claimstatus==0){
					$sHTML .= '<div class="claim">Are you the owner of this brewery?<br/>';
					$sHTML .= 'Claim your free management account now.</div>';
					$sHTML .= '<div class="claim"><a class="button" href="about.php">Claim</a></div>';
			}
		$sHTML .= '</div>';
*/

		//$sHTML .= '</div>';
		return $sHTML;
		}

	static public function renderBeersByBrewery($aBeersAvailable,$domain){
	
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
		
		if($oBeer->photo){
			$sHTML .= '<div class="col itemLogo"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'"><img src="'.$photo.'"/></a></div>';
		} else {
			$sHTML .= '<div class="col itemLogo"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'"><img src="'.$breweryphoto.'"/></a></div>';
		}

		$sHTML .= '<div class="col itemTitleCol">';
		if($oBeer->breweryID!=1){
			$sHTML .= '								<div class="itemBrewery desktopVisible"><a href="app_viewbrew.php?slug='.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
			$sHTML .= '							<div class="itemBrewery phoneVisible"><a class="open-preloader" href="'.$domain.$oBeer->slug.'">'.$oBrewery->breweryname.'</a></div>';
		} else	{
			$sHTML .= '								<div class="itemBrewery desktopVisible"><a href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
			$sHTML .= '							<div class="itemBrewery phoneVisible"><a class="open-preloader" href="'.$domain.$oBeer->slug.'">'.$oBeer->brewery.'</a></div>';
		}								
											
		$sHTML .= '								<div class="itemTitle"><a class="open-preloader" href="app_viewbrew.php?slug='.$oBeer->slug.'">'.$oBeer->title.'</a></div>';
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

	static public function renderUser($oUser,$domain){

			//$sHTML = '<div class="wrapper clearfix">';
			$sHTML = '<h3>'.$oUser->username.'</h3>';
			
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
				$sHTML .= '<div class="">
				<a class="button">follow</a>
				</div>';
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
	
			//$sHTML .= '</div>';
			return $sHTML;	
		}

	static public function renderStatusUpdates($aStatusUpdates,$domain,$limit){
		
		$sHTML = '';
				
		for($iCount=0;$iCount<count($aStatusUpdates);$iCount++){
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
				$sHTML .= '<div class="sideitemText"><a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a> 
							checked in <a href="'.$domain.$oBeer->slug.'">'.$oBeer->breweryname.' '.$oBeer->title.'</a> 
							at <a href="'.$domain.'location/'.$oLocation->slug.'">'.$oLocation->locationname.'</a> '.time2str($oStatus->created_at).' 
							<a href="'.$domain.$oBeer->slug.'">details <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
							</div>';
							
				if($rating!=0){
					$sHTML .=	'<span class="stars">'.$starsHTML.$blankstarsHTML.'</span>';
				}
				$sHTML .= '<div class="clearfix"></div>';
			$sHTML .= '</div>';
			}
		
		$sHTML .= '';
		return $sHTML;	
	}


	
	static public function renderFollowersList($aFollowersIDsList,$domain){
	
		$sHTML = "";
		for($iCount=0;$iCount<count($aFollowersIDsList);$iCount++){
			$iFollower = $aFollowersIDsList[$iCount];
			$oUser = new User();
			$oUser->load($iFollower);
			$sHTML .= '<p><a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.'</a></p>';
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
					$sHTML .= '<p><a href="'.$domain.'app_viewbrewery.php?slug='.$oBrewery->slug.'">'.$oBrewery->breweryname.'</a></p> ';
			}

			
			if($oFollowing->followlocationID!=0){
			$oLocation = new Location();
			$oLocation->load($oFollowing->followlocationID);
			$sHTML .= '<p><a href="'.$domain.'app_viewlocation.php?slug='.$oLocation->slug.'">'.$oLocation->locationname.' </a></p> ';
			}
			
			if($oFollowing->followuserID!=0){
			$oUser = new User();
			$oUser->load($oFollowing->followuserID);
			//$sHTML .= '<p><a href="'.$domain.'user/'.$oUser->username.'">'.$oUser->username.' </a></p> ';
			$sHTML .= '<p><a href="'.$domain.'app_viewuserbyusername.php?username='.$oUser->username.'">'.$oUser->username.' </a></p> ';
			}			
			
		}

	echo $sHTML;
	
	}


	static public function renderAllLocations($aLocations,$domain){
		
		// Pagination
		//$iPage = 1;

		
/*
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
*/
	
		//$sHTML = '<div class="wrapper clearfix">';
		
/*
			$totalTaps = Availability::totalTaps();
			$totalLoc = Location::all();
*/
			//$sHTML .= '<p><a class="btn btn-default" href="brews.php">'.$totalTaps.' taps</a></p>'; 


		$sHTML = '<ul id="listings" class="locations">';
		for($iCount=0;$iCount<count($aLocations);$iCount++){
			$location = $aLocations[$iCount];
			$oLocation = new Location();
			$oLocation->load($location->locationID);
		
			$aBeersAvailable = Availability::loadBeers($oLocation->locationID);
		
			$taps = count($aBeersAvailable);
			
			$sHTML .= '<a href="app_viewlocation.php?slug='.$oLocation->slug.'"><li><div class="row">';
// 				$sHTML .= '<div class="col item-logo"></div>';
				//$sHTML .= '<div class="col itemTitle desktopVisible"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="col itemTitleCol phoneVisible"><h3>'.$oLocation->locationname.'</h3></div>';
				$sHTML .= '<div class="col phoneCol phoneVisible">'.$oLocation->locationsuburb.'</div>';
				
				//$sHTML .= '<div class="col responsive desktopVisible">'.$oLocation->locationsuburb.'</div>';
				//$sHTML .= '<div class="col responsive desktopVisible">'.$oLocation->locationregion.'</div>';
				
				//$sHTML .= '<div class="col responsive desktopVisible"><b>'.$taps.'</b> Taps listed</div>';
				$sHTML .= '<div class="col phoneCol phoneVisible"><b>'.$taps.'</b> Taps</div>';
				
			$sHTML .= '</li></a>';	
		}

/*
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
*/
			
		$sHTML .= '</ul>';
		return $sHTML;
	}


		
} // end View class
	


	
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
	
	
?>