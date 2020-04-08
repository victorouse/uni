<?php
    // Include Files
	include("inc/authenticated.php");

	// API Required for this page
	include("api/financial.php");
	include("api/recurring.php");
    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;
	
	assignUserVariables($tpl);

	if(!ISSET($_REQUEST['property'])){
		header("Location: manage.php");
		die();
	}
    // Get property from form
	$property_id = $_REQUEST['property'];
	
	if(isset($_REQUEST['add'])) {
		$info = getInfo($property_id);
		$tpl->assign("property_id", $property_id);
		$tpl->draw("add_payment_bank");
		exit();
	}
	
	//add to tenant
	if($user_type == "Tenant"){
		// Tenant Financials
		
		if(!ISSET($_REQUEST['start']) || $_REQUEST['start'] == ''){
			$start = date('Y')."-07-01";
		} else {
			$start = $_REQUEST['start'];
		}
		
		if(!ISSET($_REQUEST['end']) || $_REQUEST['end'] == ''){
			$year = date('Y') + 1;
			$end = $year."-07-01";
		} else {
			$end = $_REQUEST['end'];
		}
		

		$info = getInfo($property_id);
		$rent = getRent($property_id, $start, $end);
		$address = $info["address"];
		// Get payment schedule
		$schedules = getPaymentSchedules($user_id);
		// Assign TPL variables

		$tpl->assign("schedules", $schedules);
		$tpl->assign("rent", $rent);
		$tpl->assign("results", $info);
		$tpl->assign("property_id", $property_id);
		$tpl->assign("address", $address);
		// List all rent payments
		$tpl->draw("financial_tenant");

	}elseif($user_type == "Agent" || $user_type == "Owner"){
		// Agent Financials	
		
		if(!ISSET($_REQUEST['start']) || $_REQUEST['start'] == ''){
			$startDate = date('Y')."-07-01";
		} else {
			$startDate = $_REQUEST['start'];
		}
		
		if(!ISSET($_REQUEST['end']) || $_REQUEST['end'] == ''){
			$year = date('Y') + 1;
			$endDate = $year."-07-01";
		} else {
			$endDate = $_REQUEST['end'];
		}
		//it shows the requested time, not approved time - need to figure that out

		$info = getInfo($property_id);
		$rent = getRent($property_id, $startDate, $endDate);

		$maintenance = getMaintenance($property_id, $startDate, $endDate);
		$main_total = getMaintenanceTotal($property_id, $startDate, $endDate);
		$cc_total = getCCTotal($property_id, $startDate, $endDate);
		$other_total = getOtherTotal($property_id, $startDate, $endDate);
		$rent_final_total = $cc_total[0]['cc_total'] + $other_total[0]['other_total'];
		$payout = getPayouts($property_id, $startDate, $endDate);
		$payout_total = getPayoutsTotal($property_id, $startDate, $endDate);
		$address = $info["address"];
		
		$total = $rent_final_total - $main_total[0]['total'] - $payout_total[0]['total'];
		// Convert to dollars
		$total /= 100;
		$days_until_due = daysUntilRentDue($property_id, $rent_final_total);
		
		$owner = getOwner($property_id);
		$ownership_id = $owner[0]['ownership_id'];

		// pending payments array
		$a = getPendingPayments($property_id);
		$total_table = getTotalTable($property_id, $startDate, $endDate);

		$tpl->assign("days_until_due", $days_until_due);
		$tpl->assign("total_table", $total_table);
		$tpl->assign("property", $property_id);
		$tpl->assign("ownership_id", $ownership_id);
		$tpl->assign("total", $total);
		$tpl->assign("rent", $rent);
		$tpl->assign("payout", $payout);
		$tpl->assign("maintenance", $maintenance);
		$tpl->assign("results", $info);
		$tpl->assign("address", $address);
	    // Render HTML
		if ($user_type == "Agent") {
			$tpl->draw("financial_agent"); 
		} else {
			$tpl->draw("financial_owner");
		}
	}
	
?>