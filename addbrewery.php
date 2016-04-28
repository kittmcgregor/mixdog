<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
	
/*
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
*/

	    $oForm = new Form();
		
		if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
		$oForm->checkRequired("BreweryName");
		//$oForm->checkRequired("BreweryAddress");
		
		if($oForm->valid==true){
			// insert data to database to create new page
			$oNewBrewery = new Brewery();
	
				$rawurl = $_POST["BreweryWebsite"];
				$clensedurl = str_replace('http://', '', $rawurl);
	
				// set values;
			if($_FILES["photo"]["error"] == 0){
				if($_FILES["photo"]["type"] == "image/jpeg"){
					
				$newName = $_FILES["photo"]["name"].time().'.jpg';
				}
				if($_FILES["photo"]["type"] == "image/png"){
				$newName = $_FILES["photo"]["name"].time().'.png';
				}
				if($_FILES["photo"]["type"] == "image/gif"){
				$newName = $_FILES["photo"]["name"].time().'.gif';
				}
				$oForm->moveFile("photo",$newName);
				$oNewBrewery->breweryphoto = $newName;
			}
			$oNewBrewery->breweryname = $_POST["BreweryName"];
			$oNewBrewery->fullbreweryname = $_POST["FullBreweryName"];
				$createslug = strtolower(str_replace(array('&','+','.',"'",'(',')'),"",str_replace(' ','-',$oNewBrewery->breweryname)));
			$oNewBrewery->slug = $createslug;
			$oNewBrewery->brewerywebsite = $clensedurl;
			$oNewBrewery->breweryaddress = $_POST["BreweryAddress"];
				
			$oNewBrewery->save();
			$iNewBreweryID = $oNewBrewery->breweryID;
			

			
			$oUser = new User();
			$oUser->load($_SESSION["UserID"]);
			
// Send email notification to admin
			$to      = 'webmaster@brewhound.nz';
			$subject = 'Brewhound: Brewery Added';
			
$message = '<html><body>';
$message = '<p>A new brewery was added at brewhound.nz</p>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr style='background: #eee;'><td><strong>Title:</strong> </td><td>" . strip_tags($_POST["BreweryName"]) . "</td></tr>";
$message .= "<tr><td><strong>Address:</strong> </td><td>".strip_tags($_POST["BreweryAddress"])."</td></tr>";
$message .= "</table>";
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
			
			
			// redirect to success page
			header("location:addbrewery.php?brewerysuccess=true&newBreweryID=".$iNewBreweryID);
			exit;

		}
		
		
	}

	$oForm->makeTextInput("Short Brewery Name (required)","BreweryName","displayed above brew name");
	$oForm->makeTextInput("Full Brewery Name (required)","FullBreweryName","displayed on brewery profile");
	$oForm->makeTextInput("Website","BreweryWebsite","mysite.com");	
	$oForm->makeTextInput("Address","BreweryAddress","");
	$oForm->makeFileInput("Upload Photo","photo");
	$oForm->makeSubmit("Add Brewery","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Add Brewery: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>