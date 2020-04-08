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
	if(!isset($_GET['payment_id'])){
		header("Location: view.php");
		die();
	}else{
		$payment_id = $_GET['payment_id'];
	}

	// This page should be only viewable by tenant
	// And additionally only if the property 'belongs' to them
	if(!restrictUsers(["Tenant"])){
		header("Location: view.php");
		die();
	}
	$results = null;
	$results = getPaymentDetails($user_id, $payment_id);
	if($results==null){
		header("Location: view.php");
		die();
	}

	$results = $results[0];
	$property_id = $results["property_id"];
	$cus_token = $results["cus_token"];
	$day = $results["day"];
	$rent_amount = (float)$results["amount"]/100;

	// Get card details
	$card_details = getCardDetails($db, $user_id);
	if(sizeof($card_details)){
		$tpl->assign("card_details", $card_details);
	}else{
		$tpl->assign("card_details", null);
	}

	// Get card details
	$tpl->assign("property_id", $property_id);
	$tpl->assign("payment_id", $payment_id);
	$tpl->assign("cus_token", $cus_token);
	$tpl->assign("rent_amount", $rent_amount);
	$tpl->draw("editPayment");

?>