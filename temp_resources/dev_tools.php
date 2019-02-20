<?php
    // Get the database connection and create the database object
    require_once("dev_tools_database.class.php");
    $Database = new Database(["servername"=>"127.0.0.1", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"]);

$successMessage = false;

    // Check for any GET requests performed by clicking on the linkgs
    if (isset($_GET['createtable'])) {
        if ($_GET['createtable'] == 'all') {
            $successMessage = $Database->createAllTables();

        // -------- Base Tables --------
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
        } elseif ($_GET['createtable'] == 'todo') {
            $successMessage = $Database->createTodoTable();
        } elseif ($_GET['createtable'] == 'main_settings') {
            $successMessage = $Database->createMainSettingsTable();
        } elseif ($_GET['createtable'] == 'personal_settings') {
            $successMessage = $Database->createPersonalSettingsTable();
        } elseif ($_GET['createtable'] == 'style_settings') {
            $successMessage = $Database->createStyleSettingsTable();
        } elseif ($_GET['createtable'] == 'content') {
            $successMessage = $Database->createContentTable();
        } elseif ($_GET['createtable'] == 'bookmarks') {
            $successMessage = $Database->createBookmarksTable();
        } elseif ($_GET['createtable'] == 'permissions') {
            $successMessage = $Database->createPermissionsTable();

        // ------ Lookup Tables ---------
        } elseif ($_GET['createtable'] == 'posts_to_media_content') {
            $successMessage = $Database->createPostsToMediaContentTable();
        } elseif ($_GET['createtable'] == 'posts_to_tags') {
            $successMessage = $Database->createPostsToTagsTable();
        } elseif ($_GET['createtable'] == 'posts_to_labels') {
            $successMessage = $Database->createPoststoLabelsTable();
        } elseif ($_GET['createtable'] == 'posts_to_categories') {
            $successMessage = $Database->createPostsToCategoriesTable();
        } elseif ($_GET['createtable'] == 'media_content_to_tags') {
            $successMessage = $Database->createMediaContentToTagsTable();
        } elseif ($_GET['createtable'] == 'media_content_to_categories') {
            $successMessage = $Database->createMediaContenttoCategoriesTable();
        } elseif ($_GET['createtable'] == 'content_to_tags') {
            $successMessage = $Database->createContentToTagsTable();
        } elseif ($_GET['createtable'] == 'content_to_labels') {
            $successMessage = $Database->createContentToLabelsTable();
        } elseif ($_GET['createtable'] == 'content_to_categories') {
            $successMessage = $Database->createContentToCategoriesTable();
        } elseif ($_GET['createtable'] == 'user_to_permissions') {
            $successMessage = $Database->createUserToPermissionsTable();
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
        <li>2/19/2019 - Logic for all tables added to the database. Dropping/Creating Tables functional. Error/Success feedback in place.</li>
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
    } elseif ($successMessage != false) {
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
<h2>-------- Base Tables --------</h2>
<br>
<h2>Posts Table</h2>
<a href="dev_tools.php?createtable=posts">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts">Drop</a>
<br>
<h2>Tags Table</h2>
<a href="dev_tools.php?createtable=tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=tags">Drop</a>
<br>
<h2>Labels Table</h2>
<a href="dev_tools.php?createtable=labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=labels">Drop</a>
<h2>Users Table</h2>
<a href="dev_tools.php?createtable=users">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=users">Drop</a>
<h2>Categories Table</h2>
<a href="dev_tools.php?createtable=categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=categories">Drop</a>
<h2>Media Content Table</h2>
<a href="dev_tools.php?createtable=media_content">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content">Drop</a>
<h2>Comments Table</h2>
<a href="dev_tools.php?createtable=comments">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=comments">Drop</a>
<h2>Todo Table</h2>
<a href="dev_tools.php?createtable=todo">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=todo">Drop</a>
<h2>Main Settings Table</h2>
<a href="dev_tools.php?createtable=main_settings">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=main_settings">Drop</a>
<h2>Personal Settings Table</h2>
<a href="dev_tools.php?createtable=personal_settings">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=personal_settings">Drop</a>
<h2>Style Settings Table</h2>
<a href="dev_tools.php?createtable=style_settings">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=style_settings">Drop</a>
<h2>Content Table</h2>
<a href="dev_tools.php?createtable=content">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=content">Drop</a>
<h2>Bookmarks Table</h2>
<a href="dev_tools.php?createtable=bookmarks">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=bookmarks">Drop</a>
<h2>Permissions Table</h2>
<a href="dev_tools.php?createtable=permissions">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=permissions">Drop</a>
<br>
<hr>
<br>
<h2>-------- Lookup Tables --------</h2>
<h2>Posts To Media Content Table</h2>
<a href="dev_tools.php?createtable=posts_to_media_content">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_media_content">Drop</a>
<h2>Posts To Tags Table</h2>
<a href="dev_tools.php?createtable=posts_to_tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_tags">Drop</a>
<h2>Posts To Labels Table</h2>
<a href="dev_tools.php?createtable=posts_to_labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_labels">Drop</a>
<h2>Posts To Categories Table</h2>
<a href="dev_tools.php?createtable=posts_to_categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_categories">Drop</a>
<h2>Media Content To Tags Table</h2>
<a href="dev_tools.php?createtable=media_content_to_tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_tags">Drop</a>
<h2>Media Content To Categories Table</h2>
<a href="dev_tools.php?createtable=media_content_to_categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_categories">Drop</a>
<h2>Content To Tags Table</h2>
<a href="dev_tools.php?createtable=content_to_tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=content_to_tags">Drop</a>
<h2>Content To Labels Table</h2>
<a href="dev_tools.php?createtable=content_to_labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_labels">Drop</a>
<h2>Content To Categories Table</h2>
<a href="dev_tools.php?createtable=content_to_categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_categories">Drop</a>
<h2>User To Permissions Table</h2>
<a href="dev_tools.php?createtable=user_to_permissions">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=user_to_permissions">Drop</a>