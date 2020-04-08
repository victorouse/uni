<?php
 	include("website_variables.php");
  	session_start();
	
	// Check if the user is logged in
	if (!isset($_SESSION['user'])) {
		// If not redirec tthem to the index page
		header("Location: ./?fail=1");
		die();
	}else{
		// Otherwise create the session variables
		$user_id = $_SESSION["user_id"];
		$user_name = $_SESSION["user"];
		$user_type = $_SESSION["type"];
		$email_address = $_SESSION["email_address"];
	}
	
	/**
	*	A nifty function which can be used to restrict flows to
	*		particular user types, assigned in $user_array
	*/
	function restrictUsers($user_array){
		global $user_type;
		$i_type = "";
		// If one of the user types matches this user, then return 1
		foreach($user_array as $i_type){
			if(strtolower($i_type) == strtolower($user_type)){
				return 1;
			}
		}
		// Otherwise return 0
		return 0;
	}
	
	/**
	*	This function takes in a tpl object, by reference, then
	* 		assigns the session variables to the tpl objects to be
	*		used in various templates, such as in headers
	*/
	function assignUserVariables(&$tpl){
		global $user_id, $user_name, $user_type, $email_address, $website_name;
           // Assign for template
		$tpl->assign("user_name",$user_name);
		$tpl->assign("user_type",$user_type);
		$tpl->assign("user_id", $user_id);
		$tpl->assign("email", $email_address);
		assignWebsiteName($tpl);
	}
?>