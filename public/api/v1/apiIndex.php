<?php
require_once('../../../private/functions/functions.php');
// This is the endpoint that will display general information and navigation for the API
// the information is displayed in JSON format upon a request to this endpoint: root/public/api/v1/
// Check to be sure we are using https communication, if not then force it.
// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
//     $redirectUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     redirect_to($redirectUrl);
//     exit();
// }
// TODO: 
// 1- Dynamic companyName
// 2- Dynamic termsOfUse
// 3- Dynamic contact
// 4- Dynamic root
// 5- Force HTTPS communication
// 6- HTTP status codes and error returning
// ----------------------------------------- Root API Data -------------------------------------------------
$apiRoot = array(
    // General Info
    "companyName" => "Placeholder Company",
    "termsOfUse" => "Placeholder Terms URL",
    "version" => "1.0.0",
    "contact" => "someone@someone.com",
    "description" => "V1.0.0 of the api. This API may be used to retrieve data fromt the CMS system and in some cases create data. If the system has an API key it is required on all requests.",
    "root" => "https://www.placeholdersite.com",
    "mainPath" => "/public/api/v1/",
    // Routes
    "routes" => [
        // Main Authentication
        "mainAuthentication" => [
            "required" => "If the system has an API key, it is required on all requests",
            "default" => "none",
            "example" => "root/public/api/v1/categories/?authToken=12466486351864sd4f8164g89rt6rgfsdfunwiuf74"
        ],
        // Categories
        "/categories" => [
            "methods" => [
                "availableMethods" => [
                    "GET" => "To get categories information"
                ],
                "GET" => [
                    "parameters" => [
                        "noParamsSent" => [
                            "description" => "When no parameters are passed then all categories are returned",
                            "example" => "root/public/api/v1/categories/"
                        ],
                        "id" => [
                            "required" => false,
                            "type" => "int / list",
                            "description" => "Gets categoires by the category id or list of category ids",
                            "example" => [
                                "intExample" => "root/public/api/v1/categories/?id=5",
                                "listExample" => "root/public/api/v1/categories/?id=5,6,7,8,9"
                            ]
                        ],
                        "ctr" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Gets categories by the Collection Type Reference. 0 = all, 1 = posts, 2 = media content, 3 = users, 4 = content",
                            "example" => "root/public/api/v1/categories/?ctr=3"
                        ],
                        "subCatId" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Gets categories by the Sub Category id or list of sub category ids",
                            "example" => [
                                "intExample" => "root/public/api/v1/categories/?subCatId=5",
                                "listExample" => "root/public/api/v1/categories/?subCatId=5,6,7,8,9"
                            ]
                        ]
                    ]
                ]
            ]
        ],
        // Posts
        "/posts" => [
            "methods" => [
                "availableMethods" => [
                    "GET" => "To get posts information"
                ],
                "GET" => [
                    "parameters" => [
                        "noParamsSent" => [
                            "description" => "When no parameters are passed then all posts are returned",
                            "example" => "root/public/api/v1/posts/"
                        ],
                        "id" => [
                            "required" => false,
                            "type" => "int / list",
                            "description" => "Gets posts by the post id or list of post ids",
                            "example" => [
                                "intExample" => "root/public/api/v1/posts/?id=5",
                                "listExample" => "root/public/api/v1/posts/?id=5,6,7,8,9"
                            ]
                        ],
                        "createdDate" => [
                            "required" => false,
                            "type" => "date",
                            "description" => "Gets posts by the date they were created",
                            "example" => "root/public/api/v1/posts/?createdDate='2019-02-01'"
                        ],
                        "postDate" => [
                            "required" => false,
                            "type" => "date",
                            "description" => "Gets posts by the post date", // TODO: Define what the post date is....
                            "example" => "root/public/api/v1/posts/?createdDate='2019-02-01'"
                        ],
                        "greaterThan" => [
                            "required" => false,
                            "type" => "date",
                            "description" => "Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan paramter to get dates in posts with createdDates between the two values",
                            "example" => [
                                "greaterThan" => "root/public/api/v1/posts/?greaterThan='2018-02-01'",
                                "between" => "root/public/api/v1/posts/?greaterThan='2018-02-01'&lessThan='2019-03-01'"
                            ]
                        ],
                        "lessThan" => [
                            "required" => false,
                            "type" => "date",
                            "description" => "Gets posts that have a createdDate <= the date given with the lessThan parameter. May be used with the greaterThan paramter to get dates in posts with createdDates between the two values",
                            "example" => [
                                "lessThan" => "root/public/api/v1/posts/?lessThan='2019-03-01'",
                                "between" => "root/public/api/v1/posts/?greaterThan='2018-02-01'&lessThan='2019-03-01'"
                            ]
                        ],
                        "status" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Gets posts by the current post approval status. 0 = Unapproved, 1 = Approved",
                            "example" => "root/public/api/v1/posts/?status=1"
                        ],
                        "extendedData" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Returns all extended post data. 0 = Return basic post data, 1 = Return extended post data. Default is 0.  ",
                            "example" => "root/public/api/v1/posts/?extendedData=1"
                        ],
                        "allImages" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Returns all images associated with the posts. 0 = Return no images, 1 = Return all images. Default is 0.  ",
                            "example" => "root/public/api/v1/posts/?allImages=1"
                        ],
                        "page" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Returns the specified set of posts for the page of results requested. Default = 1",
                            "example" => "root/public/api/v1/posts?page=1"
                        ],
                        "perPage" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Specifies the number of results to return with each page of information.",
                            "example" => "root/public/api/v1/posts?perPage=50"
                        ]
                    ]
                ],
                "exampleResponse" => [
                    // TODO: Make this example response dynamic based on the current CMS system
                    "successResponse" => [
                        "success" => true,
                        "statusCode" => 200,
                        "errors" => [],
                        "requestType" => "GET",
                        "totalPages" => 4,
                        "currentPage" => 1,
                        "posts" => [
                            [
                                "id" => 1,
                                "createdBy" => 69,
                                "catIds" => [
                                    3, 4, 5, 6
                                ],
                                "tagIds" => [
                                    2, 3
                                ],
                                "labelIds" => [
                                    9, 6, 5
                                ],
                                "content" => [
                                    "url" => "www.something.com/somethingelse/thisthing",
                                    "status" => 1,
                                    "title" => "This Title",
                                    "description" => "A description",
                                    "contentUrl" => "www.anotherurl.com/thisone"
                                ]
                            ]
                        ]
                    ],
                    "errorResponse" => [
                        "success" => false,
                        "statusCode" => 500,
                        "errors" => [
                            "code" => 500,
                            "message" => "500 Internal Server Error"
                        ],
                        "requestType" => "GET",
                        "totalPages" => 1,
                        "currentPage" => 1
                    ]
                ]
            ]
        ],
        // Tags
        "/tags" => [
            "methods" => [
                "availableMethods" => [
                    "GET" => "To get tags information"
                ],
                "GET" => [
                    "parameters" => [
                        "noParamsSent" => [
                            "description" => "When no parameters are passed then all tags are returned",
                            "example" => "root/public/api/v1/tags/"
                        ],
                        "id" => [
                            "required" => false,
                            "type" => "int / list",
                            "description" => "Gets tags by the tag id or list of tag ids",
                            "example" => [
                                "intExample" => "root/public/api/v1/tags/?id=5",
                                "listExample" => "root/public/api/v1/tags/?id=5,6,7,8,9"
                            ]
                        ],
                        "ctr" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Gets tags by the Collection Type Reference. 0 = all, 1 = posts, 2 = media content, 3 = users, 4 = content",
                            "example" => "root/public/api/v1/tags/?ctr=3"
                        ]
                    ]
                ]
            ]
        ],
        // Labels
        "/labels" => [
            "methods" => [
                "availableMethods" => [
                    "GET" => "To get labels information"
                ],
                "GET" => [
                    "parameters" => [
                        "noParamsSent" => [
                            "description" => "When no parameters are passed then all labels are returned",
                            "example" => "root/public/api/v1/labels/"
                        ],
                        "id" => [
                            "required" => false,
                            "type" => "int / list",
                            "description" => "Gets labels by the label id or list of label ids",
                            "example" => [
                                "intExample" => "root/public/api/v1/labels/?id=5",
                                "listExample" => "root/public/api/v1/labels/?id=5,6,7,8,9"
                            ]
                        ],
                        "ctr" => [
                            "required" => false,
                            "type" => "int",
                            "description" => "Gets labels by the Collection Type Reference. 0 = all, 1 = posts, 2 = media content, 3 = users, 4 = content",
                            "example" => "root/public/api/v1/labels/?ctr=3"
                        ]
                    ]
                ]
            ]
        ]
    ]
);
// Dynamic content
// API Root dynamic content
$apiRoot['mainPath'] = $apiRoot['root'] . $apiRoot['mainPath'];
// JSON encode the data structure and return it
$jsonData = json_encode($apiRoot);
echo $jsonData;
?>