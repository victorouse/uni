<?php
    require("comment_view.php");
    class IssueView extends ViewModel {
        private $html;
        private $issue;
        private $author;

        public function __construct($id) {
            parent::__construct();
            $this->issue = $this->model->getIssue($id);
        }

        public function getIssue() {
            return $this->issue;
        }

        public function displayVotebuttons() {
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

            if ($this->model->checkVoted($this->user, $this->issue['issue_id'], "issue")) {
                if ($this->model->getVote($this->user, $this->issue['issue_id'], "issue") == "up") {
                    // issue has been voted up already
                    $voteButtons = $votedUp;
                    $voteButtons .= $voteDown;
                } else if ($this->model->getVote($this->user, $this->issue['issue_id'], "issue") == "down") {
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

        public function displayVotebox() {
            $voteBox = "";
            $voteBoxClass = "<div class='votebox pull-right'>";

            $scoreVotes = "<span class='votes'>%1\$s</span>";
            $scoreVotes = sprintf($scoreVotes, $this->model->getScore($this->issue['issue_id'], "issue"));

            $form = "<form class='vote-form' method='POST' action='inc/api.php?ap=vote'>";

            $hiddenInputs = 
            "
                <input type='hidden' name='issueID' value='%1\$s'>
                <input type='hidden' name='referrer' value='issue'>
                <input type='hidden' name='voteType' value='issue'>
            ";
            $hiddenInputs = sprintf($hiddenInputs, $this->issue['issue_id']);

            $voteButtons = $this->displayVotebuttons();

            $form .= $hiddenInputs;
            $form .= $voteButtons;
            $form .= "</form>";

            $voteBoxBody = $scoreVotes;
            $voteBoxBody .= $form;

            if ($this->issue['member_id'] == $this->user) {
                $voteBoxClass = "<div class='votebox-author pull-right'>";
                $creatorButtons = 
                "
                    <div class='btn-group author-btn-group'>
                        <button type='button' class='btn btn-sm btn-warning dropdown-toggle' data-toggle='dropdown'>
                            <span class='glyphicon glyphicon-cog'></span>
                        </button>
                        <ul class='dropdown-menu edit-menu' role='menu'>
                            <li><a href='editIssue.php?id=%1\$s'>Edit</a></li>
                            <li><a href='inc/api.php?ap=deleteIssue?id=%1\$s'>Delete</a></li>
                        </ul>
                    </div>
                ";
                $creatorButtons = sprintf($creatorButtons, $this->issue['issue_id']);
                $voteBoxBody .= $creatorButtons;
            }

            // construct full div
            $voteBox .= $voteBoxClass;
            $voteBox .= $voteBoxBody;
            $voteBox .= "</div>";

            return $voteBox;
        }

        public function displayPostbox() {
            $html = "";
            $input = 
            "
                <div class='form-group'>
                    <label for='inputTitle'>Title</label>
                    <input type='text' class='form-control' name='title' placeholder='Enter title'>
                </div> 
                <textarea class='input-block-level summernote' name='comment' rows='18'></textarea>
            ";

            /*
            $js =
            "
                <script type='text/javascript'>
                    $(document).ready(function() {
                        $('.summernote').summernote({
                            height: 250px
                        });
            
                    });

                    var postForm = function(me) {
                        var content = $(me).find('textarea[name=\"comment\"]').val($(me).find('.summernote').code());
                    }
                </script>
            ";
            */

            $html .= $input;
            //$html .= $js;

            return $html;
        }

        private function displayInput() {
            $html = "<div class='reply'>";
                
            $panelHeading = 
            "
                <div class='panel-heading'>
                    <h3 class='panel-title new-post' data-toggle='collapse' data-target='.message-body'>
                        Write a comment...
                    </h3>
                </div>
            ";

            $panelBody = 
            "
                <div class='panel-body message-body collapse in'>
                    <form id='postForm' action='inc/api.php?ap=newComment' method='POST' onsubmit='return postForm(this)' enctype='multipart/form-data'>
                        <input type='hidden' name='issueID' value='%1\$s'>
                        <input type='hidden' name='author' value='%2\$s'>
            ";
            $panelBody .= $this->displayPostbox();
            $panelBody .= 
            "
                        <button type='submit' class='btn btn-primary' style='margin-top:15px'>Post</button>
                    </form>
                </div>
            ";
            $panelBody = sprintf($panelBody, $this->issue['issue_id'], $this->user);

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

        public function displayAuthorDate($item) {
            // item can be comment or issue - both have member_id
            $author = $this->model->getUser($item['member_id']);

            $authorDate = 
            "
                <div class='author'>
                    Posted by %1\$s on <span id='create-date'>%2\$s</span>
                </div>
            ";
            $authorDate = sprintf($authorDate, $author['name'], $item['create_dt']);

            return $authorDate;
        }

        public function displayInfo() {
            $postInfo = "<div class='post-info pull-left'>";
                    
            $title = "<h3 class='panel-title post-title'>%1\$s</h3>";
            $title = sprintf($title, $this->issue['issue']);

            $authorDate = $this->displayAuthorDate($this->issue);
            $tags = $this->displayTags($this->issue);

            $postInfo .= $title;
            $postInfo .= $authorDate;
            $postInfo .= $tags;
            $postInfo .= "</div>";

            return $postInfo;
        }

        private function displayIssue() {
            $html = "";

            $issueHeader = $this->displayInfo();
            $issueHeader .= $this->displayVotebox();
            $html .= $this->createPanel($issueHeader, $this->issue['answer'], "panel-default");

            if ($this->issue['allow_comments']) {
                $html .= $this->displayInput();
            }

            return $html;
        }

        private function displayComments() {
            $html = "<div id='comments'>";
            $comments = $this->model->getIssueComments($this->issue['issue_id']);

            foreach ($comments as $c) {
                $comment = new Comment($c['comment_id'], $this->issue['issue_id']);
                $html .= $comment->displayComment();
            }

            // close comments
            $html .= "</div>";
            return $html;
        }

        public function render() {
            $this->html = $this->displayIssue();

            if ($this->issue['allow_comments']) {
                $this->html .= "<hr><h3>Comments</h3><hr>";
                $this->html .= $this->displayComments();
            } else {
                $this->html .= "<hr><div class='alert alert-warning text-center'>Comments have been disabled for this issue</div>";
            }

            echo $this->html;
        }
    }
?>
