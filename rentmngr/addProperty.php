<?php
    // Include Files
	include("inc/authenticated.php");

	// API Required for this page
	include("api/property.php");

    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;
	$email_address = $_SESSION['email_address'];

	// Assign all session variables to tpl to use
	assignUserVariables($tpl);
    
    // Template variable assignment
	$tpl->assign("agent_id", $user_id);
	
    // Render HTML
	$tpl->draw("addProperty");
?>
