<?php

    // Initiate variables
	include('inc/tpl.php');
	include("inc/connect_pdo.php");
	include('api/referral.php');
	include("inc/website_variables.php");
	$tpl = new RainTPL;
	
	session_start();

    // Check user is logged in
	if (!isset($_SESSION["user"])) {
		$fail = 0;
		if (isset($_GET['fail'])) {
			$fail = 1;
		}
		
        // Send non-logged in user to marketing page
		$tpl->assign("fail", $fail);
		assignWebsiteName($tpl);
		if (isset($_REQUEST['result'])) {
			$accountSuccess = $_REQUEST['result'];
			$tpl->assign("acctSuccess", $accountSuccess);
			$tpl->draw("account_result");
			exit();
		}
	
		// If the referral id is valid, display signup page. Otherwise display error
		if (isset($_REQUEST['referrerid'])) {
			$referrerid = $_REQUEST['referrerid'];
			if (checkReferralIdIsValid($referrerid)) {
				$referrerid = $_REQUEST['referrerid'];
                $_SESSION['referrerid'] = $referrerid;
				$result = getReferralUser($referrerid);
				$email = $result[0]['email_address'];
				$type = $result[0]['type'];
                if ($type == 'Tenant') {
                    $start_date = $result[0]['start_date'];
                    $end_date = $result[0]['end_date'];
                    $daily_rent = $result[0]['daily_rent'];
                    $tpl->assign("start_date", $start_date);
                    $tpl->assign("end_date", $end_date);
                    $tpl->assign("daily_rent", $daily_rent);
                }
				
				$tpl->assign("referrerid", $referrerid);
				$tpl->assign("email", $email);
				$tpl->assign("accountType", $type);
				$tpl->draw("signup");
				exit();
			} else {
				$accountSuccess = 2;
				$tpl->assign("acctSuccess", $accountSuccess);
				$tpl->draw("account_result");
				exit();
			}
		}
	} else {
		header("Location: home.php");
		
	}	
	
?>
