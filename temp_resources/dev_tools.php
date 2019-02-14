<?php
    require_once("dev_tools_database.class.php");
    $Database = new Database(["servername"=>"localhost", "username"=>'devteam', "password"=>"devPass1!", "dbname"=>"developmentdb"])
?>

<div class="update-notes">
    <ul>
        <li></li>
    </ul>
</div>

<div class="info">
    <h3>Open Source Project</h3>
    <h3>Development Tools</h3>
    <h3>Maintained by John Peterson</h3>
</div>
<br>
<div style="color: red; border 1px solid black;">
    <h2>Errors: </h2>
    <?php foreach($error in )?>
</div>

<h2>Please use the code listed below to make sure you have the coorect user in your Database</h2>
<h2 style="color: blue;">CREATE USER 'devteam'@''127.0.0.1' IDENTIFIED BY 'devPass1!';</h2>
<h2 style="color: blue;">GRANT ALL ON developmentdb.* TO 'devteam'@'127.0.0.1';</h2>

<h2>Please see the options below: </h2>
<h2>Create Table</h2>