<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

/*
		echo "<pre>";
		print_r($_POST);
		print_r($_FILES);
		echo "</pre>";
*/

	    $oForm = new Form();
	
	if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		//$oForm->files = $_FILES;
		
		$oForm->checkRequired("Email");
		$oForm->checkRequired("Password");
		
		$testUser = new  User();
		$isThere = $testUser->loadByUserEmail($_POST["Email"]);
		
		if($isThere){
			$oForm->raiseCustomError("Email","This email is already registered");
		}
				
		if($oForm->valid==true){
			// insert data to database to create new page
			$oNewUser = new User();

			
/*
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
				$oNewUser->photo = $newName;
			}
*/
			
			$usernameslug = strtolower(str_replace(array(':','+','&','!','#','.',"'",'(',')'),"",str_replace(' ','-',$_POST["Username"])));
			$email = $_POST["Email"];
			// set values;
			$oNewUser->slug = $usernameslug;
			$oNewUser->username = $_POST["Username"];
			$username = $oNewUser->username;
			
			$oNewUser->firstname = $_POST["FirstName"];
			$oNewUser->lastname = $_POST["LastName"];
			$oNewUser->email = $email;
			$password = $_POST["Password"];
			$oNewUser->password = password_hash($password,PASSWORD_DEFAULT);
			$sEmail = $_POST["Email"];
			//$oNewUser->password = password_hash($_POST["Password"],PASSWORD_DEFAULT);
			//$oNewUser->saveUsingEmail();
			
			$oNewUserID = $oNewUser->userID;
			$_SESSION["UserID"] = $oNewUserID;
			$_SESSION["NewUserEmail"] = $_POST["Email"];
			$_SESSION["LocationManagerID"] = $oTestUser->locationID;
			$_SESSION["BreweryManagerID"] = $oTestUser->breweryID;
			if($usernameslug==''){
				$oNewUser->username = 'hound'.$oNewUserID;
				$oNewUser->slug = 'hound'.$oNewUserID;
				$usernameslug = 'hound'.$oNewUserID;
				$username = 'hound'.$oNewUserID;
				$oNewUser->update();
			}
			
			
			// Send email confirmation to New User
			$to      = "$sEmail";
			
			$subject = 'Brewhound Notification: new user';
			
			$message = '<html><body>';
			$message .= '<p>' . 'New user registration confirmation at brewhound.nz'.'</p>';
			$message .= '<p><a href="http://brewhound.nz/user/'.$usernameslug.'">' . $username . '</a> | email: ' .$email.'</p>';
			$message .= '<p>' .'You can view your profile here: <a href="http://brewhound.nz/viewuseradmin.php">brewhound.nz</a>'.'</p>';
			$message .= '</body></html>';
			
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: webmaster@brewhound.nz" . "\r\n" .
			    'Bcc: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
				
			// redirect to success page
			header("location:register.php?registersuccess=true&newUserID=".$oNewUserID);			
			exit;
		}
		
		
	}
	
/*
	echo "<pre>";
	print_r($oForm);
	
	echo "</pre>";
*/
	$oForm->makeTextInput("Email","Email","required - not shown publicly");
	$oForm->makePasswordInput("Password","Password","required");
	$oForm->makeTextInput("Username","Username","optional");	
	$oForm->makeTextInput("First Name","FirstName","optional - not shown publicly");
	$oForm->makeTextInput("Last Name","LastName","optional - not shown publicly");

	//$oForm->makeFileInput("Upload Photo","photo");
		
	$oForm->makeSubmit("Register","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Register: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    $('#regex').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            Username: {
                validators: {
                    regexp: {
                        regexp: /^[a-z\s]+$/i,
                        message: 'The full name can consist of alphabetical characters and spaces only'
                    }
                }
            }
        }
    });
});
</script>