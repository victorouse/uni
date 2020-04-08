<?php
    // Connect to database
    include("inc/api.php");

?>
<!DOCTYPE html>
<html>
    <head>
        <title>UQ Knowledgebase</title>
        <?php include("inc/head.php"); ?>
        <script>$('.panel-collapse').collapse({toggle:true});</script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <!-- Issues -->
                <div class="col-md-12">
                    <h3 class="text-center">UQ Knowledgebase</h3>
                    <?php include("search.php"); ?>
                    <div class="panel-group">
                        <?php if ($topics = ($model->getAllTopics())) : ?>
                        <?php foreach($topics as $topic) : ?>
                            <div class="panel panel-default" style="margin-bottom:10px">
                                <div class="panel-heading">
                                    <h3 class="panel-title post-title">
                                        <a data-toggle="collapse" data-target="#<?php echo $topic['topic_id']; ?>">
                                            <?php echo $topic['name']; ?>
                                        </a> 
                                    </h3>
                                </div>
                                <div id="<?php echo $topic['topic_id']; ?>" class="topic-panel panel-collapse collapse in">
                                    <div class="topic-body panel-body">
                                        <?php if ($issues = ($model->getAllIssues($topic['topic_id']))) : ?>
                                        <div class="issuelist">
                                            <table class="issue-table table table-hover">
                                                <tbody>
                                                    <?php foreach ($issues as $issue) : ?>
                                                        <tr>
                                                            <td class="score-column">
                                                                <span class="votes"><?php echo $model->getScore($issue['issue_id'], "issue"); ?></span>
                                                            </td>
                                                            <td class="votes-column">
                                                                <span class="updowns">
                                                                    <span>
                                                                    (
                                                                        <span class="upvotes"><?php echo $issue['upvotes']; ?></span>
                                                                    |
                                                                        <span class="downvotes"><?php echo $issue['downvotes']; ?></span>
                                                                    )
                                                                    </span>
                                                                </span>
                                                            </td>
                                                            <td class="title-tags">
                                                                <a class="title" href="issue.php?id=<?php echo $issue['issue_id']; ?>"><?php echo $issue['issue'] ?></a>
                                                                <?php if ($issue['tags']) : ?>
                                                                <div class="tags">
                                                                    <?php $tags = explode(',', trim($issue['tags'])); ?>
                                                                    <?php foreach($tags as $tag) : ?>
                                                                        <span class='label label-primary tag'><?php echo $tag; ?></span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="vote-column">
                                                                <form class="post" method="POST" action="inc/api.php?ap=vote">
                                                                    <input type="hidden" name="issueID" value="<?php echo $issue['issue_id']; ?>">
                                                                    <input type="hidden" name="memberID" value="<?php echo $_SESSION['user']; ?>">
                                                                    <input type="hidden" name="referrer" value="topics">
                                                                    <input type="hidden" name="voteType" value="issue">
                                                                    <div class="votebox pull-right">
                                                                        <div class="btn-group votebuttons">
                                                                            <?php if ($model->checkVoted($_SESSION['user'], $issue['issue_id'], "issue")) : ?> 
                                                                                <?php if ($model->getVote($_SESSION['user'], $issue['issue_id'], "issue") == "up") : ?>
                                                                                    <button type="submit" name="vote-up" style="background-color:#C8F0C8" class="vote disabled btn btn-sm btn-default">
                                                                                        <span class="glyphicon glyphicon-chevron-up"></span>
                                                                                    </button>
                                                                                    <button type="submit" name="vote-down" class="vote btn btn-sm btn-default">
                                                                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                                                                    </button>
                                                                                <?php elseif ($model->getVote($_SESSION['user'], $issue['issue_id'], "issue") == "down") : ?>
                                                                                    <button type="submit" name="vote-up" class="vote btn btn-sm btn-default">
                                                                                        <span class="glyphicon glyphicon-chevron-up"></span>
                                                                                    </button>
                                                                                    <button type="submit" name="vote-down" style="background-color:#DBA8A8" class="vote disabled btn btn-sm btn-default">
                                                                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                                                                    </button>
                                                                                <?php endif; ?>
                                                                            <?php else: ?>
                                                                                <button type="submit" name="vote-up" class="vote btn btn-sm btn-default">
                                                                                    <span class="glyphicon glyphicon-chevron-up"></span>
                                                                                </button>
                                                                                <button type="submit" name="vote-down" class="vote btn btn-sm btn-default">
                                                                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php else : ?>
                                            <p>There are no issues for this topic.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="panel-footer">
                                        <a href="newIssue.php?id=<?php echo $topic['topic_id']; ?>"><button type="button" class="btn btn-xs btn-success">New Issue</button></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <div class="alert alert-info text-center">There are currently no topics</div>
                        <?php endif; ?>
                    </div>
                </div>   
            </div>
        </div>
    </body>
</html>
