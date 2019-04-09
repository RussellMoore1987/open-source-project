<?php
require_once('../../../../private/functions/functions.php');
// The endpoint for Posts

// Check to be sure we are using https communication, if not then force it.
if ($_SERVER['HTTPS'] != "on") {
    $redirectUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    redirect_to($redirectUrl);
    exit();
}

// Check to see if it was a GET request
if (is_get_request()) {

    // Declare an array to hold all the parameters to use for the query;
    $parameters_array = [];
    
    // Check what parameters have been passed in the request
    if (isset($_GET['id'])) {
    
        // TODO: Check to see if we got a list of ids
        $parameters_array[] = [
            "id" => $_GET['id']
        ];
    }
    
    if (isset($_GET['createdDate'])) {
        $parameters_array[] = [
            "createdDate" => $_GET['createdDate']
        ];
    }
    
    if (isset($_GET['postDate'])) {
        $parameters_array[] = [
            "postDate" => $_GET['postDate']
        ];
    }
    
    if (isset($_GET['greaterThan'])) {
        $parameters_array[] = [
            "greaterThan" => $_GET['greaterThan']
        ];
    }
    
    if (isset($_GET['lessThan'])) {
        $parameters_array[] = [
            "lessThan" => $_GET['lessThan']
        ];
    }
    
    if (isset($_GET['status'])) {
        $parameters_array[] = [
            "status" => $_GET['status']
        ];
    }
    
    if (isset($_GET['extendedData'])) {
        $parameters_array[] = [
            "extendedData" => $_GET['extendedData']
        ];
    }
    
    if (isset($_GET['allImages'])) {
        $parameters_array[] = [
            "allImages" => $_GET['allImages']
        ];
    }
    
    if (isset($_GET['page'])) {
        $parameters_array[] = [
            "page" => $_GET['page']
        ];
    }
    
    if (isset($_GET['perPage'])) {
        $parameters_array[] = [
            "perPage" => $_GET['perPage']
        ];
    }
    
    // Get the data using the parameters given, else get all the data if no parameters given
    
    if (empty($parameters_array)) {
        // TODO: Call the code to execute the a query to get all the posts
    
    } else {
        // TODO: Call the code to execute the query to get the posts using the specified parameters
    
    }

// If it was a POST request
} else {
    $data = [
        "success" => false,
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