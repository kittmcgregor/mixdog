<?php
	
	require_once("includes/user.php");
	require_once("includes/view.php");

$id = $_GET['id'];

echo "<pre>";
echo $id;
echo "</pre>";

convertPass($id);

?>