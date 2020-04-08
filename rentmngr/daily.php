<?php
require_once(dirname(__DIR__) . "/htdocs/inc/connect_pdo.php");

/**
*	Helper Functions
**/

/**
*	Retrieve payment schedule information by today 
**/
function getTodaySchedules($day){
       	global $db;

        // Return the payment schedules information
        // Join on tenancy_id
        try{
            $query = "SELECT * FROM `recurring_payments` as r
            LEFT JOIN tenancy as t on t.tenancy_id = r.tenancy_id
            LEFT JOIN users as u on t.tenant_id = u.user_id
            WHERE r.day = ?";
            $stmt = $db->prepare($query);

            $stmt->execute(array($day));
            //success
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Something went wrong
            var_dump($e);
        }
    }

function interact($page, $post = false, $params = NULL){
    	// This is the black magic (my secret key)
    	$authentication = "V2XIrIMkWBo2Im9VX5fgpQ".":". "";
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


function chargeCustomer($db, $tenancy_id, $email_address, $customer_token, $amount, $description){
        // Check variables
        if($amount<100){
            throw new Exeception("Amount must be in cents and be greater than or equal to 100 ($1)");
        }
        if(sizeof($description)<1){
            throw new Exception("Must include a description for the charge");
        }

        // Setup params
        $ip_address = "130.102.79.50";
        $params = array("email"=>$email_address, "description"=>$description, "amount"=>$amount, "ip_address"=>$ip_address, "customer_token"=>$customer_token);
        
        // Interact
        $response = interact("charges", true, $params);
        // Step into the response
        if(isset($response->{'response'})){
            $response = $response->{'response'};
            $charge_token = $response->{'token'};
            // Add charge to db
            $query = "INSERT INTO cc_charges (tenancy_id, charge_token, amount, datetime) VALUES (?, ?, ?, NOW())";
            $stmt = $db->prepare($query);
            $stmt->execute(array($tenancy_id, $charge_token, $amount));     

            //sendMail($email_address, "Payment Received", "Thanks! We have received payment for ". $amount . ". Your receipt number is ". $charge_token .". If you have any queries please reply to this email.");
            return true;   
        }else{
            // handle errors
            var_dump($response);
            return false;
        }
    }

function calculateFiscalYearForDate($inputDate){
    $fyStart = "7/1";
    $fyEnd = "6/30";
    $date = strtotime($inputDate);
    $inputyear = strftime('%Y',$date);
    $fystartdate = strtotime($fyStart.$inputyear);
    $fyenddate = strtotime($fyEnd.$inputyear);
 
    if($date < $fyenddate){ 
        $fy = intval($inputyear);
    }else{
        $fy = intval(intval($inputyear) + 1);
    }
    return $fy;
}

function thisDayofWeek(){
	$today = date_create();
	$fiscal_year = calculateFiscalYearForDate("2013/8/3");
    $start_of_fiscal_year = $fiscal_year - 1;
    // so work out first monday from 7/01/ year
    $first_of_year = "07/01/".$start_of_fiscal_year;
    $i = 0;
    $monday = 1;
    while($monday){
        $text = $first_of_year . " + " . $i . " days";
        $loop_date = date_create_from_format("U", strtotime($text));
        $day_of_week = $loop_date->format("l");
        if($day_of_week == "Monday"){
            $monday = 0;
            break;
        }
        $i++;
    }
    $text = $first_of_year . " + " . $i . " days";
    $start_day = date_create_from_format("U", strtotime($text));
    $today_in_week = (date_diff($start_day, $today)->days % 14) + 1;
    return $today_in_week;
}

// get today's schedules
$day = thisDayofWeek();

$schedules = getTodaySchedules($day);
foreach($schedules as $schedule){
	$a = chargeCustomer($db, $schedule["tenancy_id"], $schedule["email_address"], $schedule["cus_token"], $schedule["amount"], "Automatic scheduled payment");
}
echo("Done!\n");


?>