<?php
if(ISSET($_POST['email_address'])){
	$interest_file = 'interest.txt';
	$handle = fopen($interest_file, 'a') or die('Cannot open file:  '.$interest_file);
	$data = $_POST['email_address']."\n";
	fwrite($handle, $data);
	fclose($handle);
}
header("Location: ./");
die();
?>