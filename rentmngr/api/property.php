<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
    require_once(dirname(__DIR__) . "/inc/authenticated.php");
    
    /*******************/
    /* ACCOUNT METHODS */
    /*******************/
	
	/**
	*	Add a new property and setup the agent as the manager
	*	@param int user_id - user_id of the agent
	*	@param string address - property address
	*	@param string suburb
	*	@param string state
	*	@return int property_id of newly created property
	*/
	function addProperty($user_id, $address, $suburb, $state) {
		global $db;

		// Add the property
		$query = "INSERT INTO properties (address, suburb, state) 
			VALUES (?, ?, ?)";

		$stmt = $db->prepare($query);		
		$stmt->execute(array($address, $suburb, $state));

		// Get the property_id we just created
		$property_id = $db->lastInsertId();

		// Add set up this user as the manager
		$query = "INSERT INTO management (property_id, agent_id) 
			VALUES (?, ?)";
			
		$stmt = $db->prepare($query);		
		$stmt->execute(array($property_id, $user_id));
		return $property_id;
	}

    /*******************/
    /*    DELEGATOR    */
    /*******************/
	if (isset($_REQUEST['api'])) {
		$type = $_REQUEST['api'];
	
	    switch($type) {
	        case "addProperty":
		        // Get form variables
				$address = $_REQUEST['address'];
				$suburb = $_REQUEST['suburb'];
				$state = $_REQUEST['state'];
	            $new_property_id = addProperty($user_id, $address, $suburb, $state);
	            
	            // Redirect back to view page for this new property
				header("Location: ../view.php?property=".$new_property_id);
	            die();
	            break;
	    }
	}
?>