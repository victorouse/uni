<?php
    // Debug
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    class DataModel {
        private $db;

        public function __construct() {
            $db_user = "root";
            $db_pass = "pass";

            try {
                $this->db = new PDO("mysql:host=localhost;dbname=uqkb;charset=utf8", 
                    $db_user, $db_pass, array(
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) {
                echo "Error connecting to database " . $e->getMessage() . "<br />";
            }
        }

        public function addTopic($topicTitle) {
            $query = "INSERT INTO topics VALUES ('', ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($topicTitle));
        }

        public function removeTopic($topicID) {
            $query = "DELETE FROM topics WHERE topic_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($topicID));
        }

        public function getAllTopics() {
            $query = "SELECT * FROM topics";

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        public function getAllIssues($topicID) {
            $query = "SELECT *, (upvotes - downvotes) AS score FROM issues WHERE topic_id = ? ORDER BY score DESC"; 
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($topicID));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        public function getIssue($issueID) {
            $query = "SELECT * FROM issues WHERE issue_id = ?"; 
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($issueID));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }

        public function insertIssue($topicID, $issue, $answer, $tags, $member, $allowComments) {
            $query = "INSERT INTO issues (topic_id, issue_id, issue, 
                answer, tags, create_dt, revision, member_id, allow_comments)
                VALUES (?, '', ?, ?, ?, NOW(), '', ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($topicID, $issue, $answer, $tags, $member, $allowComments));
        }

        public function updateIssue($issueID, $issue, $answer, $tags, $allowComments) {
            $query = "UPDATE issues SET issue = ?, answer = ?, tags = ?,
                update_dt = NOW(), revision = revision + 1, allow_comments = ? WHERE issue_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($issue, $answer, $tags, $allowComments, $issueID));
        }

        public function deleteIssues($issues) {
            $query = "DELETE FROM issues WHERE issue_id = ?";
            $stmt = $this->db->prepare($query);

            foreach ($issues as $issue) {
                $stmt->execute(array($issue));
            }
        }

        public function addIssueComment($issueID, $title, $comment, $author) {
            $query = "INSERT INTO comments (title, comment, member_id, create_dt)
                VALUES (?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($title, $comment, $author));

            $commentID = $this->db->lastInsertId();
            $query = "INSERT INTO issue_comments VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($issueID, $commentID));
        }

        public function getIssueComments($issueID) {
            $query = "SELECT *, (upvotes - downvotes) AS votes FROM issue_comments INNER JOIN comments
                ON issue_comments.comment_id = comments.comment_id
                WHERE issue_id = ? ORDER BY votes DESC, create_dt DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($issueID));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        public function getComment($commentID) {
            $query = "SELECT * FROM comments WHERE comment_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($commentID));

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public function updateComment($commentID, $title, $comment) {
            $query = "UPDATE comments SET title = ?, comment = ?, update_dt = NOW() WHERE comment_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($title, $comment, $commentID));
        }

        public function deleteComment($commentID) {
            $query = "DELETE FROM comments WHERE comment_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($commentID));
        }

        public function addCommentReply($commentID, $title, $comment, $author) {
            $query = "INSERT INTO comments (title, comment, member_id, create_dt)
                VALUES (?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($title, $comment, $author));

            $replyID = $this->db->lastInsertId();
            $query = "INSERT INTO comment_replies VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($commentID, $replyID));
        }

        public function getReplies($commentID) {
            $query = "SELECT * FROM comments WHERE comments.comment_id IN 
                (SELECT reply_id FROM comments JOIN comment_replies 
                ON comments.comment_id = comment_replies.comment_id 
                WHERE comment_replies.comment_id = ?) ORDER BY create_dt DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($commentID));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        public function getScore($id, $type) {
            if ($type == "issue") {
                $query = "SELECT upvotes, downvotes FROM issues WHERE issue_id = ?";
            } else if ($type == "comment") {
                $query = "SELECT upvotes, downvotes FROM comments WHERE comment_id = ?";
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $score = $result['upvotes'] - $result['downvotes'];
            return $score;
        }

        public function checkVoted($memberID, $id, $type) {
            if ($type == "issue") {
                $query = "SELECT EXISTS (SELECT * FROM member_issue_votes WHERE member_id = ? AND issue_id = ?)";
            } else if ($type == "comment") {
                $query = "SELECT EXISTS (SELECT * FROM member_comment_votes WHERE member_id = ? AND comment_id = ?)";
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($memberID, $id));
            $result = $stmt->fetch(PDO::FETCH_NUM);

            return $result[0];
        }

        public function changeVote($memberID, $id, $action, $type) {
            if ($action == "up") {
                if ($type == "issue") {
                    $query = "UPDATE member_issue_votes SET voted_up = 1, voted_down = 0
                        WHERE member_id = ? AND issue_id = ?";
                } else if ($type == "comment") {
                    $query = "UPDATE member_comment_votes SET voted_up = 1, voted_down = 0
                        WHERE member_id = ? AND comment_id = ?";
                }
            } else {
                if ($type == "issue") {
                    $query = "UPDATE member_issue_votes SET voted_up = 0, voted_down = 1
                        WHERE member_id = ? AND issue_id = ?";
                } else if ($type == "comment") {
                    $query = "UPDATE member_comment_votes SET voted_up = 0, voted_down = 1
                        WHERE member_id = ? AND comment_id = ?";
                }
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($memberID, $id));
        }

        public function getVote($memberID, $id, $type) {
            if ($type == "issue") {
                $query = "SELECT * FROM member_issue_votes WHERE member_id = ? AND issue_id = ?";
            } else if ($type == "comment") {
                $query = "SELECT * FROM member_comment_votes WHERE member_id = ? AND comment_id = ?";
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($memberID, $id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['voted_up']) {
                return "up";
            }

            return "down";
        }

        public function addVote($memberID, $id, $action, $type) {
            if ($action == "up") {
                if ($type == "issue") {
                    $query = "INSERT INTO member_issue_votes VALUES (?, ?, 1, 0)";
                } else if ($type == "comment") {
                    $query = "INSERT INTO member_comment_votes VALUES (?, ?, 1, 0)";
                }
            } else {
                if ($type == "issue") {
                    $query = "INSERT INTO member_issue_votes VALUES (?, ?, 0, 1)";
                } else if ($type == "comment") {
                    $query = "INSERT INTO member_comment_votes VALUES (?, ?, 0, 1)";
                }
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($memberID, $id));
        }

        public function vote($id, $memberID, $action, $type) {
            // User has voted already (e.g. changing vote)
            if ($this->checkVoted($memberID, $id, $type)) { 
                // Find out what they voted
                $vote = $this->getVote($memberID, $id, $type);

                // Change vote
                if (($vote == "up") && ($action == "down")) {
                    // Decrease upvote -> increase downvote
                    if ($type == "issue") {
                        $query = "UPDATE issues SET upvotes = upvotes - 1, downvotes = downvotes + 1
                            WHERE issue_id = ?";
                    } else if ($type == "comment") {
                        $query = "UPDATE comments SET upvotes = upvotes - 1, downvotes = downvotes + 1
                            WHERE comment_id = ?";
                    }

                    // Change member voted values (T, F) => (F, T)
                    $this->changeVote($memberID, $id, $action, $type);
                } else if (($vote == "down") && ($action == "up")) {
                    // Decrease downvote -> increase upvote
                    if ($type == "issue") {
                        $query = "UPDATE issues SET upvotes = upvotes + 1, downvotes = downvotes - 1
                            WHERE issue_id = ?";
                    } else if ($type == "comment") {
                        $query = "UPDATE comments SET upvotes = upvotes + 1, downvotes = downvotes - 1
                            WHERE comment_id = ?";
                    }

                    // Change member voted values (F, T) => (T, F)
                    $this->changeVote($memberID, $id, $action, $type);
                } else {
                    // Can't vote again with same value
                    // e.g. action(up) + vote(up)
                    return false;
                }
            }  else {
                // New vote
                if ($action == "up") {
                    // Increase upvote
                    if ($type == "issue") {
                        $query = "UPDATE issues SET upvotes = upvotes + 1 
                            WHERE issue_id = ?";
                    } else if ($type == "comment") {
                        $query = "UPDATE comments SET upvotes = upvotes + 1 
                            WHERE comment_id = ?";
                    }

                    // Insert member vote (T, F)
                    $this->addVote($memberID, $id, $action, $type);
                } else if ($action == "down") {
                    // Increase downvote
                    if ($type == "issue") {
                        $query = "UPDATE issues SET downvotes = downvotes + 1 
                            WHERE issue_id = ?";
                    } else if ($type == "comment") {
                        $query = "UPDATE comments SET downvotes = downvotes + 1 
                            WHERE comment_id = ?";
                    }

                    // Insert member vote (F, T)
                    $this->addVote($memberID, $id, $action, $type);
                }
            }

            // After everything is said and done, increase/decrease vote
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));

            // Finally return the score to display on page
            $score = $this->getScore($id, $type);
            return $score;
        }

        public function search($term, $filter) {
            // For LIKE clause
            $term = "%" . $term . "%";

            switch ($filter) {
                case "title":
                    $query = "SELECT * FROM issues 
                        INNER JOIN member ON issues.member_id = member.member_id 
                        WHERE issue LIKE ?"; 
                    break;
                case "tag":
                    $query = "SELECT * FROM issues 
                        INNER JOIN member ON issues.member_id = member.member_id 
                        WHERE tags LIKE ?"; 
                    break;
                case "author":
                    $query = "SELECT * FROM issues 
                        INNER JOIN member ON issues.member_id = member.member_id 
                        WHERE member.name LIKE ?";
                    break;
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute(array($term));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        public function getUser($member_id) {
            $query = "SELECT * FROM member WHERE member_id = ?"; 
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($member_id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
    }

    // Instantiate database
    $model = new DataModel();

    // Delegate if called
    if (isset($_REQUEST['ap'])) {
        $args = parse_url($_REQUEST['ap']);

        $type = $args['path'];
        $param = array('value' => end(explode('=', $args['query'])));
        
        switch ($type) {
            case "newIssue":
                $topicID = $_REQUEST['topicID'];
                $issue = $_REQUEST['issue'];
                $answer = $_REQUEST['answer'];
                $tags = $_REQUEST['tags'];
                $author = $_REQUEST['member'];
                $allowComments = ($_REQUEST['commentsFlag'] == "on");

                $model->insertIssue($topicID, $issue, $answer, $tags, $author, $allowComments);
                header("Location: ../editTopics.php");
                break;
            
            case "newTopic":
                $topicTitle = $_REQUEST['topicTitle'];
                $model->addTopic($topicTitle);
                header("Location: ../editTopics.php");
                break;

            case "removeTopic":
                $topicID = $_REQUEST['topicID'];
                $model->removeTopic($topicID);
                header("Location: ../editTopics.php");

            case "deleteIssues":
                $issues = $_REQUEST['issue'];

                $model->deleteIssues($issues);
                header("Location: ../editTopics.php");
                break;

            case "editIssue":
                $issueID = $_REQUEST['issueID'];
                $issue = $_REQUEST['issue'];
                $answer = $_REQUEST['answer'];
                $tags = $_REQUEST['tags'];
                $allowComments = ($_REQUEST['commentsFlag'] == "on");

                $model->updateIssue($issueID, $issue, $answer, $tags, $allowComments);
                header("Location: ../editTopics.php");
                break;

            case "newComment":
                $issueID = $_REQUEST['issueID'];
                $author = $_REQUEST['author'];
                $title = $_REQUEST['title'];
                $comment = $_REQUEST['comment'];

                $model->addIssueComment($issueID, $title, $comment, $author);
                header("Location: ../issue.php?id=" . $issueID);
                break;

            case "editComment":
                $commentID = $_REQUEST['commentID'];
                $title = $_REQUEST['title'];
                $comment = $_REQUEST['comment'];

                $model->updateComment($commentID, $title, $comment);
                header("Location: ../topics.php");
                break;

            case "deleteComment":
                echo "you will be deleted";
                $commentID = $param['value'];
                $model->deleteComment($commentID);
                break;

            case "commentReply":
                $issueID = $_REQUEST['issueID'];
                $commentID = $_REQUEST['commentID'];
                $author = $_REQUEST['author'];
                $title = $_REQUEST['title'];
                $comment = $_REQUEST['comment'];

                $model->addCommentReply($commentID, $title, $comment, $author);
                header("Location: ../issue.php?id=" . $issueID);
                break;

            case "vote":
                //print_r($_POST);
                session_start();

                if (isset($_REQUEST['issueID'])) {
                    $id = $_REQUEST['issueID'];
                }

                if (isset($_REQUEST['commentID'])) {
                    $id = $_REQUEST['commentID'];
                }

                if (isset($_SESSION['user'])) {
                    $memberID = $_SESSION['user'];
                } else {
                    break;
                }

                $type = $_REQUEST['voteType'];

                if (isset($_REQUEST['vote-up'])) {
                    $action = "up";
                } else if (isset($_REQUEST['vote-down'])) {
                    $action = "down";
                }

                $model->vote($id, $memberID, $action, $type);

                if (isset($_REQUEST['referrer'])) {
                    $referrer = $_REQUEST['referrer'];

                    if ($referrer == "topics") {
                        header("Location: ../topics.php");
                    } else if ($referrer == "issue") {
                        if (isset($_REQUEST['issueID'])) {
                            $refID = $_REQUEST['issueID'];
                            header("Location: ../issue.php?id=" . urlencode($refID));
                        }
                    }
                }

                break;

            default:
                echo "i'm sorry dave, i can't let you do that...";
                break;
        }
    }
?>
