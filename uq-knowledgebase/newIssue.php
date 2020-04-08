<?php
    // Connect to database
    include("inc/api.php");

    if (isset($_GET['id'])) {
        $topicID = $_GET['id'];
    } else {
        $topicID = -1;
    }

    if (!session_id()) {
       session_start(); 
    }

    $member_id = $_SESSION['user'];
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
                    <div class="panel panel-info" id="topics">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add New Issue</h3>
                        </div>
                        <form role="form" id="addIssue" method="POST" enctype="multipart/form-data" action="inc/api.php?ap=newIssue" onsubmit="return postForm()" style="padding:15px">
                            <input type="hidden" name="topicID" value="<?php echo $topicID; ?>">
                            <input type="hidden" name="member" value="<?php echo $member_id; ?>">
                            <div class="form-group">
                                <label for="inputIssue">Issue</label>
                                <input type="text" class="form-control" name="issue" id="inputIssue" placeholder="Enter issue name">
                            </div>
                            <div class="form-group">
                                <label for="inputTags">Tags (optional)</label>
                                <input type="text" class="form-control" name="tags" id="inputTags" placeholder="Enter comma separated tags (e.g. network, troubleshooting, ... )">
                            </div>
                            <div class="form-group">
                                <label>Options</label>
                                <div class="checkbox">
                                    <input type="checkbox" name="commentsFlag">Allow comments 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAnswer">Answer</label>
                                <textarea class="input-block-level" id="summernote" name="answer" id="inputAnswer" rows="10" style="overflow-y:scroll" placeholder="Enter answer"></textarea>
                            </div>
                            <button type="submit" name="insert" class="btn" style="text-align:center">Add issue</button>
                            <button type="button" name="back" class="btn" style="text-align:center">Back</button>
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
            });
            
            var postForm = function() {
                var content = $('textarea[name="answer"]').val($('#summernote').code());
            }
        </script>
    </body>
</html>

