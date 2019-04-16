<?php
    // include main logic for all pages
    require_once('../../../private/initialize.php');

    // set up the router 
    $Router = new ApiRouter($_SERVER['QUERY_STRING']);

    // execute output based off of route
    $Router->output();
?>