<?php

// The trait for the API
trait Api {

    // ! feel free to change the names, should be located in the databaseObject class
    // pseudo code for get API functionality
    static function get_api_info() {
        // validate incoming parameters
        $PrepApiData_array = static::validateAndPrepApiParameters($_GET);
        // check to see if we have errors
        if (!$PrepApiData_array['errors']) {
            // send in query
            // sqlOptions will contain [whereOptions],[sortingOptions],[columnOptions]
            $Obj_array = static::find_where($PrepApiData_array['sqlOptions']);
            // check to see if you got anything back, if yes move over and get API info
            if ($Obj_array) {
                // loop over and get api info
                foreach ($Obj_array as $Obj) {
                    // get api info
                    $ObjApiInfo = $Obj->get_obj_api_info();
                    // put info into a new array
                    $apiData_array[] = $ObjApiInfo;
                }
            }
        } else {
            // todo: construct error message
        }
        // create normal return body
            // todo: construct return body see if we need to add apiData_array, also check to see whether not we need to send back an error message
        // data into Json
            // todo: turn array into json
        // return json data

    }

    // Validate and prep the API parameters
    static function validateAndPrepApiParameters($getParams_array) {
        // Prepare the array we will use to hold our preped API data
        $prepApiData_array['errors'] = [];

        // Prep the API parameters to be used in the SQL
        foreach($getParams_array as $paramKey => $paramValue) {
            // If the API parameter is defined then add the info to our prepped array
            if(isset(static::$apiParameters[$paramKey]) && static::$apiParameters[$paramKey] == $paramKey) {
                // Check if the data is a list
                if(isList($paramValue)) {
                    // Check if we accept a list as a data type
                    if(isset(static::$apiParameters[$paramKey]['connection']['list'])) {
                        $prepApiData_array['errors'][] = "{$paramKey} does not accept a list of values!";
                        return $prepApiData_array;
                    }
                    // Turn the list into an array and add it to our list of whereOptions
                    $newList_array = split_string_by_comma($paramValue);
                    foreach($newList_array as $listItem) {
                        // The parameter was found, add the info needed to our array
                        $prepApiData_array['sqlOptions']['whereOptions'] = [
                            "column" => static::$apiParameters[$paramKey]['refersTo'],
                            "operator" => static::$apiParameters[$paramKey]['connection']['list'],
                            "value" => $listItem
                        ];
                    }

                // The data is not a list
                } else {
                    // The parameter was found, add the info needed to our array
                    $prepApiData_array['sqlOptions']['whereOptions'] = [
                        "column" => static::$apiParameters[$paramKey]['refersTo'],
                        "operator" => static::$apiParameters[$paramKey]['connection'][static::$apiParameters[$paramKey]['type']],
                        "value" => $paramValue
                    ];
                }
                // The parameter was found, add the info needed to our array
                $prepApiData_array['sqlOptions']['whereOptions'] = [$paramKey];

            // There was no matching parameter, add to the errors array and return the array
            } else {
                $prepApiData_array['errors'][] = "{$paramKey} is not a valid parameter!";
                return $prepApiData_array;
            }
        }

        // Validate the parameters from the $getParams_array
        foreach($getParams_array as $param) {
            static::validate($param);
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
        // turn array into Jason
        $jsonData_array = json_encode($data_array);
        // return data
        return $jsonData_array;
    }

}

?>