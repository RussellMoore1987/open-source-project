<?php
    // Get the database connection and create the database object
    require_once("dev_tools_database.class.php");
    $Database = new Database(["servername"=>"127.0.0.1", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"]);

$successMessage = false;

    // Check for any GET requests performed by clicking on the links
    if (isset($_GET['createtable'])) {
        if ($_GET['createtable'] == 'all') {
            $successMessage = $Database->create_all_tables();

        // -------- Base Table Creation --------
        } elseif ($_GET['createtable'] == 'posts') {
            $successMessage = $Database->create_posts_table();
        } elseif ($_GET['createtable'] == 'tags') {
            $successMessage = $Database->create_tags_table();
        } elseif ($_GET['createtable'] == 'labels') {
            $successMessage = $Database->create_labels_table();
        } elseif ($_GET['createtable'] == 'users') {
            $successMessage = $Database->create_users_table();
        } elseif ($_GET['createtable'] == 'categories') {
            $successMessage = $Database->create_categories_table();
        } elseif ($_GET['createtable'] == 'media_content') {
            $successMessage = $Database->create_media_content_table();
        } elseif ($_GET['createtable'] == 'comments') {
            $successMessage = $Database->create_comments_table();
        } elseif ($_GET['createtable'] == 'todo') {
            $successMessage = $Database->create_todo_table();
        } elseif ($_GET['createtable'] == 'main_settings') {
            $successMessage = $Database->create_main_settings_table();
        } elseif ($_GET['createtable'] == 'personal_settings') {
            $successMessage = $Database->create_personal_settings_table();
        } elseif ($_GET['createtable'] == 'style_settings') {
            $successMessage = $Database->create_style_settings_table();
        } elseif ($_GET['createtable'] == 'content') {
            $successMessage = $Database->create_content_table();
        } elseif ($_GET['createtable'] == 'bookmarks') {
            $successMessage = $Database->create_bookmarks_table();
        } elseif ($_GET['createtable'] == 'permissions') {
            $successMessage = $Database->create_permissions_table();

        // ------ Lookup Table Creation ---------
        } elseif ($_GET['createtable'] == 'posts_to_media_content') {
            $successMessage = $Database->create_posts_to_media_content_table();
        } elseif ($_GET['createtable'] == 'posts_to_tags') {
            $successMessage = $Database->create_posts_to_tags_table();
        } elseif ($_GET['createtable'] == 'posts_to_labels') {
            $successMessage = $Database->create_posts_to_labels_table();
        } elseif ($_GET['createtable'] == 'posts_to_categories') {
            $successMessage = $Database->create_posts_to_categories_table();
        } elseif ($_GET['createtable'] == 'media_content_to_tags') {
            $successMessage = $Database->create_media_content_to_tags_table();
        } elseif ($_GET['createtable'] == 'media_content_to_categories') {
            $successMessage = $Database->create_media_content_to_categories_table();
        } elseif ($_GET['createtable'] == 'media_content_to_labels') {
            $successMessage = $Database->create_media_content_to_labels_table();
        } elseif ($_GET['createtable'] == 'content_to_tags') {
            $successMessage = $Database->create_content_to_tags_table();
        } elseif ($_GET['createtable'] == 'content_to_labels') {
            $successMessage = $Database->create_content_to_labels_table();
        } elseif ($_GET['createtable'] == 'content_to_categories') {
            $successMessage = $Database->create_content_to_categories_table();
        } elseif ($_GET['createtable'] == 'user_to_permissions') {
            $successMessage = $Database->create_user_to_permissions_table();
        }
        
        // Inserting into Tables
    } elseif (isset($_GET['inserttable'])) {
        if ($_GET['inserttable'] == 'all') {
           // TODO: Add the query to insert into all tables
           $successMessage = $Database->insert_into_all_tables();

           // ------- Base Table Inserts --------------
        } elseif ($_GET['inserttable'] == 'posts') {
            $successMessage = $Database->insert_into_posts();
        } elseif ($_GET['inserttable'] == 'tags') {
            $successMessage = $Database->insert_into_labels_or_tags('tags');
        } elseif ($_GET['inserttable'] == 'labels') {
            $successMessage = $Database->insert_into_labels_or_tags('labels');
        } elseif ($_GET['inserttable'] == 'categories') {
            $successMessage = $Database->insert_into_categories();
        } elseif ($_GET['inserttable'] == 'media_content') {
            $successMessage = $Database->insert_into_media_content();
        } elseif ($_GET['inserttable'] == 'comments') {
            $successMessage = $Database->insert_into_comments();
        } elseif ($_GET['inserttable'] == 'content') {
            $successMessage = $Database->insert_into_content();
        } elseif ($_GET['inserttable'] == 'bookmarks') {
            $successMessage = $Database->insert_into_bookmarks();
        } elseif ($_GET['inserttable'] == 'users') {
            $successMessage = $Database->insert_into_users();
        } elseif ($_GET['inserttable'] == 'permissions') {
            $successMessage = $Database->insert_into_permissions();

        // ---------- Lookup Table Inserts -----------
        // Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
        } elseif ($_GET['inserttable'] == 'posts_to_media_content') {
            $successMessage = $Database->insert_into_lookup_table([
                'tablename' => $_GET['inserttable'],
                'field1' => 'postId',
                'field2' => 'mediaContentId',
                'table1_ids' => $Database->get_table_ids('posts'),
                'table2_ids' => $Database->get_table_ids('media_content'),
                'connections' => 3,
                'relationships' => 4
            ]);
        }

        // Dropping Tables
    } elseif (isset($_GET['droptable'])) {
        if ($_GET['droptable'] == 'all') {
            $successMessage = $Database->drop_table('all');
        } else {
            $tableToDrop = $_GET['droptable'];
            $successMessage = $Database->drop_table($tableToDrop);
        }

        // Truncating Tables
    } elseif (isset($_GET['truncatetable'])) {
        if ($_GET['truncatetable'] == 'all') {
            $successMessage = $Database->truncate_table('all');
        } else {
            $tableTruncate = $_GET['truncatetable'];
            $successMessage = $Database->truncate_table($tableTruncate);
        }

        // Selecting from Tables
    } elseif (isset($_GET['selecttable'])) {
        $tableSelect = $_GET['selecttable'];
        $successMessage = $Database->select_from_table($tableSelect);
    }

    // Get the latest selecion data
    $select_array = $Database->latest_selection_array;

    // Get the database tables
    $tables_array = $Database->show_all_tables();
?>

<div class="update-notes">
<br>
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
<div style="border: 1px solid black;">
    <h2>Errors / Messages </h2>
    <?php
    if (!empty($Database->errors_array)) {
        foreach($Database->errors_array as $error) {
            echo "<h3 style='color: red;'>" . $error . "</h3>";
        }
    } elseif ($successMessage != false) {
        if (is_array($successMessage)) {
            foreach($successMessage as $message) {
                echo "<h3 style='color: green;'>" . $message . "</h3>";
            }
        } else {
            echo "<h3 style='color: green;'>" . $successMessage . "</h3>";
        }
    } else {
        echo "<h3 style='color: green;'>No Errors Detected</h3>";
    }
    ?>
</div>
<br>
<div style="color: black; border: 1px solid black; position: relative">
    <h2>Current Tables in Database</h2>
    <?php
        if(!empty($tables_array)) {
            foreach($tables_array as $table) {
                echo "<a style='display: block; font-size: 20px; margin-bottom: 5px; text-decoration: none;' href='dev_tools.php?selecttable=" . $table .  "'>" . $table . "</a>";
            }
        }
    ?>
    <div style="position: absolute; right: 0%; top: 0%; background-color: lightgrey; width: 75%; max-height: 100%; overflow-y: scroll;">
        <h2 style="text-align: center; vertical-align: top; margin-bottom: 5px; ">Sample Data:</h2>
        <h4 style="text-align: center; vertical-align: top; margin-top: 0px;">(Click on a table to see a sample of the data contained)</h4>
        <?php
            // Show the table of sample data
            if(!empty($select_array)) {
                echo "<h3 style='text-align: center;'>" . $tableSelect . " Table</h3>";
                echo "<table border='1'>";

                // fr Just to control if we get the keys as the table headers
                $fr = TRUE;
                for ($i = 0; $i < sizeof($select_array); $i++) {
                    // On the first iteration get the table headers
                    if ($fr === TRUE) {
                        echo "<tr>";
                        $keys = array_keys($select_array[$i]);
                        foreach($keys as $key) {
                            echo "<th style='text-align: center;'>" . $key . "</th>";
                        }
                        $fr = FALSE;
                        echo "</tr>";
                    } 

                    // On all other iterations just fill the table with data
                    echo "<tr>";
                    foreach ($select_array[$i] as $record) {
                        echo "<td style='text-align: center;'>" . $record . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>
    </div>
</div>
<br>
<h2 style="width: 30%;">Please use the code listed below to make sure you have the correct user in your Database</h2>
<h3 style="color: blue;">CREATE USER 'devteam'@''127.0.0.1' IDENTIFIED BY 'devPass1!';</h3>
<h3 style="color: blue;">GRANT ALL ON developmentdb.* TO 'devteam'@'127.0.0.1';</h3>
<br>
<h2>Please see the options below: </h2>
<br>
<a style ="color: darkgreen; padding: 10px; border: 1px solid darkgreen"href="dev_tools.php?createtable=all">CREATE ALL TABLES</a>
<a style="color: darkred; margin-lefT: 50px; padding: 10px; border: 1px solid darkred;" href="dev_tools.php?droptable=all">DROP ALL TABLES</a>
<a style="color: darkgreen; margin-lefT: 50px; padding: 10px; border: 1px solid darkgreen;" href="dev_tools.php?inserttable=all">INSERT INTO ALL TABLES</a>
<a style="color: darkred; margin-lefT: 50px; padding: 10px; border: 1px solid darkred;" href="dev_tools.php?truncatetable=all">TRUNCATE ALL TABLES</a>
<br>
<h2>-------- Base Tables --------</h2>
<br>
<h2>Posts Table</h2>
<a href="dev_tools.php?createtable=posts">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=posts">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=posts">Truncate</a>
<br>
<h2>Tags Table</h2>
<a href="dev_tools.php?createtable=tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=tags">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=tags">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=tags">Truncate</a>
<br>
<h2>Labels Table</h2>
<a href="dev_tools.php?createtable=labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=labels">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=labels">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=labels">Truncate</a>
<h2>Users Table</h2>
<a href="dev_tools.php?createtable=users">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=users">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=users">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=users">Truncate</a>
<h2>Categories Table</h2>
<a href="dev_tools.php?createtable=categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=categories">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=categories">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=categories">Truncate</a>
<h2>Media Content Table</h2>
<a href="dev_tools.php?createtable=media_content">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=media_content">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=media_content">Truncate</a>
<h2>Comments Table</h2>
<a href="dev_tools.php?createtable=comments">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=comments">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=comments">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=comments">Truncate</a>
<h2>Todo Table</h2>
<a href="dev_tools.php?createtable=todo">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=todo">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=todo">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=todo">Truncate</a>
<h2>Main Settings Table</h2>
<a href="dev_tools.php?createtable=main_settings">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=main_settings">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=main_settings">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=main_settings">Truncate</a>
<h2>Personal Settings Table</h2>
<a href="dev_tools.php?createtable=personal_settings">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=personal_settings">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=personal_settings">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=personal_settings">Truncate</a>
<h2>Style Settings Table</h2>
<a href="dev_tools.php?createtable=style_settings">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=style_settings">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=style_settings">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=style_settings">Truncate</a>
<h2>Content Table</h2>
<a href="dev_tools.php?createtable=content">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=content">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=content">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=content">Truncate</a>
<h2>Bookmarks Table</h2>
<a href="dev_tools.php?createtable=bookmarks">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=bookmarks">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=bookmarks">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=bookmarks">Truncate</a>
<h2>Permissions Table</h2>
<a href="dev_tools.php?createtable=permissions">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=permissions">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=permissions">Insert</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?truncatetable=permissions">Truncate</a>
<br>
<hr>
<br>
<h2>-------- Lookup Tables --------</h2>
<h2>Posts To Media Content Table</h2>
<a href="dev_tools.php?createtable=posts_to_media_content">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_media_content">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=posts_to_media_content">Insert</a>
<h2>Posts To Tags Table</h2>
<a href="dev_tools.php?createtable=posts_to_tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_tags">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=posts_to_tags">Insert</a>
<h2>Posts To Labels Table</h2>
<a href="dev_tools.php?createtable=posts_to_labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_labels">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=posts_to_labels">Insert</a>
<h2>Posts To Categories Table</h2>
<a href="dev_tools.php?createtable=posts_to_categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=posts_to_categories">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=posts_to_categories">Insert</a>
<h2>Media Content To Tags Table</h2>
<a href="dev_tools.php?createtable=media_content_to_tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_tags">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=media_content_to_tags">Insert</a>
<h2>Media Content To Categories Table</h2>
<a href="dev_tools.php?createtable=media_content_to_categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_categories">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=media_content_to_categories">Insert</a>
<h2>Media Content To Labels Table</h2>
<a href="dev_tools.php?createtable=media_content_to_labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_labels">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=media_content_to_labels">Insert</a>
<h2>Content To Tags Table</h2>
<a href="dev_tools.php?createtable=content_to_tags">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=content_to_tags">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=content_to_tags">Insert</a>
<h2>Content To Labels Table</h2>
<a href="dev_tools.php?createtable=content_to_labels">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_labels">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=media_content_to_labels">Insert</a>
<h2>Content To Categories Table</h2>
<a href="dev_tools.php?createtable=content_to_categories">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=media_content_to_categories">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=media_content_to_categories">Insert</a>
<h2>User To Permissions Table</h2>
<a href="dev_tools.php?createtable=user_to_permissions">Create</a>
<a style="color: darkred; padding-left: 50px;" href="dev_tools.php?droptable=user_to_permissions">Drop</a>
<a style="color: darkgreen; padding-left: 50px;" href="dev_tools.php?inserttable=user_to_permissions">Insert</a>



