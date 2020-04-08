<?php
    // Database
    include("inc/connect.php");
	include("inc/website_variables.php");
    // Template
	include "inc/tpl.php";
	$tpl = new RainTpl();
	session_start();

    // Check user is logged in
	if (!isset($_SESSION["user"])) {
		$fail = 0;
		if (isset($_GET['fail'])) {
			$fail = 1;
		}
		
        // Send non-logged in user to marketing page
		$tpl->assign("fail", $fail);
		assignWebsiteName($tpl);
		$tpl->draw("marketing");
	} else {
		header("Location: home.php");
		
	}
?>
