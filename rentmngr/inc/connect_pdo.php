<?php
    // Credentials
    $db_user = "deco";
    $db_pass = "3801";
    $db_name = "wallfly";
    $hostname = "127.0.0.1";

    // Connect to database
    try {
        /* 
         * Config:
         *  - persistent connection (e.g. no need to re-establish connection
         *  - use native PDO mysql prepared statements
         *  - show exception errors
         */
        $db = new PDO("mysql:host=".$hostname.";dbname=".$db_name.";charset=utf8", 
            $db_user, $db_pass, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        echo "Error connecting to database " . $e->getMessage() . "<br />";
    }
?>
