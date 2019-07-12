<?php
    class Router {
        // Parameters
        public $path;
        public $pathJs;

        // Constructor method, We expect the path and then parameters
        public function __construct($url) {
            // get path from url
            $parameters_array = explode("&", $url);
            // unset $_GET path, So that it doesn't show up as an option
            unset($_GET[$parameters_array[0]]);

            // set path
            $this->path = $parameters_array[0] . ".php";
            $this->pathJs = $parameters_array[0] . ".js";
        }
    }
?>