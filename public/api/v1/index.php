<?php

// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint

// Begin structuring our response and packaging it to send back to the user

// TODO: 
// 1- Dynamic companyName
// 2- Dynamic termsOfUse
// 3- Dynamic contact
// 4- Dynamic root

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
$authAndRoutes = array(
    "mainAuthentication" => NULL,
    "/categories" => NULL
);

// 3rd Level - category methods and mainAuthentication data
$mainAuthData = array(
    "required" => "If the system has an API key, it is required on all requests",
    "default" => "none",
    "example" => "root/public/api/v1/categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
);

$categoryMethods = array(
    "methods" => NULL
);

// 4th Level - available methods, GET, POST
$availableMethods = array(
    "availableMethods" => NULL
);

$getMethod = array(
    "GET" => NULL
);

$postMethod = array(
    "POST" => NULL
);

// Package all our arrays for the 4th level
$fourthLevelPackage[] = $availableMethods;
$fourthLevelPackage[] = $getMethod;
$fourthLevelPackage[] = $postMethod;

// You must link your json in reverse from the bottom of the structure to the top for it to package correctly

// Link the 3rd and 4th levels
$categoriesMethods['methods'] = $fourthLevelPackage;
// Link the 2nd and 3rd Levels
$authAndRoutes['mainAuthentication'] = $mainAuthData;
$authAndRoutes['/categories'] = $categoryMethods;
// Link the 1st and 2nd Levels
$generalInfo['routes'] = $authAndRoutes;

$jsonData = json_encode($generalInfo);

echo $jsonData;

?>
