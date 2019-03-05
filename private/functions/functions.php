<?php

function url_for($script_path) {
  // add the leading '/' if not present
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

// reference: https://www.gyrocode.com/articles/php-urlencode-vs-rawurlencode/
// URL-encodes string
function u($string="") {
  return urlencode($string);
}
// URL-encode according to RFC 3986
function raw_u($string="") {
  return rawurlencode($string);
}

// escapes special characters, renders HTML harmless, ex " = &quot;
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

// allows page redirect
function redirect_to($location) {
  header("Location: " . $location);
  exit();
}

// checks to see if a post request has been submitted
function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

// checks to see if a get request has been submitted
function is_get_request() {
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// creates an array of key value pairs, relating to possible tags, categories, and labels. mostly used in classes
function get_key_value_array($obj_array) {
  // empty array
  $array = [];
  // loop through result to create a key value pair array
  while ($record = $obj_array) {
      $id = $obj_array->id; 
      $title = $obj_array->title; 
      $array[$id] = $title; 
  }
  // sort array alphabetically by title
  natcasesort($array);
  // return array
  return $array;
}

// get image path // * image_paths located at: root/private/reference_information.php
function get_image_path($type = 'small') {
  // just in case somebody spelled something wrong coming in make them go through the switch statement
  switch ($type) {
      case 'thumbnail': $type = 'thumbnail'; break;
      case 'medium': $type = 'medium'; break;
      case 'large': $type = 'large'; break;
      case 'original': $type = 'original'; break;
      default: $type = 'small'; break;
  }
  return IMAGE_PATH . "/{$type}" ;
}

// give it an array of objects and it will give you back an array of Json on objects ready for the API
function obj_array_api_prep(array $obj_array, $type = 'basic') {
    // set blank array, set below
    $apiObj_array = [];
    // loop over array to make new array of api ready info
    foreach ($obj_array as $odj) {
       $apiObj_array[] = $odj->get_api_data($type);
    }   
    // return data
    return $apiObj_array;
}


?>
