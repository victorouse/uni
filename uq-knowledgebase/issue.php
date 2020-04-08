<?php
    require("inc/bootstrap_api.php");
    require("inc/issue_view.php");

    $issueID = $_GET['id'];
    $view = new IssueView($issueID);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>UQ Knowledgebase</title>
        <?php include("inc/head.php"); ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.collapse').collapse();
                $('.summernote').summernote({
                    height: "250px"
                });

            });

            var postForm = function(me) {
                var content = $(me).find('textarea[name="comment"]').val($(me).find('.summernote').code());
            }
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center">UQ Knowledgebase</h3>
                    <?php //include("search.php"); ?>
                    <?php $view->render(); ?>
                </div>   
            </div>
        </div>
    </body>
</html>
