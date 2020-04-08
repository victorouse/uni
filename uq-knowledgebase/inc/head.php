<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap and co. -->
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script> 
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet"> 
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script> 
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
<link href="css/summernote.css" / rel="stylesheet">
<script src="js/summernote.min.js"></script>
<!-- include summernote css/js-->
<link href="css/summernote.css" / rel="stylesheet">
<script src="js/summernote.min.js"></script>
<link href="css/uqkb.css" / rel="stylesheet">
<?php
    if (!session_id()) {
        session_start();
        $_SESSION['user'] = 3;
    }
?>
