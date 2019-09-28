<?php
    // include main logic for all pages
    require_once('../../../../private/initialize.php');

    // set content return type
    header('Content-Type: application/json');

    // Setting up some server access controls to allow people to get information
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE');

    // set up the router 
    $Router = new ApiRouter($_SERVER['QUERY_STRING']);

    // execute output based off of route
    $Router->output();
?>