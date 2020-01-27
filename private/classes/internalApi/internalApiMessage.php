<?php
    $success = $data['success'] ?? "true";
    $statusCode = $data['statusCode'] ?? 200;
    $content = $data['content'] ?? [];
    $errors = $data['errors'] ?? [];
    if ($errors && !$content) {
        $statusCode = 400;
        $success = "false";
        $errors = [
            "code" => 400,
            "statusMessage" => "Bad Request",
            "errorMessages" => $data['errors']
        ];
    }
    // set http response code
    http_response_code($statusCode);

    // helps to get PUT and DELETE content body
    $postVars_array = $postVars_array ?? $_POST ?? parse_str(file_get_contents("php://input"),$postVars_array) ?? [];
    $requestsAccepted = $data['requestsAccepted'] ?? [];
    $requestsNotAccepted = $data['requestsNotAccepted'] ?? [];
    $endpoint = $data['endpoint'] ?? "contextApi";

    // # Create the response message
    $responseData = [
        "success" => $success,
        "statusCode" => $statusCode,
        "errors" => $errors,
        "requestMethod" => $_SERVER['REQUEST_METHOD'],
        "paramsSent" => [
            "GET" => $_GET,
            "POST" => $postVars_array
        ],
        "requestsAccepted" => $requestsAccepted,
        "requestsNotAccepted" => $requestsNotAccepted,
        "endpoint" => $endpoint,
        "content" => $content
    ];

    // # JSON encode the data structure and return it
    $jsonData = json_encode($responseData);
    echo $jsonData;  
?>