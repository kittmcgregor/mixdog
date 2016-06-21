<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
	
	
	$oUsername = new User();
	$id = $_SESSION["UserID"];
	$oUsername->load($id);
	$username = $oUsername->username;
		
		if(isset($_GET['brew'])){
			$brewid = $_GET['brew'];
		}
		
	    $oForm = new Form();
		
		if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
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
			
			if(isset($_POST["rating"])){
				$oNewStatusUpdate->rating = $_POST["rating"];
				$rating = $oNewStatusUpdate->rating;
			} else {
				$rating = 0;
			}
			
			$oNewStatusUpdate->review = $_POST["review"];
			
			$oNewStatusUpdate->save();
			
			$brew = new Beer();
			$brew->load($_POST["beerid"]);
			$title = $brew->title;
			$brewery = $brew->breweryname;
			
			$table = 'followUserID';
			$aFollowersIDsList = FollowManager::getFollowersIDsList($id,$table);

			foreach($aFollowersIDsList as $userid){
				
			$oFollower = new User();
			$oFollower->load($userid);
					
				if($oFollower->settings != 1){
					
					// Compose email notifications to followers
					$to      = $oFollower->email;
	
					$subject = "Brewhound: Activity Notification";
								
					$message = '<html><body>';
					$message .= '<p><a href="'.$domain.'user/'.strip_tags($username).'">'.strip_tags($username).'</a> added a checkin</p>';
					$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
					$message .= "<tr style='background: #eee;'><td><strong>Brew:</strong> </td><td>" . strip_tags($title) . "</td></tr>";
					$message .= "<tr><td><strong>Brewery:</strong> </td><td>".strip_tags($brewery)."</td></tr>";
					if(isset($_POST["rating"])){
						$message .= "<tr><td><strong>Rating:</strong> </td><td>".strip_tags($rating)."/5</td></tr>";
					}
					$message .= "</table>";
					$message .= "<p><b>Review:</b> ".strip_tags($_POST["review"])."</p>";
					$message .= "<p></p>";
					$message .= '<p>You are following <a href="'.$domain.'user/'.strip_tags($username).'">'.strip_tags($username).'</a><p>';
					$message .= '<p>You can customise your notification settings <a href="http://brewhound.nz/viewuseradmin">here</a></p>';
					$message .= "</body></html>";
		
								
					// In case any of our lines are larger than 70 characters, we should use wordwrap()
					$message = wordwrap($message, 70, "\r\n");
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					
					$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
					    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
					
					// Send each email
					mail($to, $subject, $message, $headers);
				}
			}
			
			if($_GET["name"]){
				$redirectSlug = $_GET["name"];
				header("location:http://brewhound.nz/$redirectSlug");
				exit;
			}
			
			// redirect to success page
			header("location:$domain/user/$username?success=true");
			exit;

		}
		
		
	}

	$oForm->makeTextInput("Select Brew:","beerselect","Start typing keyword to search");
	$oForm->makeHiddenSelectInput("#beerid ","beerid","");

	$oForm->makeTextInput("Select Location","locationselect","Start typing keyword to search");
	$oForm->makeHiddenSelectInput("#locationid ","locationid","");
	
	//$oForm->makeRatingSelectInput("Rating","rating","");
	$oForm->makeRatingRadioInput("Rating","rating",array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'),$rating);
	$oForm->makeTextArea("Review","review","Tasting notes, full critque or just a comment...");
	
	$oForm->makeFileInput("Photo","photo","");
	$oForm->makeSubmit("Add","submit");
?>
  
<!--   <button data-toggle="modal" data-target="#addbrewajax" class="btn btn-primary">add new</button> -->
  
<div class="wrapper clearfix">
    <div class="col-md-12">
        <div class="box">
            <h2>Status Update / Check In: </h2>
            <hr>
            
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<!-- Modal -->
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
  
<?php require_once 'includes/footer.php'; ?>

<script>
	
/*
	$("#addbrewajax").on("show.bs.modal", function(e) {
		//console.log(this);
		//console.log(e.currentTarget);
		//$('#ajaxContent').load("http://brewhound.nz/addbeer.php");

		    //var link = $(e.currentTarget);
		    //$(this).find(".modal-body").load(link.attr("http://brewhound.nz/addbeer.php"));

	});
*/

	
	var $_GET = <?php echo json_encode($_GET); ?>;
	var beerid = $_GET["brew"];
	$('input[name="beerid"]').val(beerid);
	var brewname = $_GET["name"];
	$('input[name="beerselect"]').val(brewname);
	
	
	$('input#beerselect').focus(
    function(){
        $(this).val('');
    });
    
	$('#beerselect').autocomplete({
	  	source: function( request, response ) {
		  	$('#beerselect').addClass('loading');
	  		$.ajax({
	  			url : 'listBrews.php',
	  			sortResults: false,
	  			dataType: "json",
	  			type: 'Get',
	  			data: {term: request.term},
				success: function( data ) {
					$('#beerselect').removeClass('loading');
					 var array = ( $.map( data, function( item,i ) {
						return {
							label: item,
							value: i
						}
						
					}));
				//call the filter here
	            response($.ui.autocomplete.filter(array, request.term));
	            //$('#addbrewajax').removeClass('transparent');
	            //$('.helptip').css("opacity","1");
	            //$('#help-beerselect').html("Having trouble finding your brew? <br/>Try using the brewery as search term");
	            //$('#help-beerselect').append(' or <button data-toggle="modal" data-target="#addbrewajax" class="btn btn-primary">add new brew</button> ');
				//$('#addlocationajax').modal() 
/*
	            $('#addnewbrew').on('click', function(){
					console.log('pop');
				});
*/
				console.log(request.term);
				},
				error: function() {
			         $('.searcherror').html('<p>An error has occurred</p>');
			    }
	  		});
	  	},
	  	select: function(event, ui) { 
		  	event.preventDefault(); 
		  	$(this).val(ui.item.label);
	/*
		  	console.log(ui.item.value);
		  	console.log(ui.item.label);
		  	$(this).val(ui.item.label);
	*/
		  	$('input[name="beerid"]').val(ui.item.value.replace('-', ''));
		  	$('.helptip').css("opacity","0");
		  	//$('input[name="locationid"]').val(ui.item.label);
		  	//$('input[name="beerselect"]').val(ui.item.label);
	    } 	
	});

	$('input#locationid').focus(
    function(){
        $(this).val('');
    });

	$('#locationselect').autocomplete({
	  	source: function( request, response ) {
		  	$('#locationselect').addClass('loading');
	  		$.ajax({
	  			url : 'listLocations.php',
	  			sortResults: false,
	  			dataType: "json",
	  			type: 'Get',
	  			data: {term: request.term},
				success: function( data ) {
					$('#locationselect').removeClass('loading');
					 var array = ( $.map( data, function( item,i ) {
						return {
							label: item,
							value: i
						}
						
					}));
				//call the filter here
	            response($.ui.autocomplete.filter(array, request.term));
	            //$('#addbrewajax').removeClass('transparent');
	            //$('.helptip').css("opacity","1");
	            //$('#help-beerselect').html("Having trouble finding your brew? <br/>Try using the brewery as search term");
	            //$('#help-beerselect').append(' or <button data-toggle="modal" data-target="#addbrewajax" class="btn btn-primary">add new brew</button> ');
				//$('#addlocationajax').modal() 
/*
	            $('#addnewbrew').on('click', function(){
					console.log('pop');
				});
*/
				console.log(request.term);
				},
				error: function() {
			         $('.searcherror').html('<p>An error has occurred</p>');
			    }
	  		});
	  	},
	  	select: function(event, ui) { 
		  	event.preventDefault(); 
		  	$(this).val(ui.item.label);
	/*
		  	console.log(ui.item.value);
		  	console.log(ui.item.label);
		  	$(this).val(ui.item.label);
	*/
		  	$('input[name="locationid"]').val(ui.item.value);
		  	$('.helptip').css("opacity","0");
		  	//$('input[name="locationid"]').val(ui.item.label);
		  	//$('input[name="beerselect"]').val(ui.item.label);
	    } 	
	});


</script>