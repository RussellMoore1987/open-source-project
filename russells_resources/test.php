<?php
    function post($url, $postVars = []){
        //Transform our POST array into a URL-encoded query string.
        $postStr = http_build_query($postVars);
        //Create an $options array that can be passed into stream_context_create.
        $options = array(
            'http' =>
                array(
                    'method'  => 'POST', //We are using the POST HTTP method.
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postStr //Our URL-encoded query string.
                )
        );
        //Pass our $options array into stream_context_create.
        //This will return a stream context resource.
        $streamContext  = stream_context_create($options);
        //Use PHP's file_get_contents function to carry out the request.
        //We pass the $streamContext variable in as a third parameter.
        $result = file_get_contents($url, false, $streamContext);
        //If $result is FALSE, then the request has failed.
        if($result === false){
            //If the request failed, throw an Exception containing
            //the error.
            $error = error_get_last();
            throw new Exception('POST request failed: ' . $error['message']);
        }
        //If everything went OK, return the response.
        return $result;
    }

    // $result = post("http://localhost/open_source_project/russells_resources/test2.php", $postVars = [
    //     "name" => "fred",
    //     "age" => "22"
    // ]);
    $result = post("http://localhost/open_source_project/public/api/v1/posts", $postVars = [
        "name" => "fred",
        "age" => "22"
    ]);

    echo "*******<br>";
    echo $result;
    echo "*******<br>";

//Send a POST request without cURL.
// $result = post('http://example.com', array(
//     'foo' => 'bar',
//     'name' => 'Wayne'
// ));
// echo $result;
?>