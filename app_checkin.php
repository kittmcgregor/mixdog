
<!-- We don't need full layout here, because this page will be parsed with Ajax-->
<!-- Top Navbar-->
<div class="navbar">
  <div class="navbar-inner">
    <div class="left"><a href="#" class="back link"> <i class="icon icon-back"></i><span>Back</span></a></div>
    <div class="center sliding">Latest</div>
    <div class="right">
      <!-- Right link contains only icon - additional "icon-only" class--><a href="#" class="link icon-only open-panel"> <i class="icon icon-bars"></i></a>
    </div>
  </div>
</div>
<div class="pages">
  <!-- Page, data-page contains page name-->
  <div data-page="checkin" class="page">
    <!-- Scrollable page content-->
    <div class="page-content">
      <div class="content-block">
        <div class="content-block-inner">

<?php
	ob_start(); // for redirection to work
	require_once'app_includes/form.php';

/*
	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
	
	
	$oUsername = new User();
	$id = $_SESSION["UserID"];
	$oUsername->load($id);
	$username = $oUsername->username;
*/

	
/*
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
*/
		
		if(isset($_GET['brew'])){
			$brewid = $_GET['brew'];
		}
		
	    $oForm = new Form();
	    $rating = "";
	    
		
		if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
	//	$rating = $_POST["rating"];
		
		$oForm->checkRequired("beerid");
		$oForm->checkRequired("locationid");
		
		if($oForm->valid==true){
			// insert data to database to create new page
			$oNewStatusUpdate = new Status();
	
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
				$oNewStatusUpdate->photo = $newName;
			}
	
	
	
			// set values;
			$oNewStatusUpdate->userid = $_SESSION["UserID"];
			
			$oNewStatusUpdate->beerid = $_POST["beerid"];
			$oNewStatusUpdate->locationid = $_POST["locationid"];

			$oNewStatusUpdate->rating = $_POST["rating"];
			
			$oNewStatusUpdate->review = $_POST["review"];
			
			$oNewStatusUpdate->save();
			
			$redirectSlug = $_GET["name"];

			
			$brew = new Beer();
			$brew->load($_POST["beerid"]);
			$title = $brew->title;
			$brewery = $brew->breweryname;
			
			$table = 'followUserID';
			$aFollowersMailList = FollowManager::getFollowersMailList($id,$table);
			
				echo "<pre>";
				print_r($aFollowersMailList);
				echo "</pre>";

			
			foreach($aFollowersMailList as $contact){
				
			// Send email notification to follower
			$to      = $contact;
			$subject = "Brewhound: Activity Notification";
						
			$message = '<html><body>';
			$message .= '<p><a href="'.$domain.'user/'.strip_tags($username).'">'.strip_tags($username).'</a> added a checkin</p>';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Brew:</strong> </td><td>" . strip_tags($title) . "</td></tr>";
			$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($brewery)."</td></tr>";
			$message .= "<tr><td><strong>Rating:</strong> </td><td>".strip_tags($_POST["rating"])."/5</td></tr>";
			$message .= "</table>";
			$message .= "<p><b>Review:</b> ".strip_tags($_POST["review"])."</p>";
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
				
			}
					
			if($_GET["name"]){
				header("location:http://brewhound.nz/$redirectSlug");
				exit;
			}
			
			// redirect to success page
			header("location:$domain/user/$username?success=true");
			exit;

		}
		
		
	}

	$oForm->makeAutoInput("Brew:","beerselect","brew");
	//$oForm->makeHiddenSelectInput("#beerid ","beerid","");

	$oForm->makeTextInput("Location","locationselect","location");
	//$oForm->makeHiddenSelectInput("#locationid ","locationid","");
	
	//$oForm->makeRatingSelectInput("Rating","rating","");
	$oForm->makeSliderInput("Rating","rating",array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'),$rating);
	//$oForm->makeRatingRadioInput("Rating","rating",array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'),$rating);
	//$oForm->makeTextArea("Review","review","Tasting notes, full critque or just a comment...");
	
	//$oForm->makeFileInput("Photo","photo","");
	$oForm->makeSubmit("Submit","submit");
?>
  
<!--   <button data-toggle="modal" data-target="#addbrewajax" class="btn btn-primary">add new</button> -->
  
 <div class="item-content"> 
      <div class="item-inner">

            <h2>Checkin: </h2>
            <hr>
<!--
            <div class="list-block">
			  <ul>
-->
			  	<?php echo $oForm->html; ?>
			  	
			  	  	
			  	
<!--
			  </ul>
            </div>
-->

    </div>
</div>

<!-- Modal -->
<!--
<div class="modal fade" id="addbrewajax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Brew</h4>
      </div>
      <div class="modal-body">
        <div id="ajaxContent"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
-->
  
        </div>
      </div>
    </div>
  </div>
</div>

