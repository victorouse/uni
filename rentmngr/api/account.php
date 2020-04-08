<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once(dirname(__DIR__) . "/inc/connect_pdo.php");
    require_once(dirname(__DIR__) . "/api/referral.php");
	//include("../inc/connect.php");


    /*******************/
    /* ACCOUNT METHODS */
    /*******************/

    /**
     * Retrieves a user from the database from an email address.
     * It is assumed that email addresses are unique per-user.
     *
     * @param string email_address 
     * @return associative array of the USER table, 
     *          false if no user was foumd or if a failure occured 
     */
    function getUser($db, $email_address) {
        // Construct query
        $query = 
            "SELECT user_id, first_name, last_name, type, email_address, 
            password, salt
            FROM `users` WHERE `email_address` = ?";

        // Execute query
        $stmt = $db->prepare($query);
        $stmt->execute(array($email_address));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // No user found
        if ($stmt->rowCount() == 0) {
            return false;
        }

        // Note: can be false if PDO error
        return $result;
    }

    /**
    *   Gets the user details of a user with a given user_id
    *   @param PDO db
    *       PDO Database object when can be interacted with
    *   @param int user_id
    *       user_id for the user being queried
    */
    function getUserDetailsById($db, $user_id){
        $query = "SELECT user_id, first_name, last_name, type, email_address 
            FROM `users` WHERE `user_id` = ?";
        // Execute query
        $stmt = $db->prepare($query);
        $stmt->execute(array($user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Looks up a user by email address and checks their (database)
     * password against the (form) entered password. 
     *
     * @param string $email_address  - form email address
     * @param string $password - form password
     * @return associative USER array if credentials match, 
     *          false if the user does not exist or the credentials are wrong
     */
    function checkCredentials($db, $email_address, $password) {
        // Get the user from the database

        $result = getUser($db, $email_address);

        
        // Construct encrypted password
        $hashed_salted_password = hash('sha512', $password . $result[0]['salt']);

        if (count($result) == 0) {
            // User does not exist
            return false;
        }

        // Check password
        if ($hashed_salted_password == $result[0]['password']) {
            return $result[0];
        }

        // Password was incorrect
        return false;
    }

    /**
     * Creates a session for a user if they correctly login and 
     * redirects them back to their relevant (user/owner/tennant) homepage.
     *
     * If they incorrectly login they are redirected back to the current
     * page with failure flag.
     *
     * @param $email 
     *      form email address
     * @param $password
     *      form password
     * @return true if user has logged in correctly,
     *          false if otherwise
     */
    function login($db, $email, $password) {

        if (($user = checkCredentials($db, $email, $password))) {
            // Start a session
            session_start();
			
            // Set session variables
            $_SESSION['user'] = $user['first_name'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['email_address'] = $user['email_address'];
			
            // Redirect to relevant homepage
            //header("Location ./index.php");
            //header("Location: ../");
			header("Location: ../");
            return true;
        } 

        // Redirect with fail flag to $_GET later
        header("Location: ../?fail=1");
        return false;
    }

    /**
     * Clears session variables for the currently logged in user
     * (requires a user session to be created from login() already)
     */
    function logout() {
        // Start session to destroy session (weird, I know)
        session_start();

        // Unset session variables
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);
        unset($_SESSION['type']);
        unset($_SESSION['email_address']);
        session_destroy();

        // Validate user has been logged out
        if (session_id()) {
            return false;
        }

        // Redirect to homepage
        header("Location: ../index.php");
        return true;
    }

    /**
     * Creates a hashed password with a random salt
     *  @param string password
     *      password as passed from the HTML form, pre-hashed
     *  @param string random_salt
     *      salty salt, not too salty preferably
     */
    function hash_password($password, $random_salt) {
        //$password = hash('sha512', $password);
        $password = hash('sha512', $password.$random_salt);

        return $password;
    }
    
    /**
     * Insert a referred (by agent) user into the database depending on their
     * account type (tenant/owner)
     *
     * @param $fname - form first name 
     * @param $lname - form last name
     * @param $email - form email
     * @param $password - form password
     * @param $type - user type (from referral)
     * @param $referrerid - agent referral identifier
     * @param $notify - notification types array
     * @return true if user has been successfully signed up,
     *          false if otherwise
     */
    function signup($db, $fname, $lname, $password, $referrerid, $notify) {
        // Default notify values
        $result = getReferralUser($referrerid);
        $type = $result[0]['type'];
        $email = $result[0]['email_address'];
        $structural = 0;
        $electrical = 0;
        $plumbing = 0;
        $security = 0;

        // Set notify values
        if (!empty($notify)) {
            foreach($notify as $n) {
                if ($n == 'structural') {
                    $structural = 1;
                }

                if ($n == 'electrical') {
                    $electrical = 1;
                }

                if ($n == 'plumbing') {
                    $plumbing = 1;
                }

                if ($n == 'security') {
                    $security = 1;
                }
            }
        }

        // Hash password and create salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $hashed_password = hash_password($password, $random_salt);
        
        // Send to appropriate page based on insert of user
        $add_user_success = insertUserToDB($db, $email, $fname, $lname, $type, 
            $hashed_password, $random_salt);
        $user_id = $db->lastInsertId();
        $property_id = getPropertyIdFromRefId($referrerid);

        if ($type == 'Owner') {
            $add_type_success = insertOwnerToDB($db, $user_id, $property_id, 
                $structural, $electrical, $plumbing, $security);
        } else if ($type == 'Tenant') {
            // TODO get start_date and end_date from agent
            $daily_rent = $result[0]['daily_rent'];
            $start_date = $result[0]['start_date'];
            $end_date = $result[0]['end_date'];
            $add_type_success = insertTenantToDB($db, $user_id,
                $property_id, $start_date, $end_date, $daily_rent);

        } else { //TODO AGENT
            $add_type_success = false;
        }
        $delete_referral_success = deleteReferralById($referrerid);
        // Ensure all values are correctly set or fail
        if ($add_user_success && $delete_referral_success && $add_type_success) {
			login($db, $email, $password);
			return true;
        }
        die();
        //header("Location: ../signup.php?result=2");
        return false;
    }
    
    /**
     * Inserts a user into the database.
     *
     * @param string $email
     * @param string $fname
     * @param string $lname
     * @param string $type
     * @param string $password
     * @param string $random_salt
     * @return true is user has been inserted successfully,
     *          false if otherwise 
     */
    function insertUserToDB($db, $email, $fname, $lname, $type, $password, $random_salt) {
        // Construct query
        try {
            $query = "INSERT INTO users (email_address, first_name, 
                last_name, type, password, salt) VALUES (?,?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($email, $fname, $lname, 
                $type, $password, $random_salt));
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Inserts an owner into the database.
     *
     * @param string $property_id
     * @param string $owner_id
     * @param string $structural
     * @param string $electrical
     * @param string $plubming
     * @param string $security
     * @return true if owner has been successfully added,
     *          false if otherwise
     */
    function insertOwnerToDB($db, $user_id, $property_id, $structural, 
                $electrical, $plumbing, $security) {
        // Construct query
        try {
            $query = "INSERT INTO ownership 
                (owner_id, property_id, notify_structural, notify_electrical, 
                notify_plumbing, notify_security) VALUES (?,?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id, $property_id, (string) $structural, 
                (string) $electrical, (string) $plumbing, (string) $security));
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }
    
    /**
     * Inserts a tenant into the database.
     *
     * @param $last_user_inserted
     * @param $property_id
     * @param $start_date
     * @param $end_date
     * @return true if tenant was successfully added,
     *          false if otherwise
     */
    function insertTenantToDB($db, $user_id, $property_id, 
            $start_date, $end_date, $daily_rent) {
        // Construct query
        try {
            $query = "INSERT INTO tenancy 
                (tenant_id, property_id, start_date, end_date, daily_rent) VALUES (?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->execute(array($user_id, $property_id, 
                $start_date, $end_date, $daily_rent));
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    /**
    *   Function to update the users details.
    *   @param PDO db - database object
    *   @param int user_id
    *   @param string fname - first name of user
    *   @param string lname - last name of user
    *   @param string email - email address of user
    *   @return Boolean success status
    */
    function updateUser($db, $user_id, $fname, $lname, $email) {
        try {
            $query = "UPDATE users
                SET first_name = ?, last_name = ?, email_address = ?
                WHERE user_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($fname, $lname, $email, $user_id));
            header("Location: ../account.php");
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }
    
    /**
    *   Function to update the owners notification for a given property
    *   @param PDO db - database object
    *   @param Associative_array notify - array of notification settings
    *   @param int property_id
    *   @param int owner_id - user_id of the owner
    *   @return Boolean success status
    */
    function updateOwnerNotification($db, $notify, $property_id, $owner_id) {
        $structural = (int) in_array("structural", $notify);
        $plumbing = (int) in_array("plumbing", $notify);
        $electrical = (int) in_array("electrical", $notify);
        $security = (int) in_array("security", $notify);
        try {
            $query = "UPDATE ownership
                SET notify_structural = ?, notify_electrical = ?, 
                    notify_plumbing = ?, notify_security = ?
                WHERE property_id = ? AND owner_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute(array($structural, $electrical, $plumbing, $security,
                $property_id, $owner_id));
            header("Location: ../editOwnerNotifications.php?property_id=". 
            $property_id);
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }
    
    /*******************/
    /*    DELEGATOR    */
    /*******************/
    if (isset($_REQUEST['api'])) {
        $type = $_REQUEST['api'];

        switch($type) {
            case "login": 
               
                // Request credentials from POST
                $email = $_REQUEST['email'];
                $password = $_REQUEST['password'];

                login($db, $email, $password);
                break;

            case "logout":
                logout();
                break;
            
            case "update":
                session_start();
                // Request updates from POST
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $email = $_POST['email'];
                $user_id = $_SESSION['user_id'];
                updateUser($db, $user_id, $fname, $lname, $email);
                break;
                
            case "signup":
                session_start();
                // Request details from POST
                $fname = $_REQUEST['fname'];
                $lname = $_REQUEST['lname'];
                $password = $_REQUEST['password'];
                $referrerid = $_SESSION['referrerid'];

                if (isset($_REQUEST['notify'])) {
                    $notify = $_REQUEST['notify'];
                } else {
                    $notify = array();
                }

                signup($db, $fname, $lname, $password, $referrerid, $notify);
                break;
            
            case "updateOwnerNotification":
                session_start();
                $notify = $_POST['notify'];
                $property_id = $_SESSION['property_id'];
                $user_id = $_SESSION['user_id'];
                updateOwnerNotification($db, $notify, $property_id, $user_id);
                break;
            default:
                echo("nothing happened...");
                die();
        }
    }
?>
