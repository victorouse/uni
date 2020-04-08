<?php

// Include Files
include("inc/authenticated.php");

// API Required for this page
include("api/newsfeed.php");
include("api/view.php");

// Template
include("inc/tpl.php");
$tpl = new RainTPL;

// Assign all session variables to tpl to use
assignUserVariables($tpl);

if (ISSET($_GET['property'])) {
    $property_id = $_GET['property'];
    // We must enforce that this user is allowed to view the property
    if (propertyExistsToUser($user_id, $property_id)) {
        // User is allowed to see the property, continue
        $items = getNewsfeedItems("property", $property_id);
        $results = getInfo($property_id);
        $property_address = $results['address'];
        $property_suburb = $results['suburb'];
        $tpl->assign("items", $items);

        /* TODO: Condense these next two functions into a single function
         * 	they are now assumed variables from inc/authenticated.php,
         * 	so could be an additional function inside inc/authenticated.php?
         */
        $tpl->assign("user_id", $user_id);
        $tpl->assign("user_name", $user_name);
        $tpl->assign("property_id", $property_id);
        $tpl->assign("property_address", $property_address);
        $tpl->assign("property_suburb", $property_suburb);
        // TODO: Draw a different template for a property view
        $tpl->draw("newsfeed");
    } else {
        // User is not allowed to see this property, or it doesn't exist
        // TODO: Fix up design of this fail page
        $tpl->assign("user_id", $user_id);
        $tpl->assign("user_name", $user_name);
        echo("Yeah so you can't see that property, that or it doesn't even exist!");
        $tpl->draw("newsfeed");
    }
} else {
    $tpl->assign("user_id", $user_id);
    $tpl->assign("user_name", $user_name);
    $items = getNewsfeedItems($user_type, $user_id);

    // loop over items
    // do all of below for each item, create a $subitem and append it to the $items array
    if (ISSET($_GET['sort_date'])) {
            $date = array();
            foreach($items as $key => $row){
                $date[$key] = $row['date_created'];
            }
            array_multisort($date, SORT_ASC, $items);
            
        }
        
        if (ISSET($_GET['sort_date_desc'])) {
            $date = array();
            foreach($items as $key => $row){
                $date[$key] = $row['date_created'];
            }
            array_multisort($date, SORT_DESC, $items);
            
        }
        
        if (ISSET($_GET['show_inspection'])) {
            $inspection = array();
            $index = 0;
            foreach($items as $key => $row){
                if($row['type'] == "inspection"){
                $inspection[$index] = $items[$key];
                $index++;
                }
            }
            $items = $inspection;
            //array_multisort($date, SORT_ASC, $items);
            
        }
        
        if (ISSET($_GET['show_issue'])) {
            $issue = array();
            $index = 0;
            foreach($items as $key => $row){
                if($row['type'] == "issue"){
                $issue[$index] = $items[$key];
                $index++;
                }
            }
            $items = $issue;
            //array_multisort($date, SORT_ASC, $items);
            
        }
        
        if (ISSET($_GET['show_open'])) {
            $open = array();
            $index = 0;
            foreach($items as $key => $row){
                if($row['open'] == 1){
                $open[$index] = $items[$key];
                $index++;
                }
            }
            $items = $open;
            //array_multisort($date, SORT_ASC, $items);
            
        }
        
        if (ISSET($_GET['show_closed'])) {
            $closed = array();
            $index = 0;
            foreach($items as $key => $row){
                if($row['open'] == 0){
                $closed[$index] = $items[$key];
                $index++;
                }
            }
            $items = $closed;
            //array_multisort($date, SORT_ASC, $items);
            
        }
        $tpl->assign("items", $items);
        $tpl->draw("newsfeed");
    

//    if ($user_type == "agent") {
//        $tpl->draw("newsfeed");
//    } else {
//        $tpl->draw("newsfeed");
//    }
}
?>