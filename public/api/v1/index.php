<?php

// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint

// Begin structuring our response and packaging it to send back to the user

// TODO: 
// 1- Dynamic companyName
// 2- Dynamic termsOfUse
// 3- Dynamic contact
// 4- Dynamic root

// package our top level info - TOP Level
$generalInfo = array(
    "companyName" => "Placeholder Company",
    "termsOfUse" => "Placeholder Terms URL",
    "version" => "1.0.0",
    "contact" => "someone@someone.com",
    "description" => "Placeholder Description",
    "root" => "https://www.placeholdersite.com",
    "routes" => NULL
);

// package our main authentication - 2nd Level
$mainAuthentication = array(
    "required" => "If the system has an API key, it is required on all requests",
    "default" => "none",
    "example" => "root/public/api/v1/categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
);

// package all of our array together and send it back
$generalInfo['routes'] = $mainAuthentication;

$jsonData = json_encode($generalInfo);

echo $jsonData;

?>
