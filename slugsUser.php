<?php
	
	require_once("includes/user.php");
	require_once("includes/view.php");
$users = User::userIDs();

/*
echo "<pre>";
print_r($users);
echo "</pre>";
*/

createUserSlugs($users);

?>