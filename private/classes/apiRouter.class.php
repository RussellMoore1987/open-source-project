<?php
    class ApiRouter {
        // Parameters
        protected $className = "noClass";
        protected $pathInterpretation_array = [];
        protected $classList_array = [];
        public $path;
        public $pathStr;

        // Constructor method, We expect the path and then parameters
        public function __construct($url) {
            // set $pathInterpretation_array
            $this->classList_array = DatabaseObject::get_class_list();
            // check to see if we have any classes available
            if ($this->classList_array) {
                // reformat classList array
                foreach ($this->classList_array as $key => $value) {
                    // check to see if we should add it to the array, can we even use it in the API
                    if (!isset($this->classList_array[$key]['apiReference'])) { continue; }
                    // load up a new array API ready classes, key => value, posts => Post
                    $apiClassList_array[$value['apiReference']] = $key;
                }
                $this->pathInterpretation_array = $apiClassList_array;

                // get path from url
                $parameters_array = explode("&", $url);
                // unset $_GET path, So that it doesn't show up as an option
                unset($_GET[$parameters_array[0]]);
                // set path into local variable
                $this->pathStr = $parameters_array[0];
                // see if we need to remove the "/"
                if (substr($this->pathStr, -1) == "/") {
                    // remove that character
                    $this->pathStr = substr_replace($this->pathStr,"",-1);
                }

                // check to see if we are getting the index, a particular path, or if that path does not exist
                if ($this->pathStr == " " || $this->pathStr == "index") {
                    $this->path = "index";
                } else {
                    // check to see if we have a path defined, if so set class name
                    if (isset($this->pathInterpretation_array[$this->pathStr])) {
                        // set className
                        $this->className = $this->pathInterpretation_array[$this->pathStr];

                        // double check just to see if the class exists
                        if (class_exists($this->className)) {
                            $this->path = "class";
                        } else {
                            $this->path = "index";
                        }
                    } else {
                        $this->path = "index";
                    }
                }
            } else {
                echo "No api endpoints established for the system";
            }
        }

        // output method, either get them index or specific class
        public function output() {
            // check path
            if ($this->path == "index") {
                require_once PUBLIC_PATH . '/api/v1/apiIndex.php';
            } else {
                require_once PUBLIC_PATH . '/api/v1/apiEndPoint.php';
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