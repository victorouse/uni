<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
    require_once(dirname(__DIR__) . "/inc/authenticated.php");
    require_once(dirname(__DIR__) . "/api/pin.php");


    /**
    *   Function to retrieve the established payment schedules for a given user
    *   @param int user_id
    *       user_id of the given user
    *   @return null (no results or PDOException) or array of payment schedule
    *       details
    **/
    function getPaymentSchedules($user_id){
        global $db;
        // Return the payment schedules information
        // Join on tenancy_id
        try{
            $query = "SELECT * FROM `recurring_payments` 
            LEFT JOIN tenancy on tenancy.tenancy_id = `recurring_payments`.tenancy_id
            LEFT JOIN pin_customer_tokens on pin_customer_tokens.cus_token = `recurring_payments`.cus_token 
            WHERE tenancy.tenant_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id));
            //success
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
        }
    }

    /**
    *	Add a new referring payment to the payment schedule
    *   @param int property_id
    *       property_id of the tenancy for this user
    *	@param int amount
    *       rent payment in cents
    *   @param string cus_token
    *       Pin generated customer token
    *   @param int day
    *       
    *   @throws Exception - $day is not an integer
    *   @throws Exception - $Amount is not an integer
    *   @throws Exception - $Amount must be greater than 100
    *   @throws Exception - $day is not in range
    *   @throws Exception - $user_id is not a tenant at $property_id
    *   @return boolean - success 
    */
    function addRecurringPayment($property_id, $user_id, $cus_token, $day, $amount){
        global $db;
        // Check amount, day etc. all fall within ranges
        if(!is_int($day)){
            throw new Exception("Day must be an integer");
        }
        if(!is_int($amount)){
            throw new Exception("Amount must be an integer");
        }
        if($amount < 100){
            throw new Exception("Rent must be greater than 100 cents");
        }
        if($day<1 | $day>14){
            throw new Exception("Day must be between 1 and 14 (inclusive)");
        }
        
        $tenancy_id = null;

        // Find the tenancy_id
        try{
            $query = "SELECT tenancy_id FROM tenancy where tenant_id = ? and property_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id, $property_id));
            //success
            $tenancy_id = $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
        }
        if($tenancy_id == null){
            // no tenancy_id found
            throw new Exception("You don't belong to this property");
        }

        // Add the recurring payment into the schedule
        try{
            $query = "INSERT INTO recurring_payments
                    (tenancy_id, cus_token, day, amount) VALUES (?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($tenancy_id, $cus_token, $day, $amount));
            //success
            return true;
        } catch (PDOException $e) {
            // Something went wrong
            throw new Exception("Error Processing Request ". $e);       
        }

    }

    /**
    *   Function which updates a recurring payment
    *   @param int payment_id
    *       recurring_payment_id which identifies the payment being editted
    *   @param string cus_token
    *       Pin generated customer token for this user
    *   @param int day
    *       Day index (1 is first monday, 14 is second sunday)
    *   @param int amount
    *       Payment amount in cents
    *   @throws Exception "Error Processing Request"
    *       Error updating the database or variables of wrong type
    *   @return boolean success status of this operation
    */
    function editRecurringPayment($payment_id, $cus_token, $day, $amount){
        global $db;
        // Do SQL Update on recurring payment table
        try{
            $query = "UPDATE `recurring_payments` 
            SET `cus_token`=?,
            `day`=?,
            `amount`=? 
            WHERE `recurring_payment_id`=?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($cus_token, $day, $amount, $payment_id));
            return true;
        } catch (PDOException $e){
            throw new Exception("Error Processing Request ". $e);
        }
    }

    /**
    *   Function retrieves the payment details such as amount, day for a 
    *       particular user_id and a particular recurring_payment_id
    *   @param int user_id
    *       User_id of the specific user
    *   @param int recurring_payment_id
    *       Recurring_payment_id as stored in database
    *   @throws Exception "Error Processing Request"
    *       Error interacting with the database or variables are of
    *           wrong type
    *   @return boolean success status of this operation
    **/
    function getPaymentDetails($user_id, $recurring_payment_id){
        global $db;
        // Add the recurring payment into the schedule
        try{
            $query = "SELECT * FROM recurring_payments as r 
            LEFT JOIN tenancy as t on r.tenancy_id = t.tenancy_id WHERE r.recurring_payment_id = ? AND t.tenant_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($recurring_payment_id, $user_id));
            //success
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Something went wrong
            throw new Exception("Error Processing Request ". $e);       
        }        
    }

    /**
    *   Function which removes a recurring payment by it's payment_id. By passing in
    *       a user_id, we ensure that only the user who owns this payment can remove it
    *   @param int user_id
    *       user_id of the user who is performing this action
    *   @param int payment_id
    *       the payment_id of the payment which is being removed
    *   @throws Exception "This property is not associated with you"
    *       User_id does not 'own' this payment
    *   @throws Exception "Error Processing Request"
    *       Error interacting with the database or variables are of
    *           wrong type
    *   @return boolean success status of removal of payment
    **/
    function removeByPaymentId($user_id, $payment_id){
        global $db;
        // Try and fetch this payment_ids payment details with this user_id
        $paymentDetails = getPaymentDetails($user_id, $payment_id);
        // if there's no results
        if(!$paymentDetails){
            // then this property isn't for this user!
            throw new Exception("This property is not associated with you");
        }
        // Wrap database interaction object in a try/catch incase of database errors
        try{
            $query = "DELETE FROM `recurring_payments` WHERE recurring_payment_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($payment_id));
            // Query has executed without any exceptions being thrown
            return true;
        } catch (PDOException $e){
            throw new Exception("Error Processing Request ". $e);
        }

    }

	/*******************/
    /*    DELEGATOR    */
    /*******************/
    if(isset($_REQUEST['api'])){
    	$type = $_REQUEST['api'];

	    switch($type) {
            case "add":
                $property_id = $_POST['property_id'];
                $day = (int) $_POST['day'];
                $amount = (int) $_POST['amount']*100;
                $cus_token = $_POST['cus_token'];
                if(addRecurringPayment($property_id, $user_id, $cus_token, $day, $amount)){
                    header("Location: ../view.php");
                    die();
                }else{
                    echo("error");
                }
                break;
            case "edit":
                $payment_id = $_POST['payment_id'];
                $property_id = $_POST['property_id'];
                $day = (int) $_POST['day'];
                $amount = (int) $_POST['amount']*100;
                $cus_token = $_POST['cus_token'];
                if(editRecurringPayment($payment_id, $cus_token, $day, $amount)){
                    header("Location: ../property_financials.php?property=".$property_id);
                    die();
                }else{
                    echo("error");
                }
                break;
            case "remove":
                $payment_id = $_POST['payment_id'];
                $property_id = $_POST['property_id'];
                if(removeByPaymentId($user_id, $payment_id)){
                    header("Location: ../financial.php");
                    die();
                }
                break;
	    	default:
	    	// do nothing
	    	break;

	    }

	}


?>