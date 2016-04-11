<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	$oExistingUser = new User();
	$oExistingUser->load($_SESSION["UserID"]);	
	
	$aExistingData = array();
	//$aExistingData["BeerID"] = $oExistingBeer->beerID;
	$aExistingData["Username"] = $oExistingUser->username;
	$aExistingData["FirstName"] = $oExistingUser->firstname;
	$aExistingData["LastName"] = $oExistingUser->lastname;
	$aExistingData["Email"] = $oExistingUser->email;	
	
	$oForm = new Form();
	$oForm->data = $aExistingData;

	if(isset($_POST["submit"])){
	
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->files = $_FILES;
		
		$oForm->checkRequired("Username");
		$oForm->checkRequired("Email");
		
		if($oForm->valid==true){
		// insert data to database to update data
		// set values;
	
		$oExistingUser->username = $_POST["Username"];
		$oExistingUser->firstname = $_POST["FirstName"];
		$oExistingUser->lastname = $_POST["LastName"];
		$oExistingUser->email = $_POST["Email"];
		//$oExistingUser->password = $_POST["Password"];

		$oExistingUser->update();
		
		header("location:viewuseradmin.php?usersuccess=true");
		exit;
	}
}


	$oForm->makeTextInput("Username","Username","required");	
	$oForm->makeTextInput("First Name","FirstName","optional - not shown publicly");
	$oForm->makeTextInput("Last Name","LastName","optional - not shown publicly");
	$oForm->makeTextInput("Email","Email","required - not shown publicly");
	
	$oForm->makeSubmit("Update","submit");
?>

<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Edit User: </h2>
            <hr>
			<?php echo $oForm->html; ?>
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