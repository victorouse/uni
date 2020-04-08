<?php
    // Include Files
	include("inc/authenticated.php");

	// API Required for this page
	include("api/newsfeed.php");
	include("api/manage.php");
	include("api/financial.php");

    // Template
	include("inc/tpl.php");
	$tpl = new RainTPL;
	
	assignUserVariables($tpl);

	switch($user_type){
		case "Agent":
			// Do things
			// Print agent page

			// But for now redirect to newsfeed
			$results = selectProperties($user_type, $user_id);
			$property_id = $results[0]["property_id"];
			$property_address = $results[0]["address"];
			$items = getNewsfeedItems("property", $property_id);
			$financial_issues = countFinancialIssues($user_id);
			$item_count = count($items);
			$tpl->assign("property_address", $property_address);
			$tpl->assign("item_count", $item_count);
			$tpl->assign("days_count", 18);
			$tpl->assign("inspection_count", 0);
			$tpl->assign("property_issues", 0);
			$tpl->assign("financial_issues", $financial_issues);
			// Considering not showing newsfeed stuff on the front page?
			//$tpl->assign("items", $items);
			// Print tenant page
			$tpl->draw("ahome");
			if(restrictUsers(array("agent","owner"))){
           // Get owner/tenant/property information from view_management
			if(!ISSET($_GET['suburb'])){
			$results = selectProperties($user_type, $user_id);
			// Assign for template
			$tpl->assign("results", $results);
			// Render HTML
			$tpl->draw("manage_mini");
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
			break;
		
		case "Owner":
			// Do things
			// Print owner page
			// But for now redirect to newsfeed
			$results = selectProperties($user_type, $user_id);
			$property_id = $results[0]["property_id"];
			$property_address = $results[0]["address"];
			$items = getNewsfeedItems("property", $property_id);
			$item_count = count($items);
			$tpl->assign("property_address", $property_address);
			$tpl->assign("item_count", $item_count);
			$tpl->assign("property_issues", 3);
			$tpl->assign("financial_issues", 1);
			// Considering not showing newsfeed stuff on the front page?
			//$tpl->assign("items", $items);
			// Print tenant page
			$tpl->draw("ohome");
			
			if(ISSET($_GET['property'])){
	$property_id = $_GET['property'];
	// We must enforce that this user is allowed to view the property
	if(propertyExistsToUser($user_id, $property_id)){	
		// User is allowed to see the property, continue
		$items = getNewsfeedItems("property", $property_id);
		$results = getInfo($property_id);
		$property_address = $results['address'];
		$property_suburb = $results['suburb'];
		$tpl->assign("items", $items);
	
		/* TODO: Condense these next two functions into a single function
		*	they are now assumed variables from inc/authenticated.php,
		*	so could be an additional function inside inc/authenticated.php?
		*/
		$tpl->assign("user_id", $user_id);
	    $tpl->assign("user_name", $user_name);
	    $tpl->assign("property_id", $property_id);
	    $tpl->assign("property_address", $property_address);
	    $tpl->assign("property_suburb", $property_suburb);
		// TODO: Draw a different template for a property view
		$tpl->draw("newsfeed");
	}else{
		// User is not allowed to see this property, or it doesn't exist
		// TODO: Fix up design of this fail page
		$tpl->assign("user_id", $user_id);
	    $tpl->assign("user_name", $user_name);
		echo("Yeah so you can't see that property, that or it doesn't even exist!");
		$tpl->draw("newsfeed");
	}
	}else{
	$tpl->assign("user_id", $user_id);
    $tpl->assign("user_name", $user_name);
    $items = getNewsfeedItems($user_type, $user_id);
    
    // loop over items
    // do all of below for each item, create a $subitem and append it to the $items array
    $tpl->assign("items", $items);
	
	$tpl->draw("onewsfeed_mini");
	
	break;
   
}
		case "Tenant":
			// Do things
			// Get property id for latest tenancy
			$results = selectProperties($user_type, $user_id);
			$property_id = $results[0]["property_id"];
			$property_address = $results[0]["address"];
			$items = getNewsfeedItems("property", $property_id);
			$item_count = count($items);
			$tpl->assign("property_address", $property_address);
			$tpl->assign("item_count", $item_count);
			$tpl->assign("days_count", 18);
			$tpl->assign("inspection_count", 1);
			// Considering not showing newsfeed stuff on the front page?
			//$tpl->assign("items", $items);
			// Print tenant page
			$tpl->draw("thome");
			
					if(ISSET($_GET['property'])){
	$property_id = $_GET['property'];
	// We must enforce that this user is allowed to view the property
	if(propertyExistsToUser($user_id, $property_id)){	
		// User is allowed to see the property, continue
		$items = getNewsfeedItems("property", $property_id);
		$results = getInfo($property_id);
		$property_address = $results['address'];
		$property_suburb = $results['suburb'];
		$tpl->assign("items", $items);
	
		/* TODO: Condense these next two functions into a single function
		*	they are now assumed variables from inc/authenticated.php,
		*	so could be an additional function inside inc/authenticated.php?
		*/
		$tpl->assign("user_id", $user_id);
	    $tpl->assign("user_name", $user_name);
	    $tpl->assign("property_id", $property_id);
	    $tpl->assign("property_address", $property_address);
	    $tpl->assign("property_suburb", $property_suburb);
		// TODO: Draw a different template for a property view
		$tpl->draw("newsfeed");
	}else{
		// User is not allowed to see this property, or it doesn't exist
		// TODO: Fix up design of this fail page
		$tpl->assign("user_id", $user_id);
	    $tpl->assign("user_name", $user_name);
		echo("Yeah so you can't see that property, that or it doesn't even exist!");
		$tpl->draw("newsfeed");
	}
	}else{
	$tpl->assign("user_id", $user_id);
    $tpl->assign("user_name", $user_name);
    $items = getNewsfeedItems($user_type, $user_id);
    
    // loop over items
    // do all of below for each item, create a $subitem and append it to the $items array
    $tpl->assign("items", $items);
	$tpl->draw("tnewsfeed_mini");

			break;
	}
	}
?>