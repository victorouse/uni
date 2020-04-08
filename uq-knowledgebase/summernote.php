<!DOCTYPE html>
<html lang="en">
<!-- include libries(jQuery, bootstrap, fontawesome) -->
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script> 
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.no-icons.min.css" rel="stylesheet"> 
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> 
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!-- include summernote css/js-->
<link href="css/summernote.css" / rel="stylesheet">
<script src="js/summernote.min.js"></script>
<body>
<div class="container">
    <div class="row">
        <form id="postForm" action="" method="POST" enctype="multipart/form-data">
                    <textarea class="input-block-level" id="summernote" name="content" rows="18">
                    </textarea>
            <button type="submit" class="btn btn-primary">Save changes</button>
            <button type="button" id="cancel" class="btn">Cancel</button>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#summernote').summernote({
        height: "500px"
    });
});
</script>
</body>
</html>
