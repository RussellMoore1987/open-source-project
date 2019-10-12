




<?php
    // Pull in needed classes 
    require_once("../../../../private/initialize.php");

    // get post instructions
    $postVars_array = $_POST ?? parse_str(file_get_contents("php://input"),$postVars_array) ?? [];
    $instructions = $postVars_array['instructions'] ?? [];
    // check to see if we have $instructions
    if ($instructions) {
        // Connect to internal API
        InternalApi::request($instructions);
    } else {
        // Get generic error message
        InternalApi::internalApi_message(["errors" => "Unable to process request did not send in the correct instructions."]);
    }
?>