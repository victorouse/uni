<?php
    // Connect to database
    include("inc/api.php");

    $issueID = $_GET['id'];
    $issue = $model->getIssue($issueID);
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
                    <div class="panel panel-warning" id="topics">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Issue</h3>
                        </div>
                        <form role="form" id="editIssue" method="POST" enctype="multipart/form-data" action="inc/api.php?ap=editIssue" onsubmit="return postForm()" style="padding:15px">
                            <input type="hidden" name="issueID" value="<?php echo $issue['issue_id']; ?>">
                            <div class="form-group">
                                <label for="inputIssue">Issue</label>
                                <input type="text" class="form-control" name="issue" id="inputIssue" value="<?php echo $issue['issue']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputTags">Tags</label>
                                <input type="text" class="form-control" name="tags" id="inputTags" value="<?php echo $issue['tags']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Options</label>
                                <div class="checkbox">
                                    <?php if ($issue['allow_comments'] == 1) : ?>
                                    <input type="checkbox" name="commentsFlag" checked>Allow comments 
                                    <?php else : ?>
                                    <input type="checkbox" name="commentsFlag">Allow comments 
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAnswer">Answer</label>
                                <textarea class="input-block-level" id="summernote" name="answer" id="inputAnswer" rows="10" style="overflow-y:scroll">
                                    <?php echo $issue['answer']; ?> 
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
                var content = $('textarea[name="answer"]').val($('#summernote').code());
            }
        </script>
    </body>
</html>

