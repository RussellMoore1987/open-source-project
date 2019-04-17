<?php

// The trait for the API
trait Api {
        // ! feel free to change the names, should be located in the databaseObject class
    // pseudo code for get API functionality
    static function get_api_info() {
        // validate incoming parameters
        $PrepApiData_array = $this->validateAndPrepApiParameters($_GET);
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
}

?>