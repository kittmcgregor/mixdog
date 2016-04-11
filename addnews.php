<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
	    $oForm = new Form();
		
		if(isset($_POST["submit"])){
		// is it a post request?
		$oForm->data = $_POST;
		//$oForm->files = $_FILES;
		
		$oForm->checkRequired("newsTitle");
		$oForm->checkRequired("newsContent");
		
		if($oForm->valid==true){
			// insert data to database to create new page
			$oNews = new News();
	
			// set values;
			$oNews->newstitle = $_POST["newsTitle"];
			$oNews->newscontent = $_POST["newsContent"];
			$oNews->newsexcerpt = $_POST["newsExcerpt"];
			$oNews->newsimagelink = $_POST["newsImageLink"];
			$oNews->newsimagelinkaddress = $_POST["newsImageLinkAddress"];
			
			$oNews->save();
			$newNewsID = $oNews->newsID;
			
// Send email notification to admin
/*
			$to      = 'admin@version.nz';
			$subject = 'Brewhound: Style added';
			
$message = '<html><body>';
$message = '<p>A new style was added at brewhound.nz</p>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr style='background: #eee;'><td><strong>Title:</strong> </td><td>" . strip_tags($_POST["StyleName"]) . "</td></tr>";
$message .= "</table>";
$message .= "</body></html>";

						
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= 'From: webmaster@brewhound.nz' . "\r\n" .
			    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
*/
			
			
			// redirect to success page
			header("location:addnews.php?newssuccess=true&newNewsID=".$newNewsID);
			exit;

		}
		
		
	}

	$oForm->makeTextInput("News Title","newsTitle","");
	$oForm->makeTextArea("Content","newsContent","");
	$oForm->makeTextInput("Excerpt","newsExcerpt","");
	$oForm->makeTextInput("Image Link","newsImageLink","http://mysite.com/myimage.jpg");
	$oForm->makeTextInput("Image Link Address","newsImageLinkAddress","http://mysite.com/myblogpost");
	
	$oForm->makeSubmit("Add News","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Add News: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>