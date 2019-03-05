<?php 
    // creating a new database object 
    function db_connect() {
        $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        confirm_db_connect($connection);
        return $connection;
    }

    // confirming database connection
    function confirm_db_connect($connection) {
        if ($connection->connect_errno) {
            $msg = "Database connection failed: ";
            $msg .= $connection->connect_error;
            $msg .= " (" . $connection->connect_errno . ")";
            exit($msg);
        }    
    }

    // disconnect or close MySQL connection
    function db_disconnect($connection) {
        if (isset($connection)) {
            $connection->close();
        }   
    }
?>