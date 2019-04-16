<?php
    class ApiRouter {
        // todo: check to see if there is a "/" if there is at the end of the path remove it
        // Parameters
        public $className = "noClass";
        public $pathInterpretation_array = [
            "categories" => "Category",
            "content" => "Content",
            "labels" => "Label",
            "mediaContent" => "MediaContent",
            "posts" => "Post",
            "tags" => "Tag",
            "users" => "User"
        ];
        public $path;

        // Constructor method, We expect the path and then parameters
        public function __construct($url) {
            // get path from url
            $parameters_array = explode("&", $url);
            // unset $_GET path, So that it doesn't show up as an option
            unset($_GET[$parameters_array[0]]);

            // check to see if we are getting the index, a particular path, or if that path does not exist
            if ($parameters_array[0] == " " || $parameters_array[0] == "index") {
                $this->path = "index";
            } else {
                // check to see if we have a path defined, if so set class name
                if (isset($this->pathInterpretation_array[$parameters_array[0]])) {
                    // set className
                    $this->className = $this->pathInterpretation_array[$parameters_array[0]];
                }
                // double check just to see if the class exists
                if (class_exists($this->className)) {
                    $this->path = "class";
                } else {
                    $this->path = "index";
                }
            }
        }

        // output method, either get them index or specific class
        public function output() {
            // check path
            if ($this->path == "index") {
                require_once PUBLIC_PATH . '/api/v1/apiIndex.php';
            } else {
                // run class api
                echo $this->className::get_api_info();
                // echo "Got class:{$this->className}, From path:{$parameters_array[0]}";
            }
        }
    }
?>

<?php

    // // ! feel free to change the names, should be located in the databaseObject class
    // // pseudo code for get API functionality
    // static function get_api_info() {
    //     // validate incoming parameters
    //     $PrepApiData_array = $this->validateAndPrepApiParameters($_GET);
    //     // check to see if we have errors
    //     if (!$PrepApiData_array['errors']) {
    //         // send in query
    //         // sqlOptions will contain [whereOptions],[sortingOptions],[columnOptions]
    //         $Obj_array = static::find_where($PrepApiData_array['sqlOptions']);
    //         // check to see if you got anything back, if yes move over and get API info
    //         if ($Obj_array) {
    //             // loop over and get api info
    //             foreach ($Obj_array as $Obj) {
    //                 // get api info
    //                 $ObjApiInfo = $Obj->get_obj_api_info();
    //                 // put info into a new array
    //                 $apiData_array[] = $ObjApiInfo;
    //             }
    //         }
    //     } else {
    //         // todo: construct error message
    //     }
    //     // create normal return body
    //         // todo: construct return body see if we need to add apiData_array, also check to see whether not we need to send back an error message
    //     // data into Json
    //         // todo: turn array into json
    //     // return json data

    // }
?>