<?php
    // Connect to database
    include("inc/api.php");

    $issue = $model->getIssue($_GET['id']);
    $user = $model->getUser($issue['member_id']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>UQ Knowledgebase</title>
        <?php include("inc/head.php"); ?>
        <script>
            $(document).ready(function() {
                $(".collapse").collapse();
            });
        </script>
    </head>
    <body>
        <div class="container">
            <h3 class="text-center"><a href="topics.php">UQ Knowledgebase</a></h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default" id="issue">
                        <div class="panel-heading" style="overflow:hidden">
                            <form class="post" method="POST" action="inc/api.php?ap=vote">
                                <div class="post-info pull-left">
                                    <input type="hidden" name="issueID" value="<?php echo $issue['issue_id']; ?>">
                                    <input type="hidden" name="memberID" value="<?php echo $_SESSION['user']; ?>">
                                    <input type="hidden" name="referrer" value="issue">
                                    <input type="hidden" name="voteType" value="issue">
                                    <h2 class="panel-title issue-title"><?php echo $issue['issue']; ?></h2>
                                    <div class="author">
                                        Posted by <?php echo $user['name']; ?> <span id="create-date"> on <?php echo $issue['create_dt']; ?></span>
                                    </div>
                                    <?php if ($issue['tags']) :?>
                                    <div class="tags">
                                        <?php $tags = explode(',', trim($issue['tags']));
                                            foreach($tags as $tag) {
                                                echo "<span class='label label-primary tag'>" . $tag . "</span>";
                                            }
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="votebox pull-right">
                                    <span class="votes">
                                        <?php echo $model->getScore($issue['issue_id'], "issue"); ?>
                                    </span>
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
                                    <?php if ($_SESSION['user'] == $issue['member_id']) : ?>
                                    <a href="editIssue.php?id=<?php echo $issue['issue_id']; ?>"><button type="button" class="btn btn-sm btn-warning">Edit</button></a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        <div class="panel-body">
                            <?php echo $issue['answer']; ?>
                        </div>
                        <?php if ($issue['update_dt']) :?>
                        <div class="panel-footer">
                            <div class="update-info">
                                Last updated: <?php echo $issue['update_dt']; ?> 
                                <span class="revision pull-right"> Revision: <?php echo $issue['revision']; ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                    <?php if ($issue['allow_comments']) : ?>
                    <div class="reply">
                        <div class="panel panel-reply panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title new-post" data-toggle="collapse" data-target=".message-body">
                                    Write a comment...
                                </h3>
                            </div>
                            <div class="panel-body message-body collapse in">
                                <form id="postForm" action="inc/api.php?ap=newComment" method="POST" onsubmit="return postForm(this)" enctype="multipart/form-data">
                                    <input type="hidden" name="issueID" value="<?php echo $issue['issue_id'] ?>">
                                    <input type="hidden" name="author" value="<?php echo $_SESSION['user']; ?>">
                                    <?php include("postbox.php"); ?>
                                    <button type="submit" class="btn btn-primary" style="margin-top:15px">Post</button>
                                    <button type="reset" class="btn btn-primary" style="margin-top:15px">Clear</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3>Comments</h3>
                    <hr>
                    <div id="comments">
                        <?php if (($comments = $model->getIssueComments($issue['issue_id']))) : ?>
                        <?php foreach ($comments as $comment) : ?>
                        <div class="panel panel-default comment">
                            <div class="panel-heading" style="overflow:hidden">
                                <div class="post-info pull-left">
                                    <h3 class="panel-title post-title"><?php echo $comment['title']; ?></h3>
                                    <div class="author">
                                        <?php $commentUser = $model->getUser($comment['member_id']); ?>
                                        Posted by <?php echo $commentUser['name']; ?> <span id="create-date"> on <?php echo $comment['create_dt']; ?></span>
                                    </div>
                                </div>
                                <form class="post" method="POST" action="inc/api.php?ap=vote">
                                    <div class="votebox pull-right">
                                        <span class="votes">
                                            <?php echo $model->getScore($comment['comment_id'], "comment"); ?>
                                        </span>
                                        <input type="hidden" name="memberID" value="<?php echo $_SESSION['user']; ?>">
                                        <input type="hidden" name="commentID" value="<?php echo $comment['comment_id']; ?>">
                                        <input type="hidden" name="issueID" value="<?php echo $issue['issue_id']; ?>">
                                        <input type="hidden" name="referrer" value="issue">
                                        <input type="hidden" name="voteType" value="comment">
                                        <div class="btn-group votebuttons">
                                            <?php if ($model->checkVoted($_SESSION['user'], $comment['comment_id'], "comment")) : ?> 
                                                <?php if ($model->getVote($_SESSION['user'], $comment['comment_id'], "comment") == "up") : ?>
                                                    <button type="submit" name="vote-up" style="background-color:#C8F0C8" class="vote disabled btn btn-sm btn-default">
                                                        <span class="glyphicon glyphicon-chevron-up"></span>
                                                    </button>
                                                    <button type="submit" name="vote-down" class="vote btn btn-sm btn-default">
                                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                                    </button>
                                                <?php elseif ($model->getVote($_SESSION['user'], $comment['comment_id'], "comment") == "down") : ?>
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
                                        <?php if ($_SESSION['user'] == $comment['member_id']) : ?>
                                        <a href="editComment.php?id=<?php echo $comment['comment_id']; ?>"><button type="button" class="btn btn-sm btn-warning">Edit</button></a>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                            <div class="panel-body">
                                <?php echo $comment['comment']; ?>
                            </div>
                        </div>
                        <div class="reply">
                            <div class="panel panel-reply panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title new-post" data-toggle="collapse" data-target="#<?php echo $comment['comment_id']; ?>">
                                        Write a comment...
                                    </h3>
                                </div>
                                <div class="panel-body collapse in" id="<?php echo $comment['comment_id']; ?>">
                                    <form id="postForm" action="inc/api.php?ap=commentReply" method="POST" onsubmit="return postForm(this)" enctype="multipart/form-data">
                                        <input type="hidden" name="issueID" value="<?php echo $issue['issue_id'] ?>">
                                        <input type="hidden" name="commentID" value="<?php echo $comment['comment_id'] ?>">
                                        <input type="hidden" name="author" value="<?php echo $_SESSION['user']; ?>">
                                        <?php include("postbox.php"); ?>
                                        <button type="submit" class="btn btn-primary" style="margin-top:15px">Post</button>
                                        <button type="reset" class="btn btn-primary" style="margin-top:15px">Clear</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php if ($replies = ($model->getReplies($comment['comment_id']))) : ?>
                        <div class="replies">
                            <?php foreach ($replies as $reply) :?>
                            <blockquote class="block-reply">
                            <div class="panel panel-default comment-reply">
                                <div class="panel-heading" style="overflow:hidden">
                                    <div class="post-info pull-left">
                                        <h3 class="panel-title post-title"><?php echo $reply['title']; ?></h3>
                                        <div class="author">
                                            <?php $commentUser = $model->getUser($reply['member_id']); ?>
                                            Posted by <?php echo $commentUser['name']; ?> <span id="create-date"> on <?php echo $reply['create_dt']; ?></span>
                                        </div>
                                    </div>
                                    <form class="post" method="POST" action="inc/api.php?ap=vote">
                                        <div class="votebox pull-right">
                                            <span class="votes">
                                                <?php echo $model->getScore($reply['comment_id'], "comment"); ?>
                                            </span>
                                            <input type="hidden" name="memberID" value="<?php echo $_SESSION['user']; ?>">
                                            <input type="hidden" name="commentID" value="<?php echo $reply['comment_id']; ?>">
                                            <input type="hidden" name="issueID" value="<?php echo $issue['issue_id']; ?>">
                                            <input type="hidden" name="referrer" value="issue">
                                            <input type="hidden" name="voteType" value="comment">
                                            <div class="btn-group votebuttons">
                                                <?php if ($model->checkVoted($_SESSION['user'], $reply['comment_id'], "comment")) : ?> 
                                                    <?php if ($model->getVote($_SESSION['user'], $reply['comment_id'], "comment") == "up") : ?>
                                                        <button type="submit" name="vote-up" style="background-color:#C8F0C8" class="vote disabled btn btn-sm btn-default">
                                                            <span class="glyphicon glyphicon-chevron-up"></span>
                                                        </button>
                                                        <button type="submit" name="vote-down" class="vote btn btn-sm btn-default">
                                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                                        </button>
                                                    <?php elseif ($model->getVote($_SESSION['user'], $reply['comment_id'], "comment") == "down") : ?>
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
                                            <?php if ($_SESSION['user'] == $reply['member_id']) : ?>
                                            <a href="editComment.php?id=<?php echo $reply['comment_id']; ?>"><button type="button" class="btn btn-sm btn-warning">Edit</button></a>
                                            <?php endif; ?>
                                            </div>
                                    </form>
                                </div>
                                <div class="panel-body">
                                    <?php echo $reply['comment']; ?>
                                </div>
                            </div>
                            </blockquote>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <hr>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <div class="alert alert-info text-center">No comments</div>
                        <?php endif; ?>
                    </div>
                    <?php else : ?>
                            <div class="alert alert-warning text-center">Comments have been disabled</div>
                    <?php endif; ?>
                </div>   
            </div>
        </div>
    </body>
</html>


