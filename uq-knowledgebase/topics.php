<?php
    require("inc/bootstrap_api.php");
    require("inc/topic_view.php");
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
                    <?php include("search.php"); ?>
                    <?php $view->render(); ?>
                </div>   
            </div>
        </div>
    </body>
</html>
