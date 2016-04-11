<?php include_once'includes/header.php'; 
	$total = Availability::totalTaps();
	echo $total;

?>

<?php
	 include_once'includes/footer.php'; 
?>