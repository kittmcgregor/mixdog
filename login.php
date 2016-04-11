<?php
	ob_start(); // for redirection to work
	require_once'includes/header.php';

	$redirectafterlogin = 'viewuseradmin.php';

/*
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
*/
		
	$oForm = new Form();

	if(isset($_POST["submit"]) == true){
		// is it a post request?
		$oForm->data = $_POST;
		$oForm->checkRequired("Username");
		$oForm->checkRequired("Password");

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

		if($oForm->valid==true){
			$oTestUser = new User();
			$bLoaded = $oTestUser->loadByUserName($_POST["Username"]);

/*
echo "<pre>";
print_r($oTestUser);
echo "</pre>";
*/

		if($bLoaded==false){
				$oForm->raiseCustomError("Username","username does not exist");
			} else { // username does exist
			if ($oTestUser->password!= $_POST["Password"]){
			//if  ( (password_verify($_POST["Password"],$oCheckUsername->Password)) || ($oCheckUsername->Password == $_POST["Password"]) ) {
/*
				echo '<pre>database: "'.$oTestUser->password.'"</pre>';
				echo '<pre>forminput: "'.$_POST["Password"].'"</pre>';
*/
					$oForm->raiseCustomError("Password","Incorrect Password");
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

	$oForm->makeLoginInput("Username","Username","Username");
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
					<li><button class="btn"><a href="forgot.php">recover login</a></button></li>
				</ul>
			</p>
			
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>