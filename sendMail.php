<?php	

$to      = 'kitt@version.nz';
$subject = 'Brewhound Account Registration';

$message = "
Thanks for registering at brewhound\r\n

You can view your account details including beers added to your liked list here: http://beta.brewhound.nz/viewuser.php\r\n

Or if you find a beer that hasn't been listed yet, add it here: http://beta.brewhound.nz/addbeer.php";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

$headers = 'From: webmaster@brewhound.nz' . "\r\n" .
    'Reply-To: webmaster@brewhound.nz' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>