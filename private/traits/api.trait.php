<?php
    // The trait for the API
    // TODO: remove all echoes and var dumps
    // Return accepted vs rejected parameters
    trait Api {
        // @ main methods start
            // # Method for getting api info from the DB
            static function get_api_info($className, $routName) {
                // make class info array
                $dbKeyInfo_array['mainApiKey'] = static::get_main_api_key();
                $dbKeyInfo_array['mainGetApiKey'] = static::get_main_get_api_key();
                $dbKeyInfo_array['mainPostApiKey'] = static::get_main_post_api_key();
                // get api info for class
                $apiInfo_array = $className::get_api_class_info();
                // rout info
                $routInfo_array = $apiInfo_array['routes'][$routName];
                // set static::$getApiParameters/arrayInfo parameters, check to make sure we actually have I get HTTP method
                if (isset($routInfo_array['httpMethods']['get'])) {
                    $arrayName = $routInfo_array['httpMethods']['get']['arrayInfo'];
                    // check if it's a property/array or a function  
                    if (contains($arrayName, '_')) {
                        $getApiParameters = static::$arrayName();
                    } else {
                        $getApiParameters = static::$$arrayName;
                    }
     
                    // validate incoming parameters
                    $prepApiData_array = static::validate_and_prep_api_parameters($_GET, $className, $routInfo_array, $getApiParameters);
    
                    // check for api authentication, we are authenticating afterwards as the return message needs more data
                    if (!(static::apiGetAuthentication($dbKeyInfo_array, $className, $apiInfo_array, $routInfo_array))) {
                        $prepApiData_array['errors'][] = "You are not authorized to access this information.";
                    } else {
                        // except authentication token is sent through
                        $prepApiData_array['paramsAccepted']['authToken'] = "authToken was accepted or not required.";
                        // hide authToken from showing up in the documentation
                        $_GET['authToken'] = "****authToken value hidden*******";
                    }
    
                    // thorough error
                    if (isset($_GET['error'])) {
                        $prepApiData_array['errors'][] = "You have purposely triggered an error message with a GET parameter error";
                        $prepApiData_array['paramsAccepted']['error'] = "error = triggered";
                    }
                } else {
                    $prepApiData_array['errors'][] = "The GET HTTP method is not available for this endpoint.";
                }

                // prep message
                $responseData = static::prep_api_message($prepApiData_array, $routInfo_array, $routName);

                // Package the response into json and return it
                $jsonData = json_encode($responseData);
                return $jsonData;
            }

            // # Method for processing POST, PUT, PATCH, DELETE api info
            static function get_post_api_info($className, $routName) {
                // make class info array
                $dbKeyInfo_array['mainApiKey'] = static::get_main_api_key();
                $dbKeyInfo_array['mainPostApiKey'] = static::get_main_post_api_key();
                // check to see which path to send it down
                $request = $_SERVER['REQUEST_METHOD'];

                // get api info for class
                $apiInfo_array = $className::get_api_class_info();
                $routInfo_array = $apiInfo_array['routes'][$routName];
                // This should get all post like requests POST, PUT, PATCH, DELETE
                if (count($_POST) >= 1) {
                    $postVars_array = $_POST;
                } else {
                    parse_str(file_get_contents("php://input"), $postVars_array);
                }
                // default arrays
                $prepApiData_array = self::get_empty_data_array();
                $prepApiData_array['postVars_array'] = $postVars_array;
                // get authToken
                $authToken = $_POST['authToken'] ?? $postVars_array['authToken'] ?? $_GET['authToken'] ?? "";

                // check for error and success trigger
                $errorTrigger = $_GET['error'] ?? "";
                if ($errorTrigger) {
                    $prepApiData_array['errors'][] = "You have triggered an error with the error parameter.";
                    $prepApiData_array['paramsAccepted']['error'] = "error parameter = triggered";
                }
                $successTrigger = $_GET['success'] ?? "";
                if ($successTrigger) {
                    $prepApiData_array['content']['message'] = "You Triggered the success message with the success parameter.";
                    $prepApiData_array['paramsAccepted']['success'] = "success parameter = triggered";
                }

                // check for triggers, if not come in
                if (!($errorTrigger || $successTrigger) && !$prepApiData_array['errors']) {
                    // check for api authentication
                    if (!(static::apiPostAuthentication($dbKeyInfo_array, $apiInfo_array, $routInfo_array, $className, $authToken, $request))) {
                        $prepApiData_array['errors'][] = "You are not authorized to access this information.";
                    } else {
                        // except authentication token is sent through
                        $prepApiData_array['paramsAccepted']['authToken'] = "authToken was accepted.";
                    }
    
                    // check for errors
                    if (!$prepApiData_array['errors']) {
                        // validate and perform request 
                        $tempData_array = static::validate_and_perform_request($request, $postVars_array, $className, $routInfo_array, $routName);
                        // merge arrays
                        $prepApiData_array = static::merge_data_arrays($prepApiData_array, $tempData_array);
                    }
                }

                // prep message
                $responseData = static::prep_post_api_message($prepApiData_array, $routName);
            }
        // @ main methods end

        // @ prep methods start
            // @ get prep start
                // # validate_and_prep_api_parameters() Validate and prep the API parameters
                static function validate_and_prep_api_parameters(array $getParams_array, $className, array $routInfo_array, array $getApiParameters) {
                    // Prepare the array we will use to hold our prepped API data
                    $prepApiData_array['errors'] = [];
                    
                    // Prep and validate the parameter/GET options // also sorts out not accepted parameters
                    $temp_array_1 = static::prep_sorting_options($getParams_array, $routInfo_array, $getApiParameters);
                    
                    // add info to $prepApiData_array from $temp_array_1
                        // Add any errors to our array
                        foreach($temp_array_1['errors'] as $error) {
                            $prepApiData_array['errors'][] = $error;
                        }
                        // add params accepted
                        foreach($temp_array_1['paramsAccepted'] as $key => $param) {
                            $prepApiData_array['paramsAccepted'][$key] = $param;
                        }
                        // add params not accepted
                        foreach($temp_array_1['paramsNotAccepted'] as $param) {
                            $prepApiData_array['paramsNotAccepted'][] = $param;
                        }
                        // Add the prepped sorting options to the array
                        $prepApiData_array['sqlOptions']['sortingOptions'][] = $temp_array_1['data']['sortingOptions'];

                        // Add the extra things we need for some overhead
                        $prepApiData_array['extra'] = [
                            'page' => $temp_array_1['page'],
                            'perPage' => $temp_array_1['perPage']
                        ];

                    // Prep and validate the where options
                    $temp_array_2 = static::prep_where_options($getParams_array, $getApiParameters, $routInfo_array, $className);

                    // add info to $prepApiData_array from $temp_array_2
                        // Add any errors to our array
                        foreach($temp_array_2['errors'] as $error) {
                            $prepApiData_array['errors'][] = $error;
                        }
                        // add params accepted
                        foreach($temp_array_2['data']['paramsAccepted'] as $key => $param) {
                            $prepApiData_array['paramsAccepted'][$key] = $param;
                        }
                        // Add the prepped where options to the array
                        foreach($temp_array_2['data']['whereOptions'] as $options) {
                            $prepApiData_array['sqlOptions']['whereOptions'][] = $options;
                        }
                        // add columnOptions
                        $prepApiData_array['sqlOptions']['columnOptions'] = $temp_array_2['data']['columnOptions'];
                        // add propertyExclusions
                        $prepApiData_array['propertyExclusions'] = $temp_array_2['data']['propertyExclusions'];
                        // add functionCall
                        $prepApiData_array['functionCall'] = $temp_array_2['data']['functionCall'];
                    
                    // Return the array
                    return $prepApiData_array;
                }

                // # prep_sorting_options() For validating and prepping the sorting options
                static private function prep_sorting_options($getParams_array, $routInfo_array, $getApiParameters) {
                    // An array to hold the sorting options
                    $options_array['data'] = [];
                    $options_array['errors'] = [];
                    $options_array['paramsAccepted'] = [];
                    $options_array['paramsNotAccepted'] = [];

                    // check for properties that should come through that have not come through, run validation on them
                    foreach ($getApiParameters as $key => $value) {
                        // do a check to see if we have any required fields
                        if (isset($value['required']) && $value['required'] && !isset($getParams_array[$key])) {
                            $options_array['errors'][] = "{$key} is a required parameter that must be sent in order to access this GET endpoint";
                        }
                    }

                    // Loop through the params array to get all of the sorting options
                    foreach($getParams_array as $paramKey => $paramValue) {
                        // make sorting options array, possible values
                        $sortingOptions_array = ['perPage', 'page', 'orderBy'];

                        // Check if the parameter is defined in the class, else put in paramsNotAccepted
                        if (isset($getApiParameters[$paramKey]) || 
                            in_array($paramKey, $sortingOptions_array) ||
                            $paramKey == "authToken" ||
                            $paramKey == "error") {
                            // Check if it is a sorting option
                            if (in_array($paramKey, $sortingOptions_array)) {

                                // Check if the value is page or perPage then add it accordingly
                                if ($paramKey == 'page' || $paramKey == 'perPage') {
                                    // these parameters are global to all, create global validation
                                    $validation = [
                                        'name' => "$paramKey",
                                        'required' => 'yes',
                                        'type' => 'int', // type of int
                                        'num_min'=> 1 // number min value
                                    ];
                                    // reset validation array
                                    $validationError_array = [];
                                    // Validate the value
                                    $validationError_array = val_validation($paramValue, $validation, 'parameter');
                                    // check for errors
                                    if (!($validationError_array)) {
                                        // check to see if it is page or perPage
                                        // Add it to the options array
                                        if ($paramKey == 'page') {
                                            $options_array['page'] = $paramValue;
                                        } else {
                                            $options_array['perPage'] = $paramValue;
                                        }
                                        // Make note of the parameter
                                        $options_array['paramsAccepted'][$paramKey] = $paramValue;
                                    // add the error 
                                    } else {
                                        // loop over errors and put them in the right spot
                                        foreach ($validationError_array as $value) {
                                            $options_array['errors'][] = $value;
                                        }
                                    }

                                // The value must be orderBy
                                } else {
                                    // validate orderby
                                        // this parameters is global to all, create global validation
                                        $validation = [
                                            'name' => "$paramKey",
                                            'required' => 'yes',
                                            'type' => 'str', // type of int
                                            'min'=> 1, // string length
                                            'max' => 300, // string length
                                            'html' => 'no'
                                        ];
                                        // reset validation array
                                        $validationError_array = [];
                                        // Validate the value
                                        $validationError_array = val_validation($paramValue, $validation, 'parameter');
                                    // check for errors
                                    if (!($validationError_array)) {
                                        // Check to see if we have a list of values separated by "," orderBy=date::DESC,title
                                        if (is_list($paramValue, ",")) {
                                            // Get the values from the list
                                            $firstList_array = split_string_by_separator($paramValue, ",");

                                            // For each item in our list check to see if it has a ::
                                            foreach($firstList_array as $item) {
                                                if (is_list($item, "::")) {
                                                    // make list to array
                                                    $secondList_array = split_string_by_separator($item, "::");

                                                    // check to see if it is a real database field
                                                    if (in_array($secondList_array[0], static::get_class_db_columns())) {
                                                        // is it DESC, else make default, sql = date DESC
                                                        if ($secondList_array[1] == "DESC" || $secondList_array[1] == "desc") {
                                                            $sqlString = $secondList_array[0] . " DESC";
                                                        } else {
                                                            $sqlString = $secondList_array[0];
                                                        }
                                                        // add sql to the return data
                                                        $options_array['data']['orderBy'][] = $sqlString;
                                                        // add to paramsAccepted
                                                        $options_array['paramsAccepted'][$paramKey][$secondList_array[0]] = $sqlString;
                                                    } else {
                                                        $options_array['errors'][] = $secondList_array[0] . " is not a valid value for the orderBy parameter!";
                                                    }    

                                                // just do normal check and SQL preparation
                                                } else {
                                                    // check to see if it is a real database field
                                                    if (in_array($item, static::get_class_db_columns())) {
                                                        // make default, sql = date 
                                                        $sqlString = $item;
                                                        // add sql to the return data
                                                        $options_array['data']['orderBy'][] = $sqlString;
                                                        // add to paramsAccepted
                                                        $options_array['paramsAccepted'][$paramKey][$item] = $sqlString;
                                                    } else {
                                                        $options_array['errors'][] = "{$item} is not a valid value for the orderBy parameter!";
                                                    }    
                                                }
                                            }

                                        // this means just one value made it through do the necessary checks
                                        }  else {
                                            // check to see if it has a ::
                                            if (is_list($paramValue, "::")) {
                                                // make list to array
                                                $paramValue_array = split_string_by_separator($paramValue, "::");

                                                // check to see if it is a real database field
                                                if (in_array($paramValue_array[0], static::get_class_db_columns())) {
                                                    // is it DESC, else make default, sql = date DESC
                                                    if ($paramValue_array[1] == "DESC" || $paramValue_array[1] == "desc") {
                                                        $sqlString = $paramValue_array[0] . " DESC";
                                                    } else {
                                                        $sqlString = $paramValue_array[0];
                                                    }
                                                    // add sql to the return data
                                                    $options_array['data']['orderBy'][] = $sqlString;
                                                    // add to paramsAccepted
                                                    $options_array['paramsAccepted'][$paramKey][$paramValue_array[0]] = $sqlString;
                                                } else {
                                                    $options_array['errors'][] = $paramValue_array[0] . " is not a valid value for the orderBy parameter!";
                                                }    

                                            // just do normal check and SQL preparation
                                            } else {
                                                // check to see if it is a real database field
                                                if (in_array($paramValue, static::get_class_db_columns())) {
                                                    // make default, sql = date 
                                                    $sqlString = $paramValue;
                                                    // add sql to the return data
                                                    $options_array['data']['orderBy'][] = $sqlString;
                                                    // add to paramsAccepted
                                                    $options_array['paramsAccepted'][$paramKey][$paramValue] = $sqlString;
                                                } else {
                                                    $options_array['errors'][] = "{$paramValue} is not a valid value for the orderBy parameter!";
                                                }    
                                            }
                                        }
                                    } else {
                                        // loop over errors and put them in the right spot
                                        foreach ($validationError_array as $value) {
                                            $options_array['errors'][] = $value;
                                        }
                                    }
                                }
                            }

                        // The Parameter given is not accepted
                        } else {
                            $options_array['paramsNotAccepted'][$paramKey] = "{$paramKey} is not a valid parameter!";
                        }
                    }

                    // Use the default value if the page is not defined
                    if (!isset($options_array['page'])) {
                        // Set the values
                        $options_array['page'] = 1;
                    }

                    // Use the default value if the perPage is not defined
                    if (!isset($options_array['perPage'])) {
                        // Set the values
                        $options_array['perPage'] = 10;
                    }

                    // convert to LIMIT
                    $limit = $options_array['perPage'];
                    // add LIMIT
                    $options_array['data']['LIMIT'] = 'LIMIT ' . $limit;
                    // convert to OFFSET
                    $offset = (($options_array['page'] - 1) * $limit);
                    // add OFFSET
                    $options_array['data']['OFFSET'] = 'OFFSET ' . $offset;

                    // Make sure the limit and offset are the correct values in our prepped data array
                    // also order the values to be in the correct order for the MySQL query
                    // The order must be 1 - ORDER BY, 2 - LIMIT, 3 - OFFSET
                    // make the sql sortingOptions
                    // check to see if there are ORDER BY options
                    $sql = "";
                    if (isset($options_array['data']['orderBy'])) {
                        $sql .= "ORDER BY " . implode(', ',$options_array['data']['orderBy']) . " ";
                    }
                    $sql .= $options_array['data']['LIMIT'] . ' ' . $options_array['data']['OFFSET'];
                    $options_array['data']['sortingOptions'] = $sql;

                    // Return the array
                    return $options_array;
                }

                // # prep_where_options() For validating and prepping the where options
                static private function prep_where_options(array $getParams_array, array $getApiParameters, array $routInfo_array, $className) {
                    // An array to hold the where options
                    $options_array['data'] = [];
                    $options_array['errors'] = [];
                    $options_array['data']['paramsAccepted'] = [];
                    $options_array['data']['whereOptions'] = [];
                    $options_array['data']['functionCall'] = [];
                    $options_array['data']['columnOptions'] = [];
                    $options_array['data']['propertyExclusions'] = [];

                    // Loop through the parameters and get all of the where options
                    foreach($getParams_array as $paramKey => $paramValue) {

                        // Check if the parameter is defined in the class
                        if (isset($getApiParameters)) {

                            // Make sure the parameter is not a extraOptions option, we also want to skip all other options if not in our arrayInfo/getApiParameters
                            if (isset($getApiParameters[$paramKey]) && !in_array('extraOptions', $getApiParameters[$paramKey]['refersTo'])) {

                                // Check if the data is a list
                                if (is_list($paramValue, ",")) {

                                    // Check if we accept a list as a data type if not then add the error
                                    if (!isset($getApiParameters[$paramKey]['connection']['list'])) {
                                        $options_array['errors'][] = "{$paramKey} does not accept a list of values!";

                                    // The data is accepted as a list type so sort through the list
                                    } else {
                                        // Turn the list into an array and add it to our list of whereOptions
                                        $newList_array = split_string_by_separator($paramValue, ",");

                                        // Check if the parameter uses LIKE or IN
                                        if ($getApiParameters[$paramKey]['connection']['list'] == 'LIKE' || 
                                            $getApiParameters[$paramKey]['connection']['list'] == 'like') {
                                            // Prep the beginning of the string for holding our list of values
                                            $valueList = "(";
                                            // loop over each value
                                            foreach($newList_array as $listItem) {
                                                // Validate the value
                                                $errors = self::validate_api_params($listItem, $paramKey, $getApiParameters);

                                                // If the parameter accepts a date value then format the date correctly
                                                if (in_array('date', $getApiParameters[$paramKey]['type'])) {
                                                    // Get the new format for the list item
                                                    $newDate = format_date($listItem);

                                                    // Set the correct format for the list item
                                                    $listItem = $newDate;
                                                }
                        
                                                // If there are errors then add them to the errors array
                                                if (!empty($errors)) {
                                                    // Add each error from the validation error array
                                                    foreach($errors as $err) {
                                                        $options_array['errors'][] = $err;
                                                    }
                        
                                                // No errors were found add to our sql prepped list
                                                } else {
                                                    // If at not at the end of the array add the OR
                                                    // Also add the % for the search
                                                    if ($listItem !== end($newList_array)) {
                                                        // do it for each refersTo
                                                        foreach ($getApiParameters[$paramKey]['refersTo'] as $refersTo) {
                                                            $valueList .= "{$refersTo} LIKE '%" . self::db_escape($listItem) . "%' OR "; 
                                                        }
                        
                                                    // end of foreach
                                                    } else {
                                                        foreach ($getApiParameters[$paramKey]['refersTo'] as $refersTo) {
                                                            if ($refersTo !== end($getApiParameters[$paramKey]['refersTo'])) {
                                                                $valueList .= "{$refersTo} LIKE '%" . self::db_escape($listItem) . "%' OR ";
                                                            } else {
                                                                # code...
                                                                $valueList .= "{$refersTo} LIKE '%" . self::db_escape($listItem) . "%' "; 
                                                            }
                                                        } 
                                                    }
                                                    // end it off right
                                                    // add to paramsAccepted
                                                    $options_array['data']['paramsAccepted'][$paramKey][] = "{$paramKey} = {$listItem}";
                                                }
                                            }
                                            $valueList .= ")";
                                            // Add the sql prepped list to the whereOptions
                                            $options_array['data']['whereOptions'][] = $valueList;

                                        // list for in
                                        } elseif ($getApiParameters[$paramKey]['connection']['list'] == 'IN' ||
                                            $getApiParameters[$paramKey]['connection']['list'] == 'in') {
                                            // Prep the beginning of the string for holding our list of values
                                            $valueList = "( ";
                                            foreach($newList_array as $listItem) {
                                                // Validate the value
                                                $errors = self::validate_api_params($listItem, $paramKey, $getApiParameters);

                                                // If the parameter accepts a date value then format the date correctly
                                                if (in_array('date', $getApiParameters[$paramKey]['type'])) {
                                                    // Get the new format for the list item
                                                    $newDate = format_date($listItem);

                                                    // Set the correct format for the list item
                                                    $listItem = $newDate;
                                                }
                        
                                                // If there are errors then add them to the errors array
                                                if (!empty($errors)) {
                                                    // Add each error from the validation error array
                                                    foreach($errors as $err) {
                                                        $options_array['errors'][] = $err;
                                                    }
                        
                                                // No errors were found add to our sql prepped list
                                                } else {
                                                    // If at not at the end of the array add the comma
                                                    if ($listItem !== end($newList_array)) {
                                                        $valueList .= "'" . self::db_escape($listItem) . "', ";
                        
                                                    // If at the end of the array then add a parentheses instead
                                                    } else {
                                                        $valueList .= "'" . self::db_escape($listItem) . "' )";
                                                    }
                                                    // add to paramsAccepted
                                                    $options_array['data']['paramsAccepted'][$paramKey][] = "{$paramKey} = {$listItem}";
                                                }
                                            }
                                            // Add the sql prepped list to the whereOptions
                                            // do it for each refersTo
                                            foreach ($getApiParameters[$paramKey]['refersTo'] as $refersTo) {
                                                $options_array['data']['whereOptions'][] = "{$refersTo} IN {$valueList}";
                                            }
                                        }
                    
                                    }

                                // The data is not a list
                                } else {
                                    // Validate the value
                                    $errors = self::validate_api_params($paramValue, $paramKey, $getApiParameters);

                                    // If the parameter accepts a date value then format the date correctly
                                    
                                    if (in_array('date', $getApiParameters[$paramKey]['type'])) {
                                        // Get the new format for the list item
                                        $newDate = format_date($paramValue);
                                        // Set the correct format for the parameter Value
                                        $paramValue = $newDate;
                                    }

                                    // If there are errors then add the errors to the array
                                    if (!empty($errors)) {
                                        // Add each error from the validation error array
                                        foreach($errors as $err) {
                                            $options_array['errors'][] = $err;
                                        }

                                    // No errors were found add the data to the array
                                    } else {

                                        // set connectionType
                                        $connectionType = $getApiParameters[$paramKey]['connection'][$getApiParameters[$paramKey]['type'][0]];

                                        // set up valueList
                                        if (count($getApiParameters[$paramKey]['refersTo']) > 1) {
                                            $valueList = "(";
                                        } else {
                                            $valueList = "";
                                        }

                                        // check for special delimiters LIKE, IN
                                            // If the connectionType is a like then add the % to the value for the SQL prep
                                            if ($connectionType == "like" || $connectionType == "LIKE") {
                                                // do it for each refersTo
                                                foreach ($getApiParameters[$paramKey]['refersTo'] as $refersTo) {
                                                    if ($refersTo !== end($getApiParameters[$paramKey]['refersTo'])) {
                                                        $valueList .= "{$refersTo} LIKE '%" . self::db_escape($paramValue) . "%' OR ";
                                                    } else {
                                                        $valueList .= "{$refersTo} LIKE '%" . self::db_escape($paramValue) . "%' "; 
                                                    }
                                                } 
                                            }

                                            // If the param key is a search then add the % to the value for the SQL prep
                                            elseif ($connectionType == "in" || $connectionType == "IN") {
                                                // just make it an equal sign because we should only have one value
                                                // do it for each refersTo
                                                foreach ($getApiParameters[$paramKey]['refersTo'] as $refersTo) {
                                                    if ($refersTo !== end($getApiParameters[$paramKey]['refersTo'])) {
                                                        $valueList .= "{$refersTo} = '" . self::db_escape($paramValue) . "' OR ";
                                                    } else {
                                                        $valueList .= "{$refersTo} = '" . self::db_escape($paramValue) . "' "; 
                                                    }
                                                } 
                                            }

                                            // this should catch all else
                                            else {
                                                // do it for each refersTo
                                                foreach ($getApiParameters[$paramKey]['refersTo'] as $refersTo) {
                                                    if ($refersTo !== end($getApiParameters[$paramKey]['refersTo'])) {
                                                        $valueList .= "{$refersTo} {$connectionType} '" . self::db_escape($paramValue) . "' OR ";
                                                    } else {
                                                        $valueList .= "{$refersTo} {$connectionType} '" . self::db_escape($paramValue) . "' "; 
                                                    }
                                                } 
                                            }

                                        // posable end to valueList
                                        if (count($getApiParameters[$paramKey]['refersTo']) > 1) {
                                            $valueList .= ")";
                                        }

                                        // The parameter was found, add the info needed to our array
                                        // Add the sql prepped list to the whereOptions
                                        $options_array['data']['whereOptions'][] = $valueList;
                                        // add to paramsAccepted
                                        $options_array['data']['paramsAccepted'][$paramKey] = "{$paramKey} = {$paramValue}";
                                    }
                                }
                            } else {
                                // check to make sure it's extra option 
                                if (isset($getApiParameters[$paramKey]) && in_array('extraOptions', $getApiParameters[$paramKey]['refersTo'])) {
                                    // this assumes that we have extra options
                                    // Validate the value
                                    $errors = self::validate_api_params($paramValue, $paramKey, $getApiParameters);

                                    // If the parameter accepts a date value then format the date correctly
                                    if (in_array('date', $getApiParameters[$paramKey]['type'])) {
                                        // Get the new format for the list item
                                        $newDate = format_date($paramValue);
                                        // Set the correct format for the parameter Value
                                        $paramValue = $newDate;
                                    }

                                    // If there are errors then add the errors to the array
                                    if (!empty($errors)) {
                                        // Add each error from the validation error array
                                        foreach($errors as $err) {
                                            $options_array['errors'][] = $err;
                                        }
                                    } else {
                                        // check to see which action we should take
                                        if ($getApiParameters[$paramKey]['useFor'] == 'columns') {
                                            // make a list into an array
                                            $columns_array = explode(',', $paramValue);
                                            // check to see if it each column is a real database column
                                            foreach ($columns_array as $columnValue) {
                                                // trim value
                                                $columnValue = trim($columnValue);
                                                if (in_array($columnValue, static::get_class_db_columns())) {
                                                    // add sql to the return data
                                                    $options_array['data']['columnOptions'][] = $columnValue;
                                                    // add to paramsAccepted
                                                    $options_array['data']['paramsAccepted'][$paramKey][$columnValue] = $columnValue;
                                                } else {
                                                    // add error
                                                    $options_array['errors'][] = "{$columnValue} is not a valid {$paramKey} option!";
                                                }    
                                            }
                                            // recombine all column options into one string
                                            if (!$options_array['errors']) {
                                                $options_array['data']['columnOptions'] = implode(', ', $options_array['data']['columnOptions']);
                                            }
                                        // this assumes that were going to run a function later on store information to trigger function call 
                                        } elseif (contains($getApiParameters[$paramKey]['useFor'], 'code::')) {
                                            // make list to array
                                            $useForValue_array = split_string_by_separator($getApiParameters[$paramKey]['useFor'], "::");
                                            // add to the return data
                                            $options_array['data']['functionCall']['call'] = $useForValue_array['1'];
                                            // add to the return data
                                            $options_array['data']['functionCall']['value'] = $paramValue;
                                            // add to paramsAccepted
                                            $options_array['data']['paramsAccepted'][$paramKey] = $paramValue;
                                        } elseif ($getApiParameters[$paramKey]['useFor'] == 'propertyExclusions') {
                                            // make a list into an array
                                            $propertyExclusions_array = explode(',', $paramValue);
                                            // check to see if it each column is a real database column
                                            foreach ($propertyExclusions_array as $propertyName) {
                                                // trim value
                                                $propertyName = trim($propertyName);
                                                // check to see if the property exists
                                                if (property_exists($className, $propertyName)) {
                                                    // add to the return data
                                                    $options_array['data']['propertyExclusions'][] = $propertyName;
                                                    // add to paramsAccepted
                                                    $options_array['data']['paramsAccepted'][$paramKey][$propertyName] = $propertyName;
                                                } else {
                                                    // add error
                                                    $options_array['errors'][] = "{$propertyName} is not a valid {$paramKey} option!";
                                                }    
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
     
                    // check to see if we have a whereConditions
                    if (isset($routInfo_array['httpMethods']['get']['whereConditions'])) {
                        // we got it, now add it
                        $options_array['data']['whereOptions'][] = $routInfo_array['httpMethods']['get']['whereConditions'];
                    }
                    
                    // Return the array
                    return $options_array;
                }

                // # prep_api_message() prep message for the api
                private static function prep_api_message(array $prepApiData_array, array $routInfo_array, $routName) {

                    // check to see if we have errors
                    if (!$prepApiData_array['errors']) {

                        // sqlOptions may contain [whereOptions],[sortingOptions],[columnOptions]
                        // check to see if we need to add column options
                        if (isset($routInfo_array['httpMethods']['get']['columnOptions'])) {
                            // add columnOptions
                            $prepApiData_array['sqlOptions']['columnOptions'] = $routInfo_array['httpMethods']['get']['columnOptions'];
                        } elseif (!$prepApiData_array['sqlOptions']['columnOptions']) {
                            $prepApiData_array['sqlOptions']['columnOptions'] = ['*'];
                        }

                        // Submit the query to get the data
                        $Obj_array = static::find_where($prepApiData_array['sqlOptions']);
                        // check for whereOptions, helps with some error handling, the count_all function only accepts the ['whereOptions']
                        $queryCount_array = $prepApiData_array['sqlOptions']['whereOptions'] ?? [];
                        // get count
                        $queryCount = static::count_all($queryCount_array);

                        // Set the totalPages by getting a count
                        $totalPages = ceil(($queryCount / $prepApiData_array['extra']['perPage']));

                        // check to see if you got anything back, if yes move over and get API info
                        if (isset($Obj_array)) {
                            // check to see if we have any property exclusions
                            $propertyExclusions = $prepApiData_array['propertyExclusions'] ?? [];
                            // check to see if extra options are available
                            if ($prepApiData_array['functionCall']) {
                                // get call and value
                                $functionCall = $prepApiData_array['functionCall']['call'];
                                $codeData_array['data'] = $prepApiData_array['functionCall']['value'] ?? [];
                                // pass in one array, add all data to the array, make default just in case anything happens
                                $codeData_array['propertyExclusions'] = $propertyExclusions ?? [];
                                $codeData_array['prepApiData_array'] = $prepApiData_array ?? [];
                                $codeData_array['routInfo_array'] = $routInfo_array ?? [];
                                $codeData_array['routName'] = $routName ?? '';
                                // loop over and get api info
                                foreach ($Obj_array as $Obj) {
                                    // get api info
                                    $ObjApiInfo = $Obj->$functionCall($codeData_array);
                                    // put info into a new array
                                    $apiData_array[] = $ObjApiInfo;
                                }
                            } else {
                                // loop over and get api info
                                foreach ($Obj_array as $Obj) {
                                    // get api info
                                    $ObjApiInfo = $Obj->get_api_data($routInfo_array, $propertyExclusions);
                                    // put info into a new array
                                    $apiData_array[] = $ObjApiInfo;
                                }
                            }

                        }

                        // Create the response message
                        $responseData = [
                            "success" => "true",
                            "statusCode" => 200,
                            "errors" => [],
                            "requestMethod" => $_SERVER['REQUEST_METHOD'],
                            "currentPage" => $prepApiData_array['extra']['page'],
                            "totalPages" => $totalPages,
                            "resultsPerPage" => $prepApiData_array['extra']['perPage'],
                            "totalResults" => $queryCount,
                            "paramsSent" => $_GET,
                            "paramsAccepted" => $prepApiData_array['paramsAccepted'] ?? [],
                            "paramsNotAccepted" => $prepApiData_array['paramsNotAccepted'] ?? [],
                            "endpoint" => $routName,
                            "content" => $apiData_array ?? []
                        ];

                    // There were errors, construct the error message
                    } else {
                        // set http response code
                        http_response_code(400);
                        // Errors response
                        $responseData = [
                            "success" => "false",
                            "statusCode" => 400,
                            "errors" => [
                                "code" => 400,
                                "statusMessage" => "Bad Request",
                                "errorMessages" => $prepApiData_array['errors']
                            ],
                            "requestMethod" => $_SERVER['REQUEST_METHOD'],
                            "currentPage" => 0,
                            "totalPages" => 0,
                            "resultsPerPage" => 0,
                            "totalResults" => 0,
                            "paramsSent" => $_GET,
                            "paramsAccepted" => $prepApiData_array['paramsAccepted'] ?? [],
                            "paramsNotAccepted" => $prepApiData_array['paramsNotAccepted'] ?? [],
                            "endpoint" => $routName,
                            "content" => []
                        ];
                    }

                    // return response data
                    return $responseData;
                }
            // @ get prep end

            // @ post prep start
                // # prep_request_data
                public static function prep_request_data($postVars, $request, $routInfo_array) {
                    // set default variables
                    $processedData_array = static::get_empty_data_array();
                    $processedData_array['requestData'] = [];
                    // lower case request
                    $lcHttpMethod = strtolower($request);
                    // get arrayInfo
                    $arrayName = $routInfo_array['httpMethods'][$lcHttpMethod]['arrayInfo'] ?? '';
                    // check if it's a property/array or a function  
                    if (contains($arrayName, '_')) {
                        $postApiParameters = static::$arrayName() ?? [];
                    } else {
                        $postApiParameters = static::$$arrayName ?? [];
                    }
                    $validation_columns = static::$validation_columns;

                    // check for properties that should come through that have not come through, run validation on them
                    foreach ($postApiParameters as $key => $value) {
                        // check to see if any properties should be there, for custom validation, normal validation will be done later 
                        if (isset($value['validation']['required']) && !isset($postVars[$key]) && $request == "POST") {
                            // run validation on property that should be there
                            // validation array
                            $validationError_array = [];
                            // Check to see if we have custom validation for the post requests
                            $validation = $value['validation'];
                            // validate parameter
                            // * validation_options located at: root/private/rules_docs/reference_information.php
                            $validationError_array = val_validation("", $validation, 'parameter');
                            // loop over errors and put them in the right spot
                            foreach ($validationError_array as $error) {
                                $processedData_array['errors'][] = $error;
                            }
                        }

                        // also do a check to see if we have any required fields
                        if (isset($value['required']) && $value['required'] && !isset($postVars[$key])) {
                            $processedData_array['errors'][] = "{$key} is a required parameter that must be sent in order to access this $request endpoint";
                        }
                    }

                    // loop over get vars, only delete should have them id and authToken
                    foreach ($postVars as $key => $value) {
                        // check to see if id or authToken in request = delete, take care else where
                        if ($key == "authToken") { continue; }
                        if ($request === "DELETE" && ($key == "id" || $key == "deleteWhere")) { continue; } 
                        if ($request === "PUT" && ($key == "id" || $key == "putWhere")) { continue; } 
                        if ($request === "PATCH" && $key == "id") { continue; } 

                        // check to see if Parameter can be used, If yes then validated, if not put them in the paramsNotAccepted array 
                        if (((isset($postApiParameters[$key]) && $request !== "DELETE") && 
                            !($request === "POST" && $key == "id"))) {
                            // validation array
                            $validationError_array = [];
                            // Check to see if we have custom validation for the post requests
                            $validation = $postApiParameters[$key]['validation'] ?? $validation_columns[$key];
                            // validate parameter
                            // * validation_options located at: root/private/rules_docs/reference_information.php
                            $validationError_array = val_validation($value, $validation, 'parameter');
                            // loop over errors and put them in the right spot
                            foreach ($validationError_array as $error) {
                                $processedData_array['errors'][] = $error;
                            }
                            // check for errors
                            if (!$validationError_array) {
                                // set validated parameters
                                $processedData_array['requestData'][$key] = $value;
                                // Add validated parameters to paramsAccepted
                                $processedData_array['paramsAccepted'][$key] = $value;
                            }
                        } else {
                            // add to paramsNotAccepted
                            $processedData_array['paramsNotAccepted'][$key] = $value;
                        }
                    }


                    return $processedData_array;
                }

                // # validate and perform request
                private static function validate_and_perform_request($request, array $postVars_array, $className, array $routInfo_array, $routName) {
                    // set default variables
                    $data_array = static::get_empty_data_array();
                    
                    // Process post and get variables
                    $processedData_array = static::prep_request_data($postVars_array, $request, $routInfo_array);
                    // get parameters
                    $requestData_array = $processedData_array['requestData'];
                    // merge arrays
                    $data_array = static::merge_data_arrays($data_array, $processedData_array);

                    // check for errors
                    if (!$data_array['errors']) {
                        // check to see which request
                        if ($request === "POST") {
                            $requestResponse_array = static::prep_post_post($requestData_array, $className, $routInfo_array, $routName);
                        } elseif ($request === "PUT") {
                            $requestResponse_array = static::prep_post_put($requestData_array, $postVars_array, $className, $routInfo_array);
                        } elseif ($request === "PATCH") {
                            $requestResponse_array = static::prep_post_patch($requestData_array, $postVars_array, $className, $routInfo_array, $routName);
                        } else {
                            // else $request === "DELETE"
                            $requestResponse_array = static::prep_post_delete($postVars_array, $className, $routInfo_array);
                        }
                        // merge arrays
                        $data_array = static::merge_data_arrays($data_array, $requestResponse_array);
                    }

                    // return data
                    return $data_array;
                }

                // # prep_post_api_message() prep message for the api
                private static function prep_post_api_message(array $prepApiData_array, $routName) {

                    // set vars
                    $errors = $prepApiData_array['errors'] ?? [];
                    $statusCode = $prepApiData_array['statusCode'] ?? 200;
                    if ($prepApiData_array['errors']) {
                        $success = "false";
                    }
                    $paramsAccepted = $prepApiData_array['paramsAccepted'] ?? [];
                    $paramsNotAccepted = $prepApiData_array['paramsNotAccepted'] ?? [];
                    $content = $prepApiData_array['content'] ?? [];

                    // helps to get PUT and DELETE content body
                    $postVars_array = $prepApiData_array['postVars_array'] ?? [];

                    require_once(PUBLIC_PATH . '/api/restApi/v1/responseMessage.php');
                }

                // # prep post post
                public static function prep_post_post(array $requestData_array, $className, array $routInfo_array, $routName) {
                    // set default variables
                    $data_array = static::get_empty_data_array();

                    // check if the POST method is available
                    if (!isset($routInfo_array['httpMethods']['post']) || 
                        !isset($routInfo_array['httpMethods']['post']['arrayInfo'])) {
                        $data_array['errors'][] = "The POST HTTP method is not available for this endpoint.";  
                    } else {
                        // make new object
                        $Obj = new static($requestData_array); 
                        // save object 
                        $Obj->save();
                        // set id
                        $id = $Obj->id;

                        // check to see if we have an ID
                        if (!$Obj->errors) {
                            // make success message
                            $data_array['content']['message'] = "A new record was created with the id of {$Obj->id}.";
                            $data_array['content']['id'] = $Obj->id;
                            // check to see if a link can be shown
                            // see if rout has GET
                            if (isset($routInfo_array['httpMethods']['get']) && isset($routInfo_array['httpMethods']['get']['arrayInfo'])) {
                                $arrayName = $routInfo_array['httpMethods']['get']['arrayInfo'];
                                // check if it's a property/array or a function  
                                if (contains($arrayName, '_')) {
                                    $arrayInfo = static::$arrayName() ?? [];
                                } else {
                                    $arrayInfo = static::$$arrayName ?? [];
                                }
                                if ($arrayInfo) {
                                    if (isset($arrayInfo['id'])) {
                                        $data_array['content']['link'] = PUBLIC_LINK_PATH . "/api/restApi/v1/" . $routName . "/?id=" . $Obj->id;
                                    } else {
                                        $data_array['content']['link'] = PUBLIC_LINK_PATH . "/api/restApi/v1/" . $routName . "/";
                                    }
                                }
                            }
                        } else {
                            // Loop over errors
                            foreach ($Obj->errors as $error) {
                                $data_array['errors'][] = $error;
                            }
                        }  
                    }

                    // return data
                    return $data_array;
                }

                // # prep post put
                public static function prep_post_put(array $requestData_array, array $postVars_array, $className, array $routInfo_array) {
                    // set default variables
                    $data_array = static::get_empty_data_array();

                    // check if the PUT method is available
                    if (!isset($routInfo_array['httpMethods']['put'])  || 
                        !isset($routInfo_array['httpMethods']['put']['arrayInfo'])) {
                        $data_array['errors'][] = "The PUT HTTP method is not available for this endpoint.";  
                    } else {
                        // check to see if putWhere is allowed and there
                        if (isset($routInfo_array['httpMethods']['put']['putWhere']) && 
                            isset($postVars_array['putWhere'])) {
                            // check to see if id is set, put it in the paramsNotAccepted
                            if (isset($postVars_array['id'])) {
                                $data_array['paramsNotAccepted']['id'] = $postVars_array['id'];
                            } 
                            // set putWhere var
                            $putWhere = $postVars_array['putWhere'];
                            // * validation_options located at: root/private/rules_docs/reference_information.php
                            // Validate putWhere
                            $validation = [
                                'name' => "putWhere",
                                'required' => 'yes',
                                'type' => 'str',
                                'min'=> 1,
                                'max'=> 100,
                                'html'=> 'no',
                                'contains' => '::'
                            ];
                            // validation array
                            $validationError_array = [];
                            // Validate the id
                            $validationError_array = val_validation($putWhere, $validation, 'parameter');

                            // loop over errors and put them in the right spot
                            foreach ($validationError_array as $value) {
                                $data_array['errors'][] = $value;
                            }
                            // check for errors
                            if (!$data_array['errors']) {
                                // make array by splitting putWhere 
                                $putWhereSplit_array = explode("::", $putWhere);
                                $putWhereColumn = $putWhereSplit_array[0];
                                $putWhereValue = $putWhereSplit_array[1];
                                // check for valid db column
                                if (in_array($putWhereColumn, static::$columns)) {
                                    // find records
                                    $putWhereObj_array = $className::find_where(self::db_escape($putWhereColumn) . " = '" . self::db_escape($putWhereValue) . "'");
                                    // Check to see if there are any records that match that description
                                    if ($putWhereObj_array) {
                                        // get count
                                        $putWhereCount = count($putWhereObj_array);
                                        // Loop through each object and up date it
                                        foreach ($putWhereObj_array as $Obj) {
                                            // take off ID and then save
                                            $Obj->merge_attributes($requestData_array);
                                            // run save
                                            $Obj->save();
                                            // check for errors
                                            if ($Obj->errors) {
                                                // Loop over errors
                                                foreach ($Obj->errors as $error) {
                                                    $data_array['errors'][] = $error;
                                                }
                                            }  
                                        }
                                        if (!$data_array['errors']) {
                                            // make success message
                                            $data_array['content']['message'] = "{$putWhereCount} record(s) were updated that match the condition: {$putWhereColumn} = {$putWhereValue}.";
                                            // add putWhere to paramsAccepted
                                            $data_array['paramsAccepted']['putWhere'] = "putWhere = {$putWhere}";
                                        }
                                    } else {
                                        // make error message 
                                        $data_array['errors'][] = "No records were found that match the condition: {$putWhereColumn} = {$putWhereValue}.";
                                    }

                                } else {
                                    $data_array['errors'][] = "The {$putWhereColumn} column dose not reference any valid columns on this end point.";
                                }
                            }
                        } else {
                            // check to see if putWhere is set, put it in the paramsNotAccepted
                            if (isset($requestData_array['putWhere'])) {
                                $data_array['paramsNotAccepted']['putWhere'] = $requestData_array['putWhere'];
                            } 
                            // get id
                            $id = $postVars_array['id'] ?? "";
                            // validation array
                            $validationError_array = [];
                            // Validate the id
                            // * validation_options located at: root/private/rules_docs/reference_information.php
                            $validationError_array = val_validation($id, static::$validation_columns['id'], 'parameter');
                            // loop over errors and put them in the right spot
                            foreach ($validationError_array as $value) {
                                $data_array['errors'][] = $value;
                            }
                            // check for errors
                            if (!$data_array['errors']) {
                                // get record
                                $Obj = $className::find_by_id($id);
                                // check to see if we found a record
                                if ($Obj) {
                                    // merge changes for update
                                    $Obj->merge_attributes($requestData_array);
                                    // run save
                                    $Obj->save();
                                    // check for errors
                                    if ($Obj->errors) {
                                        // Loop over errors
                                        foreach ($Obj->errors as $error) {
                                            $data_array['errors'][] = $error;
                                        }
                                    }
                                    if (!$data_array['errors']) {
                                        // make success message
                                        $data_array['content']['message'] = "The record with the id of {$Obj->id} was updated.";
                                        // add id to paramsAccepted
                                        $data_array['paramsAccepted']['id'] = "id = {$id}";
                                    }
                                } else {
                                    $data_array['errors'][] = "The record with the id of {$id} dose not exist.";
                                }
                            }
                        }
                    }

                    // return data
                    return $data_array;
                }

                // # prep post patch
                public static function prep_post_patch(array $requestData_array, array $postVars_array, $className, array $routInfo_array, $routName) {
                    // set default variables
                    $data_array = static::get_empty_data_array();

                    // check if the PATCH method is available
                    if (!isset($routInfo_array['httpMethods']['patch'])) {
                        $data_array['errors'][] = "The PATCH HTTP method is not available for this endpoint.";  
                    } else {
                        // get id
                        $id = $postVars_array['id'] ?? "";
                        // validation array
                        $validationError_array = [];
                        // Validate the id
                        // * validation_options located at: root/private/rules_docs/reference_information.php
                        $validationError_array = val_validation($id, static::$validation_columns['id'], 'parameter');
                        // loop over errors and put them in the right spot
                        foreach ($validationError_array as $value) {
                            $data_array['errors'][] = $value;
                        }
                        // check for errors
                        if (!$data_array['errors']) {
                            // get record
                            $Obj = $className::find_by_id($id);
                            // check to see if we found a record
                            if ($Obj) {
                                // make id blank then merge changes, and save the object to make a copy
                                $requestData_array['id'] = '';
                                // merge changes for update
                                $Obj->merge_attributes($requestData_array);
                                // run save
                                $Obj->save();
                                // check for errors
                                if ($Obj->errors) {
                                    // Loop over errors
                                    foreach ($Obj->errors as $error) {
                                        $data_array['errors'][] = $error;
                                    }
                                }
                                if (!$data_array['errors']) {
                                    // make success message
                                    $data_array['content']['message'] = "The record with the id of {$id} was copied to the record with the id of {$Obj->id}. Other parameters, if submitted, have altered the copy.";
                                    $data_array['content']['id'] = $Obj->id;
                                    // check to see if a link can be shown
                                    // see if rout has GET
                                    if (isset($routInfo_array['httpMethods']['get']) && isset($routInfo_array['httpMethods']['get']['arrayInfo'])) {
                                        $arrayName = $routInfo_array['httpMethods']['get']['arrayInfo'];
                                        // check if it's a property/array or a function  
                                        if (contains($arrayName, '_')) {
                                            $arrayInfo = static::$arrayName() ?? [];
                                        } else {
                                            $arrayInfo = static::$$arrayName ?? [];
                                        }
                                        if ($arrayInfo) {
                                            if (isset($arrayInfo['id'])) {
                                                $data_array['content']['link'] = PUBLIC_LINK_PATH . "/api/restApi/v1/" . $routName . "/?id=" . $Obj->id;
                                            } else {
                                                $data_array['content']['link'] = PUBLIC_LINK_PATH . "/api/restApi/v1/" . $routName . "/";
                                            }
                                        }
                                    }
                                    // add id to paramsAccepted
                                    $data_array['paramsAccepted']['id'] = "id = {$id}";
                                }
                            } else {
                                $data_array['errors'][] = "The record with the id of {$id} dose not exist.";
                            }
                        }
                    }

                    // return data
                    return $data_array;
                }

                // # prep post delete
                public static function prep_post_delete(array $postVars_array, $className, array $routInfo_array) {
                    // set default variables
                    $data_array = static::get_empty_data_array();

                    // check if the DELETE method is available
                    if (!isset($routInfo_array['httpMethods']['delete'])) {
                        $data_array['errors'][] = "The DELETE HTTP method is not available for this endpoint.";  
                    } else {
                        // check to see if deleteWhere is allowed and there
                        if (isset($routInfo_array['httpMethods']['delete']['deleteWhere']) && 
                            isset($postVars_array['deleteWhere'])) {
                            // check to see if id is set, put it in the paramsNotAccepted
                            if (isset($postVars_array['id'])) {
                                $data_array['paramsNotAccepted']['id'] = $postVars_array['id'];
                            } 
                            // set deleteWhere var
                            $deleteWhere = $postVars_array['deleteWhere'];
                            // * validation_options located at: root/private/rules_docs/reference_information.php
                            // Validate deleteWhere
                            $validation = [
                                'name' => "deleteWhere",
                                'required' => 'yes',
                                'type' => 'str',
                                'min'=> 1,
                                'max'=> 100,
                                'html'=> 'no',
                                'contains' => '::'
                            ];
                            // validation array
                            $validationError_array = [];
                            // Validate the id
                            $validationError_array = val_validation($deleteWhere, $validation, 'parameter');

                            // loop over errors and put them in the right spot
                            foreach ($validationError_array as $value) {
                                $data_array['errors'][] = $value;
                            }
                            // check for errors
                            if (!$data_array['errors']) {
                                // make array by splitting deleteWhere 
                                $deleteWhereSplit_array = explode("::", $deleteWhere);
                                $deleteWhereColumn = $deleteWhereSplit_array[0];
                                $deleteWhereValue = $deleteWhereSplit_array[1];
                                // check for valid db column
                                if (in_array($deleteWhereColumn, static::$columns)) {
                                    // find records
                                    $deleteWhereObj_array = $className::find_where(self::db_escape($deleteWhereColumn) . " = '" . self::db_escape($deleteWhereValue) . "'");
                                    // Check to see if there are any records that match that description
                                    if ($deleteWhereObj_array) {
                                        // get count
                                        $deleteWhereCount = count($deleteWhereObj_array);
                                        // Loop through each object and delete it
                                        foreach ($deleteWhereObj_array as $Obj) {
                                            // run delete
                                            $Obj->delete();
                                        }
                                        // make success message
                                        $data_array['content']['message'] = "{$deleteWhereCount} record(s) were deleted that match the condition: {$deleteWhereColumn} = {$deleteWhereValue}.";
                                        // add deleteWhere to paramsAccepted
                                        $data_array['paramsAccepted']['deleteWhere'] = "deleteWhere = {$deleteWhere}";
                                    } else {
                                        // make error message 
                                        $data_array['errors'][] = "No records were found that match the condition: {$deleteWhereColumn} = {$deleteWhereValue}.";
                                    }

                                } else {
                                    $data_array['errors'][] = "The {$deleteWhereColumn} column dose not reference any valid columns on this end point.";
                                }
                            }
                        } else {
                            // check to see if deleteWhere is set, put it in the paramsNotAccepted
                            if (isset($postVars_array['deleteWhere'])) {
                                $data_array['paramsNotAccepted']['deleteWhere'] = $postVars_array['deleteWhere'];
                            } 
                            // get id
                            $id = $postVars_array['id'] ?? $_GET['id'] ?? "";
                            // validation array
                            $validationError_array = [];
                            // Validate the id
                            // * validation_options located at: root/private/rules_docs/reference_information.php
                            $validationError_array = val_validation($id, static::$validation_columns['id'], 'parameter');
                            // loop over errors and put them in the right spot
                            foreach ($validationError_array as $value) {
                                $data_array['errors'][] = $value;
                            }
                            // check for errors
                            if (!$data_array['errors']) {
                                // get record
                                $Obj = $className::find_by_id($id);
                                // check to see if we found a record
                                if ($Obj) {
                                    // run delete
                                    $Obj->delete();
                                    // make success message
                                    $data_array['content']['message'] = "The record with the id of {$Obj->id} was deleted.";
                                    // add id to paramsAccepted
                                    $data_array['paramsAccepted']['id'] = "id = {$id}";
                                } else {
                                    $data_array['errors'][] = "The record with the id of {$id} dose not exist.";
                                }
                            }
                        }
                    }

                    // return data
                    return $data_array;
                }
            // @ post prep end
        // @ prep methods end

        // @ helper methods start
            // # get empty data array
            private static function get_empty_data_array() {
                // set default variables
                $data_array['errors'] = [];
                $data_array['content'] = [];
                $data_array['paramsAccepted'] = [];
                $data_array['paramsNotAccepted'] = [];

                // return array
                return $data_array;
            }

            // # merge data arrays
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
                // loop over paramsAccepted and put them in the right spot
                foreach ($temp_array['paramsAccepted'] as $key => $value) {
                    $data_array['paramsAccepted'][$key] = $value;
                }
                // loop over paramsAccepted and put them in the right spot
                foreach ($temp_array['paramsNotAccepted'] as $key => $value) {
                    $data_array['paramsNotAccepted'][$key] = $value;
                }

                // return merged array
                return $data_array;
            }

            // # This function leverages the val_validation function.
            private static function validate_api_params($value, $param, $getApiParameters) {

                // If there is a custom validation column then use it for validation
                if (isset($getApiParameters[$param]['validation'])){
                    // Set the custom validation
                    $customValidation = $getApiParameters[$param]['validation'];
                    // Validate based on the custom validation
                    $errors = val_validation($value, $customValidation, 'parameter');
                    // Return the validation errors array
                    return $errors;

                // elseIf there is a default validation column then use it, first column gets to be the validator 
                } elseif (isset($getApiParameters[$param]['refersTo'][0]) && 
                    isset(static::$validation_columns[$getApiParameters[$param]['refersTo'][0]])) {
                    // Set the default validation
                    $defaultValidation = static::$validation_columns[$getApiParameters[$param]['refersTo'][0]];
                    // Validate based on the default validation
                    $errors = val_validation($value, $defaultValidation, 'parameter');
                    // Return the validation errors array
                    return $errors;

                // else there were no validation defined. Return the error.
                } else {
                    $errors = "Parameter: {$param} with Value: {$value} was rejected as there are no validation rules defined.";
                    // Return the error
                    return $errors;
                }
            }

            // # create an associative array, key value pair from the static::$columns excluding id
            protected function api_attributes(array $routInfo_array = [], array $propertyExclusions = []) {
                // empty array to be filled below
                $attributes = [];
                // column and API attributes merge arrays
                $apiAttributes_array = array_merge(static::$columns, static::$apiProperties);
                // remove unwanted attributes from the API, example password, secure information, etc. check if we ned to
                if (isset($routInfo_array['httpMethods']['get']['apiPropertyExclusions']) && is_array($routInfo_array['httpMethods']['get']['apiPropertyExclusions'])) {
                    // array_diff(what does this array have that's different, then this array);
                    $apiAttributes_array = array_diff($apiAttributes_array, $routInfo_array['httpMethods']['get']['apiPropertyExclusions']);
                }
                // remove unwanted attributes from the API through extra options, check if there
                if ($propertyExclusions) {
                    $apiAttributes_array = array_diff($apiAttributes_array, $propertyExclusions);
                }

                // loop over and make a key value pair array of api attributes
                foreach ($apiAttributes_array as $attribute) {
                    // construct attribute list with object values
                    $attributes[$attribute] = $this->$attribute;
                }

                // return array of attributes
                return $attributes;
            }

            // # get data and turn it into json
            public function get_api_data(array $routInfo_array = [], array $propertyExclusions = []) {
                // get api data
                $data_array = $this->api_attributes($routInfo_array, $propertyExclusions);
                // return data
                return $data_array;
            }

            // TODO: probably can merge a couple of these functions together, especially for authentication validation
            // * password/key specificity located at root/private/rules_docs/reference_information.php: // @ api_documentation // # password/key specificity general to specific, the order that keys matter
            // # api get authentication
            static public function apiGetAuthentication(array $dbKeyInfo_array, $className, array $apiInfo_array, array $routInfo_array) {
                // check for authentication, API key, specific to general
                if (isset($routInfo_array['httpMethods']['get']['methodKey']) && 
                    trim(strlen($routInfo_array['httpMethods']['get']['methodKey'])) > 0) {
                    // we have a class specific key, let's see if it matches
                    if (isset($_GET["authToken"]) && $routInfo_array['httpMethods']['get']['methodKey'] === $_GET["authToken"]) {
                        $authenticated = true;
                    } else {
                        $authenticated = false;
                    }
                } elseif (isset($routInfo_array['routKey']) && trim(strlen($routInfo_array['routKey'])) > 0) {
                    // we have a rout specific key
                    if (isset($_GET["authToken"]) && $routInfo_array['routKey'] === $_GET["authToken"]) {
                        $authenticated = true;
                    } else {
                        $authenticated = false;
                    }
                } elseif (isset($apiInfo_array['classKey']) && trim(strlen($apiInfo_array['classKey'])) > 0) {
                    // we have a class specific key
                    if (isset($_GET["authToken"]) && $apiInfo_array['classKey'] === $_GET["authToken"]) {
                        $authenticated = true;
                    } else {
                        $authenticated = false;
                    }
                } elseif (trim(strlen($dbKeyInfo_array['mainGetApiKey'])) > 0) {
                    // GET specific key 
                    if (isset($_GET["authToken"]) && $dbKeyInfo_array['mainGetApiKey'] === $_GET["authToken"]) {
                        $authenticated = true;
                    } else {
                        $authenticated = false;
                    }
                } elseif (trim(strlen($dbKeyInfo_array['mainApiKey'])) > 0) {
                    // main key 
                    if (isset($_GET["authToken"]) && $dbKeyInfo_array['mainApiKey'] === $_GET["authToken"]) {
                        $authenticated = true;
                    } else {
                        $authenticated = false;
                    } 
                } else {
                    // no API key needed, or set
                    $authenticated = true;
                }

                // return authentication
                return $authenticated;
            }

            // # api post authentication
            static public function apiPostAuthentication(array $dbKeyInfo_array, array $apiInfo_array, array $routInfo_array, $className, $authToken, $request) {
                // check for authentication, API key, specific to general
                $authenticated = false;

                // lower case request
                $lcHttpMethod = strtolower($request);

                // method specific key
                if (isset($routInfo_array['httpMethods'][$lcHttpMethod]['methodKey']) && 
                    trim(strlen($routInfo_array['httpMethods'][$lcHttpMethod]['methodKey'])) > 0) {
                     // we have a class specific key, let's see if it matches
                     if ($routInfo_array['httpMethods'][$lcHttpMethod]['methodKey'] === $authToken) {
                        $authenticated = true;
                    }
                // rout specific key
                } elseif (isset($routInfo_array['routKey']) && 
                    trim(strlen($routInfo_array['routKey'])) > 0) {
                    // we have a class specific key, let's see if it matches
                    if ($routInfo_array['routKey'] === $authToken) {
                        $authenticated = true;
                    }
                // class specific key
                } elseif (isset($apiInfo_array['classKey']) && 
                    trim(strlen($apiInfo_array['classKey'])) > 0) {
                    // we have a class specific key, let's see if it matches
                    if ($apiInfo_array['classKey'] === $authToken) {
                        $authenticated = true;
                    }
                // POST specific key 
                } elseif (isset($dbKeyInfo_array['mainPostApiKey']) && 
                    trim(strlen($dbKeyInfo_array['mainPostApiKey'])) > 0) {
                    if ($dbKeyInfo_array['mainPostApiKey'] === $authToken) {
                        $authenticated = true;
                    }
                // overall API key
                } elseif (isset( $dbKeyInfo_array['mainApiKey']) && 
                    trim(strlen($dbKeyInfo_array['mainApiKey'])) > 0) {
                    if ($dbKeyInfo_array['mainApiKey'] === $authToken) {
                        $authenticated = true;
                    }
                } 
                // return authentication
                return $authenticated;
            }

            // # api check if there is a key set, what key is set
            static public function apiKeyCheck(array $dbKeyInfo_array, array $apiInfo_array, array $routInfo_array, $httpMethod) {
                // check for authentication key, API key, specific to general
                $keyName = '';

                // specific to general key search
                if (isset($routInfo_array['httpMethods'][$httpMethod]['methodKey']) && 
                    trim(strlen($routInfo_array['httpMethods'][$httpMethod]['methodKey'])) > 0) {
                    // we have a method specific key
                    $keyName = 'methodKey';
                } elseif (isset($routInfo_array['routKey']) && trim(strlen($routInfo_array['routKey'])) > 0) {
                    // we have a rout specific key
                    $keyName = 'routKey';
                } elseif (isset($apiInfo_array['classKey']) && trim(strlen($apiInfo_array['classKey'])) > 0) {
                    // we have a class specific key
                    $keyName = 'classKey';
                } elseif (trim(strlen($dbKeyInfo_array['mainGetApiKey'])) > 0 && $httpMethod == 'get') {
                    // GET specific key 
                    $keyName = 'mainGetApiKey';
                }  elseif (trim(strlen($dbKeyInfo_array['mainPostApiKey'])) > 0 && $httpMethod !== 'get') {
                    // POST specific key 
                    $keyName = 'mainPostApiKey';
                } elseif (trim(strlen($dbKeyInfo_array['mainApiKey'])) > 0) {
                    // main key 
                    $keyName = 'mainApiKey';
                } 

                // check to see if a post key has been set
                if (strlen($keyName) < 1 && $httpMethod != 'get') {
                    $keyName = 'This method can not be used until a API key has been set up.';
                }

                // return keyName
                return $keyName;
            }
        // @ helper methods end
    }
?>