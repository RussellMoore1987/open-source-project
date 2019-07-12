<?php
    // check to see if it is a post or get request
    $request = $_SERVER['REQUEST_METHOD'];

    // check directions PUT/PATCH/DELETE/POST or GET
    if (is_post_request() || $request == "PUT" || $request == "PATCH" || $request == "DELETE") {
        echo $this->className::get_post_api_info($this->className, $this->pathStr);
    } else {
        // run class api
        echo $this->className::get_api_info($this->className, $this->pathStr);
    }
?>