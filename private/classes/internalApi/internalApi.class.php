
















<?php
    // TODO: get_class_methods() use this for docs
    // * documentation located at: root/private/rules_docs/internalApi.js
    class InternalApi {
        // Request
            // Builds request and that makes it
            public static function request(string $instructions = "") {
                // Setting default values for the page to work
                $data['errors'] = [];

                // Check to see if $instructions are json, It will return to null if it cannot be compiled into json
                $instructions = json_decode($instructions, true);
                if ($instructions && is_array($instructions)) {
                    // see what type of request it is
                    foreach ($instructions as $requestName => $request) {
                        // get info, type and method
                        $type = $request['type'] ?? null;
                        $method = $request['method'] ?? "no_do_not_have_it";
                        if ($type) {
                            if ($type === 'getter') {
                                // make a new getter object
                                $Getter = new Getter;
                                // check to see if it has the method requested
                                if (method_exists($Getter, $method)) {
                                    // process request
                                    $requestInfo = $Getter->request($request);
                                    // check for errors
                                    $requestErrors = $requestInfo['errors'] ?? [];
                                    // TODO: if errors is there switch it to its proper place and unset it
                                    // check for errors
                                    if (!$requestErrors) {
                                        // set request status
                                        $data['content'][$requestName]['success'] = true;
                                        $data['content'][$requestName]['statusCode'] = 200;
                                        // set content 
                                        $data['content'][$requestName]['content'] = $requestInfo;
                                        // set errors
                                        $data['content'][$requestName]['errors'] = [];
                                        // set requestsAccepted 
                                        $data['requestsAccepted'][] = $requestName;
                                    } else {
                                        // unset $requestInfo['errors']
                                        unset($requestInfo['errors']);
                                        // set request status
                                        $data['content'][$requestName]['success'] = false;
                                        $data['content'][$requestName]['statusCode'] = 400;
                                        // loop over errors and put them in the right spot
                                        foreach ($requestErrors as $error) {
                                            $data['errors'][$requestName][] = $error;
                                            // set request with errors
                                            $data['content'][$requestName]['errors'][] = $error;
                                        }
                                        // set requestsNotAccepted 
                                        $data['requestsNotAccepted'][] = $requestName;
                                    }
                                } else {
                                    $data['errors'][] = "The request with the name of {$requestName} was not past a valid method. Method requested \"{$method}\".";
                                }
                            } elseif ($type === 'setter') {
                                # code...
                            } elseif ($type === 'makeRequest') {
                                # code...
                            } else {
                                $data['errors'][] = "The request with the name of {$requestName} has an invalid \"type\" of \"{$type}\" key value pair.";
                            }
                        } else {
                            $data['errors'][] = "Each request requires a type, method, and data key value pair. the request with the name of {$requestName} was missing a \"type\" key value pair.";
                        }
                    }
                } else {
                    $data['errors'][] = "Instructions were not given an a json format, and/or could not be compiled into an array of requests, please check your code and try again.";
                }

                // output request response
                self::internalApi_message($data);
            }

        // Make request
            // ! Note that direct use of this function should only be done in development you should create a setter or getter to perform the queries that you're doing.You put your system at risk if you do anything other than that.
            // The actual request that is made

        // Error message
        public static function internalApi_message(array $data = []) {
            include("internalApiMessage.php");  
        }

        // @ Helper functions start
            // Permission check
            // Internal widget check
            // Pull through session check
            // Pull through session or token check
            // Pull through token check
            // check for columns
            // Check for methods
            // Check for classes
        // @ Helper functions end
    }
    // Output JSON to page
    // $jsonData = json_encode($requestedData);
    // echo $jsonData;


    // Add to main settings trait
        // Internal API only for server use
        // internal API make request directly only for development
?>
