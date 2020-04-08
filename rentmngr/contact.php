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
		assignWebsiteName($tpl);
		$tpl->assign("fail",0);
		$tpl->draw("contact");
	} else {
		header("Location: home.php");
	}
?>
