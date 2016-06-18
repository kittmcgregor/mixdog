<?php
session_start();
unset($_SESSION["UserID"]);
unset($_SESSION["previous_page"]);
unset($_SESSION["previous_previous_page"]);
unset($_SESSION["LocationManagerID"]);
unset($_SESSION["BreweryManagerID"]);

	$data['success'] = true;
    $data['message'] = 'Logged out successfully!';

echo json_encode($data);

exit();



?>