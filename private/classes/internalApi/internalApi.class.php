<?php
    // TODO: get_class_methods() use this for docs
    // * documentation located at: root/private/rules_docs/internalApi.js
    class InternalApi {
        // Request
            // Builds request and that makes it
            public static function request(string $instructions = "") {
                // Setting default values for the page to work
                $data = self::get_empty_data_array();

                // Check to see if $instructions are json, It will return to null if it cannot be compiled into json, "true" turns the json into an associative array 
                $instructions = json_decode($instructions, true);
                if ($instructions && is_array($instructions)) {
                    // see what type of request it is
                    foreach ($instructions as $requestName => $request) {
                        // separate type/class from request/method
                        // could be a string or array, process accordingly
                        if (is_array($request)) {
                            // var_dump($requestName, $request);
                        } else {
                            // var_dump($requestName, $request);
                        }
                        
                        // if authentication token, set as a global for all requests
                        // TODO: make it happen
                        if ($requestName === 'authToken') {
                            // set authentication as a global variable
                            continue;
                        }
                        // get info, type and method
                        $type = $request['type'] ?? null;

                        if ($type) {
                             // type/route/paths: registeredClass, getter, setter, makeRequest, devTool, authToken.
                            if ($type === 'registeredClass') {
                                # code...
                            } elseif ($type === 'getter') {
                                // run getter request
                                $data = self::check_prep_return('Getter', $request, $requestName, $data);
                            } elseif ($type === 'setter') {
                                # code...
                            } elseif ($type === 'makeRequest') {
                                # code...
                            } elseif ($type === 'documentation') {
                                # code...
                            } elseif ($type === 'devTool') {
                                // run devTool request check_prep_return
                                $data = self::check_prep_return('DevTool', $request, $requestName, $data);
                            } elseif ($type === 'authToken') {
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
        // TODO: use or get ride of
        // @ type/route/paths start
           
        // @ type/route/paths end

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
            // # check prep return the request
            private static function check_prep_return($type = 'Getter', $request, $requestName, $data) {
                // set defaults
                $method = $request['method'] ?? "no_do_not_have_it";
                $requestData = $request['data'] ?? null;
                
                // check to see if it has the method requested
                if (method_exists($type, $method)) {
                    // process request
                    $requestInfo = $type::request($method, $requestData);
                    // check for errors
                    $requestErrors = $requestInfo['errors'] ?? [];
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
                            $data['errors'][] = "{$requestName}: {$error}";
                            // set request with errors
                            $data['content'][$requestName]['errors'][] = $error;
                        }
                        // set requestsNotAccepted 
                        $data['requestsNotAccepted'][] = $requestName;
                    }
                } else {
                    $data['errors'][] = "The request with the name of {$requestName} was not past a valid method. Method requested \"{$method}\".";
                    // set requestsNotAccepted 
                    $data['requestsNotAccepted'][] = $requestName;
                }

                // return data
                return $data;
            }

            // # get empty data array
            private static function get_empty_data_array() {
                // set default variables
                $data_array['errors'] = [];
                $data_array['content'] = [];
                $data_array['requestsAccepted'] = [];
                $data_array['requestsNotAccepted'] = [];

                // return array
                return $data_array;
            }

            // # merge data arrays
            // TODO: take it out if we don't use it
            private static function merge_data_arrays(array $data_array, array $temp_array) {
                // merge arrays
                // loop over errors and put them in the right spot
                foreach ($temp_array['errors'] as $value) {
                    $data_array['errors'][] = $value;
                }
                // loop over content and put them in the right spot
                foreach ($temp_array['content'] as $key => $value) {
                    $data_array['content'][$key] = $value;
                }
                // loop over requestsAccepted and put them in the right spot
                foreach ($temp_array['requestsAccepted'] as $key => $value) {
                    $data_array['requestsAccepted'][] = $value;
                }
                // loop over requestsNotAccepted and put them in the right spot
                foreach ($temp_array['requestsNotAccepted'] as $key => $value) {
                    $data_array['requestsNotAccepted'][] = $value;
                }

                // return merged array
                return $data_array;
            }
        // @ Helper functions end
    }
    // Output JSON to page
    // $jsonData = json_encode($requestedData);
    // echo $jsonData;


    // Add to main settings trait
        // Internal API only for server use
        // internal API make request directly only for development
?>
