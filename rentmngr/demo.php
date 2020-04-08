<?php
include("api/account.php");
$email = "LiamJoseph79@example.com";
if(ISSET($_GET['type'])){
	switch($_GET['type']){
		case "agent":
			$email = "JasperEthan48@example.com";
			break;
		case "owner":
			$email = "ArthurAdam39@example.com";
			break;
	}
}
$hashed_password = "b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86";
login($db, $email, $hashed_password);
header("Location: .");
?>