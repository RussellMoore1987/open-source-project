<?php
    class ApiRouter {
        // Parameters
        protected $className = "noClass";
        protected $pathInterpretation_array = [];
        protected $classList_array = [];
        public $path;
        public $pathStr;
        // error message properties
        public $errorEndpointMessage;
        public $apiIndexErrorMessage;

        // Constructor method, We expect the path and then parameters
        public function __construct($url) {
            // set $pathInterpretation_array
            $this->classList_array = DatabaseObject::get_class_list();
            // check to see if we have any classes available
            if ($this->classList_array) {
                // reformat classList array
                foreach ($this->classList_array as $className) {
                    // get apiInfo array
                    $apiInfo_array = $className::get_api_class_info();
                    // check to see if we should add it to the array, can we even use it in the API
                    if ($apiInfo_array) {
                        // loop over apiInfo_array to create array of routes
                        foreach ($apiInfo_array['routes'] as $routName => $routInfo) {
                            $apiClassList_array[$routName] = $className;
                        }
                    }
                }
                $this->pathInterpretation_array = $apiClassList_array ?? [];

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
                if ($this->pathStr == "" || $this->pathStr == "index") {
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
                        $this->path = "pathNotFound";
                        $this->apiIndexErrorMessage =  "'{$this->pathStr}' is not a valid API path. Please view documentation at " . PUBLIC_LINK_PATH . "/api/restApi/v1/ for viable API paths";
                        $this->errorEndpointMessage = "'{$this->pathStr}' path not found";
                    }
                }
            } else {
                $this->path = "pathNotFound";
                $this->apiIndexErrorMessage =  "No API endpoints established for the system";
                $this->errorEndpointMessage = "No API endpoints established for the system";
            }
        }

        // output method, either get them index or specific class
        public function output() {
            // check path
            if ($this->path == "index") {
                // index/documentation page
                if (file_exists(PUBLIC_PATH . "/api/restApi/v1/setIndex.php")) {
                    require_once PUBLIC_PATH . '/api/restApi/v1/setIndex.php';
                } else {
                    require_once PUBLIC_PATH . '/api/restApi/v1/apiIndex.php';
                }
            } elseif ($this->path == "pathNotFound") {
                // setting error messages
                $success = "false";
                $endpoint = $this->pathStr ?? "index";
                $errors = $this->errorEndpointMessage;
                $content = $this->apiIndexErrorMessage;
                // used the responseMessage for the error message
                require_once PUBLIC_PATH . '/api/restApi/v1/responseMessage.php';
            } else {
                // specific endpoints
                require_once PUBLIC_PATH . '/api/restApi/v1/apiEndPoint.php';
            }
        }
    }
?>