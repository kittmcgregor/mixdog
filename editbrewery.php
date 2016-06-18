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

	$editBrewery = $_GET["breweryID"];

	
	$oExistingBrewery = new Brewery();
	$oExistingBrewery->load($editBrewery);	
	
	$breweryslug = $oExistingBrewery->slug;
	
	$aExistingData = array();
	$aExistingData["BreweryName"] = $oExistingBrewery->breweryname;
	$aExistingData["FullBreweryName"] = $oExistingBrewery->fullbreweryname;
	$aExistingData["BreweryWebsite"] = $oExistingBrewery->brewerywebsite;
	$aExistingData["BreweryAddress"] = $oExistingBrewery->breweryaddress;

	$oForm = new Form();
	$oForm->data = $aExistingData;
		
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
				$oExistingBrewery->breweryphoto = $newName;
			}
			$oExistingBrewery->breweryname = $_POST["BreweryName"];
			$oExistingBrewery->fullbreweryname = $_POST["FullBreweryName"];
			$oExistingBrewery->brewerywebsite = $clensedurl;
			$oExistingBrewery->breweryaddress = $_POST["BreweryAddress"];
			
			$oExistingBrewery->update();

			
			// redirect to success page
			header('location:'.$domain.'brewery/'.$breweryslug);
			exit;

		}
		
		
	}

	$oForm->makeTextInput("Short Brewery Name (required)","BreweryName","displayed above brew name");
	$oForm->makeTextInput("Full Brewery Name (required)","FullBreweryName","displayed on brewery profile");
	$oForm->makeTextInput("Website","BreweryWebsite","mysite.com");	
	$oForm->makeTextInput("Address","BreweryAddress","");
	$oForm->makeFileInput("Upload Photo","photo");
	$oForm->makeSubmit("Update Brewery","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Edit Brewery: </h2>
            <hr>
			<?php echo $oForm->html; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>