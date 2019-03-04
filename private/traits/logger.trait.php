<?php
    // todo: finish
    trait Logger {
        protected function log(array $data_array) {
            // get file
            // turn information into an array
            // add to that array, the new log info
            // turning to Json
            // right back to file
            // return true
            return true;
        }  
        
        // default implementation, if needed you can override in class
        protected function log_implementation() {
            $data_array = [];
            // get info
            // turn into array
            // return array
            return $data_array;
        }  
    }

// possible implementation
// @ logging information start
    // file name
    // static protected $logFileName = 'user_log';
// @ logging information end
?>
