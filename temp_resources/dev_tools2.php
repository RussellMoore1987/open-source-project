<?php
    // details for the page
    echo "
        <div>
            <h3>Open Source Project</h3>
            <h3>Development Tools</h3>
            <h3>Maintained by John Peterson</h3>
        </div>
        <h2>Please use the code listed below to make sure you have the correct user and Database</h2>
        <h3>CREATE DATABASE developmentdb;</h3>
        <h3>CREATE USER 'devteam'@'127.0.0.1' IDENTIFIED BY 'devPass1!';</h3>
        <h3>GRANT ALL ON developmentdb.* TO 'devteam'@'127.0.0.1';</h3>
    ";
    // Get the database connection and create the database object
    require_once("dev_tools_database2.class.php");
    $Database = new Database(["servername"=>"127.0.0.1", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"]);

    $successMessage = false;

    // Check for any GET requests performed by clicking on the links
    if (isset($_GET['createtable']) && strlen(trim($_GET['createtable'])) > 0) {
        if ($_GET['createtable'] == 'all') {
            $successMessage = $Database->create_all_tables();
        } 

    // Inserting into Tables
    } elseif (isset($_GET['inserttable'])) {
        if ($_GET['inserttable'] == 'all') {
           $successMessage = $Database->insert_into_all_tables();
        } 

    // Dropping Tables
    } elseif (isset($_GET['droptable'])) {
        if ($_GET['droptable'] == 'all') {
            $successMessage = $Database->drop_tables('all');
        }
    
    // Selecting from Tables
    } elseif (isset($_GET['selecttable'])) {
        $tableSelect = $_GET['selecttable'];
        $successMessage = $Database->select_from_table($tableSelect);
    }

    // all actions actions=all = drop create insert
    if (isset($_GET['actions']) && $_GET['actions'] == "all") {
        // first query
        $successMessage = $Database->drop_tables('all');
        // if first query in successful move on to the next
        if ($successMessage) {
            // second query
            $successMessage = $Database->create_all_tables();
            // if the second query is successful move on to the third
            if ($successMessage) {
                // third query
                $successMessage = $Database->insert_into_all_tables();
            }
        }
    } elseif (isset($_GET['actions']) && $_GET['actions'] == "addMainTestUser") {
        $successMessage = $Database->add_main_test_user();
    }

    // Get the latest selecion data
    $select_array = $Database->latest_selection_array;

    // Get the database tables
    $tables_array = $Database->show_all_tables();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <br>
    <!-- showing air and success messages here -->
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
        <h2>Current Tables in Database and Options</h2>
        <?php
            if(!empty($tables_array)) {
                foreach($tables_array as $table) {
                    echo "<div>";
                        echo "<a style='font-size: 20px; margin-bottom: 5px; text-decoration: none;' href='dev_tools2.php?selecttable=" . $table .  "'>" . $table . "</a>";
                    echo "</div>";  
                }
            }
        ?>
        <!-- showing table data if accessed -->
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
                            echo "<td style='text-align: center;'>" . htmlspecialchars($record) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
    </div>
    <br>
    <h2>Please see the options below: </h2>
    <br>
    <a style ="color: darkgreen; padding: 10px; border: 1px solid darkgreen"href="dev_tools2.php?createtable=all">CREATE ALL TABLES</a>
    <a style="color: darkred; margin-lefT: 50px; padding: 10px; border: 1px solid darkred;" href="dev_tools2.php?droptable=all">DROP ALL TABLES</a>
    <a style="color: darkgreen; margin-lefT: 50px; padding: 10px; border: 1px solid darkgreen;" href="dev_tools2.php?inserttable=all">INSERT INTO ALL TABLES</a>
    <a style="color: darkgreen; margin-lefT: 50px; padding: 10px; border: 1px solid darkgreen;" href="dev_tools2.php?actions=all">ALL ACTIONS, DROP CREATE INSERT</a>
    <a style="color: darkgreen; margin-lefT: 50px; padding: 10px; border: 1px solid darkgreen;" href="dev_tools2.php?actions=addMainTestUser">ADD MAIN TEST USER</a>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    
</body>
</html>