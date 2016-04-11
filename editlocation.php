<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';
	
	if (isset($_GET["locationID"])) {
		$redirecteditLocation = $_GET["locationID"];
	}
	// authorise
	
	if ($_SESSION["UserID"]==1) {
		$editLocation = $_GET["locationID"];
	} elseif(isset($_SESSION["LocationManagerID"])){
		$editLocation = $_SESSION["LocationManagerID"];
	} else {
		// redirect 
		header("location:login.php");
	}




	$oExistingLocation = new Location();
	$oExistingLocation->load($editLocation);	
	
	$aExistingData = array();
	//$aExistingData["BeerID"] = $oExistingBeer->beerID;
	$aExistingData["locationID"] = $oExistingLocation->locationID;
	$aExistingData["oldLocationManagerID"] = $oExistingLocation->oldlocationmanagerID;
	$aExistingData["newLocationManagerID"] = $oExistingLocation->oldlocationmanagerID;
	$aExistingData["locationName"] = $oExistingLocation->locationname;
	$aExistingData["locationAddress"] = $oExistingLocation->locationaddress;
	$aExistingData["locationSuburb"] = $oExistingLocation->locationsuburb;
	$aExistingData["locationRegion"] = $oExistingLocation->locationregion;	
	$aExistingData["locationContact"] = $oExistingLocation->locationcontact;		
	$aExistingData["locationWebsite"] = $oExistingLocation->locationwebsite;	
	$aExistingData["locationInfo"] = $oExistingLocation->locationinfo;
	$aExistingData["locationEvents"] = $oExistingLocation->locationevents;
		
	$oForm = new Form();
	$oForm->data = $aExistingData;

/*
	echo "<pre>";
	print_r($aExistingData);
	echo "</pre>";
*/
	
	if(isset($_POST["submit"])){
	
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
		$oForm->checkRequired("locationName");
		$oForm->checkRequired("locationSuburb");
		$oForm->checkRequired("locationRegion");
		
		if($oForm->valid==true){
		// insert data to database to update data
		// set values;
	
	
		$rawurl = $_POST["locationWebsite"];
		$clensedurl = str_replace('http://', '', $rawurl);
	
		$oExistingLocation->locationname = $_POST["locationName"];
		$oExistingLocation->locationaddress = $_POST["locationAddress"];
		$oExistingLocation->locationsuburb = $_POST["locationSuburb"];
		$oExistingLocation->locationregion = $_POST["locationRegion"];
		$oExistingLocation->locationcontact = $_POST["locationContact"];
		//$oExistingLocation->locationwebsite = $_POST["locationWebsite"];
		$oExistingLocation->locationwebsite = $clensedurl;
		$oExistingLocation->locationinfo = $_POST["locationInfo"];
		$oExistingLocation->locationevents = $_POST["locationEvents"];
		//$oExistingUser->password = $_POST["Password"];
		
		$oExistingLocation->newlocationmanagerID = $_POST["newLocationManagerID"];

		$oExistingLocation->update();

		header("location:viewlocation.php?locationID=".$redirecteditLocation);
		exit;
	}
}


	$oForm->makeTextInput("Location Name","locationName","required");	
	$oForm->makeTextInput("Location Address","locationAddress","");
	$oForm->makeTextInput("location Suburb","locationSuburb","required");
	$oForm->makeTextInput("location Region","locationRegion","required");
	$oForm->makeTextInput("location Contact (ph prefix 09 or +64)","locationContact","");
	$oForm->makeTextInput("location Website (remove the http:// - we'll add it)","locationWebsite","");
	$oForm->makeTextArea("location Info","locationInfo","");	
	$oForm->makeTextArea("location Events","locationEvents","");
	if(isset($_SESSION["UserID"])){
		if ($_SESSION["UserID"]==1) {
			
			$oForm->makeSelectInput("User","newLocationManagerID",User::userlist(),"");
		}
	}
	
	$oForm->makeSubmit("Update","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Edit Location: </h2>
            <hr>
			<?php echo $oForm->html; 
			
				if ($_SESSION["UserID"]==1) {
					if ($oExistingLocation->oldlocationmanagerID==0){
					echo "no manager";
					}
				}
					
			?>
        </div>
    </div>
</div>
<?php 
	
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<pre>";
print_r($oExistingUser);
echo "</pre>";
*/
	
	require_once 'includes/footer.php'; ?>