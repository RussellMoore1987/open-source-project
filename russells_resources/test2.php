<?php
   echo $_SERVER['REQUEST_METHOD'];
    var_dump($_POST);
    var_dump($_GET);
    // helps to get PUT and DELETE content body
    parse_str(file_get_contents("php://input"),$post_vars);
    var_dump($post_vars);
?>