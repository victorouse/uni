<?php
    // see if we're on a chosen screen and grab it 
    if ($_GET['step']) {
        $current = $_GET['step'];
    } else {
        // start from the start
        $current = 0;
    }

    // open screenshot directory and load screens into array
    $dh = opendir('screens');
    $files = array();

    if ($dh) {
        while (false !== ($entry = readdir($dh))) {
            if ($entry != "." && $entry != "..") {
                $files[] = $entry;
            }
        }
        closedir($dh);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <script src="scripts/jquery.js"></script>
    <link rel="stylesheet" href="css/stepViewer.css" type="text/css"/>
    <title>Viewer</title>
</head>
<body>
    <div id="container">
        <div id="content">
            <div class="title">Preview</div>
            <div id="current">
                <?php
                    $current_img = $files[$current];
                    $current_file = substr($current_img, 0, strrpos($current_img, '.')); // get rid of extension
                    echo "<a href='harview.php?file={$current_file}' file='{$current_file}'> 
                        <img src='screens/{$current_img}'></a>";
                ?>
            </div>

            <div id="steps">
                <?php
                    for ($i = 0; $i < sizeof($files); $i++) {
                        if ($files[$i] == $files[$current]) {
                            echo "<a href='stepviewer.php?step={$i}'><img src='screens/{$files[$i]}' class='selected'></a>";
                        } else {
                            echo "<a href='stepviewer.php?step={$i}'><img src='screens/{$files[$i]}'></a>";                
                        }
                    }
                ?>
            </div>
        </div>
        <div id="tests">
            <script src="scripts/testloader.js"></script> 
            <div id="testsuite">
            <div class="title">Test Steps</div>
            </div>
        </div>
    </div>
</body>
</html>
