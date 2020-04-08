<?php
/**
 * Logs user into system using an email and password
 * Requires the following variables from POST
 *		email - the email of the user
 *		password - the password of the user
 *
 */
	// Instantiate variables
	include('inc/connect.php');

    // Request variables from POST
    $email = $_REQUEST['email'];  
    $password = $_REQUEST['password'];
	$password = hash('sha512', $password);

    // Check if credentials are correct against DB
    $stmt = mysqli_prepare($con, "SELECT user_id, first_name, type, 
        email_address, password, salt FROM `users` 
        WHERE `email_address` = ?"); 

    mysqli_stmt_bind_param($stmt, 's', $email);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $first_name, $type, 
        $email, $rq_password, $random_salt);
	mysqli_stmt_fetch($stmt);
	$count = mysqli_stmt_num_rows($stmt);

	mysqli_stmt_close($stmt);

    // Hash password & salt
	$password = hash('sha512', $password.$random_salt); 
	
    // Email matches - time to check password
	if ($count==1) {
        // Correct password
		if($rq_password == $password) {
			// Add user to session to be 'logged in'
			session_start();
			$_SESSION['user'] = $first_name;
			$_SESSION['user_id'] = $user_id;
			$_SESSION['type'] = strtolower($type);
			$_SESSION['email_address'] = $email;
		
			header("Location: ./");
        } else {
            // Incorrect password
			header("Location: ./?fail=1");
		}
    } else { 
        // Incorrect email
		header("Location: ./?fail=1");
	}
?>
