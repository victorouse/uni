<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
    require_once(dirname(__DIR__) . "/api/mail.php");

    /*******************/
    /* REFERRAL METHODS */
    /*******************/
    
    /**
     * Adds email address, account to be created type and referral key
     * It is assumed that email addresses are unique per-user.
     *  @param string email
     *      email address for the invited user
     *  @param int property_id
     *      property_id for the referred person
     *  @param string type
     *      string to describe whether agent or owner
     *  @param string start_date
     *      Start date of tenancy in 'Y-m-d H:i:s' format
     *  @param string end_date
     *      End date of tenancy in 'Y-m-d H:i:s' format
     *  @param int daily_rent OPTIONAL
     *      Daily rent amount for tenancy in cents
     *  @return md5 hashed referral id
     */
    function addReferral($email, $property_id, $type, $start_date, 
    $end_date, $daily_rent = null) {
        // Generate referral id
        $key = $email . $property_id . date('mY');
        $key = md5($key);
        
        insertReferralToDB($email, $type, $property_id, $daily_rent, $start_date
        , $end_date, $key);
        // TODO: Mail user referral code
        // mailReferall()
        //send_mail($email, "New User", "Rentmn.gr signup", "Hello there, thanks for signing up, your referral id is ". $key);
        return $key;
        
    }
    
    /**
     * Adds email address, account to be created type and referral key to 
     * database
     * It is assumed that email addresses are unique per-user.
     *
     * @param string email
     * @param string type - owner, tenant, agent
     * @param string property_id - id of the property they are registering for
     * @param int daily_rent - daily rent for the tenancy in cents
     * @param string start_date
     *      Start date of tenancy in 'Y-m-d H:i:s' format
     * @param string end_date
     *      End date of tenancy in 'Y-m-d H:i:s' format
     * @param string key - the key in which they must use to sign up with
     */
    function insertReferralToDB($email, $type, $property_id, $daily_rent, 
    $start_date, $end_date, $key) {
        // Access to database
        global $db;

        // Construct query
        $query = "INSERT INTO referred(referrerid, 
        email_address, property_id, type, start_date, end_date, daily_rent) VALUES (?,?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->execute(array($key, $email, $property_id, $type, $start_date, 
        $end_date, $daily_rent));
    }
	
    /**
     * Gets details of the referred user
     * 
     * @param string referrerid - key that is being used as referral id
     * @return associative array of the referred table
     */
	function getReferralUser($referrerid) {
		// Access to database
		global $db;
		
		// Construct the query
		$query = "SELECT type, email_address, property_id, start_date, end_date,
                    daily_rent
            FROM referred WHERE referrerid = ?";
		$stmt = $db->prepare($query);
		$stmt->execute(array($referrerid));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
    /**
     * Checks if the referral id is valid
     * 
     * @param string referrerid - key that is being used as referral id
     * @return TRUE if valid, FALSE if not valid or error
     */
    function checkReferralIdIsValid($referrerid) {
        // Access to database
        global $db;
        
        //Construct the query
        $query = "SELECT * FROM referred WHERE referrerid = ?";
        $stmt = $db->prepare($query);
        $stmt->execute(array($referrerid));
        $number_of_rows = $stmt->rowCount();
        
        if ($number_of_rows == 1) {
            return true;
        } else return false;
    
    }
    /**
     * Deletes referral from table based on referrer id
     * 
     * @param string referrerid - key that is being used as referral id
     * @return TRUE if success, otherwise FALSE
     */
    function deleteReferralById($referrerid) {
        // Access to database
        global $db;
        //Construct the query
        try {
            $query = "DELETE FROM referred WHERE referrerid = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($referrerid));
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Gets property id from the referral table based on referrer id
     * 
     * @param string referrerid - key that is being used as referral id
     * @return property id corresponding to the referral id
     */
    function getPropertyIdFromRefId($referrerid) {
        // Access to database
        global $db;
        
        // Construct the query
        $query = "SELECT property_id FROM referred WHERE 
            referrerid = ?";
        $stmt = $db->prepare($query);
        $stmt->execute(array($referrerid));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]['property_id'];
    }

    /**
    *   Function to retrieve the daily rent for a referred tenant
    *   
    *   @param PDO db
    *   @param int referrerid
    *   @return the daily rent value for the specified tenant
    */
    function getDailyRent($db, $referrerid){
        // Construct the query
        $query = "SELECT daily_rent FROM referred WHERE 
            referrerid = ?";
        $stmt = $db->prepare($query);
        $stmt->execute(array($referrerid));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['daily_rent'];
    }
    
    /**
    *   Get the start date of the referred tenant
    *   @param PDO db
    *   @param int referrerid
    *   @return the start date
    */
    function getStartDate($db, $referrerid){
        // Construct the query
        $query = "SELECT start_date FROM referred WHERE 
            referrerid = ?";
        $stmt = $db->prepare($query);
        $stmt->execute(array($referrerid));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['start_date'];
    }
    
    /**
    *   Get the end date of the referred tenant
    *   @param PDO db
    *   @param int referrerid
    *   @return the end date
    */
    function getEndDate($db, $referrerid){
        // Construct the query
        $query = "SELECT end_date FROM referred WHERE 
            referrerid = ?";
        $stmt = $db->prepare($query);
        $stmt->execute(array($referrerid));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['end_date'];
    }

	
	/*******************/
    /*    DELEGATOR    */
    /*******************/
	if (isset($_REQUEST['api'])) {
		$api = $_REQUEST['api'];
	
    switch($api) {
        case "addReferral":
            // Retrieve variables
            $email = $_REQUEST['email'];
            $property_id = $_GET['propertyID'];
            $type = $_GET['type'];
            // Different opreations for referring owners and tenants
            if($type == "Owner"){
                $referralid = addReferral($email, $property_id, $type);
                header("Location: ../view.php?property=".$property_id);
                die();
            }else if ($type == "Tenant"){
                // Need to get some extra request variables
                $rent_amount = $_REQUEST['rent_amount'];
                $period = $_REQUEST['period'];
				$start_date = $_REQUEST['start_date'];
				$end_date = $_REQUEST['end_date'];

                // Calculate daily rent
                $daily_rent = 0;
                switch($period){
                    case 'day':
                    $daily_rent = $rent_amount;
                    break;
                    case 'week':
                    $daily_rent = ceil($rent_amount / 7);
                    break;
                    case 'fortnight':
                    $daily_rent = ceil($rent_amount / 14);
                    break;
                }

                // Check daily_rent is more than 0 cents
                if($daily_rent <= 0){
                    header("Location: ../view.php?property=".$property_id);
                    die();
                }
				// Add the referral and take the user back to the view page
                $referralid = addReferral($email, $property_id, $type, $start_date, $end_date, $daily_rent);
                header("Location: ../view.php?property=".$property_id);
                die();
            }
            break;
		case "deleteReferralById":
			deleteReferralById($_REQUEST['referral_id']);
            // redirect back to view page
			header("Location: ../view.php?property=".$_REQUEST['property_id']);
			break;
        }
	}
?>
