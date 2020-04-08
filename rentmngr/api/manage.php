<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");

    /*******************/
    /* ACCOUNT METHODS */
    /*******************/

	/**
	*	Get a list of properties that belongs to this user for a
	*		list view.
	*	@param string usertype - "Agent", "Owner", "Tenant"
	*	@param int userid - user_id of this user
	*	@param string aggregate - OPTIONAL "suburb" and it outputs
	*		the properties aggregated by suburb name
	*	@return Associative array - list of property details, aggregated by
	*		suburb if selected
	*/
	function selectProperties($usertype, $userid, $aggregate="None"){
		global $db;
		$base_query = "SELECT *	FROM view_management ";
		$results = array();
		switch($usertype){
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
		$results = array();
		
		if ($stmt = $db->prepare($query)) {
			$stmt->bindParam(1, $userid);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		if($aggregate=="suburb"){
			return aggregateBySuburb($results);
		}
		return $results;
	}


	/**
	*	Aggregation function that takes an associative array and aggregates the
	*		properties by suburb.
	*	@param Associative Array array - property array
	*	@return Associative array aggregated by suburb
	*/
	function aggregateBySuburb($array){
		$suburb_array = array();
		// Loop over all properties
		foreach($array as $property){
			$suburb = (string) $property["suburb"];
			if(ISSET($suburb_array[$suburb])){
				//suburb exists, add to existing array
				$suburb_array[$suburb][] = $property;				
			}else{
				//instantiate the array, then add
				$suburb_array[$suburb] = array();
				$suburb_array[$suburb][] = $property;
			}
		}
		return $suburb_array;	
	}
?>
