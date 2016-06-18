<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

/*
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
*/


	if(isset($_GET["beerID"])){
		$beerID = $_GET["beerID"];
	}
			
	$oExistingBeer = new Beer();
	$oExistingBeer->load($beerID);
	
	$slug = $oExistingBeer->slug;

	
	$aExistingData = array();
	//$aExistingData["BeerID"] = $oExistingBeer->beerID;
	$aExistingData["Title"] = $oExistingBeer->title;
	$aExistingData["Description"] = $oExistingBeer->description;
	
	$aExistingData["StyleID"] = $oExistingBeer->styleID;
	//$aOptions
	$aExistingData["BreweryID"] = $oExistingBeer->breweryID;
	
//	$aExistingData["Style"] = $oExistingBeer->style;
	$aExistingData["Brewery"] = $oExistingBeer->brewery;
	$aExistingData["Alcohol"] = $oExistingBeer->alcohol;
	$aExistingData["IBU"] = $oExistingBeer->ibu;
	$aExistingData["Active"] = $oExistingBeer->active;
	$aExistingData["Exclusive"] = $oExistingBeer->exclusive;
	$aExistingData["FreshHop"] = $oExistingBeer->freshhop;
	$aExistingData["Photo"] = $oExistingBeer->photo;
	
/*
	echo "<pre>";
	print_r($oExistingBeer);
	echo "</pre>";
*/
	
	$aLocationIDs = array();
	foreach($oExistingBeer->locations as $location){
		$aLocationIDs[] = $location->locationID;
	}
	$aExistingData["Locations"] = $aLocationIDs;
	
/*
	        echo "<pre>";
            echo count($aLocationIDs);
            echo "</pre>";
*/
            
/*
		echo "<pre>";
		print_r($aExistingData);
		echo "</pre>";
*/
	
	$oForm = new Form();
	$oForm->data = $aExistingData;

	if(isset($_POST["submit"])){
		// is it a post request?
	
		$oForm->data = $_POST;	
		$oForm->files = $_FILES;
		
		//$oForm->checkRequired("Username");
		$oForm->checkRequired("Title");
		// $oForm->checkRequired("Description");
		//$oForm->checkRequired("Style");
		//$oForm->checkRequired("Brewery");
		//$oForm->checkRequired("Alcohol");
		
				
		if($oForm->valid==true){
			// insert data to database to create new page
		
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
				$oExistingBeer->photo = $newName;
			}
			
/*
			if($_FILES["photo"]["error"] == 0){
				$newName = "photo".time().'.jpg';
				$oForm->moveFile("photo",$newName);
				$oExistingBeer->photo = $newName;
			}
*/
			
			// set values;
		
			$oExistingBeer->title = $_POST["Title"];
			$oExistingBeer->description = $_POST["Description"];
			$oExistingBeer->styleID = $_POST["StyleID"];
//			$oExistingBeer->style = $_POST["Style"];
			$oExistingBeer->breweryID = $_POST["BreweryID"];
			$oExistingBeer->brewery = $_POST["Brewery"];
			$oExistingBeer->alcohol = $_POST["Alcohol"];
			$oExistingBeer->ibu = $_POST["IBU"];
			//$oExistingBeer->active = $_POST["Active"];

	if($_POST["Exclusive"]!=''){
		$oExistingBeer->exclusive = $_POST["Exclusive"];
	} else {
		$oExistingBeer->exclusive = 0;
	}
	if($_POST["FreshHop"]!=''){
		$oExistingBeer->freshhop = $_POST["FreshHop"];
	} else {
		$oExistingBeer->freshhop = 0;
	}


			if(isset($_POST["Locations"])){
				$oExistingBeer->locations = $_POST["Locations"];
				$aLocations = $_POST["Locations"];
			} else {
				$aLocations = array();
			}

			// add brewery name from brewery if exists...
			if(isset($_POST["BreweryID"])){
				$oBrewery = new Brewery();
				$oBrewery->load($_POST["BreweryID"]);
				$oExistingBeer->breweryname = $oBrewery->breweryname;
			}




			$oExistingBeer->update();
			
					//Availability::updateAvailability($beerID,$_POST["Locations"],$_POST["BreweryID"]);
			
			//Hidden from edit page
			//Availability::updateSpecificAvailability($beerID,$aLocations,$_POST["BreweryID"],$aLocationIDs);


			//header("location:viewbeer.php?beerUpdatedSuccess=true&beerID=".$beerID);
			header('location:'.$domain.$slug);
			exit;
		}
		
		
	}
	
/*
	echo "<pre>";
			print_r($oForm);
			echo "</pre>";
*/
	

	//$oForm->makeTextInput("Beer ID","BeerID");
	$oForm->makeTextInput("Beer Name","Title","");
	$oForm->makeTextArea("Description","Description","");
	$oForm->makeSelectInput("Brewery","BreweryID",Brewery::brewerylist());
	//$oForm->makeTextInput("New Brewery","Brewery","");
	$oForm->makeFileInput("Brew image (Optional)<br/><span class='small'>If not added the existing Brewery image will be used if available.</span>","photo");
	$oForm->makeSelectInput("Style","StyleID",Style::stylelist());

	$oForm->makeTextInputCol50ALC("Alcohol %","Alcohol","");
	$oForm->makeTextInputCol50("IBU","IBU","");
	$oForm->makeCheckboxInputCol50("Exclusive","Exclusive","1","Tap Only");
	$oForm->makeCheckboxInputCol50("Special Edition","FreshHop","1","Fresh Hop");
	//$oForm->makeCheckboxResident("Resident","Resident","1");
	//$oForm->makeSelectInput("Location","LocationID",Location::lists());
	//$oForm->makeTextInput("Available at:","Location","multiple locations allowed");
	//$oForm->makeCheckboxInputSet("Available at:","Locations",Location::lists());
	//$oForm->makeCheckboxInputSet("Available at:","LocationID","1",Location::lists());
	//$oForm->makeFileInput("Upload Photo","photo");
		
	$oForm->makeSubmit("Update","submit");
	
/*
			echo "<pre>";
			print_r(Style::stylelist());
			echo "</pre>";
*/
			
?>


<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Edit Brew: </h2>
            <hr>
            <?php echo $oForm->html; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>