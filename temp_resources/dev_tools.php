<?php
    // Get the database connection and create the database object
    require_once("dev_tools_database.class.php");
    $Database = new Database(["servername"=>"127.0.0.1", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"]);

$successMessage = false;

    // Check for any GET requests performed by clicking on the linkgs
    if (isset($_GET['createtable'])) {
        if ($_GET['createtable'] == 'all') {
            $successMessage = $Database->createAllTables();
        } elseif ($_GET['createtable'] == 'posts') {
            $successMessage = $Database->createPostsTable();
        } elseif ($_GET['createtable'] == 'tags') {
            $successMessage = $Database->createTagsTable();
        } elseif ($_GET['createtable'] == 'labels') {
            $successMessage = $Database->createLabelsTable();
        } elseif ($_GET['createtable'] == 'users') {
            $successMessage = $Database->createUsersTable();
        } elseif ($_GET['createtable'] == 'categories') {
            $successMessage = $Database->createCategoriesTable();
        } elseif ($_GET['createtable'] == 'media_content') {
            $successMessage = $Database->createMediaContentTable();
        } elseif ($_GET['createtable'] == 'comments') {
            $successMessage = $Database->createCommentsTable();
        } elseif ($_GET['createTable'] == 'todo') {
            $successMessage = $Database->createTodoTable();
        } elseif ($_GET['createTable'] == 'main_settings') {
            $successMessage = $Database->createMainSettingsTable();
        } elseif ($_GET['createTable'] == 'personal_settings') {
            $successMessage = $Database->createPersonalSettingsTable();
        } elseif ($_GET['createTable'] == 'style_settings') {
            $successMessage = $Database->createStyleSettingsTable();
        } elseif ($_GET['createTable'] == 'content') {
            $successMessage = $Database->createContentTable();
        } elseif ($_GET['createTable'] == 'bookmarks') {
            $successMessage = $Database->createBookmarksTable();
        } elseif ($_GET['createTable'] == 'permissions') {
            $successMessage = $Database->createPermissionsTable();
        }

    } elseif (isset($_GET['droptable'])) {
        if ($_GET['droptable'] == 'all') {
            $successMessage = $Database->dropTable('all');
        } else {
            $tableToDrop = $_GET['droptable'];
            $successMessage = $Database->dropTable($tableToDrop);
        }
    }

    // Get the database tables
    $tables_array = $Database->show_all_tables();
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
<br>
<div style="color: black; border: 1px solid black;">
    <h2>Current Tables in Database</h2>
    <?php
        if(!empty($tables_array)) {
            foreach($tables_array as $table) {
                echo "<h3>" . $table . "</h3>";
            }
        }
    ?>
</div>
<br>
<h2>Please use the code listed below to make sure you have the correct user in your Database</h2>
<h3 style="color: blue;">CREATE USER 'devteam'@''127.0.0.1' IDENTIFIED BY 'devPass1!';</h3>
<h3 style="color: blue;">GRANT ALL ON developmentdb.* TO 'devteam'@'127.0.0.1';</h3>
<br>
<h2>Please see the options below: </h2>
<br>
<a style ="color: darkgreen; padding: 10px; border: 1px solid darkgreen"href="dev_tools.php?createtable=all">CREATE ALL TABLES</a>
<a style="color: darkred; margin-lefT: 50px; padding: 10px; border: 1px solid darkred;" href="dev_tools.php?droptable=all">DROP ALL TABLES</a>
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
<h2>Users Table</h2>
<a href="dev_tools.php?createtable=users">Create Users Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=users">Drop Users Table</a>
<h2>Categories Table</h2>
<a href="dev_tools.php?createtable=categories">Create Categories Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=categories">Drop Categories Table</a>
<h2>Media Content Table</h2>
<a href="dev_tools.php?createtable=media_content">Create Media Content Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content">Drop Media Content Table</a>
<h2>Comments Table</h2>
<a href="dev_tools.php?createtable=comments">Create Comments Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=comments">Drop Comments Table</a>
<h2>Todo Table</h2>
<a href="dev_tools.php?createtable=todo">Create Todo Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=todo">Drop Todo Table</a>
<h2>Main Settings Table</h2>
<a href="dev_tools.php?createtable=main_settings">Create Main Settings Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=main_settings">Drop Main Settings Table</a>
<h2>Personal Settings Table</h2>
<a href="dev_tools.php?createtable=personal_settings">Create Personal Settings Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=personal_settings">Drop Personal Settings Table</a>
<h2>Style Settings Table</h2>
<a href="dev_tools.php?createtable=style_settings">Create Style Settings Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=style_settings">Drop Style Settings Table</a>
<h2>Content Table</h2>
<a href="dev_tools.php?createtable=content">Create Content Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=content">Drop Content Table</a>
<h2>Bookmarks Table</h2>
<a href="dev_tools.php?createtable=bookmarks">Create Bookmarks Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=bookmarks">Drop Bookmarks Table</a>
<h2>Permissions Table</h2>
<a href="dev_tools.php?createtable=permissions">Create Permissions Table</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=permissions">Drop Permissions Table</a>