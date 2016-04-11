<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	if (isset($_SESSION["UserID"])){
		if($_SESSION["UserID"]!=1){
			header("location:login.php");
		}
	}

	if(isset($_GET["newsID"])){
		$beerID = $_GET["newsID"];
	}

	$oExistingNews = new News();
	$oExistingNews->load($_GET["newsID"]);
	
	$aExistingData = array();
	//$aExistingData["BeerID"] = $oExistingBeer->beerID;
	$aExistingData["newsTitle"] = $oExistingNews->newstitle;
	$aExistingData["newsContent"] = $oExistingNews->newscontent;
	$aExistingData["newsExcerpt"] = $oExistingNews->newsexcerpt;
	$aExistingData["newsImageLink"] = $oExistingNews->newsimagelink;
	$aExistingData["newsImageLinkAddress"] = $oExistingNews->newsimagelinkaddress;

	$oForm = new Form();
	$oForm->data = $aExistingData;

	if (isset($_SESSION["UserID"])==false){
		header("location:login.php?requirelogin=true");
	}
		
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
		$oExistingNews->newstitle = $_POST["newsTitle"];
		$oExistingNews->newscontent = $_POST["newsContent"];
		$oExistingNews->newsexcerpt = $_POST["newsExcerpt"];
		$oExistingNews->newsimagelink = $_POST["newsImageLink"];
		$oExistingNews->newsimagelinkaddress = $_POST["newsImageLinkAddress"];
		
		$oExistingNews->update();
		
		// redirect to success page
		header("location:editnews.php?newssuccess=true&newsID=".$_GET["newsID"]);
		exit;

	}
		
		
	}

	$oForm->makeTextInput("News Title","newsTitle","");
	$oForm->makeTextArea("Content","newsContent","");
	$oForm->makeTextInput("Excerpt","newsExcerpt","");
	$oForm->makeTextInput("Image Link","newsImageLink","http://mysite.com/myimage.jpg");
	$oForm->makeTextInput("Image Link Address","newsImageLinkAddress","http://mysite.com/myblogpost");
	
	$oForm->makeSubmit("Update","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Edit News: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>