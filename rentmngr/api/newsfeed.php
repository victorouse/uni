<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
	require_once(dirname(__DIR__) . "/inc/authenticated.php");

    /********************/
    /* NEWSFEED METHODS */
    /********************/

	/**
	*	This function is a somewhat complex function that handles the getting of newsfeed items.
	*		There are 4 'types' of newsfeeds: agent, owner, tenant and property. A mysql view is
	*		used to handle the complex table joins (about 9 of them) to construct a newsfeed view.
	*		Based on the type, a different where clause is used to restrict the newsfeed elements 
	*		to be only the relevant posts.
	*	@param string type
	*		User type as set in the session variables but could be overwritten as 'property'
	*	@param int primary_id
	*		This is either the user_id in the case of agent, tenant and owner types but could be
	*			property_id in the case of type being 'property'
	*	@throws Exception "Unknown usertype, please log in again"
	*		If type is not agent, tenant, owner or property
	*	@return Array An associative array of newsfeed items and details
	*/
    function getNewsFeedItems($type, $primary_id){
		global $db;
		// Enforce that only our three usertypes can pass through
		$all_query_types = array("agent","tenant", "owner", "property");
		if(!in_array(strtolower($type), $all_query_types)){
			// Somewhere the wrong user type has snuck in!
			throw new Exception("Unknown usertype, please log in again");
			return null;
		}
		// Based on the sort of query this is, we need to use different queries, slightly
		$items_query = "";
		switch(strtolower($type)){
			case "agent":
			$items_query = "select * from view_newsfeed where agent_id = ? ORDER BY item_id DESC";
			break;
			case "owner":
			$items_query = "select * from view_newsfeed where owner_id = ? ORDER BY item_id DESC";
			break;
			case "tenant":
			$items_query = "select * from view_newsfeed where tenant_id = ? ORDER BY item_id DESC";
			break;
			case "property":
			$items_query = "select * from view_newsfeed where property_id = ? ORDER BY item_id DESC";
			break;
		}
		// Regardless of the type of query, it's the same view for subitems
        $subitems_query = "select * from view_newsfeed_subitems where item_id = ?";
        $items = array();
        if ($stmt = $db->prepare($items_query)) {
			// Execute the appropriate view with the primary_id passed in
            $test = array("$primary_id");
            $stmt->execute($test);
			$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
		
        if (strtolower($type) == 'owner') {
            $items_owner = array();
            foreach($items as &$item){
                $add = false;
                if ($item['escalated']) {
                    $add = true;
                } else {
                    switch ($item['issue_category']) {
                        case "electrical":
                            if ($item['notify_electrical']) {
                                $add = true;        
                            }
                            break;
                        case "plumbing":
                            if ($item['notify_plumbing']) {
                                $add = true;        
                            }
                            break;
                        case "structural":
                            if ($item['notify_structural']) {
                                $add = true;        
                            }
                            break;
                        case "security":
                            if ($item['notify_security']) {
                                $add = true;        
                            }
                            break;
                        default:
                            $add = true;
                            break;
                    }
                }
                if ($add) {
                    array_push($items_owner, $item);
                }
            }
            $items = $items_owner;
        }
            
        // Loop over each item and retrieve all their subitems
        foreach($items as &$item){
            $subitems = array();
            if ($stmt = $db->prepare($subitems_query)) {
                    $stmt->bindParam(1, $item["item_id"]);
                    $stmt->execute();
                    $subitems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

			// Add the subitems & number of subitems to the item array
        	$item["subitems"] = $subitems;
        	$item["numbersubitems"] = count($subitems);
        } 
        return $items;
    }
    
	/**
	*	This function determines if this user is a tenant, owner or agent of a property
	*	@param int user_id
	*		User_id of the user in question
	*	@param int property_id
	*		Property_id of the property in question
	*	@returns int Number of matches in the `view_management`
	*		(0 if no matches, else >= 1 if a match)
	*/
	function propertyExistsToUser($user_id, $property_id){
		global $db;
		$query = "SELECT * FROM `view_management` WHERE (`tenant_id` = ? OR `owner_id` = ? OR `agent_id` = ?) AND property_id = ?";
		if($stmt = $db->prepare($query)){
			$stmt->execute(array($user_id, $user_id, $user_id, $property_id));
			return $stmt->rowCount();
		}
		// If the $stmt didn't work, just assume they don't have rights
		return 0;
	}

	/**
	*	This function closes a newsfeed item
	*	@param int item_id
	*		item_id of the item to be closed
	*/
	function closeItem($item_id){
		global $db;
		$sql = "UPDATE items
				SET open = 0
				WHERE item_id = ?";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($item_id));
		//Redirect user home
		header("Location: ../newsfeed.php");

	}
      
	/**
	*	This function adds a comment to a newsfeed item
	*	@param int user_id
	*		user_id of the person that is posting
	*	@param string description
	*		Text for the comment
	*	@param date date_created
	*		Date in 'Y-m-d H:i:s' format
	*	@param int item_id
	*		The item for which the comment belongs too
	*/
    function addComment($user_id, $description, $date_created, $item_id){
		global $db;
		// insert into subitems
		$query = "INSERT INTO subitems (item_id, description, type, date)
		VALUES (?, ?, ?, ?)";
		$stmt = $db->prepare($query);		
		$stmt->execute(array($item_id, $description, "comment", $date_created));

		// Get the ID of the subitem we just inserted
		$subitemId = $db->lastInsertId();

		// Then add into subitem_comments
		$query = "INSERT INTO subitem_comments (subitem_id, user_id)
		VALUES (?, ?)";

		$stmt = $db->prepare($query);		
		$stmt->execute(array($subitemId, $user_id));

		// Redirect back to page
		header("Location: ../newsfeed.php");
	}

	/**
	*	This function adds a comment to a newsfeed item
	*	@param int user_id
	*		user_id of the person that is posting
	*	@param int requires_user_id
	*		user_id of the person that needs to accept the request
	*	@param string description
	*		Text for the request
	*	@param date date_created
	*		Date in 'Y-m-d H:i:s' format
	*	@param int item_id
	*		The item for which the request belongs too
	*/
	function addRequest($user_id, $requires_user_id, $description, $date_created, $item_id){
	    global $db;
		
		// Insert into subitems
		$query = "INSERT INTO subitems ( item_id, description, type, date)
		    VALUES (?, ?, ?, ?)";
		$stmt = $db->prepare($query);		
		$stmt->execute(array($item_id, $description, "request", $date_created));
		 
		// get the subitem_id
		$subitemId = $db->lastInsertId();
		
		// Then insert into subitems_request
		$query = "INSERT INTO subitem_requests (subitem_id, posted_user_id, requires_user_id, approved)
		    VALUES (?, ?, ?, ?)";
		$stmt = $db->prepare($query);		
		$stmt->execute(array($subitemId, $user_id, $requires_user_id, 0));
	
		// Redirect back to property newsfeed
		header("Location: ../newsfeed.php");
	 }

	/**
	*	Master process to add any sort of newsfeed item that delegates out into
	*		issue items and inspections items. Particularly useful for future 
	*		flexibility.
	*	@param int property_id
	*		property_id for which this item is attached to
	*	@param string description
	*		the text which forms part of the post, i.e. issue text
	*	@param string type
	*		type of post i.e. issue or inspection
	*	@param int created_by
	*		user_id of the person who created the post
	*	@param string cat
	*		issue category (only used if type is 'issue')
	*	@return boolean Status indicating the success off adding the item
	*/
	function addItemControl($property_id, $description, $type, $created_by, $cat) {
		// try to add item, addItem will return false if type is not valid
		$item_id = addItem($property_id, $description, $type, $created_by);

		// if item was made successfully
		if ($item_id) {
			if ($type=='issue') {
				$status = addIssueItem($item_id, $cat);
			}
			else {
				$status = addInspectionItem($item_id);
			}
		} else $status = false;

		if($status){
			header("Location: ../newsfeed.php?property=".$property_id);
			die();
		}
		return $status;
	}
    
    /**
    *	Function to add an item into the items table (also need to add to)
    *		issue items table and inspection items table.
   	*	@param int property_id
	*		property_id for which this item is attached to
	*	@param string description
	*		the text which forms part of the post, i.e. issue text
	*	@param string type
	*		type of post i.e. issue or inspection
	*	@param int created_by
	*		user_id of the person who created the post
	*	@return false or item_id of the newly created item
    */
    function addItem($property_id, $description, $type, $created_by) {
        global $db;
        $all_query_types = array("issue","inspection");
        if(!in_array(strtolower($type), $all_query_types)){
        	// The type wasn't an expected type
            return false;
        }
        // Date starts from right now
        $date_created = date('Y-m-d H:i:s');
		// Items should start open
        $open = 1;

        try {
            $query = "INSERT INTO items 
                (created_by, property_id, description, date_created, 
                type, open) VALUES (?,?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($created_by, $property_id, $description, 
                $date_created, $type, $open));
            return $db->lastInsertId();
            // TODO: Implement emails being sent for notifications
            // To be implemented before deployment after server configuration
            // is known

            // email owner if they should receive it
            //emailOwner()
            // email agent
            //emailAgent()
        } catch (PDOException $e) {
            return false;
        }   
    }
    
    /**
    *	Add the the item to be an issue.
    * 	@param int item_id
    *		item_id which was just added
    *	@param string cat
    *		issue category represented as a string
    *	@return Boolean Status indicating the result 
    */
    function addIssueItem($item_id, $cat) {
        global $db;
        $escalated = 0;
        $all_query_types = array("structural","electrical","plumbing","security");
        if(!in_array(strtolower($cat), $all_query_types)){
            return false;
        }
        try {
            $query = "INSERT INTO issue_items 
                (item_id, issue_category, escalated) VALUES (?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($item_id, $cat, $escalated));
            return true;
        } catch (PDOException $e) {
            return false;
        }   
    }
    
    /**
    *	Add the the item to be an inspection.
    * 	@param int item_id
    *		item_id which was just added
    *	@return Boolean Status indicating the result 
    */
    function addInspectionItem($item_id) {
        global $db;
        try {
            $query = "INSERT INTO inspection_items 
                (item_id) VALUES (?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($item_id));
            return true;
        } catch (PDOException $e) {
            return false;
        }   
    }
    
    /**
    *	Function which marks an issue item as escalated, such as to
    *	force this into the owners newsfeed, regardless of their
    *	notification settings.
    *	@param int item_id
    *		item_id to be escalated
    */
    function escalateItem($item_id) {
    	global $db;
		$sql = "UPDATE issue_items
				SET escalated = 1
				WHERE item_id = ?";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($item_id));
		header("Location: ../newsfeed.php");
    }
	
	/**
	*	Adds a maintenance cost to the financials for a property for a
	*	given item_id.
	*	@param int item_id
	*		item_id of the issue item
	*	@param string description
	*		A text-description of the cost
	*	@param int tenancy_id
	*		tenancy_id for which the cost is added to
	*	@return Boolean Success of this operation
	*/
	function addCost($item_id, $description, $tenancy_id, $cost) {
		global $db;
		$current_date = date('Y-m-d H:i:s');
		$cost *= 100;
		try{
			$sql = "INSERT INTO maintenance_costs (tenancy_id, item_id, amount, comment, request_datetime)
					VALUES (?,?,?,?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute(array($tenancy_id, $item_id, $cost, $description, $current_date));
			header("Location: ../newsfeed.php");
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}
    
    /*******************/
    /*    DELEGATOR    */
    /*******************/
	if(isset($_REQUEST['api'])){
	    $type = $_REQUEST['api'];

	    switch($type) {
	        case "close":
				closeItem($_REQUEST["item_id"]);
	            break;
            case "escalate":
                escalateItem($_REQUEST["item_id"]);
                break;
            case "addComment":
            	// Get form variables
				$description = $_REQUEST['description'];
				$date_created = date('Y-m-d H:i:s');
				$item_id = $_REQUEST['item_id'];
                addComment($user_id, $description, $date_created, $item_id);
                break;
            case "addRequest":
				// Get form variable
				$description = $_REQUEST['request_description'];
				$requires_user_id = $_REQUEST['requires_user_id'];
				$date_created = date('Y-m-d H:i:s');
				$item_id = $_REQUEST['item_id'];
                addRequest($user_id, $requires_user_id, $description, $date_created, $item_id);
                break;
            case "addItem":
                $property_id = $_POST['property_id'];
                $description = $_POST['description'];
                $n_type = $_POST['type'];
                $created_by = $_POST['user_id'];
				
                if (isset($_POST['cat'])) {
                    $cat = $_POST['cat'];
                } else $cat = false;
                addItemControl($property_id, $description, $n_type, $created_by, $cat);
                break;
			case "addCost":
				$item_id = $_POST['item_id'];
				$description = $_POST['description'];
				$tenancy_id = $_POST['tenancy_id'];
				$cost = $_POST['cost'];
				addCost($item_id, $description, $tenancy_id, $cost);
				break;
	    }
	}
?>
