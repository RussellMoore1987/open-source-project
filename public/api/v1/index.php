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

// ----------- 1st Level - Genral Info -------------------------------------------------
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

// ----------- 2nd Level - mainAuthentication and route endpoints ------------------------
$routes = array(
    "mainAuthentication" => NULL,
    "/categories" => NULL,
    "/posts" => NULL
);

// ---------- 3rd Level - category, posts, and mainAuthentication data -----------------
$mainAuthentication = array(
    "required" => "If the system has an API key, it is required on all requests",
    "default" => "none",
    "example" => "root/public/api/v1/categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
);

// Categories
$categories = array(
    "methods" => NULL
);

// Posts
$posts = array(
    "methods" => NULL
);

// --------- 4th Level - available methods, GET -----------------------------------------
// Categories
$categoryMethods = array(
    "availableMethods" => NULL,
    "GET" => NULL
);

// Posts
$postMethods =array(
    "availableMethods" => NULL,
    "GET" => NULL
);

// ---------- 5th level - GET, parameters -----------------------------------------------
// Categories
$categoryAvailableMethods = array(
    "GET" => "To get categories information"
);

$categoryGet = array(
    "parameters" => NULL
);

// Posts
$postAvailableMethods = array(
    "GET" => "To get posts information"
);

$postGet = array(
    "parameters" => NULL
);

// ---------- 6th level - no params, id, createdDate, greaterThan, lessThan, ctr -------------
// Categories
$categoryGetParameters = array(
    "noParamsSent" => NULL,
    "id" => NULL,
    "ctr" => NULL,
    "createdDate" => NULL,
    "greaterThan" => NULL,
    "lessThan" => NULL
);

// Posts
$postGetParameters = array(
    "noParamsSent" => NULL,
    "id" => NULL,
    "createdDate" => NULL,
    "greaterThan" => NULL,
    "lessThan" => NULL,
    "status" => NULL
    // TODO: Any additonal parameters? By the id of the user that created the post?
);

// ------------ 7th level - info and examples for each parameter -----------------------------
// Categories
$categoryGetNoParamsSent = array(
    "description" => "When no parameters are passed then all categories are returned",
    "example" => "root/public/api/v1/categories/"
);

$categoryGetId = array(
    "required" => false,
    "type" => "int / list",
    "description" => "Gets categoires by the category id or list of cateogry ids",
    "example" => NULL
);

$CategoryGetCtr = array(
    "required" => false,
    "type" => "int",
    "description" => "Gets categories by the Collection Type Reference. 0 = none, 1 = posts, 2 = media content, 3 = users, 4 = content",
    "example" => "root/public/api/v1/categories/?ctr=3"
);

$categoryGetCreatedDate = array(
    "required" => false,
    "type" => "date",
    "description" => "Gets categories by the date they were created",
    "example" => "root/public/api/v1/categories/?createdDate='2019-02-01'"
);

$categoryGetGreaterThan = array(
    "required" => false,
    "type" => "date",
    "description" => "Gets categories that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan paramter to get dates in categories with createdDates between the two values",
    "example" => NULL
);

$categoryGetLessThan = array(
    "required" => false,
    "type" => "date",
    "description" => "Gets categories that have a createdDate <= the date given with the lessThan parameter. May be used with the greaterThan paramter to get dates in categories with createdDates between the two values",
    "example" => NULL
);

// Posts
$postGetNoParamsSent = array(
    "description" => "When no parameters are passed then all posts are returned",
    "example" => "root/public/api/v1/posts/"
);

$postGetId = array(
    "required" => false,
    "type" => "int / list",
    "description" => "Gets posts by the post id or list of post ids",
    "example" => NULL
);

$postGetCreatedDate = array(
    "required" => false,
    "type" => "date",
    "description" => "Gets posts by the date they were created",
    "example" => "root/public/api/v1/posts/?createdDate='2019-02-01'"
);

$postGetGreaterThan = array(
    "required" => false,
    "type" => "date",
    "description" => "Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan paramter to get dates in posts with createdDates between the two values",
    "example" => NULL
);

$postGetLessThan = array(
    "required" => false,
    "type" => "date",
    "description" => "Gets posts that have a createdDate <= the date given with the lessThan parameter. May be used with the greaterThan paramter to get dates in posts with createdDates between the two values",
    "example" => NULL
);

$postGetStatus = array(
    "required" => false,
    "type" => "int",
    "description" => "Gets posts by the current post approval status. 0 = Unapproved, 1 = Approved",
    "example" => "root/public/api/v1/posts/?status=1"
);


// ---------------------- 8th Level - examples ---------------------------------------
// Categories
$categoryGetIdExamples = array(
    "intExample" => "root/public/api/v1/categories/?id=5",
    "listExample" => "root/public/api/v1/categories/?id=5,6,7,8,9"
);

$categoryGetGreaterThanExamples = array(
    "greaterThan" => "root/public/api/v1/categories/?greaterThan='2018-02-01'",
    "between" => "root/public/api/v1/categories/?greaterThan='2018-02-01'&lessThan='2019-03-01'"
);

$categoryGetLessThanExamples = array(
    "lessThan" => "root/public/api/v1/categories/?lessThan='2019-03-01'",
    "between" => "root/public/api/v1/categories/?greaterThan='2018-02-01'&lessThan='2019-03-01'"
);

// Posts
$postGetIdExamples = array(
    "intExample" => "root/public/api/v1/posts/?id=5",
    "listExample" => "root/public/api/v1/posts/?id=5,6,7,8,9"
);

$postGetGreaterThanExamples = array(
    "greaterThan" => "root/public/api/v1/posts/?greaterThan='2018-02-01'",
    "between" => "root/public/api/v1/posts/?greaterThan='2018-02-01'&lessThan='2019-03-01'"
);

$postGetLessThanExamples = array(
    "lessThan" => "root/public/api/v1/posts/?lessThan='2019-03-01'",
    "between" => "root/public/api/v1/posts/?greaterThan='2018-02-01'&lessThan='2019-03-01'"
);

// Package the data structure to prepare for json encoding
// NOTE: You must link your data in reverse from the bottom of the data structure to the top for it to package correctly

// ========= Link the 7th and 8th levels =============
// Categories
$categoryGetId['example'] = $categoryGetIdExamples;
$categoryGetGreaterThan['example'] = $categoryGetGreaterThanExamples;
$categoryGetLessThan['example'] = $categoryGetLessThanExamples;

// Posts
$postGetId['example'] = $postGetIdExamples;
$postGetGreaterThan['example'] = $postGetGreaterThanExamples;
$postGetLessThan['example'] = $postGetLessThanExamples;

// ======== Link the 6th and 7th levels ===============
// Categories
$categoryGetParameters['noParamsSent'] = $categoryGetNoParamsSent;
$categoryGetParameters['id'] = $categoryGetId;
$categoryGetParameters['ctr'] = $CategoryGetCtr;
$categoryGetParameters['createdDate'] = $categoryGetCreatedDate;
$categoryGetParameters['greaterThan'] = $categoryGetGreaterThan;
$categoryGetParameters['lessThan'] = $categoryGetLessThan;

// Posts
$postGetParameters['noParamsSent'] = $postGetNoParamsSent;
$postGetParameters['id'] = $postGetId;
$postGetParameters['createdDate'] = $postGetCreatedDate;
$postGetParameters['greaterThan'] = $postGetGreaterThan;
$postGetParameters['lessThan'] = $postGetLessThan;
$postGetParameters['status'] = $postGetStatus;

// ======== Link the 5th and 6th levels ===============
$categoryGet['parameters'] = $categoryGetParameters;
$postGet['parameters'] = $postGetParameters;

// ========= Link the 4th and 5th levels ==============
$categoryMethods['availableMethods'] = $categoryAvailableMethods;
$categoryMethods['GET'] = $categoryGet;
$postMethods['availableMethods'] = $postAvailableMethods;
$postMethods['GET'] = $postGet;

// ========= Link the 3rd and 4th levels ================
$categories['methods'] = $categoryMethods;
$posts['methods'] = $postMethods;

// ========= Link the 2nd and 3rd levels ================
$routes['mainAuthentication'] = $mainAuthentication;
$routes['/categories'] = $categories;
$routes['/posts'] = $posts;

// ========= Link the 1st and 2nd levels ================
$generalInfo['routes'] = $routes;

// JSON encode the data structure and return it
$jsonData = json_encode($generalInfo);
echo $jsonData;

?>
