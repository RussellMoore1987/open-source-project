<?php
    // TODO: put this stuff into an include
    // var_dump($_POST);
    // echo "Got class:{$this->className}, From path:{$this->pathStr}";
    // check to see if it is a post or get request
    if (is_post_request()) {
        echo json_encode($_POST);
    } else {
        echo json_encode($_GET);
        // run class api
        // echo $this->className::get_api_info();
    }
?>