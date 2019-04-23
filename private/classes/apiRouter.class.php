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
            // if the query string is null then just direct the path to the apiIndex.php page
            if ($url == NULL) {
                $this->path = "index";

            // The url is defined
            } else {
                // get path from url
                $checkClassName = $this->get_class_path($url);
    
                // check to see if we have a path defined, if so set class name
                if (isset($this->pathInterpretation_array[$checkClassName])) {
                    // set className
                    $this->className = $this->pathInterpretation_array[$checkClassName];
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

        // Function to help get what class the path is trying to access
        private function get_class_path($url) {
            // Split up the url to find what class to access
            $splitPaths_array = explode('/', $url);
            $tempClassName = NULL;

            // Loop through the split up array to find what class to access and what the params are
            foreach($splitPaths_array as $path) {
                // The classname should be before the list of parameters, check when the '?' appears
                if(strpos($path, '?')) {
                    // explode the string into an array and get the classname
                    $temp_array = explode('?', $path);

                    // Set the classname
                    $tempClassName = $temp_array[0];
                    //break out of the loop
                    break;
                }
            }

            // Return the tempClassName
            return $tempClassName;
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