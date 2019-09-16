<?php
    trait PostApi {
        // @ class api start
            // * api_documentation, located at: root/private/rules_docs/reference_information.php
            static protected $apiInfo = [
                'routes' => [
                    'posts' => [
                        'httpMethods' => [
                            'get' => [
                                'arrayInfo' => 'getApiParameters',
                                'whereConditions' => 'status = 1',
                                // show or not to show that is the question
                                'methodDocumentation' => 'method specific documentation, this is the main post public access'
                            ]
                        ]
                    ],
                    'posts/dev' => [
                        'routKey' => 'T3$$tK3y!2456',
                        'httpMethods' => [
                            'get' => [
                                'arrayInfo' => 'getApiParameters',
                                // show or not to show that is the question
                                'methodDocumentation' => 'method specific documentation, this is the main post dev access'
                            ],
                            'post' => [
                                'arrayInfo' => 'postApiParameters',
                            ],
                            'put' => [
                                'arrayInfo' => 'postApiParameters',
                                'putWhere' => true,
                            ], 
                            'patch' => [
                                'arrayInfo' => 'postApiParameters',
                            ], 
                            'delete' => [
                                'deleteWhere' => true,
                            ] 
                        ]
                    ]
                ]
            ];

            // * get_api_parameters, located at: root/private/rules_docs/reference_information.php
            static public $getApiParameters = [
                'id'=>[
                    'refersTo' => ['id'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "=",
                        'list' => 'in'
                    ],
                    'description' => 'Gets posts by the post id or list of post ids',
                    'example' => ['id=1', 'id=1,2,3,4,5']
                ],
                'greaterThen' => [
                    'refersTo' => ['postDate'],
                    'type' => ['date'],
                    'connection' => [
                        'date' => '>'
                    ],
                    'description' => 'Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan parameter to get dates in posts with createdDates between the two values, see examples',
                    'customExample' => [ 
                        'greaterThan' => 'greaterThan=2018-02-01',
                        'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    ]
                ], 
                'lessThen' => [
                    'refersTo' => ['postDate'],
                    'type' => ['date'],
                    'connection' => [
                        'date' => '<'
                    ],
                    'description' => 'Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan parameter to get dates in posts with createdDates between the two values, see examples',
                    'customExample' => [ 
                        'lessThan' => 'lessThan=2019-03-01',
                        'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    ]
                ],
                'search' => [
                    'refersTo' => ['title', 'content'],
                    'type' => ['str', 'list'],
                    'connection' => [
                        'str' => 'like',
                        'list' => 'like'
                    ],
                    'validation' => [
                        'name'=>'search',
                        'required' => true,
                        'type' => 'str', // type of string
                        'min'=> 2, // string length
                        'max' => 50, // string length
                        'html' => 'no'
                    ],
                    'description' => 'Gets posts by search parameters. Search will bring Posts that match the given string in both the title and the content field',
                    'example' => ['search=sale', 'search=sale,off,marked down']
                ],
                'postDate' => [
                    'refersTo' => ['postDate'],
                    'type' => ['date'],
                    'connection' => [
                        'date' => '='
                    ],
                    'description' => 'Gets posts by the post date',
                    'example' => ['postDate=2019-02-01']
                ],
                'createdDate' => [
                    'refersTo' => ['createdDate'],
                    'type' => ['date'],
                    'connection' => [
                        'date' => '='
                    ],
                    'description' => 'Gets posts by the date they were created',
                    'example' => ['createdDate=2019-02-01']
                ],
                'status' => [
                    'refersTo' => ['status'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => '='
                    ],
                    'description' => 'Gets posts by the current post status. 0 = draft, 1 = published',
                    'example' => ['status=1']
                ],
                'columns' => [
                    'refersTo' => ['extraOptions'],
                    'type' => ['str','list'],
                    'useFor' => 'columns',
                    'validation' => [
                        'name'=>'extendedData',
                        'required' => true,
                        'type' => 'str',
                        'min'=> 1,
                        'max' => 150,
                        'html' => 'no'
                    ],
                    'description' => 'What database columns you want to use',
                    'example' => ['columns=firstName', 'columns=firstName,lastName']
                ],
                'extendedData' => [
                    'refersTo' => ['extraOptions'],
                    'type' => ['str'],
                    'useFor' => 'code::get_full_api_data',
                    'validation' => [
                        'name'=>'extendedData',
                        'required' => true,
                        'type' => 'str',
                        'min'=> 1,
                        'max' => 50,
                        'html' => 'no'
                    ],
                    'description' => 'Returns all extended post data. 0 = Return basic post data, 1 = Return extended post data. Default is 0. extended data includes all images attached to the post ',
                    'example' => ['extendedData=1']
                ],
                'propertyExclusions' => [
                    'refersTo' => ['extraOptions'],
                    'type' => ['str', 'list'],
                    'useFor' => 'propertyExclusions',
                    'validation' => [
                        'name'=>'extendedData',
                        'required' => true,
                        'type' => 'str',
                        'min'=> 1,
                        'max' => 150,
                        'html' => 'no'
                    ],
                    'description' => 'What properties do you want to exclude',
                    'example' => ['propertyExclusions=firstName', 'propertyExclusions=firstName,lastName']
                ]
            ];

            // * post_api_parameters, located at: root/private/rules_docs/reference_information.php
            static public $postApiParameters = [
                'author' => [
                    'type' => ['int'],
                    'description' => 'This field expects an author/user id'
                ], 
                'authorName' => [
                    'type' => ['int'],
                    'description' => 'This is a place holder for the author\'s name, quick reference to the author\'s name'
                ], 
                'catIds' => [
                    'type' => ['str'],
                    'description' => 'list of category id\'s, quick reference'
                ],
                'content' => [
                    'type' => ['str'],
                    'description' => 'This field is the main content for the given post, it does accept HTML'
                ], 
                'createdBy' => [
                    'type' => ['int'],
                    'description' => 'This field expects the id of the user who created the post'
                ], 
                'createdDate' => [
                    'type' => ['str'],
                    'description' => 'This field expects a date, specifically the date it was created on'
                ],
                'imageName' => [
                    'type' => ['str'],
                    'description' => 'This is a placeholder for the featured image, quick reference'
                ], 
                'labelIds' => [
                    'type' => ['str'],
                    'description' => 'list of label id\'s, quick reference'
                ], 
                'mediaContentIds' => [
                    'type' => ['str'],
                    'description' => 'list of media content id\'s, quick reference'
                ], 
                'postDate' => [
                    'type' => ['str'],
                    'description' => 'This field expects a post date, when the post should be displayed'
                ], 
                'status' => [
                    'type' => ['int'],
                    'description' => 'This field expects a post status. 0 = draft, 1 = published'
                ], 
                'tagIds' => [
                    'type' => ['str'],
                    'description' => 'list of tag id\'s, quick reference'
                ], 
                'title' => [
                    'type' => ['str'],
                    'description' => 'This field expects a post title, what the post will be called and referenced as'
                ]
            ];
        // @ class api end    
    }
?>