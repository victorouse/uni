<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
    require_once(dirname(__DIR__) . "/api/view.php");
    require_once(dirname(__DIR__) . "/api/manage.php");

    /*******************/
    /* FINANCIAL METHODS */
    /*******************/
	
	/**
	*	Retrieve a financial table for owners and agents that includes maintenance costs,
	*		credit card charges, manual payments and owner payouts for a specified property
	*	@param int property_id
	*	@param string startdate
	*		start date of the range for financial details in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		start date of the range for financial details in 'Y-m-d H:i:s' format
	*/
    function getTotalTable($property_id, $startdate, $enddate){
		global $db;
		global $user_id;
        // Get the daily rent amount
        try{
            $query = "SELECT amount as other, '' as cc_charge, '' as maintenance, '' as payout, request_datetime AS datetime, NULL as cc_charge_id, comment FROM other_payment 
            		JOIN tenancy as t on t.tenancy_id = other_payment.tenancy_id WHERE t.property_id = ? AND request_datetime BETWEEN date(?) and date(?)
					UNION ALL
					SELECT '', amount, '', '', datetime, cc_charge_id, 'Automatic rent payment' FROM cc_charges
					JOIN tenancy as t on t.tenancy_id = cc_charges.tenancy_id WHERE t.property_id = ? AND datetime BETWEEN date(?) and date(?)
					UNION ALL
					SELECT '', '', amount, '', request_datetime AS datetime, NULL, comment FROM maintenance_costs
					JOIN tenancy as t on t.tenancy_id = maintenance_costs.tenancy_id WHERE t.property_id = ? AND request_datetime BETWEEN date(?) and date(?)
					UNION ALL
					SELECT '', '', '', amount, datetime, NULL, comment FROM owner_payments as o_p LEFT JOIN ownership as o on o_p.ownership_id = o.ownership_id WHERE property_id = ? AND datetime BETWEEN date(?) and date(?) 
					ORDER BY datetime DESC";
            $stmt = $db->prepare($query);
            $stmt->execute(array($property_id, $startdate, $enddate, $property_id, $startdate, $enddate, $property_id, $startdate, $enddate, $property_id, $startdate, $enddate));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
            return 0;
        }
    }

    /**
    *	Function to retrieve daily rent for a user at a property
    *	@param user_id - user_id of the tenant
    *	@param property_id
    *	@return null or daily rent amount for the tenancy
    */
	function getDailyRent($user_id, $property_id){
		global $db;
        // Get the daily rent amount
        try{
            $query = "SELECT daily_rent from tenancy WHERE tenant_id = ? and property_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id, $property_id));
            //success
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
            return null;
        }
	}

	/**
	*	Retrieve the paid rent amount (both from credit card charges and other payments) for
	*	a particular property over a given range
	*	@param int propID - property_id
	*	@param string startdate
	*		start date of the range for rent payment in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range for rent payment in 'Y-m-d H:i:s' format
	*	@return Associative array of rent details
	*/
	function getRent($propId, $startdate, $enddate) {
		global $db;
		
		$tenant = getTenant($propId);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$tenancyID = $tenant[0]["tenancy_id"];
		
		//shows tenant name, only use though is if the property goes 
		//through multiple tenants and want to see separate tenants but retain old ones
		$query = "
			SELECT concat(u.first_name, ' ', u.last_name) AS name, 
				c.amount as 'rent', c.datetime, 'Credit Card' AS type, ' ' AS comment, ' ' as main, ' ' as payout
			FROM cc_charges AS c
			LEFT JOIN tenancy AS t ON c.tenancy_id=t.tenancy_id
			LEFT JOIN users AS u ON u.user_id =t.tenant_id
			WHERE c.tenancy_id = ? AND c.datetime >= ? AND c.datetime <= ?
			UNION ALL
			SELECT concat(u.first_name, ' ', u.last_name) AS name, 
				o.amount as 'rent', o.request_datetime, 'Bank Transfer' AS type, o.comment, ' ' as main, ' ' as payout
			FROM other_payment AS o
			LEFT JOIN tenancy AS t ON o.tenancy_id=t.tenancy_id
			LEFT JOIN users AS u ON u.user_id =t.tenant_id
			WHERE o.tenancy_id = ? AND o.request_datetime >= ? AND o.request_datetime <= ?
			ORDER BY datetime";
			
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($tenancyID, $startdate, $enddate, $tenancyID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Function to recieve the total amount of credit card
	*	payments for a given period.
	*	@param int propId - property_id
	*	@param string startdate
	*		start date of the range for credit card payments in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range for credit card payments in 'Y-m-d H:i:s' format
	*	@return Associative array with the total credit card amount
	*/
	function getCCTotal ($propId, $startdate, $enddate) {
	
		global $db;
		$tenant = getTenant($propId);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$tenancyID = $tenant[0]["tenancy_id"];
		
		$query = "
			SELECT SUM(c.amount) as `cc_total`
			FROM cc_charges AS c
			LEFT JOIN tenancy AS t ON c.tenancy_id=t.tenancy_id
			LEFT JOIN users AS u ON u.user_id =t.tenant_id
			WHERE c.tenancy_id = ? AND c.datetime >= ? AND c.datetime <= ?
			ORDER BY c.amount";
			
		$stmt = $db->prepare($query);
		$stmt->execute(array($tenancyID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Retrieve the total amount of other payments made for a given
	*	property_id in a given date range.
	*	@param int propId - property_id
	*	@param string startdate
	*		start date of the range for rent payment in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range for rent payment in 'Y-m-d H:i:s' format
	*	@return Associative array with the total payment amount
	*/
	function getOtherTotal ($propId, $startdate, $enddate) {
	
		global $db;
		$tenant = getTenant($propId);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$tenancyID = $tenant[0]["tenancy_id"];
		
		$query = "SELECT SUM(o.amount) AS other_total
			FROM other_payment AS o
			LEFT JOIN tenancy AS t ON o.tenancy_id=t.tenancy_id
			LEFT JOIN users AS u ON u.user_id =t.tenant_id
			WHERE o.tenancy_id = ? AND o.request_datetime >= ? AND o.request_datetime <= ?
			ORDER BY amount";
			
		$stmt = $db->prepare($query);
		$stmt->execute(array($tenancyID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Retrieve an array of maintenance charges for a given date range
	*	and given property
	*	@param int propId - property_id
	*	@param string startdate
	*		start date of the range for maintenance charge in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range for maintenance charge in 'Y-m-d H:i:s' format
	*	@return Associative array with the maintenance charges
	*/
	function getMaintenance($propId, $startdate, $enddate) {
		global $db;
		
		$tenant = getTenant($propId);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$tenancyID = $tenant[0]["tenancy_id"];
		
		//shows tenant name, only use though is if the property goes 
		//through multiple tenants and want to see separate tenants but retain old ones
		$query = "
			SELECT concat(u.first_name, ' ', u.last_name) AS name, 
				c.amount/100 as amount, c.approved_datetime, i.issue_category, c.comment, ' ' as rent, ' ' as payout
			FROM maintenance_costs AS c
			LEFT JOIN tenancy AS t ON c.tenancy_id=t.tenancy_id
			LEFT JOIN issue_items AS i ON c.item_id=i.item_id
			LEFT JOIN users AS u ON u.user_id =t.tenant_id
			WHERE c.tenancy_id = ? AND c.request_datetime >= ? AND c.request_datetime <= ?
			GROUP BY c.approved_datetime";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($tenancyID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Retrieve the total maintenance charge amount for a given date range
	*	and given property
	*	@param int propId - property_id
	*	@param string startdate
	*		start date of the range for maintenance charge in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range for maintenance charge in 'Y-m-d H:i:s' format
	*	@return Associative array with the total maintenance charge
	*/
	function getMaintenanceTotal($propID, $startdate, $enddate) {
		global $db;
		$tenant = getTenant($propID);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$tenancyID = $tenant[0]["tenancy_id"];
		
		//shows tenant name, only use though is if the property goes 
		//through multiple tenants and want to see separate tenants but retain old ones
		$query = "
			SELECT SUM(c.amount) AS total
			FROM maintenance_costs AS c
			LEFT JOIN tenancy AS t ON c.tenancy_id=t.tenancy_id
			LEFT JOIN issue_items AS i ON c.item_id=i.item_id
			LEFT JOIN users AS u ON u.user_id =t.tenant_id
			WHERE c.tenancy_id = ? AND c.request_datetime >= ? AND c.request_datetime <= ?
			ORDER BY c.amount";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($tenancyID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Get an array of owner payouts for a particular property
	*	in a given date range.
	*	@param int property_id
	*	@param string startdate
	*		start date of the range in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range in 'Y-m-d H:i:s' format
	*	@return Associative array with the owner payouts
	*/
	function getPayouts($property_id, $startdate, $enddate) {
		global $db;
		
		$owner = getOwner($property_id);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$ownershipID = $owner[0]["ownership_id"];
		//shows tenant name, only use though is if the property goes 
		//through multiple tenants and want to see separate tenants but retain old ones
		$query = "
			SELECT concat(u.first_name, ' ', u.last_name) AS name, 
				p.amount as 'payout', p.datetime, p.comment, ' ' as rent, ' ' as main
			FROM owner_payments AS p
			LEFT JOIN ownership AS o ON p.ownership_id=o.ownership_id
			LEFT JOIN users AS u ON u.user_id =o.owner_id
			WHERE p.ownership_id = ? AND p.datetime >= ? AND p.datetime <= ?
			GROUP BY p.datetime";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($ownershipID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}

	/**
	*	Retrieve the total owner payouts amount for a given date range
	*	and given property
	*	@param int property_id
	*	@param string startdate
	*		start date of the range in 'Y-m-d H:i:s' format
	*	@param string enddate
	*		end date of the range in 'Y-m-d H:i:s' format
	*	@return Associative array with the total owner payouts
	*/
	function getPayoutsTotal($property_id, $startdate, $enddate) {
		global $db;
		
		$owner = getOwner($property_id);
		//change for multiple tenants - cycle through all tenancys that are in that property
		$ownershipID = $owner[0]["ownership_id"];
		
		//shows tenant name, only use though is if the property goes 
		//through multiple tenants and want to see separate tenants but retain old ones
		
		$query = "
			SELECT SUM(p.amount) AS total
			FROM owner_payments AS p
			LEFT JOIN ownership AS o ON p.ownership_id=o.ownership_id
			LEFT JOIN users AS u ON u.user_id =o.owner_id
			WHERE p.ownership_id = ? AND p.datetime >= ? AND p.datetime <= ?
			ORDER BY p.amount";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($ownershipID, $startdate, $enddate));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Get an array of tenancy details for a given property
	*	@param int property_id
	*	@return Associative array of tenancy details
	*/
	function getTenant($property_id) {
		global $db;
		$query = "SELECT *  
				FROM tenancy WHERE property_id = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($property_id));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
		
	}
	
	/**
	*	Get an array of ownership details for a given property
	*	@param int property_id
	*	@return Associative array of ownership details
	*/
	function getOwner($property_id) {
		global $db;
		$query = "SELECT *  
				FROM ownership WHERE property_id = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($property_id));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Get an array of management details for a given property
	*	@param PDO db - database object
	*	@param int property_id
	*	@return Associative array of management details
	*/
	function getAgent ($db, $property_id) {
		$query = "SELECT *  
				FROM management WHERE property_id = ?";
		
		$stmt = $db->prepare($query);
		$stmt->execute(array($property_id));
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	
	/**
	*	Add a manual payment into the database
	*	@param PDO db - Database object
	*	@param int property_id
	*	@param string request_datetime
	*		date for when this payment was lodged as a 'requesting approval'
	*		String in a 'Y-m-d H:i:s' format
	*	@param int amount - payment amount
	*	@param string comment - Text description to be attached to the payment
	*	@return Boolean success status
	*/
	function addPayment($db, $property_id, $request_datetime, $amount, $comment) {
		$tenant = getTenant($property_id);
		$agent = getAgent($db, $property_id);
		$tenancy_id = $tenant[0]["tenancy_id"];
		$approved_datetime = 0; // Payment will not be approved yet
		$approved_user_id = $agent[0]["agent_id"];
		try {
			$query = "INSERT INTO other_payment (tenancy_id, approved_user_id, request_datetime, approved_datetime,
						amount, comment) VALUES (?,?,?,?,?,?)";
			$stmt = $db->prepare($query);
			$stmt->execute(array($tenancy_id, $approved_user_id, $request_datetime, $approved_datetime, $amount, $comment));
			header("Location: ../property_financials.php?property=". $property_id);
			return true;
		} catch (PDOException $e) {
			die($e);
			return false;
		}
	}
	
	/**
	*	Retrieve a list of pending payments for a given property
	*	@param property_id
	*	@return null (if error) or associative array of pending payments
	*/
	function getPendingPayments($property_id){
		global $db;
		global $user_id;
        // Get the daily rent amount
        try{
            $query="SELECT * FROM other_payment as o 
			JOIN tenancy as t ON o.tenancy_id = t.tenancy_id 
			JOIN management as m on m.property_id = t.property_id 
			WHERE m.agent_id = ? AND m.property_id = ? AND o.approved_datetime = '0000-00-00 00:00:00'";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id, $property_id));
            //success
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
            return null;
        }
	}

	/**
	*	Retrieves a count of financial issues for an agent. Financial issues
	*	constitute payments that have not been approved yet
	*	@param int user_id
	*/
	function countFinancialIssues($user_id){
		global $db;
		try{
            $query="SELECT COUNT(*) FROM other_payment as o 
			JOIN tenancy as t ON o.tenancy_id = t.tenancy_id 
			JOIN management as m on m.property_id = t.property_id 
			WHERE m.agent_id = ? AND o.approved_datetime = '0000-00-00 00:00:00'";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id));
            //success
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
        }
	}

	/**
	*	Function to retrieve a list of all properties with their count of financial issues
	*	similar to the manage view.
	*	@param string user_type - "Agent, "Owner", "Tenant"
	*	@param int user_id
	*	@param string aggregate OPTIONAL - "suburb" will aggregate the results in an array
	*		of suburbs
	*	@return Associative array of results (aggregated by suburb if specified), or null, 
	*		if an error.
	*/
	function getFinancialIssues($user_type, $user_id, $aggregate="None"){
		global $db;
		global $user_id;
        // Get the daily rent amount
        try{
            $base_query=
            "SELECT `management`.`property_id` AS `property_id`,`tenancy`.`tenant_id` 
            	AS `tenant_id`,`ownership`.`owner_id` AS `owner_id`,`management`.`agent_id` 
            	AS `agent_id`,concat(`agent`.`first_name`,' ',`agent`.`last_name`) 
            	AS `agent_name`,concat(`tenant`.`first_name`,' ',`tenant`.`last_name`) 
            	AS `tenant_name`,concat(`owner`.`first_name`,' ',`owner`.`last_name`) 
            	AS `owner_name`,concat(`properties`.`address`,', ',`properties`.`suburb`) 
            	AS `address`,`properties`.`suburb` AS `suburb`, ifnull(other_payment.financial_issues, 0) 
            	as financial_issues FROM tenancy 
			LEFT JOIN (
			    SELECT count(*) as financial_issues, tenancy_id from other_payment as o_p 
			    WHERE o_p.approved_datetime = '0000-00-00 00:00:00'
			    GROUP BY tenancy_id
		    ) as other_payment on tenancy.tenancy_id = other_payment.tenancy_id
			JOIN ownership  ON ownership.property_id = tenancy.property_id
			JOIN management ON management.property_id = tenancy.property_id
			JOIN properties ON properties.property_id = tenancy.property_id
			left join `users` as `agent` on((`management`.`agent_id` = `agent`.`user_id`)) 
			left join `users` as `tenant` on((`tenancy`.`tenant_id` = `tenant`.`user_id`)) 
			left join `users` as `owner` on((`ownership`.`owner_id` = `owner`.`user_id`)) ";
			$query = "";
			switch($user_type){
				case "Agent":
				$query = $base_query . "WHERE agent_id = ?";
				break;
				case "Owner":
				$query = $base_query . "WHERE owner_id = ?";
				break;
				case "Tenant":
				$query = $base_query . "WHERE tenant_id = ? ORDER BY tenant_id DESC LIMIT 1";
				break;
				default:
				return 0;
			}
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id));
            //success
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($aggregate=="suburb"){
				return aggregateBySuburb($results);
			}
			return $results;
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
            return null;
        }
	}
	
	/**
	*	Add an owner payout to the database.
	*	@param int ownership_id - id that links owner to property
	*	@param int amount - payout amount
	*	@param string comment - comment to be attached to payout
	*	@param int property - property_id
	*	@return boolean success status
	*/
	function addPayout($ownership_id, $amount, $comment, $property) {
		global $db;
		$current_date = date('Y-m-d H:i:s');
		$amount *= 100;
		echo "here";
		try{
			$sql = "INSERT INTO owner_payments (ownership_id, amount, datetime, comment)
					VALUES (?,?,?,?)";
			$stmt = $db->prepare($sql);
			$stmt->execute(array($ownership_id, $amount, $current_date, $comment));
			header("Location: ../property_financials.php?property=".$property);
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	*	Calculate number of days until rent is due based off the rent
	*	that has been paid this far, and the daily rent amount
	*	@param int property_id
	*	@param int final_rent - total rent amount paid
	*	@return int Days until rent is due
	*/
	function daysUntilRentDue($property_id, $final_rent){
		global $db;
		$tenancies = getTenant($property_id);
		// TODO: Needs to handle multiple tenancies (over time)
		$tenancy = $tenancies[0];
		$tenancy_id = $tenancy["tenancy_id"];
		$tenant_id = $tenancy["tenant_id"];

		// Get daily rent amount
		$daily_rent = getDailyRent($tenant_id, $property_id);
		// Days paid since tenancy started
		$daysPaid = floor($final_rent / $daily_rent);
		// Now work out days between (day_start)+days paid and today
		$start_date = date_create($tenancy["start_date"]);
		// ad the days paid
		$start_date->add(new DateInterval("P".$daysPaid."D"));
		// generate today
		$today = date_create();
		// now work out the difference between today and how far we've paid
		$diff = $today->diff($start_date);
		$days_until =  $diff->days;
		if($diff->invert){
			// PHP date objects don't like negative days
			// but instead use a invert field
			// so if it's inverted, multiply by -1
			$days_until *= -1;
		}
		
		return $days_until;
	}

	/*******************/
    /*    DELEGATOR    */
    /*******************/
    if (isset($_REQUEST['api'])) {
        $type = $_REQUEST['api'];

        switch($type) {
            case "add_payment": 
                date_default_timezone_set('Australia/Brisbane');
				$current_date = date('Y-m-d H:i:s');
                $property_id = $_GET['property'];
                $request_datetime = $current_date;
				$amount = $_REQUEST['amount'] * 100;
				$comment = $_REQUEST['comment'];

                addPayment($db, $property_id, $request_datetime, $amount, $comment);
                break;
			case "addPayout":
				$ownership_id = $_REQUEST['ownership_id'];
				$amount = $_REQUEST['amount'];
				$comment = $_REQUEST['description'];
				$property = $_REQUEST['property'];
				addPayout($ownership_id, $amount, $comment, $property);
				break;
		}
	}
?>