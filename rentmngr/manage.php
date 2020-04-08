<?php
    // Include Files
	include("inc/authenticated.php");
	// API Required for this page
	include("api/manage.php");

    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;

	// Assign all session variables to tpl to use
	assignUserVariables($tpl);

    // Determine user type
    // TODO: implement tenants/owners

	if(restrictUsers(array("agent","owner"))){
           // Get owner/tenant/property information from view_management
		if(!ISSET($_GET['suburb'])){
			$results = selectProperties($user_type, $user_id);
			// Assign for template
			$tpl->assign("results", $results);
			// Render HTML
			$tpl->draw("manage");
		}else{
			
			$results = selectProperties($user_type, $user_id, "suburb");
			$tpl->assign("results", $results);
			$tpl->draw("manageBySuburb");
		}
	} else {
		// Redirect to view.php
		$results = selectProperties($user_type, $user_id);
		$property_id = $results[0]["property_id"];
		header("Location: view.php?property=".$property_id);
		// But for now redirect home
	}
		
?>
