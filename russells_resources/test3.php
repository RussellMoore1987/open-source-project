<?php
    // @ just testing an idea start
        // # in db class start
            // set over arching API keys, use function to get the key
            // you can specify individual class API keys in the databaseObject class for post and get
            // password specificity general to specific
                // mainApiKey
                // mainPostApiKey or mainGetApiKey
                // classKey
                // routKey
                // methodKey
            // static protected
            // for all
            $mainApiKey = 'T3$$tK3y!24%6'; // use get_main_api_key()
            $mainGetApiKey = 'T3$$tK3y!24%6'; // use get_main_get_api_key()
            $mainPostApiKey = 'T3$$tK3y!24%6'; // use get_main_post_api_key()
            // static protected
            $classList = [
                "Label" => ["labels","labels/mid","labels/full"],       
            ];
        // # in db class end

        // # in class start 
            $apiInfo = [
                // class specific validation, used for all HTTP methods if specific key is not available, otherwise will refer to main get and main post or main API key
                'classKey' => 'T3$$tK3y!24%6',
                'routs' => [
                    'labels' => [
                        // name specific properties you wish to exclude in the API
                        'apiPropertyExclusions' => ['password', 'adminNote'],
                        // specify httpMethods, 
                        // get does not need a password in any form
                        'httpMethods' => [
                            'get' => [
                                'arrayInfo' => 'public_access',
                                // anabel you to use whereConditions 
                                'whereConditions' => 'WHERE userType NOT IN(admin,SuperAdmin)',
                                 // show or not to show that is the question
                                "apiShowGetExamples" => 'no' // can use no, set to yes for testing 
                            ]
                        ]
                    ],
                    "labels/full" => [
                        // rout specific documentation
                        'routDocumentation' => 'rout specific documentation',
                        // name specific properties you wish to exclude in the API
                        'apiPropertyExclusions' => ['password', 'adminNote'],
                        // class specific validation, used for all HTTP methods if specific key is not available, otherwise will refer to main get and main post or main API key
                        'routKey' => 'T3$$tK3y!24%6',
                        // specify httpMethods, 
                        // get does not need a password in any form, must specify paths in order to use
                        'httpMethods' => [
                            'get' => [
                                'methodKey' => 'T3$$tK3y!24%6',
                                'arrayInfo' => 'public_access', // * required
                                // anabel you to use whereConditions 
                                'whereConditions' => 'WHERE userType NOT IN(admin,SuperAdmin)',
                                 // show or not to show that is the question
                                "apiShowGetExamples" => 'no', // can use no, set to yes for testing // default yes
                                // method documentation
                                'methodDocumentation' => 'method specific documentation'
                            ],
                            // if a post like httpMethods do not contain a password it will default the class key then to the post key then to the main key
                            // post like httpMethods must have some kind of key
                            'post' => [
                                'methodKey' => 'T3$$tK3y!24%6',
                                'arrayInfo' => 'public_post_access'
                            ],
                            'put' => [
                                'methodKey' => 'T3$$tK3y!24%6',
                                'arrayInfo' => 'public_post_access',
                                'putWhere' => true
                            ],
                            'patch' => [
                                'methodKey' => 'T3$$tK3y!24%6',
                                'arrayInfo' => 'public_post_access'
                            ],
                            'delete' => [
                                'methodKey' => 'T3$$tK3y!24%6',
                                'arrayInfo' => 'public_post_access',
                                'deleteWhere' => true
                            ]
                        ]
                    ]
                ]
            ];
        // # in class end
    // @ just testing an idea end

?>









































































<?php
// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint: root/public/api/v1/
// Check to be sure we are using https communication, if not then force it.
// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
//     $redirectUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     redirect_to($redirectUrl);
//     exit();
// }
// 5- Force HTTPS communication
// 6- HTTP status codes and error returning
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
    "siteRoot" => MAIN_LINK_PATH,
    "mainApiPath" => $rootLink,
    // Routes
    "routes" => [
        // Main Authentication
        "mainAuthentication" => [
            "required" => "If the system has an API key, it is required on all requests. All post requests require an API key, post API key must be sent as a post parameter/argument.",
            "default" => "none",
            "example" => $rootLink . "categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
        ],  
        "generalDocumentationNote" => [
            "documentationNote" => "This is a general message for all endpoints, creation of a new record calls the validation of all properties. Required properties must be provided, all others will be passed through according to their validation. On update, only the fields that are pass-through will be updated.",
            "validationDocumentation" => val_validation_documentation()
        ]   
    ]
];

// get list of class for the api, key => value, posts => Post
$apiClassList_array = $this->pathInterpretation_array;

// * what an end point looks like
// look at root/public/api/v1/

// build api, this list has been checked previously, all should be good to be used in the API
foreach ($apiClassList_array as $routName => $className) {
    // set default array for end point
    $tempEndPoint_arry = [];
    // default path
    $classReference = "/" . $routName;
    // validation columns
    $validationColumns_array = $className::get_validation_columns();

    // get api info for class
    $apiInfo_array = $className::get_api_info();
    // rout info
    $routInfo_array = $apiInfo_array['routs'][$routName];

    // check to see if we have a get http method
    if (isset($routInfo_array['httpMethods']['get']) && isset($routInfo_array['httpMethods']['get']['arrayInfo'])) {
        var_dump($routInfo_array['httpMethods']['get']['arrayInfo']);
        // get api info form the class
        $arrayName = $routInfo_array['httpMethods']['get']['arrayInfo'];
        $classGetApiInfo_arry = $className::$$arrayName;
        
        // build end point array
            // set ["methods"]["availableMethods"]["GET"]
            $tempEndPoint_arry["methods"]["availableMethods"]["GET"] = "The ability to GET information from {$routName}, you can filter results based on the parameters provided.";

            // set ["methods"]["GET"]["parameters"]["noParamsSent"]
            $tempEndPoint_arry["methods"]["GET"]["parameters"]["noParamsSent"]["description"] = "When no parameters are passed then all {$routName} are returned.";
            $tempEndPoint_arry["methods"]["GET"]["parameters"]["noParamsSent"]["example"] = $rootLink . $routName . "/";

            // loop over getApiParameters
            foreach ($classGetApiInfo_arry as $getParameterName => $getParameterValue) {
                // set other ["methods"]["GET"]["parameters"]
                    // check to see if there is a required parameter
                    $required = $getParameterValue["required"] ?? "false";
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
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["required"] = "false";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["type"] = "int";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["description"] = "Returns data from the query result, based off of the page number. If query result was 50, page 2 would return results 11 - 21, Default page number = 1.";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["validation"] = [
                    'name' => "page",
                    'required' => 'yes',
                    'type' => 'int',
                    'num_min'=> 1 
                ];
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["page"]["example"] = $rootLink . "{$routName}?page=1";

                // set ["methods"]["GET"]["parameters"]["perPage"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["required"] = "false";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["type"] = "int";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["description"] = "Specifies the number of results to return with each page of information. By default only 10 are returned per request.";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["validation"] = [
                    'name' => "page",
                    'required' => 'yes',
                    'type' => 'int',
                    'num_min'=> 1 
                ];
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["perPage"]["example"] = $rootLink . "{$routName}?perPage=10";

                // set ["methods"]["GET"]["parameters"]["orderBy"]
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["required"] = "false";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["type"] = "str / list";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["description"] = "Returns data in the order you specify, the example below is not runnable, but shows the theory behind how it is used. Each endpoint uses the database columns of its particular table to do the sorting. By default if you do not specify ascending (::ASC) or descending (::DESC) it will default to ascending (::ASC).";
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["validation"] = [
                    'name' => "orderBy",
                    'required' => 'yes',
                    'type' => 'str',
                    'min'=> 1, 
                    'max' => 300,
                    'html' => 'no'
                ];
                $tempEndPoint_arry["methods"]["GET"]["parameters"]["orderBy"]["example"] = $rootLink . "{$routName}?orderBy=date::DESC,title";
            // // set ["methods"]["GET"]["exampleResponse"], successResponse errorResponse
            //     // get api key if there
            //     // check for authentication, API key, specific to general
            //         // db class list
            //         $dbClassList = DatabaseObject::get_class_list();
            //         // main get key
            //         $mainGetApiKey = DatabaseObject::get_main_get_api_key();
            //         // main general api key
            //         $mainApiKey = DatabaseObject::get_main_api_key();
            //         // class specific key
            //         if (isset($dbClassList[$className]["apiGetKey"])) {
            //             // we have a class specific key
            //             $apiToken = "&authToken=" . $dbClassList[$className]["apiGetKey"];
            //         // GET specific key 
            //         } elseif (isset($mainGetApiKey)) {
            //             $apiToken = "&authToken=" . $mainGetApiKey;
            //         // overall API key
            //         } elseif (isset($mainApiKey)) {
            //             $apiToken = "&authToken=" . $mainApiKey;
            //         // no API key needed, or set
            //         } else {
            //             $apiToken = "";
            //         }
            //     // make the call, get live example, if allowed
            //     if (isset($dbClassList[$className]["apiShowExamples"]) && 
            //         $dbClassList[$className]["apiShowExamples"] == "no") {
            //         // don't run call
            //         echo "got here 1!!!";
            //         $data = "";
            //     } else {
            //         // make the call
            //         $data = file_get_contents($rootLink . $routName . "/?perPage=3{$apiToken}");
            //         echo $rootLink . $routName . "/?perPage=3{$apiToken}";
            //     }
            //     // check to see if we got anything back
            //     $data = trim(strlen($data)) > 10 ? json_decode($data): "no data was found, or no example allowed form " . $rootLink . $routName . "/.";
            //     // set ["methods"]["GET"]["exampleResponse"]["successResponse"]
            //     $tempEndPoint_arry["methods"]["GET"]["exampleResponse"]["successResponse"] = $data;
            //     // to make sure we get content back when we get a 400 error
            //     $context = stream_context_create(array(
            //         'http' => ['ignore_errors' => true],
            //     ));
            //     // make the call, get live example
            //     $data = file_get_contents($rootLink . $routName . "/?error=yes", false, $context);
            //     // check to see if we got anything back
            //     $data = trim(strlen($data)) > 10 ? json_decode($data): "no data was found form " . $rootLink . $routName . "/";
            //     // set ["methods"]["GET"]["exampleResponse"]["errorResponse"]
            //     $tempEndPoint_arry["methods"]["GET"]["exampleResponse"]["errorResponse"] = $data;
    }

    // check to see if post like values are available
    $postLike = false;
    foreach ($routInfo_array['httpMethods'] as $key => $value) {
        if ($key != 'get') {
            $postLike_array[$key] = $routInfo_array['httpMethods'][$key];
            $postLike = true;
        }
    }
            
    // check to see if we have any post like values are available
    if ($postLike) {
        // ! working here ***********************************************************
        // loop over each post like value and ge
        foreach ($postLike_array as $httpMethod => $values) {
            // get api info form the httpMethod
            // $valueName = $values['arrayInfo'];
            $classPostApiInfo_arry = $className::$$values['arrayInfo'];

            // build end point array
                // check for and build corresponding messages

                // loop over and create corresponding messages for the different HTTP methods
                foreach ($postLike_array as $httpMethod => $values) {
                    // if postApiActions show options then continue
                    // * post_api_parameters, located at: root/private/reference_information.php
                    // var_dump($postParameterName);
                    if ($postParameterName == "postApiActions") {
                        // loop through options
                        foreach ($postParameterValue as $key => $value) {
                            // check if the option is yes
                            if ($value == "yes") {
                                switch ($key) {
                                    case 'post': $options_array[] = "POST"; break;
                                    case 'put': $putOption = true; $options_array[] = "PUT"; break;
                                    case 'patch': $options_array[] = "PATCH"; break;
                                    case 'delete': $deleteOption = true; $options_array[] = "DELETE"; break;
                                    case 'putWhere': $putWhereOption = true; break;
                                    case 'deleteWhere': $deleteWhereOption = true; break;
                                }
                            }
                        }
                        
                        // set ["methods"]["availableMethods"]["POST"] and others
                        foreach ($options_array as $option) {
                            // reset documentationNote array
                            $documentationNote = [];
                            // check to see which options are available
                            switch ($option) {
                                case 'POST': 
                                    $text = "The ability to {$option} to {$routName}, insert a record based on the parameters provided."; 
                                    $documentationNote["main"] = ["The method POST allows you to insert a record based on the parameters provided below."];
                                break;
                                case 'PATCH': 
                                    $text = "The ability to {$option} to {$routName}, copy a record while changing some or none of it properties. An id is required for copping a record."; 
                                    $documentationNote["main"] = ["The method PATCH allows you to copy a record based on the id and parameters provided below."];
                                break;
                                case 'PUT': 
                                    $text = "The ability to {$option} to {$routName}, update a record based off an id and the parameters provided."; 
                                    $documentationNote["main"] = ["The method PUT allows you to update a record based on the parameters provided below. An id is required for updating a record, an exception is made if putWhere parameter is available.", "All PUT parameters must be sent in the HTTP body content."];
                                    // check to see if putWhere is available 
                                    if ($putWhereOption) {
                                        $documentationNote["putWhere"] = "The parameter putWhere is available on this endpoint. The parameter putWhere allows you to update all records that meet the condition of the putWhere. You can access the putWhere option by passing in a parameter named putWhere, with the value separated by two colons to indicate column and value. ex putWhere => 'jobTitle::developer'. All records that meet this description will be updated with the content provided.";
                                    }
                                break;
                                case 'DELETE': 
                                    $text = "The ability to {$option} to {$routName}, delete a record based on the id provided."; 
                                    // check to see if putWhere is available 
                                    $documentationNote["main"] = ["The method DELETE allows you to delete a record based on the id provided. An id is required for deleting a record, an exception is made if deleteWhere parameter is available.", "DELETE Parameters id and authToken can be sent in the HTTP body content or the URL as a GET parameter."];
                                    if ($deleteWhereOption) {
                                        $documentationNote["deleteWhere"] = "The parameter deleteWhere is available on this endpoint. The parameter deleteWhere allows you to delete all records that meet the condition of the deleteWhere. You can access the deleteWhere option by passing in a parameter named deleteWhere, with the value separated by two colons to indicate column and value. ex deleteWhere => 'jobTitle::blackSmith'. All records that meet this description will be deleted. The deleteWhere parameter must be sent in the HTTP body. Validation max = 100, min = 1, html = no.";
                                    }
                                break;
                            }
                            $tempEndPoint_arry["methods"]["availableMethods"][$option] = $text;
                            // also added documentation note
                            $tempEndPoint_arry["methods"][$option]["parameters"]["documentationNote"] = $documentationNote;

                            // put in authToken all except GET need it
                            $tempEndPoint_arry["methods"][$option]["parameters"]["authToken"]["required"] = "required"; 
                            // set type 
                            $tempEndPoint_arry["methods"][$option]["parameters"]["authToken"]["type"] = "str"; 
                            // set description   
                            $tempEndPoint_arry["methods"][$option]["parameters"]["authToken"]["description"] = "An authToken is required for all $option requests.";
                        }
                        
                        // check to set id in PUT parameters
                        if ($putOption) {
                            $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["required"] = "required"; 
                            // set type 
                            $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["type"] = "int"; 
                            // set description   
                            $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["description"] = "The id is required for updating a record, an exception is made if the putWhere parameter is available.";
                            $tempEndPoint_arry["methods"]["PUT"]["parameters"]["id"]["validation"] = $validationColumns_array['id'] ?? "Validation can not be viewed";
                        }

                        // check to set id in PATCH parameters
                        if ($putOption) {
                            $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["required"] = "required"; 
                            // set type 
                            $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["type"] = "int"; 
                            // set description   
                            $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["description"] = "The id is required for copping a record.";
                            $tempEndPoint_arry["methods"]["PATCH"]["parameters"]["id"]["validation"] = $validationColumns_array['id'] ?? "Validation can not be viewed";
                        }

                        // check to see if delete is a viable option
                        if ($deleteOption) {
                            $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["required"] = "required"; 
                            // set type 
                            $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["type"] = "int"; 
                            // set description   
                            $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["description"] = "The id is required for deleting a record, an exception is made if the deleteWhere parameter is available";
                            $tempEndPoint_arry["methods"]["DELETE"]["parameters"]["id"]["validation"] = $validationColumns_array['id'] ?? "Validation can not be viewed";
                        }
                        continue;
                    }
                }


        }

        // default options
        $options_array = [];
        $deleteOption = false;
        $putOption = false;
        $putWhereOption = false;
        $deleteWhereOption = false;
        
        




            // loop over postApiParameters
            foreach ($classPostApiInfo_arry as $postParameterName => $postParameterValue) {
                // set other ["methods"]["POST"]["parameters"] and others
                foreach ($options_array as $option) {
                    // skip over delete
                    if ($option == "DELETE") { continue; }
                    if ($postParameterName == "id") { continue; }
                    // check if validation says that it's required
                    $required = $postParameterValue["required"] ?? "false";
                    // check to see if there is a required parameter, if in post
                    if ($option == "POST") {
                        if (isset($validationColumns_array[$postParameterName]["required"])) { 
                            $required = "true"; 
                        }
                    }
                    $tempEndPoint_arry["methods"][$option]["parameters"][$postParameterName]["required"] = $required; 
                    // set type 
                    $tempEndPoint_arry["methods"][$option]["parameters"][$postParameterName]["type"] = implode(" / ", $postParameterValue["type"]); 
                    // set description   
                    $tempEndPoint_arry["methods"][$option]["parameters"][$postParameterName]["description"] = $postParameterValue["description"]; 
                    // set validation specs
                    $tempEndPoint_arry["methods"][$option]["parameters"][$postParameterName]["validation"] = $postParameterValue["validation"] ?? $validationColumns_array[$postParameterName] ?? "Validation can not be viewed"; 

                }
            }

            foreach ($options_array as $option) {
                // set ["methods"]["POST"]["exampleResponse"], successResponse errorResponse, and PUT, PATCH, DELETE
                // make the call, POST live example
                $data = post($rootLink . $routName . "/?success=yes", [], $option);
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "No response was given from " . $rootLink . $routName . "/";
                // set ["methods"][$option]["exampleResponse"]["successResponse"]
                $tempEndPoint_arry["methods"][$option]["exampleResponse"]["successResponse"] = $data;
                
                // make the call, get live example
                // make the call, POST live example
                $data = post($rootLink . $routName . "/?error=yes", [], $option);
                // check to see if we got anything back
                $data = trim(strlen($data)) > 10 ? json_decode($data): "No response was given from " . $rootLink . $routName . "/";
                // set ["methods"][$option]["exampleResponse"]["errorResponse"]
                $tempEndPoint_arry["methods"][$option]["exampleResponse"]["errorResponse"] = $data;
            }
    }
    
    // check to see if we need to add to the documentation
    if ($tempEndPoint_arry) {
        // check to see whether not the route has a get and post
            $quickViewOFRoutesAvailable = [];

            // check to see if we have get stuff
            if ($className::get_get_api_parameters()) {
                $quickViewOFRoutesAvailable[] = "GET";
            }

            // check to see if we have post stuff
            if ($className::get_post_api_parameters()) {
                foreach ($options_array as $option) {
                    // set options
                    $quickViewOFRoutesAvailable[] = $option;

                    // check to see if putWhere or deleteWhere
                    if ($option == "PUT" && $putWhereOption) {
                        $quickViewOFRoutesAvailable[] = "PUT::putWhere";
                    } elseif ($option == "DELETE" && $deleteWhereOption) {
                        $quickViewOFRoutesAvailable[] = "DELETE::deleteWhere";
                    }
                }
            }

        // set up quick view documentation element
        $apiRoot["routes"]['quickViewOFRoutesAvailable'][$classReference] = $quickViewOFRoutesAvailable;
        // add class reference/endpoint documentation
        $apiRoot["routes"][$classReference] = $tempEndPoint_arry;
    }
}

// JSON encode the data structure and return it
$jsonData = json_encode($apiRoot);
echo $jsonData;
?>