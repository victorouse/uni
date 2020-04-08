<?php
    // Connect to database
    include("inc/api.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>UQ Knowledgebase</title>
        <?php include("inc/head.php"); ?>

        <script>
            $(document).ready(function() {
                /* Collapse panel divs initially */
                //$(".collapse").collapse();

                /* Check all checkboxes within a form */
                $(".selectall").click(function() {
                     $(this).closest("form").find(":checkbox").trigger('click');
                });

                /* Remove issue checkbox validation */
                $(".remove").click(function() {
                    if (!($(this).closest("form").find(":checkbox").is(":checked"))) {
                        alert("You must select at least one issue to delete");
                    }
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center"><a href="topics.php">UQ Knowledgebase</a></h3>
                    <div class="panel-group" id="accordion">
                    <?php if ($topics = ($model->getAllTopics())) : ?>
                        <?php foreach($topics as $topic) : ?>
                            <div class="panel panel-default" style="margin-bottom:10px">
                                <div class="panel-heading" style="overflow:hidden">
                                    <h3 class="panel-title post-title pull-left" style="line-height:1.8">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $topic['topic_id'] ?>">
                                            <?php echo $topic['name'] ?>
                                        </a> 
                                    </h3>
                                    <div id="removeTopic" class="pull-right">
                                        <form action="inc/api.php?ap=removeTopic" method="POST">
                                            <input type="hidden" name="topicID" value="<?php echo $topic['topic_id'] ?>">
                                            <button type="submit" class="btn btn-xs btn-danger" style="margin-top:3px">Remove</button> 
                                        </form>
                                    </div>
                                </div>
                                <div id="<?php echo $topic['topic_id'] ?>" class="panel-collapse collapse in">
                                    <form method="POST" action="inc/api.php?ap=deleteIssues">
                                        <div class="panel-body" style="padding-bottom:0px">
                                            <?php if ($issues = ($model->getAllIssues($topic['topic_id']))) : ?>
                                            <ul class="issuelist-editable">
                                                <?php foreach($issues as $issue) : ?>
                                                    <li>
                                                        <input type="checkbox" name="issue[]" value="<?php echo $issue['issue_id']; ?>">
                                                        <label>
                                                            <a href="editIssue.php?id=<?php echo $issue['issue_id']; ?>"><?php echo $issue['issue'] ?></a>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php else : ?>
                                            <p>There are currently no issues for this topic.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="panel-footer">
                                            <a href="newIssue.php?id=<?php echo $topic['topic_id']; ?>"><button type="button" class="btn btn-xs btn-success">New Issue</button></a>
                                            <?php if ($issues) : ?>
                                            <button type="submit" class="btn btn-xs btn-danger remove">Remove</button>
                                            <button type="button" class="btn btn-xs btn-primary selectall">Select All</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="alert alert-info text-center">There are currently no topics</div>
                    <?php endif; ?>
                        <div class="panel panel-info" style="margin-bottom:10px">
                            <div class="panel-heading">
                                <h3 class="panel-title post-title">New topic</h3>
                            </div>
                            <div id="new-topic" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <form method="POST" action="inc/api.php?ap=newTopic">
                                        <div class="form-group">
                                            <label for="inputTitle" class="control-label">Title</label>
                                            <input type="text" name="topicTitle" class="form-control" id="inputTitle" placeholder="Enter a topic title">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Topic</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div> <!-- /panel group -->
                </div>   
            </div>
        </div>
    </body>
</html>

