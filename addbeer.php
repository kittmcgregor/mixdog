<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';
	include_once'includes/availabilitymanager.php';

/*
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
*/

/*
			echo "<pre>";
			print_r($_SESSION);
			echo "</pre>";
*/
		//exit;	
	
	$intended = '';
	if($_GET['intended']){
		$intended = $_GET['intended'];
	}
	
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true&intended=".$intended);
	}
	if($_SESSION["LocationManagerID"]!=0){
		$ManagingLocation = $_SESSION["LocationManagerID"];
	}


	$oForm = new Form();
	
	if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
		$oForm->checkRequired("Title");
		//$oForm->checkRequired("Description");
		//$oForm->checkRequired("Style");
		//$oForm->checkRequired("Brewery");
		//$oForm->checkRequired("Alcohol");

				
		if($oForm->valid==true){
			// insert data to database to create new page
			$oNewProduct = new Beer();
			
			
			if($_FILES["photo"]["error"] == 0){
				if($_FILES["photo"]["type"] == "image/jpeg"){
				$rawname = $_FILES["photo"]["name"];
				$removespaces = str_replace(' ', '_', $rawname);
				$removesuffix = str_replace('.jpg', '_', $removespaces);
				$newName = $removesuffix.time().'.jpg';
				}
				if($_FILES["photo"]["type"] == "image/png"){
				$rawname = $_FILES["photo"]["name"];
				$removespaces = str_replace(' ', '_', $rawname);
				$removesuffix = str_replace('.png', '_', $removespaces);
				$newName = $removesuffix.time().'.png';
				}
				if($_FILES["photo"]["type"] == "image/gif"){
				$rawname = $_FILES["photo"]["name"];
				$removespaces = str_replace(' ', '_', $rawname);
				$removesuffix = str_replace('.gif', '_', $removespaces);
				$newName = $removesuffix.time().'.gif';
				}
				
				
				$oForm->moveFile("photo",$newName);
				$oNewProduct->photo = $newName;
			}
			
			// set values;
			$oNewProduct->title = $_POST["Title"];
			$oNewProduct->breweryID = $_POST["BreweryID"];
			
				if($oNewProduct->breweryID<2){
					$brewery = strtolower(str_replace(array('&','+','.',"'",'(',')'),"",str_replace(' ','-',$oBeer->brewery)));
				} else {
					$breweryID = new Brewery();
					$breweryID->load($oNewProduct->breweryID);
				
				$brewery = strtolower(str_replace(array('&','+','.',"'",'(',')'),"",str_replace(' ','-',$breweryID->breweryname)));
				}
				$title = strtolower(str_replace(array(':','+','&','!','#','.',"'",'(',')'),"",str_replace(' ','-',$oNewProduct->title)));
				$createslug = $brewery.'-'.$title;
			$oNewProduct->slug = $createslug;
			$oNewProduct->description = $_POST["Description"];
			//$oNewProduct->newstyle = $_POST["NewStyle"];
			$oNewProduct->styleID = $_POST["StyleID"];
			//$oNewProduct->brewery = $_POST["Brewery"];
			$oNewProduct->alcohol = $_POST["Alcohol"];
			$oNewProduct->alcohol = $_POST["IBU"];
			$oNewProduct->active = 1;
			if($_POST["Exclusive"]!=''){
				$oNewProduct->exclusive = $_POST["Exclusive"];
			} else {
				$oNewProduct->exclusive = 0;
			}
			if($_POST["FreshHop"]!=''){
				$oNewProduct->freshhop = $_POST["FreshHop"];
			} else {
				$oNewProduct->freshhop = 0;
			}

			if($_SESSION["LocationManagerID"]>0){
				$_POST["NewLocation"]=0;
			}
			$oNewProduct->newlocation = $_POST["NewLocation"];
			
				echo "<pre>";
				print_r($oNewProduct);
				echo "</pre>";
			
			// Set BreweryName from $_POST["BreweryID"]
			
			$oBrewery = new Brewery();
			$oBrewery->load($_POST["BreweryID"]);
			$oNewProduct->breweryname = $oBrewery->breweryname;

			
			$oNewProduct->save();
			$newBeerID = $oNewProduct->beerID;
			$pageTitle = $_POST["Title"];
			
			if($_SESSION["LocationManagerID"]>0){
				Availability::updateAvailabilityLocationManager($newBeerID,$_SESSION["LocationManagerID"],$_POST["BreweryID"],$_SESSION["UserID"]);
			} else {
				Availability::updateAvailability($newBeerID,$_POST["Locations"],$_POST["BreweryID"],$_SESSION["UserID"]);
			}

			$oUser = new User();
			$oUser->load($_SESSION["UserID"]);

			// Send email notification to admin
			$to      = 'webmaster@brewhound.nz';
			$subject = "Brewhound: Beer Added";
			
$message = '<html><body>';
$message .= '<p>A new beer was added at brewhound.nz</p>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr style='background: #eee;'><td><strong>Title:</strong> </td><td>" . strip_tags($_POST["Title"]) . "</td></tr>";
//$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($_POST["Brewery"])."</td></tr>";
$message .= "<tr><td><strong>Alcohol:</strong> </td><td>".strip_tags($_POST["Alcohol"])."%</td></tr>";
//$message .= "<tr><td><strong>New Style:</strong> </td><td>".strip_tags($_POST["NewStyle"])."</td></tr>";
$message .= "</table>";
$message .= "<p><b>New Location:</b> ".strip_tags($_POST["NewLocation"])."</p>";
$message .= "<p><b>Description:</b> ".strip_tags($_POST["Description"])."</p>";
$message .= 'Added by: <a href="http://beta.brewhound.nz/viewuser.php?viewUserID='.strip_tags($oUser->userID).'">'.strip_tags($oUser->username).'</a>';
$message .= "</body></html>";

						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
			
			
			// Follower activity
			$iBreweryID = $_POST["BreweryID"];
			//Follow::followActivity($iBreweryID,$newBeerID);
			
			if($_SESSION["LocationManagerID"]!=0){
				// Send location update to followers
					
					$oLocation = new Location();
					$oLocation->load($_SESSION["LocationManagerID"]);

					$title = $_POST["Title"];
					$brewery = $breweryID->breweryname;
					
					$table = 'followLocationID';
					$aFollowersMailList = FollowManager::getFollowersMailList($ManagingLocation,$table);
					
					echo "<pre>";
					print_r($aFollowersMailList);
					echo "</pre>";
					
					foreach($aFollowersMailList as $contact){
						
					// Send email notification to follower
					$to      = $contact;
					$subject = "Brewhound: Activity Notification";
								
					$message = '<html><body>';
					$message .= '<p><a href="'.$domain.'location/'.strip_tags($oLocation->slug).'">'.strip_tags($oLocation->locationname).'</a> tapped a new brew</p>';
					$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
					$message .= "<tr style='background: #eee;'><td><strong>Brew:</strong> </td><td>" . strip_tags($title) . "</td></tr>";
					$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($brewery)."</td></tr>";
					$message .= "</table>";
					$message .= '<p>You can customise your notification settings <a href="http://brewhound.nz/viewuseradmin">here</a></p>';
					$message .= "</body></html>";
		
								
					// In case any of our lines are larger than 70 characters, we should use wordwrap()
					$message = wordwrap($message, 70, "\r\n");
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					
					$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
					    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
					
		/*
						echo "<pre>";
						echo $to;
						echo $message;
						echo "</pre>";
		*/
		
					mail($to, $subject, $message, $headers);
						
					} // end for each
				
			} // end if
			
			// redirect to success page
			header("location:addbeer.php?beerAddedSuccess=true&newBeerID=".$newBeerID);
			exit;

		}
		
		
	}
	

	
	$oForm->makeTextInput("Brew Name","Title","start typing to search existing brews...");
	$oForm->makeTextArea("Description","Description","optional");
	$oForm->makeSelectBreweryInput("Brewery","BreweryID",Brewery::brewerylist());
	//$oForm->makeTextInput('New Brewery - just for this beer? if not then go here: <a class="btn btn-default" href="http://brewhound.nz/addbrewery.php">add brewery</a>',"Brewery","");
	$oForm->makeFileInput("Brew image (Optional)<br/><span class='small'>If not added the existing Brewery image will be used if available.</span>","photo");
	$oForm->makeSelectStyleInput("Style","StyleID",Style::stylelist());
	//$oForm->makeTextInput('New style - just for this beer? if not then go here: <a class="btn btn-default" href="http://brewhound.nz/addstyle.php">add style</a>',"NewStyle","");

	//$oForm->makeTextInput("Brewery ID","BreweryID","");

	
	$oForm->makeTextInputCol50ALC("Alcohol %","Alcohol","");
	$oForm->makeTextInputCol50("IBU","IBU","");
	//$oForm->makeSelectInput("Location","LocationID",Location::lists());
	//$oForm->makeCheckboxInput("Status","Active","1");
	
	$oForm->makeCheckboxInputCol50("Exclusive","Exclusive","1","Tap Only");
	$oForm->makeCheckboxInputCol50("Special Edition","FreshHop","1","Fresh Hop");
	
	if($_SESSION["LocationManagerID"]>0){

	} else {
	 	//$oForm->makeTextInput("Add new Location","NewLocation","New location Name");
	 	$oForm->makeCheckboxInputSet('Select available locations: or <a class="btn btn-default" href="http://brewhound.nz/addlocation.php">add location</a>',"Locations",Location::lists());
	}

	
	$oForm->makeSubmit("Add Brew","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-8">
        <div class="box">
            <h2>Add Brew: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
	
	$('input#Title').focus(
    function(){
        $(this).val('');
    });
$('#Title').autocomplete({
  	source: function( request, response ) {
	  	$('#Title').addClass('loading');
  		$.ajax({
  			url : 'listBrewsMeta.php',
  			sortResults: false,
  			dataType: "json",
  			type: 'Get',
  			data: {term: request.term},
			success: function( data ) {
				$('#Title').removeClass('loading');
				 var array = ( $.map( data, function( item,i ) {
					return {
						label: item,
						value: i
					}
				}));
			//call the filter here
            response($.ui.autocomplete.filter(array, request.term));
			console.log(request.term);
			},
			error: function() {
		         $('.searcherror').html('<p>An error has occurred</p>');
		    }
  		});
  	},
  	select: function(event, ui) {  
	  	//console.log(ui);
	  		location.href=ui.item.value;
             //location.href="viewbeer.php?beerID=" + ui.item.value.replace('-', '');
        } 	
});	
	
/*
  	autoFocus: true,
  	minLength: 0,
*/

</script>