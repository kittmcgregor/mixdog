<?php
session_start();
session_destroy();

/*
unset($_SESSION["UserID"]);
unset($_SESSION["previous_page"]);
unset($_SESSION["previous_previous_page"]);
unset($_SESSION["LocationManagerID"]);
unset($_SESSION["BreweryManagerID"]);
*/
header("Location:index.php?logoutsuccess=true");
exit();


?>