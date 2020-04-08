<?php
    require("inc/view_api.php");
    $view = new TopicView();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>UQ Knowledgebase</title>
        <?php include("inc/head.php"); ?>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center">UQ Knowledgebase</h3>
                    <?php echo $view->createPanel("test heading", "test body", "panel-default", "footer"); ?>
                </div>   
            </div>
        </div>
    </body>
</html>
