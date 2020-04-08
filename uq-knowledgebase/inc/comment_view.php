<?php
    class Comment extends IssueView {
        private $comment;
        private $issue;

        public function __construct($commentID, $issueID) {
            parent::__construct($issueID);
            $this->comment = $this->model->getComment($commentID);
            $this->issue = $this->getIssue();
        }

        public function displayVotebuttons($comment) {
            $buttonGroup = "<div class='btn-group votebuttons'>";

            $votedUp =
            "
                        <button type='submit' name='vote-up' class='vote vote-up disabled btn btn-sm btn-default'>
                            <span class='glyphicon glyphicon-chevron-up'></span>
                        </button>
            ";

            $votedDown =
            "
                        <button type='submit' name='vote-down' class='vote disabled vote-down btn btn-sm btn-default'>
                            <span class='glyphicon glyphicon-chevron-down'></span>
                        </button>
            ";

            $voteUp =
            "
                        <button type='submit' name='vote-up' class='vote btn btn-sm btn-default'>
                            <span class='glyphicon glyphicon-chevron-up'></span>
                        </button>
            ";

            $voteDown =
            "
                        <button type='submit' name='vote-down' class='vote btn btn-sm btn-default'>
                            <span class='glyphicon glyphicon-chevron-down'></span>
                        </button>
            ";

            if ($this->model->checkVoted($this->user, $comment['comment_id'], "comment")) {
                if ($this->model->getVote($this->user, $comment['comment_id'], "comment") == "up") {
                    // issue has been voted up already
                    $voteButtons = $votedUp;
                    $voteButtons .= $voteDown;
                } else if ($this->model->getVote($this->user, $comment['comment_id'], "comment") == "down") {
                    // issue has been voted down already
                    $voteButtons = $voteUp;
                    $voteButtons .= $votedDown;
                }
            } else {
                    // issue has not been voted on
                    $voteButtons = $voteUp;
                    $voteButtons .= $voteDown;
            }

            $buttonGroup .= $voteButtons;
            $buttonGroup .= "</div>";

            return $buttonGroup;
        }

        public function displayVotebox($comment) {
            $voteBox = "";
            $voteBoxClass = "<div class='votebox pull-right'>";

            $scoreVotes = "<span class='votes'>%1\$s</span>";
            $scoreVotes = sprintf($scoreVotes, $this->model->getScore($comment['comment_id'], "comment"));

            $form = "<form class='vote-form' method='POST' action='inc/api.php?ap=vote'>";

            $hiddenInputs = 
            "
                <input type='hidden' name='issueID' value='%1\$s'>
                <input type='hidden' name='commentID' value='%2\$s'>
                <input type='hidden' name='referrer' value='issue'>
                <input type='hidden' name='voteType' value='comment'>
            ";
            $hiddenInputs = sprintf($hiddenInputs, $this->issue['issue_id'], $comment['comment_id']);

            $voteButtons = $this->displayVotebuttons($comment);

            $form .= $hiddenInputs;
            $form .= $voteButtons;
            $form .= "</form>";

            $voteBoxBody = $scoreVotes;
            $voteBoxBody .= $form;

            if ($comment['member_id'] == $this->user) {
                $voteBoxClass = "<div class='votebox-author pull-right'>";
                $creatorButtons = 
                "
                    <div class='btn-group author-btn-group'>
                        <button type='button' class='btn btn-sm btn-warning dropdown-toggle' data-toggle='dropdown'>
                            <span class='glyphicon glyphicon-cog'></span>
                        </button>
                        <ul class='dropdown-menu edit-menu' role='menu'>
                            <li><a href='editComment.php?id=%1\$s'>Edit</a></li>
                            <li><a href='inc/api.php?ap=deleteComment?id=%1\$s'>Delete</a></li>
                        </ul>
                    </div>
                ";

                $creatorButtons = sprintf($creatorButtons, $comment['comment_id']);
                $voteBoxBody .= $creatorButtons;
            }
            
            // construct full div
            $voteBox .= $voteBoxClass;
            $voteBox .= $voteBoxBody;
            $voteBox .= "</div>";

            return $voteBox;
        }

        private function displayInput() {
            $html = "<div class='reply'>";
                
            $panelHeading = 
            "
                <div class='panel-heading'>
                    <h3 class='panel-title new-post' data-toggle='collapse' data-target='#%1\$s'>
                        Write a comment...
                    </h3>
                </div>
            ";
            $panelHeading = sprintf($panelHeading, $this->comment['comment_id']);

            $panelBody = 
            "
                <div class='panel-body collapse in' id='%2\$s'>
                    <form id='postForm' action='inc/api.php?ap=commentReply' method='POST' onsubmit='return postForm(this)' enctype='multipart/form-data'>
                        <input type='hidden' name='issueID' value='%1\$s'>
                        <input type='hidden' name='commentID' value='%2\$s'>
                        <input type='hidden' name='author' value='%3\$s'>
            ";
            $panelBody .= $this->displayPostbox();
            $panelBody .= 
            "
                        <button type='submit' class='btn btn-primary' style='margin-top:15px'>Post</button>
                    </form>
                </div>
            ";
            $panelBody = sprintf($panelBody, $this->issue['issue_id'], $this->comment['comment_id'], $this->user);

            $panel = "<div class='panel panel-reply panel-info'>";
            $panel .= $panelHeading;
            $panel .= $panelBody;

            // close panel
            $panel .= "</div>";
            $html .= $panel;

            // close reply
            $html .= "</div>";
            return $html;
        }

        public function displayInfo($comment) {
            $postInfo = "<div class='post-info pull-left'>";
                    
            $title = "<h3 class='panel-title post-title'>%1\$s</h3>";
            $title = sprintf($title, $comment['title']);

            $authorDate = $this->displayAuthorDate($comment);

            $postInfo .= $title;
            $postInfo .= $authorDate;
            $postInfo .= "</div>";

            return $postInfo;
        }

        private function displayReply($reply) {
            $html = "<blockquote class='block-reply'>";
            $replyHeader = $this->displayInfo($reply);
            $replyHeader .= $this->displayVotebox($reply);
            $html .= $this->createPanel($replyHeader, $reply['comment'], "panel-default comment-reply");

            $html .= "</blockquote>";
            return $html;
        }

        private function displayCommentReplies() {
            $html = "<div class='replies'>";
            $replies = $this->model->getReplies($this->comment['comment_id']);

            foreach ($replies as $reply) {
                $html .= $this->displayReply($reply);
            }

            $html .= "</div>";
            return $html;
        }

        public function displayComment() {
            $html = "";

            $commentHeader = $this->displayInfo($this->comment);
            $commentHeader .= $this->displayVotebox($this->comment);

            $html .= $this->createPanel($commentHeader, $this->comment['comment'], "panel-default");
            $html .= $this->displayInput();
            $html .= $this->displayCommentReplies();
            $html .= "<hr>";

            return $html;
        }
    }
?>
