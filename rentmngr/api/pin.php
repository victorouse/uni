<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    // import required include functions (PDO for $db and authenticated for user vars)
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
    require_once(dirname(__DIR__) . "/inc/authenticated.php");

 	/***************/
    /* PIN METHODS */
    /***************/

    /**
	*	Interacts with a pin payments URL and returns an array
	*		of information, decoded from the JSON return
	*		@param string page
	*			Page name (customers, charges, etc)
	*		@param boolean post
	*			Optional boolean flag to specify if the interaction
	*			is a POST method as opposed to GET.
	*				(Default false, set to GET)
	*		@param array params
	*			Optional associative array with the POST parameters
	*			for the request
	*				(Default NULL)
	*		@return
	*			Returns json_decode array of JSON reply from Pin
    */
    function interact($page, $post = false, $params = NULL){
    	// This is the black magic (my secret key)
        // Edit this next line with your own secret key
    	$authentication = "V2XIrIMkWBo2Im9VX5fgpQ".":". "";
        // Edit this next line to include your own api end-point
        $end_point = "https://test-api.pin.net.au/1/";
        $url = $end_point . $page;
    	// Set up curl params
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_USERPWD, $authentication);
    	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    	curl_setopt($ch, CURLOPT_HEADER, FALSE);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    	if($post){
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    	}
    	$result = curl_exec($ch);
	    curl_close($ch);
    	return json_decode($result);
    }

    /**
    *   Function which gets the cus_token for a given tenancy_id.
    *   @param PDO db
    *       PDO database object
    *   @param int tenancy_id
    *       tenancy_id for the given tenancy
    *   @return
    *       null (no results) or string (cus_token)
    *
    **/
    function getTenancyToken($db, $tenancy_id){
        try{
            $query = "SELECT cus_token FROM pin_customer_tokens JOIN tenancy on tenant_id = user_id WHERE tenancy_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($tenancy_id));
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($results){
                return $results[0]["cus_token"];
            }else{
                return null;
            }
        }catch(PDOException $e){
            var_dump($e);
        }
    }

    /**
    *   Function that interacts with the Pin API, creating a customer token,
    *       then adding the customer details into the database in the 
    *       pin_customer_tokens table.
    *
    *   @param PDO db
    *       PDO database object
    *   @param int user_id
    *       user_id of the user who is adding card details
    *   @param string email
    *       email address for the user, as required by Pin
    **/
    function addCustomer($db, $user_id, $email, $card_token, $ip_address){
    	// Interact with pin to create the customer with this card token
    	// Setup POST params
    	$params = array("email"=>$email, "card_token"=>$card_token);
    	$response = interact("customers", true, $params);
    	$response = $response->{'response'};
    	$customer_token = $response->{'token'};
    	$card_display = $response->{'card'}->{'display_number'};

    	// Add the customer token to the database
    	try{
	    	$query = "INSERT INTO pin_customer_tokens 
	                (user_id, cus_token, display_number) VALUES (?,?,?)";
	        $stmt = $db->prepare($query);
	        $stmt->execute(array($user_id, $customer_token, 
	                $card_display));
            // Now redirect them back to account page
	        header("Location: ../account.php?add");
	    } catch (PDOException $e) {
        	// Something went wrong
        	var_dump($e);
        }
    }

    /**
    *   Function to retrieve stored card details of a user
    *   @param PDO db
    *       PDO database object
    *   @param int user_id
    *       User_id of the user that's being queried
    *   @return
    *       null (on PDOException) or array of details
    **/
    function getCardDetails($db, $user_id){
        // Check pin_customer_tokens
        try{
            $query = "SELECT * FROM pin_customer_tokens WHERE user_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id));
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e){
            var_dump($e);
            return null;
        }
    }

    /**
    *   Function to remove the card from the database. Note that customers
    *       cannot be removed from Pin (for financial security purposes).
    *   @param PDO db
    *       PDO database object
    *   @param int user_id
    *       user_id of the card that is being removed
    **/
    function removeCard($db, $user_id){
        try{
            $query = "DELETE FROM pin_customer_tokens WHERE user_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id));
            var_dump($stmt);
            // Successful
            // Redirect back to account settings and die
            header("Location: ../account.php?removed");
            die();
        } catch (PDOException $e){
            // Something went wrong
            var_dump($e);
            // var_dump error
        }
    }

	 /*******************/
    /*    DELEGATOR    */
    /*******************/
    if(isset($_REQUEST['api'])){
    	$type = $_REQUEST['api'];
        // Use a switch statement for the api calls - do nothing if api not matching these values
	    switch($type) {
	        case "addCard":
                // Retrieve card_token as generated in client-side
	        	$card_token = $_REQUEST['card_token'];
                // Retrieve users IP Address, needed for Pin
	        	$ip_address = $_REQUEST['ip_address'];
	        	addCustomer($db, $user_id, $email_address, $card_token, $ip_address);
	        break;
            case "removeCard":
                removeCard($db, $user_id);
                break;
            break;
	    }

	}

?>