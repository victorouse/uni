<?php
    // Start session in order to destroy it (weird)
	session_start();

    // Delete session variables
	unset($_SESSION['user']);
	unset($_SESSION['user_id']);
	unset($_SESSION['type']);
	unset($_SESSION['email_address']);

	session_destroy();

    // Redirect back to homepage
	header("Location: index.php");
?>
