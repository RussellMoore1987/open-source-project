<?php
    // Get the database connection and create the database object
    require_once("dev_tools_database.class.php");
    $Database = new Database(["servername"=>"127.0.0.1", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"]);

$successMessage = false;

    // Check for any GET requests performed by clicking on the linkgs
    if(isset($_GET['createtable'])) {
        if ($_GET['createtable'] == 'posts') {
            $successMessage = $Database->createPostsTable();
        }
    } elseif(isset($_GET['droptable'])) {
        $tableToDrop = $_GET['droptable'];
        $successMessage = $Database->dropTable($tableToDrop);
    }
?>

<div class="update-notes">
    <ul>
        <li></li>
    </ul>
</div>
<br>
<hr>
<br>

<div class="info">
    <h3>Open Source Project</h3>
    <h3>Development Tools</h3>
    <h3>Maintained by John Peterson</h3>
</div>
<br>
<div style="color: red; border: 1px solid black;">
    <h2>Errors / Messages </h2>
    <?php
    if (!empty($Database->errors_array)) {
        foreach($Database->errors_array as $error) {
            echo "<h3 style='color: red;'>" . $error . "</h3>";
        }
    } elseif($successMessage != false) {
        echo "<h3 style='color: green;'>" . $successMessage . "</h3>";
    } else {
        echo "<h3 style='color: green;'>No Errors Detected</h3>";
    }
    ?>
</div>

<h2>Please use the code listed below to make sure you have the corect user in your Database</h2>
<h3 style="color: blue;">CREATE USER 'devteam'@''127.0.0.1' IDENTIFIED BY 'devPass1!';</h3>
<h3 style="color: blue;">GRANT ALL ON developmentdb.* TO 'devteam'@'127.0.0.1';</h3>
<br>
<h2>Please see the options below: </h2>
<br>
<h2>Posts Table</h2>
<a href="dev_tools.php?createtable=posts">Create Posts Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts">Drop Posts Table</a>
<br>
<h2>Tags Table</h2>
<a href="dev_tools.php?createtable=tags">Create Tags Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=tags">Drop Tags Table</a>
<br>
<h2>Labels Table</h2>
<a href="dev_tools.php?createtable=labels">Create Labels Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=labels">Drop Labels Table</a>