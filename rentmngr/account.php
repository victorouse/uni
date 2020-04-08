<?php
    // Include Files
	include("inc/authenticated.php");
	// API Required for this page
	include("api/pin.php");
    include("api/account.php");
    include("api/recurring.php");
    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;

	// Assign all session variables to tpl to use
	assignUserVariables($tpl);

	// Get users name, email address, etc.

	// TODO: Replace this section with stuff from database
    $account_details = getUser($db, $email_address);
	$first_name = $account_details[0]['first_name']; 
    $last_name= $account_details[0]['last_name'];
    
	$tpl->assign("first_name", $first_name);
    $tpl->assign("last_name", $last_name);
	if($user_type=="Tenant"){
		// Get card details
		$card_details = getCardDetails($db, $user_id);
		if(sizeof($card_details)){
			$tpl->assign("card_details", $card_details[0]);
		}else{
			$tpl->assign("card_details", null);
		}

		// Process GET params
		// Remove params
		$removed = 0;
		if(isset($_GET['removed'])){
			$removed = 1;
		}
		$tpl->assign("removed", $removed);

		// Add params
		$add = 0;
		if(isset($_GET['add'])){
			$add = 1;
		}
		$tpl->assign("add", $add);
		// Get payment schedule
		$schedules = getPaymentSchedules($user_id);
		$tpl->assign("schedules", $schedules);
		$tpl->draw("account_settings_tenant");
	} else $tpl->draw("account_settings");

?>