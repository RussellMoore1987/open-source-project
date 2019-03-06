<?php
    $data = file_get_contents('https://mooredigitalsolutions.com/wp-json/wp/v2/posts');
    $data = json_decode($data);
    var_dump($data);
?>