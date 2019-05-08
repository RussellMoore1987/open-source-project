<?php
// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint: root/public/api/v1/
// Check to be sure we are using https communication, if not then force it.
// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
//     $redirectUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     redirect_to($redirectUrl);
//     exit();
// }
// TODO: See 6 todos below
// 1- Dynamic companyName
// 2- Dynamic termsOfUse
// 3- Dynamic contact
// 4- Dynamic root
// 5- Force HTTPS communication
// 6- HTTP status codes and error returning
// TODO: what dose post data/api documentation look like 
// TODO: make it stander, with the ability to add custom, in api trait or individual class, might be able to use a interface or some thing like that
// ----------------------------------------- Root API Data -------------------------------------------------
// root link
$rootLink = PUBLIC_LINK_PATH . "/api/v1/";

// main object
$apiRoot = [
    // General Info
    "companyName" => "Placeholder Company",
    "termsOfUse" => "Placeholder Terms URL",
    "version" => "1.0.0",
    "contact" => "someone@someone.com",
    "description" => "V1.0.0 of the api. This API may be used to retrieve data from the CMS system and in some cases create data. If the system has an API key it is required on all requests.",
    "root" => MAIN_LINK_PATH,
    "mainPath" => $rootLink,
    // Routes
    "routes" => [
        // Main Authentication
        "mainAuthentication" => [
            "required" => "If the system has an API key, it is required on all requests. All post requests require an API key, post API key must be sent as a post parameter/argument",
            "default" => "none",
            "example" => $rootLink . "categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
        ]       
    ]
];

// get list of class for the api, key => value, posts => Post
$apiClassList_array = $this->pathInterpretation_array;

// * what an end point looks like
// look at root/public/api/v1/

// build api, this list has been checked previously, all should be good to be used in the API
foreach ($apiClassList_array as $key1 => $value1) {
    // set default array for end point
    $tempEndPoint_arry = [];
    // default path
    $classReference = "/" . $key1;

    // check to see if we have any getApiParameters
    if ($value1::get_get_api_parameters()) {
        // get api info form the class
        $classGetApiInfo_arry = $value1::get_get_api_parameters();
        
        // build end point array
            // set ["methods"]["availableMethods"]["GET"]
            $tempEndPoint_arry["methods"]["availableMethods"]["GET"] = "To get {$key1} information";

            // set ["methods"]["GET"]["parameters"]["noParamsSent"]
            $tempEndPoint_arry["methods"]["GET"]["parameters"]["noParamsSent"]["description"] = "When no parameters are passed then all {$key1} are returned";
            $tempEndPoint_arry["methods"]["GET"]["parameters"]["noParamsSent"]["example"] = $rootLink . $key1 . "/";

            // loop over getApiParameters
            foreach ($classGetApiInfo_arry as $key2 => $value2) {
                // set other ["methods"]["GET"]["parameters"]
                    // check to see if there is a required parameter
                    $required = $value2["required"] ?? "false";
                    $tempEndPoint_arry["methods"]["GET"]["parameters"][$key2]["required"] = $required; 
                    // set type 
                    $tempEndPoint_arry["methods"]["GET"]["parameters"][$key2]["type"] = implode(" / ", $value2["type"]); 
                    // set description   
                    $tempEndPoint_arry["methods"]["GET"]["parameters"][$key2]["description"] = $value2["description"]; 
                    // loop over each example, check if to see if there is only one example
                    if (isset($value2["customExample"]) && count($value2["customExample"]) >= 1) {
                        foreach ($value2["customExample"] as $ceKey => $customExample) {
                            $tempEndPoint_arry["methods"]["GET"]["parameters"][$key2]["example"][$ceKey] = $rootLink . $key1 . "/?" . $customExample;
                        }   
                    } elseif (isset($value2["example"]) && count($value2["example"]) >= 1) {
                        $exampleCount = 0;
                        foreach ($value2["example"] as $example) {
                            $tempEndPoint_arry["methods"]["GET"]["parameters"][$key2]["example"][$value2["type"][$exampleCount] . "Example"] = $rootLink . $key1 . "/?" . $example;    
                            $exampleCount++; 
                        }  
                    } else {
                        // get the first array item
                        $tempEndPoint_arry["methods"]["GET"]["parameters"][$key2]["example"] = "No example provided"; 
                    }
            }

            // set default parameters 
                // set ["methods"]["GET"]["parameters"]["page"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["required"] = "false";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["type"] = "int";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["description"] = "Returns data from the query result, based off of the page number. If query result was 50, page 2 would return results 11 - 21, Default page number = 1";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["example"] = $rootLink . "{$key1}?page=1";

                // set ["methods"]["GET"]["parameters"]["perPage"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["required"] = "false";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["type"] = "int";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["description"] = "Specifies the number of results to return with each page of information. By default only 10 are returned per request";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["example"] = $rootLink . "{$key1}?perPage=10";

                // set ["methods"]["GET"]["parameters"]["orderBy"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["required"] = "false";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["type"] = "str / list";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["description"] = "Returns data in the order you specify, example below is not runnable, but shows the theory behind how it is used. Each endpoint uses the database columns of its particular table to do the sorting";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["example"] = $rootLink . "{$key1}?orderBy=date::DESC,title";
              
            // set ["methods"]["GET"]["exampleResponse"], successResponse errorResponse
                // make the call, get live example
                $data = file_get_contents($rootLink . $key1 . "/");
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "no data was found for " . $rootLink . $key1 . "/";
                // set ["methods"]["GET"]["exampleResponse"]["successResponse"]
                $tempEndPoint_arry["methods"]["GET"]["exampleResponse"]["successResponse"] = $data;
                
                // make the call, get live example
                $data = file_get_contents($rootLink . $key1 . "/?error=yes");
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "no data was found for " . $rootLink . $key1 . "/";
                // set ["methods"]["GET"]["exampleResponse"]["errorResponse"]
                $tempEndPoint_arry["methods"]["GET"]["exampleResponse"]["errorResponse"] = $data;
    }
            
    // check to see if we have any postApiParameters
    if ($value1::get_post_api_parameters()) {
        // TODO: what do a post success message look like, successResponse errorResponse, make them Json
        // TODO: if column is not specified kick information out before performing operations
        // not sure what to put here
        // get api info form the class
        $classPostApiInfo_arry = $value1::get_post_api_parameters();
        
        // build end point array
            // set ["methods"]["availableMethods"]["POST"]
            $tempEndPoint_arry["methods"]["availableMethods"]["POST"] = "To post to {$key1}, update, insert, and delete";

            // loop over postApiParameters
            foreach ($classPostApiInfo_arry as $key4 => $value4) {
                // if postApiActions show options then continue
                if ($key4 == "postApiActions") {
                    $tempEndPoint_arry["methods"]["POST"]["parameters"][$key4] = $value4;
                    $tempEndPoint_arry["methods"]["POST"]["parameters"][$key4]["type"] = "str";
                    $tempEndPoint_arry["methods"]["POST"]["parameters"][$key4]["description"] = "This describes the post API actions available to this endpoint. In order to access one of these endpoints API actions directly you must specify it in the form field named 'postApiActions' with the corresponding action as a value. By default if you do not send in an id or an action ,it will assume and insert. If you include an id but no action it will assume an update. That is if those post API actions are available";
                    continue;
                }
                // set other ["methods"]["POST"]["parameters"]
                    // check to see if there is a required parameter
                    $required = $value2["required"] ?? "false";
                    $tempEndPoint_arry["methods"]["POST"]["parameters"][$key4]["required"] = $required; 
                    // set type 
                    $tempEndPoint_arry["methods"]["POST"]["parameters"][$key4]["type"] = implode(" / ", $value4["type"]); 
                    // set description   
                    $tempEndPoint_arry["methods"]["POST"]["parameters"][$key4]["description"] = $value4["description"]; 
            }

            // set ["methods"]["POST"]["exampleResponse"], successResponse errorResponse
                // TODO:
                // ! switch to post call
                // make the call, POST live example
                $data = post($rootLink . $key1, $postVars = [
                    "showSuccessMessage" => "true"
                ]);
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "No response was given from " . $rootLink . $key1 . "/";
                // set ["methods"]["POST"]["exampleResponse"]["successResponse"]
                $tempEndPoint_arry["methods"]["POST"]["exampleResponse"]["successResponse"] = $data;
                
                // make the call, get live example
                // make the call, POST live example
                $data = post($rootLink . $key1, $postVars = [
                    "showErrorMessage" => "true"
                ]);
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "No response was given from " . $rootLink . $key1 . "/";
                // set ["methods"]["POST"]["exampleResponse"]["errorResponse"]
                $tempEndPoint_arry["methods"]["POST"]["exampleResponse"]["errorResponse"] = $data;
    }
    
    // check to see if we need to add to the documentation
    if ($tempEndPoint_arry) {
        $apiRoot["routes"][$classReference] = $tempEndPoint_arry;
    }
}

// JSON encode the data structure and return it
$jsonData = json_encode($apiRoot);
echo $jsonData;
?>