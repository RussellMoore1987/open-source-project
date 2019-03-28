<?php

// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint

// Begin structuring our response and packaging it to send back to the user

// TODO: 
// 1- Dynamic companyName
// 2- Dynamic termsOfUse
// 3- Dynamic contact
// 4- Dynamic root
// 5- Force HTTPS communication
// 6- HTTP status codes and error returning

// 1st Level - Genral Info
$generalInfo = array(
    "companyName" => "Placeholder Company",
    "termsOfUse" => "Placeholder Terms URL",
    "version" => "1.0.0",
    "contact" => "someone@someone.com",
    "description" => "Placeholder Description",
    "root" => "https://www.placeholdersite.com",
    "mainPath" => "/public/api/v1/",
    "routes" => NULL
);

// 2nd Level - mainAuthentication and route endpoints
$routes = array(
    "mainAuthentication" => NULL,
    "/categories" => NULL
);

// 3rd Level - category methods and mainAuthentication data
$mainAuthentication = array(
    "required" => "If the system has an API key, it is required on all requests",
    "default" => "none",
    "example" => "root/public/api/v1/categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
);

$categories = array(
    "methods" => NULL
);

// 4th Level - available methods, GET, POST
$methods = array(
    "availableMethods" => NULL,
    "GET" => NULL,
    "POST" => NULL
);

// 5th level - available GET, POST, parameters
$availableMethods = array(
    "GET" => "to get information",
    "POST" => "to post information"
);

$get = array(
    "parameters" => NULL
);

// TODO: put POST information here

// 6th level - no params, id, createdDate, greaterThan, lessThan, ctr


// You must link your json in reverse from the bottom of the structure to the top for it to package correctly

// Link the 4th and 5th Levels
$methods['availableMethods'] = $availableMethods;
$methods['GET'] = $get;

// Link the 3rd and 4th levels
$categories['methods'] = $methods;

// Link the 2nd and 3rd Levels
$routes['mainAuthentication'] = $mainAuthentication;
$routes['/categories'] = $categories;

// Link the 1st and 2nd Levels
$generalInfo['routes'] = $routes;

$jsonData = json_encode($generalInfo);

echo $jsonData;

?>
