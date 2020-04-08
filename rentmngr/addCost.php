<?php
	include("inc/authenticated.php");
	// API's used in this page
	include("api/view.php");	

	include("inc/tpl.php");
	$tpl = new RainTPL;
	assignUserVariables($tpl);
	
	$property_id = $_REQUEST['property'];
	$tenant_id = $_REQUEST['tenant'];
	$item_id = $_REQUEST['item'];
	
	$tenancy_result = getTenancyID($property_id, $tenant_id);
	$tenancy_id = $tenancy_result[0]['tenancy_id'];
	$property_details = getInfo($property_id);
	
	$tpl->assign("property_details", $property_details);
	$tpl->assign("item_id", $item_id);
	$tpl->assign("tenancy_id", $tenancy_id);
	$tpl->draw("newsfeed_addcost");
	
?>