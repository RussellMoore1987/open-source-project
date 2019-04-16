<?php
require_once('../../../../private/functions/functions.php');
require_once('../../../../private/classes/post.class.php');

// The endpoint for Posts

// TODO: Add function call to validate api key if it exists and is needed

// TODO: Debug and get the force HTTPS working
// Check to be sure we are using https communication, if not then force it.
// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
//     $redirectUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     redirect_to($redirectUrl);
//     exit();
// }

// Check to see if it was a GET request
if (is_get_request()) {

    // Declare an array to hold all the parameters to use for the query
    $whereClause_array = NULL;

    // Declare an array to hold all of our options for the query
    $options_array = NULL;
    
    // Check what parameters have been passed in the request then construct our WHERE clauses
    if (isset($_GET['id'])) {
        
        // Check to see if we got a list of ids, then add the id or ids to the parameters array

        $ids_array = split_string_by_comma($_GET['id']);

        if ($ids_array != false) {
            $whereClause_array[] = [
                'column' => 'id',
                'operator' => '=',
                'value' => $_GET['id']
            ];
        } else {
            $whereClause_array[] = [
                'column' => 'id',
                'operator' => '=',
                'value' => $ids_array
            ];
        }
    }
    
    if (isset($_GET['createdDate'])) {
        $whereClause_array[] = [
            'column' => 'createdDate',
            'operator' => '=',
            'value' => $_GET['createdDate']
        ];
    }
    
    if (isset($_GET['postDate'])) {
        $whereClause_array[] = [
            'column' => 'postDate',
            'operator' => '=',
            'value' => $_GET['postDate']
        ];
    }
    
    if (isset($_GET['greaterThan'])) {
        $whereClause_array[] = [
            'column' => 'postDate',
            'operator' => '>=',
            'value' => $_GET['greaterThan']
        ];
    }
    
    if (isset($_GET['lessThan'])) {
        $whereClause_array[] = [
            'column' => 'postDate',
            'operator' => '<=',
            'value' => $_GET['lessThan']
        ];
    }
    
    if (isset($_GET['status'])) {
        $whereClause_array[] = [
            'column' => 'status',
            'operator' => '=',
            'value' => $_GET['status']
        ];
    }

    // Default page and perPage parameters
    $page = 1;
    $perPage = 500;
    $totalPages = NULL;

    // Get the total number of posts to determine the totalPages
    $countOfPosts = POST::count_all();

    // Calculate the LIMIT and OFFSET
    // If both are defined
    if (isset($_GET['page']) && isset($_GET['perPage'])) {
        // Set the page and perPage
        $page = $_GET['page'];
        $perPage = $_GET['perPage'];

        $options_array['LIMIT'] = $perPage;
        $options_array['OFFSET'] = (($page - 1) * $perPage) + ($page - 1); 

    // If only the page is defined
    } elseif (isset($_GET['page'])) {
        // Set the page
        $page = $_GET['page'];

        $options_array['OFFSET'] = (($page - 1) * $perPage) + ($page - 1);

    // If only the perPage is defined
    } elseif (isset($_GET['perPage'])) {
        // Set the perPage
        $perPage = $_GET['perPage'];

        $options_array['LIMIT'] = $perPage;

    // If niether are defined
    } else {
        $options_array['LIMIT'] = $perPage;
    }
    
    // Set the total number of pages
    $totalPages = ceil($countOfPosts / $perPage);

    // Get all the parameters sent
    // TODO: finish packaging the parameters send on the request
    foreach($_GET as $getKey => $getValue) {
        $getParams_array[] = [
            $getKey => $getValue
        ];
    }

    // TODO: Validation needed here. Use the internal validation function

    // Call the code to trigger the SQL query.
    $postsData = Post::find_where($whereClause_array, $options_array);

    $data = [
        "success" => true,
        "statusCode" => 200,
        "errors" => [],
        "requestType" => $_SERVER['REQUEST_METHOD'],
        "totalPages" => $totalPages,
        "currentPage" => $page,
        "resultsPerPage" => $perPage,
        "paramSent" => [],
        "posts" => $postsData
    ];

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
        "currentPage" => 1,
        "resultsPerPage" => 0,
        "paramSent" => [],
        "posts" => []
    ];
}

// Package the data into json and echo it back
$jsonData = json_encode($data);
echo $jsonData;

?>