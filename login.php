<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	$redirectafterlogin = 'viewuseradmin';
	
	if($_GET['intended']){
		$redirectafterlogin = $_GET['intended'];
	}
	
/*
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
*/
		
	$oForm = new Form();

	if(isset($_POST["submit"]) == true){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->checkRequired("Password");

		$login = $_POST["Username"];
		//echo $login;
		if(filter_var($login, FILTER_VALIDATE_EMAIL)){
			//echo 'is email';
			$logintype = 'email';
			
		} else {
			//echo 'is not email';
			$logintype = 'username';
		}
		
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

		if($oForm->valid==true){
			
			if($_POST["Username"]&&$logintype!='email'){
			$oTestUser = new User();
			$bLoaded = $oTestUser->loadByUserName($_POST["Username"]);				
			} else {
				
			//echo 'test email';
			$oTestUser = new User();
			$bLoaded = $oTestUser->loadByUserEmail($_POST["Username"]);			
			}


		$password = $_POST["Password"];
		$hash = $oTestUser->password;

/*
echo "<pre>";
print_r($oTestUser);
echo "</pre>";
*/
	if($_POST["Username"]&&$logintype!='email'){
		if($bLoaded==false){
			$oForm->raiseCustomError("Username","username does not exist");
		} else { // username does exist
			if (password_verify($password,$hash)){
			//if ($oTestUser->password!= $_POST["Password"]){
					
			} else {
				$oForm->raiseCustomError("Password","Incorrect Password");
			}
		}
	} else {
		if($bLoaded==false){
			$oForm->raiseCustomError("Email","Email does not exist");
		} else { // username does exist
			if (password_verify($password,$hash)){
			//if ($oTestUser->password!= $_POST["Password"]){
					
			} else {
				$oForm->raiseCustomError("Password","Incorrect Password");
			}
		}
	}



		if($oForm->valid==true){
		// redirect to success page
		
			$_SESSION["UserID"] = $oTestUser->userID;
			$_SESSION["LocationManagerID"] = $oTestUser->locationID;
			$_SESSION["BreweryManagerID"] = $oTestUser->breweryID;
			//$beforelogin = $_SESSION["previous_previous_page"];
			
			
			
			header("location:$redirectafterlogin");

			
/*
			if(strpos($beforelogin, "logoutsuccess")!==false){
				header("location:index.php?loginsuccess=true");
				exit;
			} elseif (strpos($beforelogin, "?")!==false){
			    header("location:$beforelogin&loginsuccess=true");
			    exit;
			}
			else {
			   header("location:$beforelogin?loginsuccess=true");
			   exit;
			}
			
			if(!empty($_SERVER['HTTP_REFERER'])){$redirectafterlogin=$_SERVER['HTTP_REFERER']}
			
*/
			
		}
	
		}
	}

	$oForm->makeLoginInput("Email or Username","Username","Username");
	$oForm->makePasswordInput("Password","Password","Password");
	$oForm->makeHiddenInput("Redirect","label","$redirectafterlogin");
	$oForm->makeSubmit("Login","submit");

	

?>
<div class="wrapper clearfix">
    <div class="col-md-6">
        <div class="box">
            <h2>Login: </h2>
            <hr>
            <p><?php echo $oForm->html; ?></p>
			<p>
				<ul class="loginExtras">
					<li><button class="btn"><a href="register.php">sign up</a></button></li>
					<li><button class="btn"><a href="forgot.php">recover login details</a></button></li>
				</ul>
			</p>
			
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>