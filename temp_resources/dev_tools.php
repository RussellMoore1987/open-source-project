<?php
    // Get the database connection and create the database object
    require_once("dev_tools_database.class.php");
    $Database = new Database(["servername"=>"127.0.0.1", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"]);

$successMessage = NULL;

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
    if (is_null($Database->errors_array)) {
        foreach($Database->errors_array as $error) {
            echo "<h3 style='color: red;'>" . $error . "</h3>";
        }
    } elseif(!is_null($successMessage)) {
        echo "<h3 style='color: green;'>" . $successMessage . "</h3>";
    } else {
        echo "<h3 style='color: green;'>No Errors Detected</h3>";
    }
    ?>
</div>

<h2>Please use the code listed below to make sure you have the coorect user in your Database</h2>
<h3 style="color: blue;">CREATE USER 'devteam'@''127.0.0.1' IDENTIFIED BY 'devPass1!';</h3>
<h3 style="color: blue;">GRANT ALL ON developmentdb.* TO 'devteam'@'127.0.0.1';</h3>
<br>
<h2>Please see the options below: </h2>
<br>
<h2>Posts Table</h2>
<a href="dev_tools.php?createtable=posts">Create Posts Table</a>
<a styel="color: red;" href="dev_tools.php?droptable=posts">Drop Posts Table</a>
<br>
<h2>Tags Table</h2>
<a href="dev_tools.php?createtable=tags">Create Tags Table</a>
<a href="dev_tools.php?droptable=tags">Drop Labels Table</a>
<br>
<h2>Labels Table</h2>
<a href="dev_tools.php?createtable=labels">Create Posts Table</a>
<a href="dev_tools.php?droptable=labels">Drop Posts Table</a>