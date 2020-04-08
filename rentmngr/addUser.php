<?php
/**
 * Adds users to the database. Users encompasses all user types - agents,
 * tenants and owners. For this to function correctly, the following
 * parameters are required through POST variables:
 *
 * 		fname - first name of user to be added
 *		lname - last name of user to be added
 *		passwd - password of the user to be added
 *		accountType - the account type (either owner, tenant, agent)
 *		notify - notifications that the owner wishes to recieve.
 *						Please note that this is only required for owner
 *						account types. NOT REQUIRED for agents and tenants.
 *						This comes in the form of an array from HTML checkboxes
 *
*/
	// Redirect logged in users
	if (isset($_SESSION['user'])) {
		header("Location: ./");
	}

	// Instantiate variables
	include('inc/tpl.php');
	include('inc/connect.php');
	$tpl = new RainTPL;
	
	// If fail is set, user has failed to login correctly. Set loginFailed to 1
	if (isset($_GET['fail'])) {
		$loginFailed = 1;
    } else {
        $loginFailed = 0;
    }
	
	$acctFailed = 0; 
	
	// Request form fields
    if (!isset($_REQUEST['fname']) || !isset($_REQUEST['lname']) || !isset($_REQUEST['email']) 
            || !isset($_REQUEST['passwd']) || !isset($_REQUEST['accountType'])) {
		$acctFailed = 1; // if all required fields not set, account setup failed
		createPage($tpl, $loginFailed, $acctFailed); // display error page
	}

	$first_name = $_REQUEST['fname'];
	$last_name = $_REQUEST['lname'];
	$email = $_REQUEST['email'];
	$password = $_REQUEST['passwd'];
	$password = hash('sha512', $password);
	$type = strtolower($_REQUEST['accountType']);
	
	if ($type=='owner') {
		$structural = IsChecked('notify', 'structural');
		$security = IsChecked('notify', 'security');
		$plumbing = IsChecked('notify', 'plumbing');
		$electrical = IsChecked('notify', 'electrical');
	}

	// Create a random salt
	$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

	// Create salted password
	$password = hash('sha512', $password.$random_salt);
	
	// Insert the user into the database. If error occurs, display error page
	$stmt = mysqli_prepare($con, "INSERT INTO users (email_address, first_name, 
        last_name, type, password, salt) VALUES (?,?,?,?,?,?)");

	mysqli_stmt_bind_param($stmt, 'ssssss', $email, $first_name, $last_name, 
        $type, $password, $random_salt);

	if (!mysqli_stmt_execute($stmt)) {
		$acctFailed = 1;
		createPage($tpl, $loginFailed, $acctFailed);
	}
	
    /* 
     * If the account is an owner or tenant, must delete their signup referral
	 * from the database. Must also insert the user into table
	 * (either ownership or tenancy depending on account type)
     */
	if ($type == 'owner' || $type == 'tenant') {
		// Get the property_id from the referral table for later use
		$stmt = mysqli_prepare($con, "SELECT property_id FROM referred WHERE 
            email_address = ? AND type = ?");

		mysqli_stmt_bind_param($stmt, 'ss', $email, $type);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $property_id); // store the property id 
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		
		// Delete the signup referral of the user as this is no longer needed
		$stmt = mysqli_prepare($con, "DELETE FROM referred WHERE email_address 
            = ? AND type = ?");

		mysqli_stmt_bind_param($stmt, 'ss', $email, $type);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
		// Get the user_id of the current user signing up
		$stmt = mysqli_prepare($con, "SELECT user_id FROM users WHERE
            email_address = ?");

		mysqli_stmt_bind_param($stmt, 's', $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $added_user);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		
		//  If the user is an owner, insert them into ownership table
		if ($type=='owner') {
			$stmt = mysqli_prepare($con, "INSERT INTO ownership 
                (property_id, owner_id, notify_structural, notify_electrical, 
                notify_plumbing, notify_security) VALUES (?,?,?,?,?,?)");

			mysqli_stmt_bind_param($stmt, 'ssiiii', $property_id, 
			$added_user, $structural, $electrical, $plumbing, $security);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		} else {
            // User is tenant. Insert their details into tenancy table
            $stmt = mysqli_prepare($con, "INSERT INTO tenancy 
                (tenant_id, property_id, start_date, end_date) VALUES (?,?,?,?)");

			$date = date('Y/m/d'); // TODO Update with dynamic date set by Agent
			$date_end = mktime(0, 0, 0, date("m"), date("d")+100, date("y"));
			mysqli_stmt_bind_param($stmt, 'ssss', $added_user, 
			$property_id, $date, $date_end);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
	}

	// Create the confirmation page that account was created successfully
	createPage($tpl, $loginFailed, $acctFailed);
	
	/**
	 * Creates a page notifying the user whether or not their account creation
	 * was successful
	 *
	 * createPage($tpl, $loginFailed, $acctFailed)
	 *		$tpl - RainTPL object
	 *		$loginFailed - 1: login of user failed
	 *					   0: user has not tried to login in
	 *		$acctFailed -  1: an error occurred during account creation. Display
	 *						error
	 *				 	   0: no error occurred. Successful
	*/
	function createPage($tpl, $loginFailed, $acctFailed) {
		$tpl->assign("fail", $loginFailed);
		$tpl->assign("acctFailed", $acctFailed);
		$tpl->draw("account_result");
		// Stop the script if account creation has failed
		if ($acctFailed == 1) {
			die();
		}
	}
	
	/**
	 * Checks if checkbox is checked
	 * 
	 * isChecked($chkname, $value)
	 *		$chkname - the checkbox name defined in form
	 *		$value - the value to check to see if checked or not
	 *	Return:
	 *			1: Value is checked
	 *			0: Value is not checked
	*/
	function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname])) {
            foreach($_POST[$chkname] as $chkval) {
                if($chkval == $value) {
                    return 1;
                }
            }
        }

        return 0;
    }
?>
