<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
	
    /*******************/
    /* ACCOUNT METHODS */
    /*******************/
	/**
	*	Main function to get all information for a property view
	*	@param int propId - property_id
	*	@return Associative Array with property view information\
	*/
	function getInfo($propId) {
		global $db;
		
		// Many table join
		$query = "SELECT management.property_id, tenancy.tenant_id, 
			tenancy.start_date, tenancy.end_date, ownership.owner_id, 
			owner.email_address, ownership.notify_structural, 
			ownership.notify_plumbing, ownership.notify_electrical, 
			ownership.notify_security, management.agent_id, 
			concat(agent.first_name, ' ', agent.last_name) AS AgentName, 
			concat(tenant.first_name, ' ', tenant.last_name) AS TenantName, 
			concat(owner.first_name, ' ', owner.last_name) AS OwnerName, 
			properties.address, properties.suburb, properties.state FROM management 
			LEFT JOIN tenancy ON tenancy.property_id = management.property_id LEFT 
			JOIN ownership ON management.property_id = ownership.property_id LEFT 
			JOIN properties ON management.property_id = properties.property_id LEFT 
			JOIN users AS agent ON management.agent_id = agent.user_id LEFT JOIN 
			users as tenant on tenancy.tenant_id = tenant.user_id LEFT JOIN users 
			as owner on ownership.owner_id = owner.user_id WHERE 
			properties.property_id = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($propId));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// fetchAll returns an array of results (therefore array of arrays)
		// so need to return the 0th element
		return $results[0];
	}
    
    
	/**
	*	Function to check if there is a referral of a given type
	*		on a given property - used for displaying on the view
	*		page.
	*	@param string type - tenant or owner
	*	@param int property_id
	*	@return null or associative array of referral details
	**/
	function checkReferral($type, $property_id) {
		global $db;
		$query = "SELECT id, referrerid, email_address, property_id, 
			type FROM referred WHERE property_id = ? AND type = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($property_id, $type));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($results)){
			return $results[0];
		}else{
			return null;
		}
		
	}
    
    /**
    *	Retrieve the owners notification settings for a properties owner
    *	@param int property_id
    *	@param in user_id - owner's user_id
    *	@return Associative array of notification details
    */
    function getNotificationDetails($property_id, $user_id) {
        global $db;
		$query = "SELECT notify_structural, notify_security, notify_plumbing,
                    notify_electrical FROM ownership WHERE property_id = ?
                    AND owner_id = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($property_id, $user_id));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
	}
	
	/**
	*	Get the tenancy_id for a given property_id & tenant_id
	*	@param int property_id
	*	@param int tenant_id - user_id for the tenant
	*	@return Associative array of tenancy_id
	*/
	function getTenancyID($property_id, $tenant_id) {
		global $db;
		$query = "SELECT tenancy_id FROM tenancy WHERE property_id = ? AND tenant_id = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($property_id, $tenant_id));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
    
    /**
    *	Useful function to check whether two users are related
    *		e.g. user1 is a tenant at a property where user2 is the owner
    *	@param int user_id
    *	@param string user_type - type of the primary user: agent, tenant, owner
    *	@param string other_user - the user_id for this other user
    *	@return int - 0 if no relation, any other values, the users are related
    */
    function related($user_id, $user_type, $other_user){
    	global $db;
    	switch($user_type){
    		case 'Agent':
    		$query = "SELECT count(*) FROM management 
			JOIN tenancy as t on t.property_id = management.property_id
			JOIN ownership as o on o.property_id = management.property_id
			WHERE t.tenant_id = ? OR o.owner_id = ?
			";
			break;
			case 'Owner':
			$query = "SELECT count(*) FROM ownership 
			JOIN tenancy as t on t.property_id = ownership.property_id
			JOIN management as m on m.property_id = ownership.property_id
			WHERE t.tenant_id = ? OR m.agent_id = ?
			";
			break;
			case 'Tenant':
			$query = "SELECT count(*) FROM tenancy
			JOIN management as m on m.property_id =tenancy.property_id
			JOIN ownership as o on o.property_id =tenancy.property_id
			WHERE o.owner_id = ? OR m.agent_id = ?
			";
    	}
		$stmt = $db->prepare($query);
		$stmt->execute(array($other_user, $other_user));
		$related = $stmt->fetchColumn();
		return $related;
    }
	
?>