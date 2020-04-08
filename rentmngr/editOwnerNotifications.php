<?php
    include("inc/authenticated.php");
	// API's used in this page
	include("api/manage.php");	
	include("api/view.php");

	include("inc/tpl.php");
	$tpl = new RainTPL;
	assignUserVariables($tpl);
    if ($user_type == 'Owner') {
        if(ISSET($_GET['property_id'])){
            // A property has been selected
            $property_id = $_GET['property_id'];
            $property_details = getInfo($property_id);
            $notification_details = getNotificationDetails($property_id, $user_id);
            $_SESSION['property_id'] = $property_id;
            // TODO: Check if property_id is allowed for this user
            $tpl->assign("notify_structural", $notification_details[0]['notify_structural']);
            $tpl->assign("notify_plumbing", $notification_details[0]['notify_plumbing']);
            $tpl->assign("notify_security", $notification_details[0]['notify_security']);
            $tpl->assign("notify_electrical", $notification_details[0]['notify_electrical']);
            $tpl->assign("property_id", $property_id);
            $tpl->assign("property_details", $property_details);
            $tpl->draw("onewsfeed_editsettings_property");

        }else{
            // A property has not yet been selected
            $properties = selectProperties($user_type, $user_id);
            $tpl->assign("properties", $properties);
            $tpl->draw("onewsfeed_editsettings_noproperty");
        }
    } else print "redirect";





?>