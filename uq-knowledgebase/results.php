<?php
    // Connect to database
    include("inc/api.php");
    //print_r($_POST);

    if (isset($_POST['term']) && isset($_POST['options'])) {
        $term = $_POST['term'];
        $filter = $_POST['options'];
        $results = $model->search($term, $filter);
    }
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
                <!-- Issues -->
                <div class="col-md-12">
                    <h3 class="text-center"><a href="topics.php">UQ Knowledgebase</a></h3>
                    <?php include("search.php"); ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Results</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Issue</th>
                                        <th>Tags</th>
                                        <th>Author</th>
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Revision</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($results as $result) : ?>
                                    <tr>
                                        <td class="col-md-4"><a href="issue.php?id=<?php echo $result['issue_id']; ?>"><?php echo $result['issue']; ?></a></td>
                                        <td class="col-md-2"><?php echo $result['tags']; ?></td>
                                        <td><?php echo $result['name']; ?></td>
                                        <td><?php echo $result['create_dt']; ?></td>
                                        <td><?php echo $result['update_dt']; ?></td>
                                        <td><?php echo $result['revision']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </body>
</html>
