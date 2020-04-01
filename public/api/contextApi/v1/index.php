<?php
    // ! Note with this page 
    // while working with this page I noticed that it needs to be /v1/ not /v1
    // http://localhost/open_source_project/public/api/contextApi/v1/

    // Pull in needed classes 
    require_once("../../../../private/initialize.php");

    // set content return type
    header('Content-Type: application/json');

    // Setting up some server access controls to allow people to get information
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods:  POST, GET');

    // ! For testing errors
    // if (isset($_GET['instructions'])) {
    //     $_POST['instructions'] = $_GET['instructions'];
    // }

    // get post instructions
    $postVars_array = $_POST ?? parse_str(file_get_contents("php://input"),$postVars_array) ?? [];
    $instructions = $postVars_array['instructions'] ?? [];
    // check to see if we have $instructions
    if ($instructions) {
        // Connect to internal API
        InternalApi::request($instructions);
    } else {
        // TODO: eventually turn this into the index page
        // Get generic error message
        InternalApi::internalApi_message(["errors" => "Unable to process request, did not send the correct instructions."]);
    }
?>