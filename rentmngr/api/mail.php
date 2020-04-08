<?php
    require_once(dirname(__DIR__) . "/inc/mailer/PHPMailerAutoload.php");
    /**
    *   Master send_mail function. Note: this does not work inside UQ Zones
    *       as they block mailservers other than UQ mailhub. Hosts, ports,
    *       usernames and passwords are all configured in this function
    *   @param string email
    *       email address of the recepient
    *   @param string name
    *       name of the recepient
    *   @param string subject
    *       the subject of the email
    *   @param string body
    *       the body of the email
    *   @return boolean status of the email being sent
    */
    function send_mail($email, $name, $subject, $body) {
        // Setup mailer variables
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPSecure = "tls";
        $mail->SMTPAuth = true;
        $mail->Username = "mailmaster.wallfly@gmail.com";
        $mail->Password = "deco3801";
        $mail->SetFrom("mailmaster.wallfly@gmail.com", "WallFly NoReply");

        $mail->addAddress($email, $name); 
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            return false;
        }

        return true;
    }
?>
