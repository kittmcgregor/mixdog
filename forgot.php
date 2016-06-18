<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

$topMessage = 'Please enter your email that you used to sign up with to recover login details.';

$oForm = new Form();

	if(isset($_POST["submit"])){
		// is it a post request?
	$oForm->data = $_POST;
	$oForm->checkRequired("recover");

		if($oForm->valid==true){
		
		$recoverEmail = $_POST["recover"];
		
		$oTestUser = new User();
		$bLoaded = $oTestUser->CheckEmailExists($recoverEmail);
		
			if($bLoaded==false||$recoverEmail=='webmaster@brewhound.nz'){
				$topMessage = "<p>Sorry we can't find that email in our database.<br/>
				Please enter your email that you used to sign up with.</p>";
			} else {
				function RandomString($len){
					$result = "";
				    $chars = "abcdefghijklmnopqrstuvwxyz?!-0123456789";
				    $charArray = str_split($chars);
				    for($i = 0; $i < $len; $i++){
					    $randItem = array_rand($charArray);
					    $result .= "".$charArray[$randItem];
				    }
				    return $result;
				}
				
				$token = RandomString(30);
				User::token($recoverEmail,$token);
				
				$topMessage = '<p>Account recovery request sent.<br/>
				An email has been sent with a password reset link and username reminder.</p>';
				
				// Send email notification to admin
				$to      = $recoverEmail;
				$cc	= 'webmaster@brewhound.nz';
				$subject = 'Brewhound: recovery request ';
				
				$message = '<html><body>';
				$message .= '<p>A password reset request has been requested for: '.$recoverEmail.'</p>';
				$message .= '<p>Click this link to <a href="http://brewhound.nz/reset_password.php?token='.$token.'&email='.$recoverEmail.'">reset your password</a></p>';
				$message .= '<p>If you did not request a password reset then just ignore this email.</p>';
				$message .= "</body></html>";
							
				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
					'CC: '.$cc . "\r\n" .
				    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
				
				//echo 'email sent';
			} // else email valid send reset email
		}
	}
	
	$oForm->makeTextInput("Email","recover","Email");

	$oForm->makeSubmit("Recover","submit");
?>
	
	<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
	        
	        <?php echo '<h5>'.$topMessage.'<h5>'; ?>
            <hr>
            <p><?php echo $oForm->html; ?></p>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>