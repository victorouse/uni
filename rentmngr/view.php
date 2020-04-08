<?php
    // Include Files
	include("inc/authenticated.php");

	// API Required for this page
	include("api/view.php");
	include("api/account.php");

    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;
	
	assignUserVariables($tpl);


	if(ISSET($_REQUEST['property'])){
		$property = $_REQUEST['property'];
		$results = getInfo($property);
		$referred_owner = checkReferral("Owner", $property);
		$referred_tenant = checkReferral("Tenant", $property);
		$address = $results["address"];
		
	    // Assign for template
		$tpl->assign("type", $user_type);
		$tpl->assign("referred_tenant", $referred_tenant);
		$tpl->assign("referred_owner", $referred_owner);
		$tpl->assign("address", $address);
		$tpl->assign("results", $results);
	    // Render HTML
		$tpl->draw("propView2");
	}elseif(ISSET($_REQUEST['user'])){
		$other_user_id = $_REQUEST['user'];
		if(related($user_id, $user_type, $other_user_id)){
			$details = getUserDetailsById($db, $other_user_id);
			$tpl->assign("details", $details);
			$tpl->draw("view_user");
		}else{
			// user is not related!
			header("Location: manage.php");
			die();
		}
		
	}else{
		header("Location: manage.php");
		die();
	}
?>
