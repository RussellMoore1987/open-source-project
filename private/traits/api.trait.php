<?php

// The trait for the API
trait Api {

    // Method for getting api info from the DB
    static function get_api_info() {
        // Array for holding the prepped api data
        $prepApiData_array['errors'] = [];

        // Get the paginationOptions
        $options_array = static::get_sorting_options($_GET);

        // If the GET params are not empty then validate and prep the parameters
        if(!empty($_GET)) {
            // validate incoming parameters
            $prepApiData_array['sqlOptions'] = static::validate_and_prep_api_parameters($_GET);

        } else {
            // create the empty sqlOptions
            $prepApiData_array['sqlOptions'] = [];
        }

        // check to see if we have errors
        if (!$prepApiData_array['errors']) {

            // sqlOptions will contain [whereOptions],[sortingOptions],[columnOptions]
            // Submit the query to get the data
            $Obj_array = static::find_where($prepApiData_array['sqlOptions']);

            // Set the totalPages by getting a count
            $totalPages = ceil(($Obj_array['count'] / $options_array['perPage']));

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
                "currentPage" => $options_array['page'],
                "totalPages" => $totalPages,
                "resultsPerPage" => $options_array['perPage'],
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
    // TODO: Add handling for sortingOptions
    static function validate_and_prep_api_parameters($getParams_array) {
        // Prepare the array we will use to hold our preped API data
        $prepApiData_array['errors'] = [];

        // Prep the API parameters to be used in the SQL
        foreach($getParams_array as $paramKey => $paramValue) {
            // Check if the parameter is defined in the class
            if(isset(static::$apiParameters[$paramKey])) {
                // Check if it is a sorting option
                if(static::$apiParameters[$paramKey]['refersTo'] === 'sortingOption') {
                   // Validate and prep the sortingOptions
                   $temp_array = static::validate_and_prep_sorting_options($getParams_array);
                
                // Check if the data is a list
                } elseif(is_list($paramValue)) {
                    // Check if we accept a list as a data type
                    if(!isset(static::$apiParameters[$paramKey]['connection']['list'])) {
                        $prepApiData_array['errors'][] = "{$paramKey} does not accept a list of values!";
                        break;
                    }
                    // Turn the list into an array and add it to our list of whereOptions
                    $newList_array = split_string_by_comma($paramValue);

                    // Prep the beginning of the string for holding our list of values
                    $valueList = "( ";
                    foreach($newList_array as $listItem) {
                        // Validate the value
                        $errors = self::validate_api_params($listItem, $paramKey);

                        // If there are errors then exit the function with the errors
                        if(!empty($errors)) {
                            // Add each error from the validation error array
                            foreach($errors as $err) {
                                $prepApiData_array['errors'][] = $err;
                            }
                            break;

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
                    $prepApiData_array['sqlOptions']['whereOptions'][] = [
                        "column" => static::$apiParameters[$paramKey]['refersTo'],
                        "operator" => static::$apiParameters[$paramKey]['connection']['list'],
                        "value" => $valueList
                    ];

                // The data is not a list or a sorting option
                } else {
                    // Validate the value
                    $errors = self::validate_api_params($paramValue, $paramKey);

                    // If there are errors then exit the function with the errors
                    if(!empty($errors)) {
                        // Add each error from the validation error array
                        foreach($errors as $err) {
                            $prepApiData_array['errors'][] = $err;
                        }
                        break;

                    // No errors were found add the data to the array
                    } else {
                        // The parameter was found, add the info needed to our array
                        $prepApiData_array['sqlOptions']['whereOptions'][] = [
                            "column" => static::$apiParameters[$paramKey]['refersTo'],
                            "operator" => static::$apiParameters[$paramKey]['connection'][static::$apiParameters[$paramKey]['type']],
                            "value" => $paramValue
                        ];
                    }
                }
            // There was no matching parameter, add to the errors array and return the array
            } else {
                $prepApiData_array['errors'][] = "{$paramKey} is not a valid parameter!";
                break;
            }
        }
        // Return the array
        return $prepApiData_array;
    }

    static function validate_and_prep_sorting_options($getParams_array) {
        // An array to hold the sorting options
        $options_array['data'] = [];
        $options_array['errors'] = [];

        // Determine if the page and perPage are set
        if(isset($getParams_array['page']) && isset($getParams_array['perPage'])) {
            // Validate the values
            $options_array['errors'][] = self::validate_api_params($getParams_array['page']);
            $options_array['errors'][] = self::validate_api_params($getParams_array['perPage']);

            // If the there are no errors set the values
            if(empty($options_array['errors'])) {
                // Set the values
                $options_array['data']['page'] = $getParams_array['page'];
                $options_array['data']['perPage'] = $getParams_array['perPage'];
            }

        // If only the page is defined
        } elseif(isset($getParams_array['page'])) {
            // Validate the values
            $options_array['errors'][] = self::validate_api_params($getParams_array['page']);

            // If there are no errors set the values
            if(empty($options_array['errors'])) {
                // Set the values
                $options_array['data']['page'] = $getParams_array['page'];
                $options_array['data']['perPage'] = static::$apiParameters['perPage']['default'];
            }

        // if only the perPage is defined
        } elseif(isset($getParams_array['perPage'])) {
            // Validate the values
            $options_array['errors'][] = self::validate_api_params($getParams_array['perPage']);

            // If there are no errors set the values
            if(empty($options_array['errors'])) {
                // Set the values
                $options_array['page'] = static::$apiParameters['page']['default'];
                $options_array['perPage'] = $getParams_array['perPage'];
            }

        // If neither are defined
        } else {
            // Set the values
            $options_array['page'] = static::$apiParameters['page']['default'];
            $options_array['perPage'] = static::$apiParameters['perPage']['default'];
        }

        // Calculate the limit and offset
        $limit = $options_array['perPage'];
        $offset = (($options_array['page'] - 1) * $limit) + ($options_array['page'] - 1);

        // TODO: check for other sorting options to validate and prep
        // TODO: also set up the sorting options in the correct format before returning

        // Add the limit and offset to the array
        $options_array['data']['sortingOptions']['limit'] = $limit;
        $options_array['data']['sortingOptions']['offset'] = $offset;

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
        } elseif(isset(static::$validation_columns[static::$apiParameters[$param]['refersTo']])) {
            // Set the default validation
            $defaultValidation = static::$validation_columns[static::$apiParameters[$param]['refersTo']];
            // Validate based on the default validation
            $errors = val_validation($value, $defaultValidation);
            // Return the validation errors array
            return $errors;

        // else there were no validation defined. Return the error.
        } else {
            $errors[] = "Parameter: {$param} with Value: {$value} was rejected as there are no validation rules defined.";
            // Return the error
            return $errors;
        }
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