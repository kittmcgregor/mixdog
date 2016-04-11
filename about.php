<?php 
	ob_start(); // for redirection to work
	include_once'includes/header.php';
	
$oForm = new Form();
$topMessage ="";
	if(isset($_POST["submit"])){
			// is it a post request?
		$oForm->data = $_POST;
		$oForm->checkRequired("contactEmail");
		$oForm->checkRequired("contactMessage");
	
				if($oForm->valid==true){
			
			$contactEmail = $_POST["contactEmail"];
			$claimMessage = $_POST["claim"];
			$contactMessage = $_POST["contactMessage"];
			
			$topMessage = '<p>Message sent</p>';
			
			// Send email notification to admin
				$to      = 'webmaster@brewhound.nz';
				$subject = 'Brewhound: Feedback';
				
				$message = '<html><body>';
				$message .= '<p> Claim: '.$claimMessage.'</p>';
				$message .= '<p> Message: '.$contactMessage.'</p>';
				$message .= '</body></html>';
							
				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");
				
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				$headers .= 'From: '.$contactEmail. "\r\n" .
				    'Reply-To: '.$contactEmail. "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $message, $headers);
				
			// redirect to success page
			
			if($claimMessage!=""){
				header("location:about.php?claim=true");
			} else {
				header("location:about.php?feedback=true");
			}
			exit;
				
			}
	
	}
	
	$oForm->makeTextInput("Email","contactEmail","Your email address");
	$oForm->makeTextInput("Claim","claim","Your Brewery or Location Name");
	$oForm->makeTextArea("Message","contactMessage","Your message");
	$oForm->makeSubmit("Send","submit");
		
?>

<div id="banner"></div>

	<div id="content" class="wrapper clearfix">

		<div id="intro">Find the freshest brews on tap</div>

		
		<div id="searchForm">
			<form id="homeSearchForm" action="search.php" method="post" enctype="multipart/form-data">
				<div class="form-group clearfix">
					<div class="col-md-8"><input type="text" class="form-control" id="searchKeywords" name="keyword" placeholder="search keywords..." value=""/></div>
					<div class="col-md-4"><input type="submit" id="submit" value="release the hound" class="btn"></div>
				</div>	
			</form>
		</div> <!-- end search form -->
		
		
	</div> <!-- end content container -->
</div> <!-- end banner container -->


<div class="main">
	<div class="wrapper clearfix">
		<div class="col-md-12">
			<h3>About Brew Hound</h3>
		</div>
		
		<div class="col-md-12">
			<p><b>Mission</b>
				<br/>Our mission is to provide an independent directory of craft beer taps. We aim to support the entire brewing community: from small scale operations to large suppliers, in order to encourage growth and diversity.</p>
				
			<p>Starting from Auckland and working our way out to the far reaches of the islands, we aim to provide a useful, powerful and simple system for updating and presenting all the relevant info and activity.</p>
			
			<p>We are a small team of volunteers/enthusiasts (not brewers/not industry related) who decided to get this ball rolling as an experiment. There was clearly a need for an aggregation of disparate activity within the brewing industry/community. Brew Hound is our proposed solution.
			</p>
		</div>
			
		<div class="col-md-6">
			<p><b>Feedback</b>
				<br/>Please share any feedback, suggestions or encouragement with us.
			</p>

				<div class="promo">
				<h3>Owner?</h3>
				<p>Breweries, Bars & Shops can claim ownership and manage their brews.</p>
				<a href="about.php"><img class="imgspace" src="<?php echo $imgpath; ?>promoadd.png"/></a>
				<a href="about.php"><img class="imgspace" src="<?php echo $imgpath; ?>promoremove.png"/></a>
				</div>
				
				<p><b>Claims</b>
				<br/>We'll verify your claim, then grant you access to manage your brewery or location. Please sign up/register then we'll upgrade/link your account.
				</p>
			
		</div>
		
		<div class="col-md-6">
							<?php echo $oForm->html; 
					
					if($topMessage!=""){
						echo $topMessage;
					}
					
				?>
		</div>
		
	</div>
</div>
<?php
	include_once'includes/footer.php';

?>