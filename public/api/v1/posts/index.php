<?php
require_once('../../../../private/functions/functions.php');
require_once('../../../../private/classes/post.class.php');

// The endpoint for Posts

// TODO: Add function call to validate api key if it exists and is needed

// Check to be sure we are using https communication, if not then force it.
// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
//     $redirectUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     redirect_to($redirectUrl);
//     exit();
// }

// Check to see if it was a GET request
if (is_get_request()) {

    // Declare an array to hold all the parameters to use for the query;
    $parameters_array = NULL;
    
    // Check what parameters have been passed in the request
    if (isset($_GET['id'])) {
        
        // Check to see if we got a list of ids, then add the id or ids to the parameters array

        $ids_array = split_string_by_comma($_GET['id']);

        if ($ids_array != false) {
            $parameters_array['id'] = $_GET['id'];
        } else {
            $parameters_array['id'] = $ids_array;
        }
    }
    
    if (isset($_GET['createdDate'])) {
        $parameters_array['createdDate'] = $_GET['createdDate'];
    }
    
    if (isset($_GET['postDate'])) {
        $parameters_array['postDate'] = $_GET['postDate'];
    }
    
    if (isset($_GET['greaterThan'])) {
        $parameters_array['greaterThan'] = $_GET['greaterThan'];
    }
    
    if (isset($_GET['lessThan'])) {
        $parameters_array['lessThan'] = $_GET['lessThan'];
    }
    
    if (isset($_GET['status'])) {
        $parameters_array['status'] = $_GET['status'];
    }
    
    if (isset($_GET['extendedData'])) {
        $parameters_array['extendedData'] = $_GET['extendedData'];
    }
    
    if (isset($_GET['allImages'])) {
        $parameters_array['allImages'] = $_GET['allImages'];
    }
    
    if (isset($_GET['page'])) {
        $parameters_array['page'] = $_GET['page'];
    }
    
    if (isset($_GET['perPage'])) {
        $parameters_array['perPage'] = $_GET['perPage'];
    }
    
    // Call the code to trigger the SQL query.
    // Submit parameters_array = NULL if no params
    // TODO: Save the output and convert to json
    Post::api_query_database($parameters_array);

// If it was a POST request
} else {
    $data = [
        "success" => false,
        "statusCode" => 405,
        "errors" => [
            "code" => 405,
            "message" => "Method Not Allowed"
        ],
        "requestType" => $_SERVER['REQUEST_METHOD'],
        "totalPages" => 1,
        "currentPage" => 1
    ];
}

// Package the data into json and echo it back
$jsonData = json_encode($data);
echo $jsonData;

?>