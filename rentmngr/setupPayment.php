<?php
    // Include Files
	include("inc/authenticated.php");


	// API Required for this page
	include("api/financial.php");
	include("api/recurring.php");
	// Newsfeed is used for the propertyExistsToUser function
	include("api/newsfeed.php");
	

    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;
	assignUserVariables($tpl);

	// If property isn't set
	if(!isset($_GET['property'])){
		header("Location: view.php");
		die();
	}else{
		$property_id = $_GET['property'];
	}

	// This page should be only viewable by tenant
	// And additionally only if the property 'belongs' to them
	if(!restrictUsers(["Tenant"])||!propertyExistsToUser($user_id, $property_id)){
		header("Location: view.php");
		die();
	}

	// Get the daily rent (in cents)
	$daily_rent = getDailyRent($user_id, $property_id);
	
	// Calculate fortnightly rent (in dollars)
	$fortnight_rent = $daily_rent * 14 /100;
	// User is qualified
	// Get card details
	$card_details = getCardDetails($db, $user_id);
	if(sizeof($card_details)){
		$tpl->assign("card_details", $card_details);
	}else{
		$tpl->assign("card_details", null);
	}
	$tpl->assign("property_id", $property_id);
	$tpl->assign("rent_amount", $fortnight_rent);
	$tpl->draw("setupPayment");

?>