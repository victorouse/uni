<?php
    $con = mysqli_connect("localhost","deco","3801","wallfly");
    if (mysqli_connect_errno($con)) {
        die("Could not establish connection with the database " . mysqli_error());
    }
?>
