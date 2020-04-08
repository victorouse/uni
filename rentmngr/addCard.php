<?php
    // Include Files
	include("inc/authenticated.php");
	// API Required for this page
	//include("api/pin.php");

    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;

	// Assign all session variables to tpl to use
	assignUserVariables($tpl);
	// Check that the user doesn't already have a card
	$tpl->draw("addCard");
?>