<?php
	include("inc/website_variables.php");
    // Template
	include "inc/tpl.php";
	$tpl = new RainTpl();
	session_start();

    // Check user is logged in
	if (!isset($_SESSION["user"])) {
		assignWebsiteName($tpl);
		$tpl->assign("fail",0);
		$tpl->draw("userguide");
	} else {
		assignWebsiteName($tpl);
		$tpl->assign("user_id", $_SESSION['user_id']);
        $tpl->assign("user_name", $_SESSION['user']);
        $tpl->assign("user_type", $_SESSION['type']);
		$tpl->draw("userguide");
	}
?>
