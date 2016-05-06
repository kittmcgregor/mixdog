<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';
	
	// check email with token
	

	
	
	if($_GET['token']){
		$token = $_GET['token'];
	}
	if($_GET['email']){
		$email = $_GET['email'];
	}

/*
	$oExistingUser = new User();
	$oExistingUser->load($_SESSION["UserID"]);	
	
	$aExistingData = array();
	//$aExistingData["BeerID"] = $oExistingBeer->beerID;
	$aExistingData["Username"] = $oExistingUser->username;
	$aExistingData["FirstName"] = $oExistingUser->firstname;
	$aExistingData["LastName"] = $oExistingUser->lastname;
	$aExistingData["Email"] = $oExistingUser->email;
*/	
	
	$oForm = new Form();
	//$oForm->data = $aExistingData;

	if(isset($_POST["submit"])){
	
		// is it a post request?
		$oForm->data = $_POST;
		//$oForm->files = $_FILES;
		
		$oForm->checkRequired("Password");
		
		
		
		if($oForm->valid==true){
		// insert data to database to update data
		// set values;
		
		$oExistingUser = new User();
		$oExistingUser->loadByUserEmail($email);
		
		
		$verifytoken = User::checktoken($token,$email);
		
		if($verifytoken==false){
			$error = "token not verified";
		} else {
			$oExistingUser->password = $_POST["Password"];
			$oExistingUser->updatepassword();
					// login
		$_SESSION["UserID"] = $oExistingUser->userID;
		$_SESSION["LocationManagerID"] = $oExistingUser->locationID;
		$_SESSION["BreweryManagerID"] = $oExistingUser->breweryID;
		header("location:http://brewhound.nz/viewuseradmin");
		}

		
	}
}


	//$oForm->makeTextInput("Username","Username","required");	
	//$oForm->makeTextInput("First Name","FirstName","optional - not shown publicly");
	//$oForm->makeTextInput("Last Name","LastName","optional - not shown publicly");
	//$oForm->makeTextInput("Email","Email","required - not shown publicly");
	
	$oForm->makePasswordInput("New Password","Password","required");
	
	$oForm->makeSubmit("Update","submit");

?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Password Reset: </h2>
            <hr>
            
            <?php 
	            if(isset($_POST["submit"])){
		            if($verifytoken==false){
			            echo '<p>'.$error.'</p>'; 
			        }
			        
			    } else {
				    echo $oForm->html;
			    }
		    ?>

        </div>
    </div>
</div>
<?php 
	
/*


echo "<pre>";
print_r($oExistingUser);
echo "</pre>";
*/
	
	require_once 'includes/footer.php'; ?>