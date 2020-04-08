<?php
	include("inc/authenticated.php");
	// API's used in this page
	include("api/manage.php");	
	include("api/view.php");

	include("inc/tpl.php");
	$tpl = new RainTPL;
	assignUserVariables($tpl);

	if(ISSET($_GET['property_id'])){
		// A property has been selected
		$property_id = $_GET['property_id'];
		$property_details = getInfo($property_id);
		// TODO: Check if property_id is allowed for this user
		$tpl->assign("property_id", $property_id);
		$tpl->assign("property_details", $property_details);

		$tpl->draw("newsfeed_additem_property");

	}else{
		// A property has not yet been selected
		$properties = selectProperties($user_type, $user_id);
		$tpl->assign("properties", $properties);
		$tpl->draw("newsfeed_additem_noproperty");
	}
?>