<?php
    // Connect to database
    include("inc/api.php");

    $commentID = $_GET['id'];
    $comment = $model->getComment($commentID);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>UQ Knowledgebase</title>
        <?php include("inc/head.php"); ?>
    </head>
    <body>
        <div class="container">
            <h3 class="text-center"><a href="topics.php">UQ Knowledgebase</a></h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Comment</h3>
                        </div>
                        <form role="form" id="editComment" method="POST" enctype="multipart/form-data" action="inc/api.php?ap=editComment" onsubmit="return postForm()" style="padding:15px">
                            <input type="hidden" name="commentID" value="<?php echo $comment['comment_id']; ?>">
                            <div class="form-group">
                                <label for="inputTitle">Title</label>
                                <input type="text" class="form-control" name="title" id="inputTitle" value="<?php echo $comment['title']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputComment">Comment</label>
                                <textarea class="input-block-level" id="summernote" name="comment" id="inputComment" rows="10" style="overflow-y:scroll">
                                    <?php echo $comment['comment']; ?> 
                                </textarea>
                            </div>
                            <button type="submit" name="update" class="btn" style="text-align:center">Save</button>
                            <a href="editTopics.php"><button type="button" name="back" class="btn" style="text-align:center">Back</button></a>
                        </form>
                    </div>
                </div>   
            </div>
        </div>

        <!-- Initialize text editor -->
        <script type="text/javascript">
            $(document).ready(function() {
                $('#summernote').summernote({
                    height: "400px"
                });

                $("#summernote").code($("#summernote").text());
            });
            
            var postForm = function() {
                var content = $('textarea[name="comment"]').val($('#summernote').code());
            }
        </script>
    </body>
</html>

