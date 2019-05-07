<?php

// The trait for the API

// TODO: Implement API Authentication, Code for having the API Keys
// TODO: Update logic for orderBy to accept a list of lists
// e.g. orderBy=postDate::DECS,createdDate::ASC
// DEBUG: multiple or single order by
// TODO: Update error handling for invalid parameters
// Return accepted vs rejected parameters
trait Api {

    // Method for getting api info from the DB
    static function get_api_info() {
        // Array for holding the finished API Data
        $apiData_array = [];

        // Array for holding the prepped api data
        $prepApiData_array['errors'] = [];

        // validate incoming parameters
        $temp_array_1 = static::validate_and_prep_api_parameters($_GET);

        // Add the errors to the array
        $prepApiData_array['errors'] = $temp_array_1['errors'];

        // Add the data and the extras to the array
        $prepApiData_array['sqlOptions'] = $temp_array_1['sqlOptions'];
        $prepApiData_array['extra'] = $temp_array_1['extra'];

        // check to see if we have errors
        if (!$prepApiData_array['errors']) {

            // sqlOptions will contain [whereOptions],[sortingOptions],[columnOptions]
            // Submit the query to get the data
            $Obj_array = static::find_where($prepApiData_array['sqlOptions']);

            // Set the totalPages by getting a count
            $totalPages = ceil(($Obj_array['count'] / $prepApiData_array['extra']['perPage']));

            // check to see if you got anything back, if yes move over and get API info
            if (isset($Obj_array['data'])) {
                // loop over and get api info
                foreach ($Obj_array['data'] as $Obj) {
                    // get api info
                    $ObjApiInfo = $Obj->get_full_api_data();
                    // put info into a new array
                    $apiData_array[] = $ObjApiInfo;
                }
            }

            // Create the response message
            $responseData = [
                "success" => true,
                "statusCode" => 200,
                "errors" => [],
                "requestMethod" => $_SERVER['REQUEST_METHOD'],
                "currentPage" => $prepApiData_array['extra']['page'],
                "totalPages" => $totalPages,
                "resultsPerPage" => $prepApiData_array['extra']['perPage'],
                "totalResults" => $Obj_array['count'],
                "paramsSent" => $_GET,
                "endpoint" => static::$tableName,
                "content" => $apiData_array
            ];

        // There were errors, construct the error message
        } else {
            // Errors response
            $responseData = [
                "success" => false,
                "statusCode" => 400,
                "errors" => [
                    "code" => 400,
                    "statusMessage" => "Bad Request",
                    "errorMessages" => $prepApiData_array['errors']
                ],
                "requestMethod" => $_SERVER['REQUEST_METHOD'],
                "currentPage" => 1,
                "totalPages" => 1,
                "resultsPerPage" => 1,
                "totalResults" => 0,
                "paramsSent" => $_GET,
                "endpoint" => static::$tableName,
                "content" => []
            ];
        }

        // Package the response into json and return it
        $jsonData = json_encode($responseData);
        return $jsonData;
    }

    // Validate and prep the API parameters
    static function validate_and_prep_api_parameters($getParams_array) {
        // Prepare the array we will use to hold our prepped API data
        $prepApiData_array['errors'] = [];

        // Prep and validate the sorting options
        $temp_array_1 = static::prep_sorting_options($getParams_array);

        // Add any errors to our array
        foreach($temp_array_1['errors'] as $error) {
            $prepApiData_array['errors'][] = $error;
        }

        // Add the prepped sorting options to the array
        $prepApiData_array['sqlOptions']['sortingOptions'] = $temp_array_1['data'];

        // Add the extra things we need for some overhead
        $prepApiData_array['extra'] = [
            'page' => $temp_array_1['page'],
            'perPage' => $temp_array_1['perPage']
        ];

        // Prep and validate the where options
        $temp_array_2 = static::prep_where_options($getParams_array);

        // Add any errors to the array
        foreach($temp_array_2['errors'] as $error) {
            $prepApiData_array['errors'][] = $error;
        }

        // Add the prepped where options to the array
        $prepApiData_array['sqlOptions']['whereOptions'] = $temp_array_2['data'];
        
        // Return the array
        return $prepApiData_array;
    }

    // For validating and preping the sorting options
    static private function prep_sorting_options($getParams_array) {
        // An array to hold the sorting options
        $options_array['data'] = [];
        $options_array['errors'] = [];

        // Loop through the params array to get all of the sorting options
        foreach($getParams_array as $paramKey => $paramValue) {

             // Check if the parameter is defined in the class
             if(isset(static::$apiParameters[$paramKey])) {

                // Check if it is a sorting option
                if(static::$apiParameters[$paramKey]['refersTo'] === 'sortingOption') {

                    // Check if the value is page or perPage then add it accordingly
                    if($paramKey == 'page' || $paramKey == 'perPage') {
                        // Validate the value if the validation is set
                        if(isset(static::$apiParameters[$paramKey]['validation'])) {
                            $options_array['errors'][] = self::validate_api_params($getParams_array[$paramKey]);
                        }
                        // Add it to the options array
                        $options_array['data'][] = [
                            'operator' => static::$apiParameters[$paramKey]['operator'],
                            'column' => NULL,
                            'value' => $paramValue
                        ];

                        // Make note of the perameter
                        $options_array[$paramKey] = $paramValue;

                    // The value must be orderBy
                    } else {
                        // Check to see if we have alist of values separated by ::
                        if(is_list($paramValue, "::")) {
                            // Get the values from the list
                            $firstList_array = split_string_by_separator($paramValue, "::");

                            // For each item in our list check to see if if is a comma separated list
                            foreach($firstList_array as $item) {
                                $tempCol = NULL;
                                $tempVal = NULL;
                                $extra = NULL;

                                if(is_list($item, ",")) {
                                    $secondList_array = split_string_by_separator($item, ",");

                                    // Temporarily store the data in a variable
                                    foreach($secondList_array as $item2) {
                                        if($item2 == 'ASC' || $item2 == 'DESC') {
                                            $tempVal = $item2;
                                        } else {
                                            // Only assign the column if it has not already been assigned
                                            if($tempCol == NULL) {
                                                $tempCol = $item2;
                                            } else {
                                                $extra = $item2;
                                            }
                                        }
                                    }

                                    // If the the extra contains an item then send an error
                                    if($extra != NULL) {
                                        $options_array['errors'][] = "{$extra} is not a valid value for the parameter!";
                                    } else {

                                        // Validate that the column and value are correct for the column
                                        $errors = self::validate_orderBy($tempVal, $tempCol);

                                        // Add the errors to the options array if there are errors
                                        if(!empty($errors)) {
                                            foreach($errors as $err) {
                                                $options_array['errors'][] = $err;
                                            }
                                        }
                                        
                                        // Put the data in our options array
                                        $options_array['data'][] = [
                                            'operator' => static::$apiParameters[$paramKey]['operator'],
                                            'column' => $tempCol,
                                            'value' => $tempVal
                                        ];
                                    }

                                // Send an error if each item is not a comma separated list
                                } else {
                                    $options_array['errors'][] = "{$paramKey} expects a comma separated list of values! Example: {$paramKey}=createdDate,ASC";
                                    break;
                                }
                            }

                        // Check to see if it is just a comma separated list
                        }  elseif (is_list($paramValue, ",")) {
                            // Get the values from the list
                            $newList_array = split_string_by_separator($paramValue, ",");
                            $tempCol = NULL;
                            $tempVal = NULL;
                            $extra = NULL;

                            // Get each item from the list and add it to our array
                            foreach($newList_array as $item) {
                                if($item == 'ASC' || $item == 'DESC') {
                                    $tempVal = $item;
                                } else {
                                    // Only assign the column if it has not already been assigned
                                    if($tempCol == NULL) {
                                        $tempCol = $item;
                                    } else {
                                        $extra = $item;
                                    }
                                }
                            }

                            // If the the extra contains an item then send an error
                            if($extra != NULL) {
                                $options_array['errors'][] = "{$extra} is not a valid value for the parameter!";
                            } else {
                                // Validate that the column and value are correct for the column
                                $errors = self::validate_orderBy($tempVal, $tempCol);
    
                                // Add the errors to the options array if there are errors
                                if(!empty($errors)) {
                                    foreach($errors as $err) {
                                        $options_array['errors'][] = $err;
                                    }
                                }
    
                                // Put the data in our options array
                                $options_array['data'][] = [
                                    'operator' => static::$apiParameters[$paramKey]['operator'],
                                    'column' => $tempCol,
                                    'value' => $tempVal
                                ];
                            }


                        // Send an error if the value is not a list
                        } else {
                            $options_array['errors'][] = "{$paramKey} expects a list of values! Example: {$paramKey}=createdDate,ASC::postDate,DESC";
                            break;
                        }
                    }
                }

            // The Parameter given is not accepted
            } else {
                $options_array['errors'][] = "{$paramKey} is not a valid parameter!";
                break;
            }
        }

        // Use the default value if the perPage is not defined
        if(!isset($options_array['perPage'])) {
            // Set the values
            $options_array['data'][] = [
                'operator' => static::$apiParameters['perPage']['operator'],
                'column' => NULL,
                'value' => static::$apiParameters['perPage']['default']
            ];

            // Also keep note of the page
            $options_array['perPage'] = static::$apiParameters['perPage']['default'];
        }

        // Calculate the limit and offset
        $limit = $options_array['perPage'];

        // Use the page for calculation only if it is defined
        if(isset($options_array['page'])) {
            $offset = (($options_array['page'] - 1) * $limit) + ($options_array['page'] - 1);
        } else {
            $offset = ((static::$apiParameters['page']['default'] - 1) * $limit) + (static::$apiParameters['page']['default'] - 1);
        }

        // Make sure the limit and offset are the correct values in our prepped data array
        // also order the values to be in the correct order for the MySQL query
        // The order must be 1 - ORDER BY, 2 - LIMIT, 3 - OFFSET
        for($i = 0; $i < sizeof($options_array['data']); $i++) {
            // Used for juggling the array elements
            $temp;
            $end = sizeof($options_array['data']) - 1;

            if($i === 0) {
                // Used to keep our multiple orderBy in the correct order at the front of the array
                $wasAdded = false;
                $posOfOrderBy = 0;
            }

            // If it is the ORDER BY then put it at the beginning of the array
            if($options_array['data'][$i]['operator'] == "ORDER BY") {
                // Keeping our orderBys in the right order
                if($wasAdded) {
                    // Move the ORDER BY to the beginning of the array but AFTER the orderBy that was already added
                    $temp = $options_array['data'][$posOfOrderBy + 1];
                    $options_array['data'][$posOfOrderBy + 1] = $options_array['data'][$i];
                    $options_array['data'][$i] = $temp;

                // This is not the first orderBy so put it at the beginning of the array
                } else {
                    // Move the ORDER BY to the beginning of the array
                    $temp = $options_array['data'][0];
                    $options_array['data'][0] = $options_array['data'][$i];
                    $options_array['data'][$i] = $temp;
    
                    $wasAdded = true;
                    $posOfOrderBy = 0;
                }
            }

            // If it is the LIMIT, set the correct limit and order it correctly
            if($options_array['data'][$i]['operator'] == "LIMIT") {
                // Set the LIMIT
                $options_array['data'][$i]['value'] = $limit;

                // This should be moved correctly due the juggling of the other two sort options
            }

            // if it is the OFFSET, set the correct offset
            if($options_array['data'][$i]['operator'] == "OFFSET") {
                // Set the OFFSET
                $options_array['data'][$i]['value'] = $offset;

                // Move the OFFSET to the end of the array
                $temp = $options_array['data'][$end];
                $options_array['data'][$end] = $options_array['data'][$i];
                $options_array['data'][$i] = $temp;
            }
            // If it is none then do nothing
        }

        // If we have not defined the default page yet then define it
        if(!isset($options_array['page'])) {
            $options_array['page'] = static::$apiParameters['page']['default'];
        }

        // Return the array
        return $options_array;
    }

    // For validating and prepping the where options
    static private function prep_where_options($getParams_array) {
        // An array to hold the where options
        $options_array['data'] = [];
        $options_array['errors'] = [];

        // Loop through the parameters and get all of the where options
        foreach($getParams_array as $paramKey => $paramValue) {

            // Check if the parameter is defined in the class
            if(isset(static::$apiParameters[$paramKey])) {

                // Make sure the parameter is not a sorting option option
                if(static::$apiParameters[$paramKey]['refersTo'] !== 'sortingOption') {
                    // If the param key is a search then add the % to the value for the SQL prep
                    if(contains($paramKey, "search")) {
                        $paramValue = "%" . $paramValue . "%";
                    }

                    // Check if the data is a list
                    if(is_list($paramValue, ",")) {

                        // Check if we accept a list as a data type if not then add the error
                        if(!isset(static::$apiParameters[$paramKey]['connection']['list'])) {
                            $options_array['errors'][] = "{$paramKey} does not accept a list of values!";

                        // The data is accepted as a list type so sort through the list
                        } else {
                            // Turn the list into an array and add it to our list of whereOptions
                            $newList_array = split_string_by_separator($paramValue, ",");
        
                            // Prep the beginning of the string for holding our list of values
                            $valueList = "( ";
                            foreach($newList_array as $listItem) {
                                // Validate the value
                                $errors = self::validate_api_params($listItem, $paramKey);

                                // If the parameter accepts a date value then format the date correctly
                                if(static::$apiParameters[$paramKey]['type'] === 'date') {
                                    // Get the new format for the list item
                                    $newDate = format_date($listItem);

                                    // If there was an error then add to the errors array
                                    if($newDate === false) {
                                        $errors[] = "The value {$listItem} for parameter {$paramKey} is not a valid date!";
                                    }

                                    // Set the correct format for the list item
                                    $listItem = $newDate;
                                }
        
                                // If there are errors then add them to the errors array
                                if(!empty($errors)) {
                                    // Add each error from the validation error array
                                    foreach($errors as $err) {
                                        $options_array['errors'][] = $err;
                                    }
        
                                // No errors were found add to our sql prepped list
                                } else {
                                    // If at not at the end of the array add the comma
                                    if($listItem !== end($newList_array)) {
                                        $valueList .= self::db_escape($listItem) . ", ";
        
                                    // If at the end of the array then add a paranetheses instead
                                    } else {
                                        $valueList .= self::db_escape($listItem) . " )";
                                    }
                                }
                            }
                            // Add the sql prepped list to the whereOptions
                            $options_array['data'][] = [
                                "column" => static::$apiParameters[$paramKey]['refersTo'],
                                "operator" => static::$apiParameters[$paramKey]['connection']['list'],
                                "value" => $valueList
                            ];
                        }

                    // The data is not a list
                    } else {
                        // Validate the value
                        $errors = self::validate_api_params($paramValue, $paramKey);

                        // If the parameter accepts a date value then format the date correctly
                        if(static::$apiParameters[$paramKey]['type'] === 'date') {
                            // Get the new format for the list item
                            $newDate = format_date($paramValue);

                            // If there was an error then add to the errors array
                            if($newDate === false) {
                                $errors[] = "The value {$paramValue} for parameter {$paramKey} is not a valid date!";
                            }

                            // Set the correct format for the parameter Value
                            $paramValue = $newDate;
                        }

                        // If there are errors then add the errors to the array
                        if(!empty($errors)) {
                            // Add each error from the validation error array
                            foreach($errors as $err) {
                                $options_array['errors'][] = $err;
                            }

                        // No errors were found add the data to the array
                        } else {
                            // The parameter was found, add the info needed to our array
                            $options_array['data'][] = [
                                "column" => static::$apiParameters[$paramKey]['refersTo'],
                                "operator" => static::$apiParameters[$paramKey]['connection'][static::$apiParameters[$paramKey]['type']],
                                "value" => $paramValue
                            ];
                        }
                    }
                }
                // If the parameter is a sorting option then continue the loop
                continue;

            // There was no matching parameter, add to the errors array
            } else {
                $options_array['errors'][] = "{$paramKey} is not a valid parameter!";
            }
        }
        // Return the array
        return $options_array;
    }

    // This function leverages the val_validation function.
    static function validate_api_params($value, $param) {

        // If there is a custom validation column then use it for validation
        if(isset(static::$apiParameters[$param]['validation'])){
            // Set the custom validation
            $customValidation = static::$apiParameters[$param]['validation'];
            // Validate based on the custom validation
            $errors = val_validation($value, $customValidation);
            // Return the validation errors array
            return $errors;

        // elseIf there is a default validation column then use it
        } elseif(isset(static::$apiParameters[$param]['refersTo']) && isset(static::$validation_columns[static::$apiParameters[$param]['refersTo']])) {
            // Set the default validation
            $defaultValidation = static::$validation_columns[static::$apiParameters[$param]['refersTo']];
            // Validate based on the default validation
            $errors = val_validation($value, $defaultValidation);
            // Return the validation errors array
            return $errors;

        // else there were no validation defined. Return the error.
        } else {
            $errors = "Parameter: {$param} with Value: {$value} was rejected as there are no validation rules defined.";
            // Return the error
            return $errors;
        }
    }

    static function validate_orderBy($val, $col) {
        // array to hold the errors
        $errors = [];
        // Check if the column given is accepted
        if(!isset(static::$apiParameters[$col])) {
            $errors[] = "{$col} is not an accepted column parameter for orderBy.";
        }

        // Check if the value is ASC or DESC
        if($val != "ASC") {
            if($val != "DESC") {
                $errors[] = "{$val} is not an accepted value parameter with {$col} for orderBy. Example: orderBy=id,DESC";
            }
        }
        return $errors;
    }

    // create an associative array, key value pair from the static::$columns excluding id
    public function api_attributes() {
        // empty array to be filled below
        $attributes = [];
        // column and API attributes merge arrays
        $apiAttributes_array = array_merge(static::$columns, static::$apiProperties);
        // loop over and make a key value pair array of api attributes
        foreach ($apiAttributes_array as $attribute) {
            // construct attribute list with object values
            $attributes[$attribute] = $this->$attribute;
        }
        // return array of attributes
        return $attributes;
    }

    // get api data plus extended data
    public function get_full_api_data() {
        // get api data
        $data_array['properties'] = $this->api_attributes();
        // if of the correct type get categories, tags, or labels
        if ($this->ctr() == 1 || $this->ctr() == 2 || $this->ctr() == 3 || $this->ctr() == 4) {
            $data_array['categories'] = $this->get_obj_categories_tags_labels('categories');
            $data_array['tags'] = $this->get_obj_categories_tags_labels('tags');
            $data_array['labels'] = $this->get_obj_categories_tags_labels('labels');
        }
        // if of the correct type get all images
        if ($this->ctr() == 1 || $this->ctr() == 3) {
            // set blank array, set below
            $image_array = [];
            // get image(s)
            if ($this->ctr() == 1) {
                $temp_array = $this->get_post_images();
            } else {                                               
                $temp_array = $this->get_user_image();
            }
            // loop over info to make new array
            $image_array = obj_array_api_prep($temp_array);
            // put images into the correct spot
            $data_array['images'] = $image_array;
        }
        // return data
        return $data_array;
    }

    // get api data
    public function get_basic_api_data() {
        // get api data
        $data_array['properties'] = $this->api_attributes();
        // return data
        return $data_array;
    }

    // get data and turn it into json
    public function get_api_data($type = 'basic') {
        // check to see which api data to use
        if ($type == 'basic') {
            $data_array = $this->get_basic_api_data();
        } else {
            $data_array = $this->get_full_api_data();
        }
        // return the data array
        return $data_array;
    }

}

?>