<?php
    // # set default parameters, you can set these earlier on in the logic
    $success = $success ?? "true";
    $statusCode = $statusCode ?? 200;
    $errors = $errors ?? [];
    if ($errors) {
        $statusCode = 400;
        $errors = [
            "code" => 400,
            "statusMessage" => "Bad Request",
            "errorMessages" => $errors
        ];
    }
    // set http response code
    http_response_code($statusCode);
    // helps to get PUT and DELETE content body
    $postVars_array = $postVars_array ?? $_POST ?? parse_str(file_get_contents("php://input"),$postVars_array) ?? [];
    $paramsAccepted = $paramsAccepted ?? [];
    $paramsNotAccepted = $paramsNotAccepted ?? [];
    $endpoint = $routName ?? $endpoint ?? static::$tableName ?? "index/api documentation";
    $content = $content ?? [];

    // # Create the response message
    $responseData = [
        "success" => $success,
        "statusCode" => $statusCode,
        "errors" => $errors,
        "requestMethod" => $_SERVER['REQUEST_METHOD'],
        "paramsSent" => [
            "GET" => $_GET,
            "PUT/PATCH/DELETE/POST" => $postVars_array
        ],
        "paramsAccepted" => $paramsAccepted,
        "paramsNotAccepted" => $paramsNotAccepted,
        "endpoint" => $endpoint,
        "content" => $content
    ];

    // # JSON encode the data structure and return it
    $jsonData = json_encode($responseData);
    echo $jsonData;
?>