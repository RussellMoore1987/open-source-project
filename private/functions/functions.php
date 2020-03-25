<?php

function url_for($script_path) {
    // add the leading '/' if not present
    if($script_path[0] != '/') {
        $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
}

// reference: https://www.gyrocode.com/articles/php-urlencode-vs-rawurlencode/
// # URL-encodes string
function u($string="") {
    return urlencode($string);
}
// # URL-encode according to RFC 3986
function raw_u($string="") {
    return rawurlencode($string);
}

// # escapes special characters, renders HTML harmless, ex " = &quot;
function h($string="") {
    return htmlspecialchars($string);
}

function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}

function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
}

// # allows page redirect
function redirect_to($location) {
    header("Location: " . $location);
    exit();
}

// # checks to see if a post request has been submitted
function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// # checks to see if a get request has been submitted
function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// # send post request
function post($url, $postVars = [], $postType = "POST"){
    //Transform our POST array into a URL-encoded query string.
    $postStr = http_build_query($postVars);
    //Create an $options array that can be passed into stream_context_create.
    $options = array(
        'http' =>
            array(
                'method'  => $postType,
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postStr,
                // added to get content back even if error
                'ignore_errors' => true
            )
    );
    //Pass our $options array into stream_context_create.
    $streamContext  = stream_context_create($options);
    //Use PHP's file_get_contents function to carry out the request.
    $result = file_get_contents($url, false, $streamContext);
    // If $result is FALSE, then the request has failed.
    if($result === false){
        // If the request failed, throw an Exception containing
        $error = error_get_last();
        throw new Exception('POST request failed: ' . $error['message']);
    }
    // return the response.
    return $result;
}

// # Checks if a string is a comma separated list of values then turns it into an array and returns it
function split_string_by_separator($string, $separator=",") {
    // Return false if there are no commas in the string
    if (strpos($string, $separator) == false) {
      return false;
      
    } else {
      // Get the array of values from the comma separated string
      $new_array = explode($separator, $string);
      // Clean the whitespace from each value, put into new array and return it
      $clean_array = [];
      foreach($new_array as $item) {
        $clean_array[] = trim($item);
      }
      return $clean_array;
    }
  }

// # function for formating the date to the Y-m-d SQL format
function format_date($date) {
    // Turn date to time string, never fails
    $dateStr = strtotime($date);
    // Format the string to an SQL format
    $formatDate = date("Y-m-d", $dateStr);
    // Return the formatted date
    return $formatDate;
}  

// # creates an array of key value pairs, relating to possible tags, categories, and labels. mostly used in classes
function get_key_value_array($obj_array) {
    // empty array
    $array = [];
    // loop through result to create a key value pair array
    foreach ($obj_array as $record) {
        $id = $record->id; 
        $title = $record->title; 
        $array[$id] = $title; 
    }
    // sort array alphabetically by title
    natcasesort($array);
    // return array
    return $array;
}

// # get image path // * image_paths located at: root/private/rules_docs/reference_information.php
function get_image_path($type = 'small') {
    // just in case somebody spelled something wrong coming in make them go through the switch statement
    switch ($type) {
        case 'thumbnail': $type = 'thumbnail'; break;
        case 'medium': $type = 'medium'; break;
        case 'large': $type = 'large'; break;
        case 'original': $type = 'original'; break;
        default: $type = 'small'; break;
    }
    return IMAGE_LINK_PATH . "/{$type}" ;
}

// # give it an array of objects and it will give you back an array of Json on objects ready for the API
function obj_array_api_prep(array $obj_array) {
    // set blank array, set below
    $apiObj_array = [];
    // loop over array to make new array of api ready info
    foreach ($obj_array as $odj) {
        $apiObj_array[] = $odj->get_api_data();
    }   
    // return data
    return $apiObj_array;
}

// # make a list into an array where key and value are the same
function list_to_array($list) {
    // make array from ids
    $list_array = explode(",", $list);
    // set array
    $associativeList_array = [];
    // fill array with form data
    foreach ($list_array as $value) {
        $associativeList_array[$value] = $value;
    }
    // return array
    return $associativeList_array;
}

// # sort by key, case insensitive, Built for associative arrays
function full_natural_key_sort(array $array) {
    // check to see if the array is empty
    if ($array) {
        // sort array keys, case insensitive
            // loop over and get keys
            foreach ($array as $key => $value) {
                $temp_array[] = $key;
            }
            // sort the temp array, case insensitive
            natcasesort($temp_array);
            // sort array to make new array
            foreach ($temp_array as $value) {
                $new_array[] = $array[$value];
            }
            // return new array
            return $new_array;
    }
}

// # get_url_ctr
function get_url_ctr() {
    $ctr = $_GET["ctr"] ?? 1;
    // make it a number
    $ctr = (int) $ctr;
    // make sure the ctr is corrected, 1-4 ok let them pass
    if ($ctr > 4 || $ctr < 1) {
        // default to 1
        $ctr = 1;
    }
    // return data
    return $ctr;
}

// # remove characters from string
function remove_char_from_str(array $arrayOfStrings = [], $string = "") {
    // over characters to remove
    foreach ($arrayOfStrings as $char) {
        $string = str_replace($char, "", $string);
    }
    // return data
    return $string;
}

// # replace characters in string
function replace_char_in_str(array $arrayOfStrings = [], array $arrayOfStringsToReplace = [], $string = "") {
    // over characters to remove
    for ($i=0; $i < count($arrayOfStrings); $i++) { 
        $string = str_replace($arrayOfStrings[$i], $arrayOfStringsToReplace[$i], $string);
    }
    // return data
    return $string;
}

// # merge data arrays
function merge_data_arrays(array $data_array, array $temp_array) {
    // merge arrays
    // loop over errors and put them in the right spot
    if (isset($temp_array['errors']) && $temp_array['errors']) {
        foreach ($temp_array['errors'] as $value) {
            $data_array['errors'][] = $value;
        }
    }
    // loop over content and put them in the right spot
    if (isset($temp_array['message']) && $temp_array['message']) {
        foreach ($temp_array['message'] as $value) {
            $data_array['message'][] = $value;
        }
    }

    // return merged array
    return $data_array;
}
?>
