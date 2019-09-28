<?php
// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint: root/public/api/v1/
// ----------------------------------------- Root API Data -------------------------------------------------

// root link
$rootLink = PUBLIC_LINK_PATH . "/api/restApi/v1/";

// main object
$apiRoot = [
    // General Info
    "companyName" => "Placeholder Company",
    "termsOfUse" => "Placeholder Terms URL",
    "version" => "1.0.0",
    "contact" => "someone@someone.com",
    "description" => "V1.0.0 of the api. This API may be used to retrieve data from the CMS system and in some cases create data. If the system has an API key it is required on all requests.",
    "siteRoot" => MAIN_LINK_PATH,
    "mainApiPath" => $rootLink,
    // general rout documentation
    "generalRoutDocumentation" => [
        // Main Authentication
        "mainAuthentication" => [
            "authToken" => "If the system has an API key, it is required on all requests. On POST, PUT, PATCH, and DELETE requests an API key (authToken) is require, POST, PUT, and PATCH requests, the API key must be sent as a post parameter/argument. DELETE requests information can be in the URL or the x-www-form-urlcoded body",
            "default" => "none",
            "example" => $rootLink . "categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
        ],
        "httpMethods" => [
            "GET" => "Accepts URL/GET variables",
            "POST" => "Accepts form data/x-www-form-urlcoded data",
            "PUT" => "Accepts x-www-form-urlcoded data",
            "PATCH" => "Accepts x-www-form-urlcoded data",
            "DELETE" => "Accepts URL/GET variables/x-www-form-urlcoded data"
        ],  
        "generalInformation" => [
            "documentationNote" => "This is a general message for all endpoints. On creation of a new record (POST/PATCH) calls the validation of all properties. Required properties must be provided, all others will be passed through according to their validation. On update (PUT), only the fields that are pass-through will be updated.",
            "validationDocumentation" => val_validation_documentation()
        ]   
    ],
    // routes
    "routes" => []
];

// get list of class for the api, key => value, posts => Post
$apiClassList_array = $this->pathInterpretation_array;

// * what an end point looks like
// look at root/public/api/v1/

// build api, this list has been checked previously, all should be good to be used in the API
foreach ($apiClassList_array as $routName => $className) {
    // set default variables
    $apiGet = false;
    // set default array for end point
    $tempEndPoint_arry = [];
    // default path
    $rout = "/" . $routName;
    // validation columns
    $validationColumns_array = $className::get_validation_columns();

    // get api info for class
    $apiInfo_array = $className::get_api_class_info();
    // rout info
    $routInfo_array = $apiInfo_array['routes'][$routName];
    // make class info array
    $dbKeyInfo_array['mainApiKey'] = DatabaseObject::get_main_api_key();
    $dbKeyInfo_array['mainGetApiKey'] = DatabaseObject::get_main_get_api_key();
    $dbKeyInfo_array['mainPostApiKey'] = DatabaseObject::get_main_post_api_key();

    // check to see if we need to set rout documentation
    if (isset($routInfo_array['routDocumentation']) && 
        trim(strlen($routInfo_array['routDocumentation'])) > 0) {
        $tempEndPoint_arry['routDocumentation'] = $routInfo_array['routDocumentation'];
    }

    // check to see if we have a get http method
    if (isset($routInfo_array['httpMethods']['get']) && isset($routInfo_array['httpMethods']['get']['arrayInfo'])) {
        // set $apiGet to true
        $apiGet = true;
        // get api info form the class
        $arrayName = $routInfo_array['httpMethods']['get']['arrayInfo'];
        // check if it's a property/array or a function  
        if (contains($arrayName, '_')) {
            $classGetApiInfo_arry = $className::$arrayName();
        } else {
            $classGetApiInfo_arry = $className::$$arrayName;
        }
        
        // build end point array
            // set ["methods"]["availableMethods"]["GET"]
            $tempEndPoint_arry["methods"]["availableMethods"]["GET"] = "The ability to GET information from {$routName}, you can filter results based on the parameters provided.";

            // set key to be use if there
            $apiKey = DatabaseObject::apiKeyCheck($dbKeyInfo_array, $apiInfo_array, $routInfo_array, 'get'); 
            if ($apiKey) {
                $tempEndPoint_arry["methods"]["GET"]["key"] = $apiKey; 
            }

            // add documentation main
            // reset methodDocumentation array
            $methodDocumentation = [];
            // The method POST allows you to insert a record based on the parameters provided below.
            $methodDocumentation["main"] = "The ability to GET information from {$rout}, you can filter results based on the parameters provided below.";
            // add specific method documentation if available
            if (isset($routInfo_array['httpMethods']['get']['methodDocumentation']) && 
                trim(strlen($routInfo_array['httpMethods']['get']['methodDocumentation'])) > 0) {
                // make array then merge it with methodDocumentation
                $methodDocumentation["additionalNote"] = $routInfo_array['httpMethods']['get']['methodDocumentation'];
            }
            // also added documentation note
            $tempEndPoint_arry["methods"]['GET']["methodDocumentation"] = $methodDocumentation;

            // set ["methods"]["GET"]["parameters"]["noParamsSent"]
            $tempEndPoint_arry["methods"]["GET"]["parameters"]["noParamsSent"]["description"] = "When no parameters are passed then all {$routName} are returned.";
            $tempEndPoint_arry["methods"]["GET"]["parameters"]["noParamsSent"]["example"] = $rootLink . $routName . "/";

            // loop over classGetApiInfo_arry
            foreach ($classGetApiInfo_arry as $getParameterName => $getParameterValue) {
                // set other ["methods"]["GET"]["parameters"] 
                    // check to see if there is a required parameter
                    $required = $getParameterValue["required"] ?? false;
                    $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["required"] = $required; 
                    // set type 
                    $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["type"] = implode(" / ", $getParameterValue["type"]); 
                    // set description   
                    $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["description"] = $getParameterValue["description"];
                     // set validation specs
                     $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["validation"] = $getParameterValue["validation"] ?? $validationColumns_array[$getParameterValue["refersTo"][0]] ?? "Validation can not be viewed"; 
                    // loop over each example, check if to see if there is only one example
                    if (isset($getParameterValue["customExample"]) && count($getParameterValue["customExample"]) >= 1) {
                        foreach ($getParameterValue["customExample"] as $ceKey => $customExample) {
                            $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["example"][$ceKey] = $rootLink . $routName . "/?" . $customExample;
                        }   
                    } elseif (isset($getParameterValue["example"]) && count($getParameterValue["example"]) >= 1) {
                        $exampleCount = 0;
                        foreach ($getParameterValue["example"] as $example) {
                            $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["example"][$getParameterValue["type"][$exampleCount] . "Example"] = $rootLink . $routName . "/?" . $example;    
                            $exampleCount++; 
                        }  
                    } else {
                        // get the first array item
                        $tempEndPoint_arry["methods"]["GET"]["parameters"][$getParameterName]["example"] = "No example provided"; 
                    }
            }

            // set default parameters 
                // set ["methods"]["GET"]["parameters"]["page"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["required"] = false;
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["type"] = "int";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["description"] = "Returns data from the query result, based off of the page number. If query result was 50, page 2 would return results 11 - 21, Default page number = 1.";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["validation"] = [
                    'name' => "page",
                    'required' => true,
                    'type' => 'int',
                    'num_min'=> 1 
                ];
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["example"] = $rootLink . "{$routName}?page=1";

                // set ["methods"]["GET"]["parameters"]["perPage"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["required"] = false;
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["type"] = "int";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["description"] = "Specifies the number of results to return with each page of information. By default only 10 are returned per request.";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["validation"] = [
                    'name' => "page",
                    'required' => true,
                    'type' => 'int',
                    'num_min'=> 1 
                ];
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["example"] = $rootLink . "{$routName}?perPage=10";

                // set ["methods"]["GET"]["parameters"]["orderBy"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["required"] = false;
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["type"] = "str / list";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["description"] = "Returns data in the order you specify, the example below is not runnable, but shows the theory behind how it is used. Each endpoint uses the database columns of its particular table to do the sorting. By default if you do not specify ascending (::ASC) or descending (::DESC) it will default to ascending (::ASC).";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["validation"] = [
                    'name' => "orderBy",
                    'required' => true,
                    'type' => 'str',
                    'min'=> 1, 
                    'max' => 300,
                    'html' => 'no'
                ];
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["example"] = $rootLink . "{$routName}?orderBy=date::DESC,title";

            // sort parameters
            if (isset($tempEndPoint_arry["methods"]["GET"]["parameters"])) {
                ksort($tempEndPoint_arry["methods"]["GET"]["parameters"]);
            }

            // TODO: see what it looks like if no json is returned
            // check to see if we should show an example
            if (!(isset($routInfo_array['httpMethods']['get']['apiShowGetExamples']) && 
                $routInfo_array['httpMethods']['get']['apiShowGetExamples'] == 'no')) {
                // set ["methods"]["GET"]["exampleResponse"], successResponse errorResponse
                // get api key if there
                // check for authentication, API key, specific to general
                    // method key 
                    $methodKey = $routInfo_array['httpMethods']['get']['methodKey'] ?? '';
                    // rout key 
                    $routKey = $routInfo_array['routKey'] ?? '';
                    // class key
                    $classKey = $apiInfo_array['classKey'] ?? '';
                    // main get key
                    $mainGetApiKey = DatabaseObject::get_main_get_api_key();
                    // main general api key
                    $mainApiKey = DatabaseObject::get_main_api_key();
                    // class specific key
                    if ($methodKey) {
                        // we have a method specific key
                        $apiToken = "&authToken=" . $methodKey;
                    } elseif ($routKey) {
                        // we have a method specific key
                        $apiToken = "&authToken=" . $routKey;
                    } elseif ($classKey) {
                        // we have a class specific key
                        $apiToken = "&authToken=" . $classKey;
                    // GET specific key 
                    } elseif ($mainGetApiKey) {
                        $apiToken = "&authToken=" . $mainGetApiKey;
                    // overall API key
                    } elseif ($mainApiKey) {
                        $apiToken = "&authToken=" . $mainApiKey;
                    // no API key needed, or set
                    } else {
                        $apiToken = "";
                    }
                // make the call, get live example, if allowed
                if (isset($dbClassList[$className]["apiShowExamples"]) && 
                    $dbClassList[$className]["apiShowExamples"] == "no") {
                    // don't run call
                    $data = "";
                } else {
                    // make the call
                    $data = file_get_contents($rootLink . $routName . "/?perPage=3{$apiToken}");
                }
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "no data was found, or no example allowed form " . $rootLink . $routName . "/.";
                // set ["methods"]["GET"]["exampleResponse"]["successResponse"]
                $tempEndPoint_arry["methods"]["GET"]["exampleResponse"]["successResponse"] = $data;
                // to make sure we get content back when we get a 400 error
                $context = stream_context_create(array(
                    'http' => ['ignore_errors' => true],
                ));
                // make the call, get live example
                $data = file_get_contents($rootLink . $routName . "/?error=yes", false, $context);
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "no data was found form " . $rootLink . $routName . "/";
                // set ["methods"]["GET"]["exampleResponse"]["errorResponse"]
                $tempEndPoint_arry["methods"]["GET"]["exampleResponse"]["errorResponse"] = $data;    
            } 
    }

    // check to see if post like values are available
    $postLike = false;
    // set or reset array
    $postLike_array = [];
    foreach ($routInfo_array['httpMethods'] as $httpMethodName => $httpMethodInfo_array) {
        if ($httpMethodName != 'get') {
            // check for arrayInfo, don't check on delete
            if ($httpMethodName != 'delete') {
                if (!(isset($httpMethodInfo_array['arrayInfo']) && 
                    trim(strlen($httpMethodInfo_array['arrayInfo'])) > 0)) {
                    continue;
                }
            }
            $postLike_array[$httpMethodName] = $routInfo_array['httpMethods'][$httpMethodName];
            $postLike = true;
        }
    }
            
    // check to see if we have any post like values are available
    if ($postLike) {
        // default options
        $options_array = [];
        // loop over each post like value and get messages
        foreach ($postLike_array as $httpMethod => $values) {
            // build end point array
            // upper case the $httpMethod
            $ucHttpMethod = strtoupper($httpMethod);
            // check for and build corresponding message
            // reset methodDocumentation array
            $methodDocumentation = [];
            // set key to be use if there
            $apiKey = DatabaseObject::apiKeyCheck($dbKeyInfo_array, $apiInfo_array, $routInfo_array, $httpMethod); 
            if ($apiKey) {
                $tempEndPoint_arry["methods"][$ucHttpMethod]["key"] = $apiKey; 
            }
            // set ["methods"]["availableMethods"]["POST"] and others
            if ($httpMethod == 'post') {
                // add to options_array
                $options_array[] = "POST";
                // variables for availableMethods and documentation
                    $tempEndPoint_arry["methods"]["availableMethods"][$ucHttpMethod] = "The ability to {$ucHttpMethod} to {$routName}, insert a record based on the parameters provided."; 
                    $methodDocumentation["main"] = "The method POST allows you to insert a record based on the parameters provided below.";
                    // also added documentation note
                    $tempEndPoint_arry["methods"][$ucHttpMethod]["methodDocumentation"] = $methodDocumentation;
            } elseif ($httpMethod == 'patch') {
                // variables for availableMethods and documentation
                    $tempEndPoint_arry["methods"]["availableMethods"][$ucHttpMethod] = "The ability to {$ucHttpMethod} to {$routName}, copy a record while changing some or none of it properties. An id is required for copping a record."; 
                    $methodDocumentation["main"] = "The method PATCH allows you to copy a record based on the id and parameters provided below.";
                    // also added documentation note
                    $tempEndPoint_arry["methods"][$ucHttpMethod]["methodDocumentation"] = $methodDocumentation;
                    // id message
                    $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["required"] = true;
                    // set type 
                    $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["type"] = "int"; 
                    // set description   
                    $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["description"] = "The id is required for copping a record.";
                    $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["validation"] = $validationColumns_array['id'] ?? "Validation can not be viewed";

            } elseif ($httpMethod == 'put') {
                // variables for availableMethods and documentation
                    $tempEndPoint_arry["methods"]["availableMethods"][$ucHttpMethod] = "The ability to {$ucHttpMethod} to {$routName}, update a record based off an id and the parameters provided."; 
                    $methodDocumentation["main"] = "The method PUT allows you to update a record based on the parameters provided below. An id is required for updating a record, an exception is made if putWhere parameter is available. All PUT parameters must be sent in the HTTP body content.";
                    // check to see if putWhere is available 
                    if (isset($values['putWhere']) && $values['putWhere'] === true) {
                        $methodDocumentation["putWhere"] = "The parameter putWhere is available on this endpoint. The parameter putWhere allows you to update all records that meet the condition of the putWhere. You can access the putWhere option by passing in a parameter named putWhere, with the value separated by two colons to indicate column and value. ex putWhere => 'jobTitle::developer'. All records that meet this description will be updated with the content provided.";
                    }
                    // also added documentation note
                    $tempEndPoint_arry["methods"][$ucHttpMethod]["methodDocumentation"] = $methodDocumentation;
                    // id message
                    $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["required"] = true;
                    // set type 
                    $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["type"] = "int"; 
                    // set description   
                    $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["description"] = "The id is required for updating a record, an exception is made if the putWhere parameter is available.";
                    $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["validation"] = $validationColumns_array['id'] ?? "Validation can not be viewed";

            } elseif ($httpMethod == 'delete') {
                // variables for availableMethods and documentation
                    $tempEndPoint_arry["methods"]["availableMethods"][$ucHttpMethod] = "The ability to {$ucHttpMethod} to {$routName}, delete a record based on the id provided."; 
                    // check to see if putWhere is available 
                    $methodDocumentation["main"] = "The method DELETE allows you to delete a record based on the id provided. An id is required for deleting a record, an exception is made if deleteWhere parameter is available.DELETE Parameters id and authToken can be sent in the HTTP body content or the URL as a GET parameter.";
                    if (isset($values['deleteWhere']) && $values['deleteWhere'] === true) {
                        $methodDocumentation["deleteWhere"] = "The parameter deleteWhere is available on this endpoint. The parameter deleteWhere allows you to delete all records that meet the condition of the deleteWhere. You can access the deleteWhere option by passing in a parameter named deleteWhere, with the value separated by two colons to indicate column and value. ex deleteWhere => 'jobTitle::blackSmith'. All records that meet this description will be deleted. The deleteWhere parameter must be sent in the HTTP body. Validation max = 100, min = 1, html = no.!!!";
                    }
                    // also added documentation note
                    $tempEndPoint_arry["methods"][$ucHttpMethod]["methodDocumentation"] = $methodDocumentation;
                    // id message
                    $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["required"] = true;
                    // set type 
                    $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["type"] = "int"; 
                    // set description   
                    $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["description"] = "The id is required for deleting a record, an exception is made if the deleteWhere parameter is available";
                    $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["validation"] = $validationColumns_array['id'] ?? "Validation can not be viewed";

            }

            // add specific method documentation if available
            if (isset($routInfo_array['httpMethods'][$httpMethod]['methodDocumentation']) && 
                trim(strlen($routInfo_array['httpMethods'][$httpMethod]['methodDocumentation'])) > 0) {
                // make array then merge it with methodDocumentation
                $tempEndPoint_arry["methods"][$ucHttpMethod]['methodDocumentation'] = array_merge(
                    $methodDocumentation, 
                    ['additionalNote' => $routInfo_array['httpMethods'][$httpMethod]['methodDocumentation']]
                );
            }

            // put in authToken all except GET need it
            $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"]["authToken"]["required"] = true; 
            // set type 
            $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"]["authToken"]["type"] = "str"; 
            // set description   
            $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"]["authToken"]["description"] = "An authToken is required for all {$ucHttpMethod} requests.";

            // set ["methods"]["POST"]["exampleResponse"], successResponse errorResponse, and PUT, PATCH, DELETE
            // make the call, POST live example
            $data = post($rootLink . $routName . "/?success=yes", [], $ucHttpMethod);
            // check to see if we got anything back
            $data = trim(strlen($data)) > 10 ? json_decode($data): "No response was given from " . $rootLink . $routName . "/";
            // set ["methods"][$ucHttpMethod]["exampleResponse"]["successResponse"]
            $tempEndPoint_arry["methods"][$ucHttpMethod]["exampleResponse"]["successResponse"] = $data;
            
            // make the call, get live example
            // make the call, POST live example
            $data = post($rootLink . $routName . "/?error=yes", [], $ucHttpMethod);
            // check to see if we got anything back
            $data = trim(strlen($data)) > 10 ? json_decode($data): "No response was given from " . $rootLink . $routName . "/";
            // set ["methods"][$ucHttpMethod]["exampleResponse"]["errorResponse"]
            $tempEndPoint_arry["methods"][$ucHttpMethod]["exampleResponse"]["errorResponse"] = $data;

            // get api info form the httpMethod, if delete skip and delete does not utilize this functionality
            if (isset($values['arrayInfo'])) {
                $valueName =  $values['arrayInfo'];
            } else {
                continue;
            }
            // check if it's a property/array or a function  
            if (contains($valueName, '_')) {
                $classPostApiInfo_arry = $className::$valueName();
            } else {
                $classPostApiInfo_arry = $className::$$valueName;
            }

            // loop over postApiParameters
            foreach ($classPostApiInfo_arry as $postParameterName => $postParameterValue) {
                // set other ["methods"]["POST"]["parameters"] and others
                // skip over delete, it does not utilize apiInfo
                if ($httpMethod == "delete") { continue; }
                // ids are taken care of else where
                if ($postParameterName == "id") { continue; }
                // check if validation says that it's required
                $required = $postParameterValue["required"] ?? false;
                // check to see if there is a required parameter, if in post
                if ($ucHttpMethod == "POST") {
                    // if custom or validation columns are set
                    if (isset($classPostApiInfo_arry[$postParameterName]['validation']["required"]) || isset($validationColumns_array[$postParameterName]["required"])) { 
                        $required = true; 
                    }
                }
                $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"][$postParameterName]["required"] = $required; 
                // set type 
                $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"][$postParameterName]["type"] = implode(" / ", $postParameterValue["type"]); 
                // set description   
                $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"][$postParameterName]["description"] = $postParameterValue["description"]; 
                // set validation specs
                $tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"][$postParameterName]["validation"] = $postParameterValue["validation"] ?? $validationColumns_array[$postParameterName] ?? "Validation can not be viewed"; 
            }

            // sort parameters
            if (isset($tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"])) {
                ksort($tempEndPoint_arry["methods"][$ucHttpMethod]["parameters"]);
            }
        }  
    }
    
    // check to see if we need to add to the documentation
    if ($tempEndPoint_arry) {
        // check to see whether not the route has a get and post
            $quickViewOFRoutesAvailable = [];

            // check to see if we have get stuff
            if ($apiGet) {
                $quickViewOFRoutesAvailable[] = "GET";
            }

            // check to see if we have post stuff
            if ($postLike) {
                foreach ($postLike_array as $httpMethod => $values) {
                    // set options
                    $quickViewOFRoutesAvailable[] = strtoupper($httpMethod);

                    // check to see if putWhere or deleteWhere
                    if (isset($values['putWhere']) && $values['putWhere'] === true) {
                        $quickViewOFRoutesAvailable[] = "PUT::putWhere";
                    } elseif (isset($values['deleteWhere']) && $values['deleteWhere'] === true) {
                        $quickViewOFRoutesAvailable[] = "DELETE::deleteWhere";
                    }
                }
            }

        // set up quick view documentation element
        $apiRoot["routes"]['quickViewOFRoutesAvailable'][$rout] = $quickViewOFRoutesAvailable;
        // add class reference/endpoint documentation
        $apiRoot["routes"][$rout] = $tempEndPoint_arry;
    }
}

// JSON encode the data structure and return it
$jsonData = json_encode($apiRoot);
echo $jsonData;
?>