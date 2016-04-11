<?php
	ob_start(); // for redirection to work
	include_once'includes/header.php'; 

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

$topMessage = 'Please enter your email (that you used to sign up with).';

$oForm = new Form();

	if(isset($_POST["submit"])){
			// is it a post request?
		$oForm->data = $_POST;
		$oForm->checkRequired("recover");
	
				if($oForm->valid==true){
			
			$recoverEmail = $_POST["recover"];
		
			
			$topMessage = '<p>Account recovery request sent.<br/>
			Your username and a temporary password will be emailed to you by the admin asap.</p>';
			
			// Send email notification to admin
				$to      = 'admin@version.nz';
				$subject = 'Brewhound: recovery request ';
				
				$message = '<html><body>';
				$message .= '<p>Username/Password recovery request: '.$recoverEmail.'</p>';
				$message .= "</body></html>";
							
				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
				    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
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
			<p>Please be patient as this process is not yet automated.</p>
			<p>Your username and a temporary password will be emailed to you by the admin asap.</p>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>