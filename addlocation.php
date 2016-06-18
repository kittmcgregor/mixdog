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
	    
	    	if(isset($_GET["success"])){
		echo '<div class="wrapper label-success">';
		echo "Location added successfully<br/>";
		echo 'View it <a href="viewlocation.php?locationID='.$_GET["newLocationID"].'">here</a> or add another location below';
		echo '</div>';
		}
		
		if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
		$oForm->checkRequired("LocationName");
		$oForm->checkRequired("LocationSuburb");
		$oForm->checkRequired("LocationRegion");
		
		if($oForm->valid==true){
			// insert data to database to create new page
			$oNewLocation = new Location();
	
	$rawurl = $_POST["LocationWebsite"];
	$clensedurl = str_replace('http://', '', $rawurl);
	
	
			// set values;
			$oNewLocation->locationname = $_POST["LocationName"];
			$createslug = strtolower(str_replace(array(',','&','+','.',"'",'(',')'),"",str_replace(' ','-',$oNewLocation->locationname)));
			$oNewLocation->slug = $createslug;
			$oNewLocation->locationaddress = $_POST["LocationAddress"];
			$oNewLocation->locationsuburb = $_POST["LocationSuburb"];
			$oNewLocation->locationregion = $_POST["LocationRegion"];
			$oNewLocation->locationcontact = $_POST["LocationContact"];
			$oNewLocation->locationwebsite = $clensedurl;
			$oNewLocation->locationinfo = $_POST["LocationInfo"];
			$oNewLocation->save();
			$newLocationID = $oNewLocation->locationID;
			
			$oUser = new User();
			$oUser->load($_SESSION["UserID"]);
			
// Send email notification to admin
			$to      = 'webmaster@brewhound.nz';
			$subject = 'Brewhound: Location Added';
			
$message = '<html><body>';
$message = '<p>A new location was added at brewhound.nz</p>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr style='background: #eee;'><td><strong>Title:</strong> </td><td>" . strip_tags($_POST["LocationName"]) . "</td></tr>";
$message .= "<tr><td><strong>Address:</strong> </td><td>".strip_tags($_POST["LocationAddress"])."</td></tr>";
$message .= "<tr><td><strong>Suburb:</strong> </td><td>".strip_tags($_POST["LocationSuburb"])."</td></tr>";
$message .= "<tr><td><strong>Region:</strong> </td><td>".strip_tags($_POST["LocationRegion"])."</td></tr>";
$message .= "<tr><td><strong>Contact:</strong> </td><td>".strip_tags($_POST["LocationContact"])."</td></tr>";
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
			header("location:addlocation.php?success=true&newLocationID=".$newLocationID);
			exit;

		}
		
		
	}

	$oForm->makeTextInput("Location Name","LocationName","required");
	$oForm->makeTextInput("Address","LocationAddress","");
	$oForm->makeTextInput("Suburb","LocationSuburb","Required");
	$oForm->makeTextInput("Region","LocationRegion","Required: Auckland, Rodney, Northland...");
	$oForm->makeTextInput("Phone","LocationContact","09xxxxxx or +6421xxxxxx");
	$oForm->makeTextInput("Website","LocationWebsite","mysite.com");
	$oForm->makeTextArea("Info","LocationInfo","");
	$oForm->makeCheckboxInput("Fills","fills","1","Off License");
	$oForm->makeSubmit("Add Location","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Add Location: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>