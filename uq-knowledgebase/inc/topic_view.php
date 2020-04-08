<?php
    class TopicView extends ViewModel {
        private $html;

        private function displayScoreVotes($issue) {
            $scoreVotes =
            "
                <td class='score-column'>
                    <span class='votes'>%1\$s</span>
                </td>
                <td class='votes-column'>
                    <span class='updowns'>
                        <span>
                        (
                            <span class='upvotes'>%2\$s</span>
                        |
                            <span class='downvotes'>%3\$s</span>
                        )
                        </span>
                    </span>
                </td>
            ";

            $scoreVotes = sprintf($scoreVotes, $this->model->getScore($issue['issue_id'], "issue"), 
                $issue['upvotes'], $issue['downvotes']);

            return $scoreVotes;
        }

        private function displayTitleTags($issue) {
            $titleTags = 
            "
                <td class='title-tags'>
                    <a class='title' href='issue.php?id=%1\$s'>
                        %2\$s
                    </a>
            ";
            $titleTags = sprintf($titleTags, $issue['issue_id'], $issue['issue']);

            if ($issue['tags']) {
                $tags = $this->displayTags($issue);
                $titleTags .= $tags;
            }

            return $titleTags;
        }

        public function displayVotebuttons($issue) {
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

            if ($this->model->checkVoted($this->user, $issue['issue_id'], "issue")) {
                if ($this->model->getVote($this->user, $issue['issue_id'], "issue") == "up") {
                    // issue has been voted up already
                    $voteButtons = $votedUp;
                    $voteButtons .= $voteDown;
                } else if ($this->model->getVote($this->user, $issue['issue_id'], "issue") == "down") {
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

        public function displayVotebox($issue) {
            $voteBox = "<div class='votebox pull-right'>";

            $form = "<form class='vote-form' method='POST' action='inc/api.php?ap=vote'>";

            $hiddenInputs = 
            "
                <input type='hidden' name='issueID' value='%1\$s'>
                <input type='hidden' name='referrer' value='issue'>
                <input type='hidden' name='voteType' value='issue'>
            ";
            $hiddenInputs = sprintf($hiddenInputs, $issue['issue_id']);

            $voteButtons = $this->displayVotebuttons($issue);

            $form .= $hiddenInputs;
            $form .= $voteButtons;
            $form .= "</form>";

            $voteBox .= $form;

            if ($issue['member_id'] == $this->user) {
                $creatorButtons = 
                "
                    <a href='editIssue.php?id=%1\$s'>
                        <button type='button' class='btn btn-sm btn-warning'>Edit</button>
                    </a>
                    <a href='inc/api.php?ap=deleteIssue?id=%1\$s'>
                        <button type='button' class='btn btn-sm btn-danger'>Delete</button>
                    </a>
                ";

                $creatorButtons = 
                "
                    <div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-warning dropdown-toggle' data-toggle='dropdown'>
                            <span class='glyphicon glyphicon-cog'></span>
                        </button>
                        <ul class='dropdown-menu edit-menu' role='menu'>
                            <li><a href='editIssue.php?id=%1\$s'>Edit</a></li>
                            <li><a href='inc/api.php?ap=deleteIssue?id=%1\$s'>Delete</a></li>
                        </ul>
                    </div>
                ";
                $creatorButtons = sprintf($creatorButtons, $issue['issue_id']);
                $voteBox .= $creatorButtons;
            }

            $voteBox .= "</div>";
            return $voteBox;
        }

        private function displayVoting($issue) {
            $voting = "<td class='vote-column'>";

            $votebox = $this->displayVotebox($issue);
            $voting .= $votebox;

            $voting .= "</td>";
            return $voting;
        }

        private function displayIssuesTable($issues) {
            $html = "";

            foreach ($issues as $issue) {
                $html .= "<tr>";

                $scoreVotes = $this->displayScoreVotes($issue);
                $html .= $scoreVotes;

                $titleTags = $this->displayTitleTags($issue);
                $html .= $titleTags;

                $voting = $this->displayVoting($issue);
                $html .= $voting;

                $html .= "</tr>";
            }

            return $html;
        }

        private function listTopics() {
            $topics = $this->model->getAllTopics();

            $html = "<div class='panel-group'>";
            foreach ($topics as $topic) {
                $issues = $this->model->getAllIssues($topic['topic_id']);

                $panelHeading = $topic['name'];

                if ($issues) {
                    $panelBody =
                    "
                        <table class='issue-table table table-hover'> 
                            <tbody>
                    ";

                    $panelBody .= $this->displayIssuesTable($issues);
                    $panelBody .=
                    "
                            </tbody> 
                        </table>
                    ";
                } else {
                    $panelBody = "<p>There are currently no issues for this topic.</p>";
                }

                $panelFooter = 
                "           
                    <a href='newIssue.php?id=%1\$s'>
                        <button type='button' class='btn btn-xs btn-success'>
                            New Issue
                        </button>
                    </a>
                ";
                $panelFooter = sprintf($panelFooter, $topic['topic_id']);

                $html .= $this->createCollapsiblePanel($topic['topic_id'], "panel-default", 
                    $panelHeading, $panelBody, $panelFooter);
            }

            $html .= "</div>";
            return $html;
        }

        public function render() {
            $this->html = $this->listTopics();
            echo $this->html;
        }
    }
?>
