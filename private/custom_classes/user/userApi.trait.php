<?php
    trait UserApi {
        // @ class api start
            // * api_documentation, located at: root/private/rules_docs/reference_information.php
            static protected $apiInfo = [
                'routes' => [
                    'users' => [
                        'httpMethods' => [
                            'get' => [
                                'arrayInfo' => 'publicApiParameters',
                                'apiPropertyExclusions' => ['password', 'adminNote', 'address', 'createdBy', 'createdDate', 'showOnWeb', 'username'],
                                // 'whereConditions' => 'id NOT IN(1,2,3,4,5,6,7,8,10) AND showOnWeb = 1',
                                'whereConditions' => 'showOnWeb = 1',
                                // show or not to show that is the question
                                "apiShowGetExamples" => 'yes',
                                'methodDocumentation' => 'method specific documentation, this is the main user public access'
                            ]
                        ]
                    ],
                    'users/dev' => [
                        'routKey' => 'T3$$tK3y!2456',
                        'httpMethods' => [
                            'get' => [
                                'methodKey' => 'T3$$tK3y!2456',
                                'arrayInfo' => 'publicApiParameters_dev',
                                // 'columnOptions' => ['id', 'address', 'createdBy', 'createdDate', 'emailAddress', 'firstName', 'lastName', 'mediaContentId', 'password'],
                                // 'apiPropertyExclusions' => ['createdDate', 'emailAddress', 'firstName', 'imageName', 'labelIds', 'lastName', 'mediaContentId', 'note', 'password', 'phoneNumber', 'showOnWeb', 'tagIds', 'title', 'username'],
                                // show or not to show that is the question
                                "apiShowGetExamples" => 'no',
                                'methodDocumentation' => 'method specific documentation, this is the main user dev access'
                            ],
                            'post' => [
                                'arrayInfo' => 'devPostParameters',
                            ],
                            'put' => [
                                'arrayInfo' => 'devPostParameters_put',
                                'putWhere' => true,
                            ], 
                            'patch' => [
                                'arrayInfo' => 'devPostParameters_patch',
                            ], 
                            'delete' => [
                                'deleteWhere' => true,
                            ] 
                        ]
                    ]
                ]
            ];

            // * get_api_parameters, located at: root/private/rules_docs/reference_information.php
            static public $publicApiParameters = [
                // ...api/v1/label/?id=22,33,5674,1,2,43,27,90,786 // ...api/v1/posts/?id=22
                'id'=> [
                    'refersTo' => ['id'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "=",
                        'list' => 'in'
                    ],
                    'description' => 'Gets users by the user id or list of user ids.',
                    'example' => ['id=1', 'id=1,2,3,4,5']
                ],
                // TODO: does not work to the full extent that I desire, think about how it can
                'catIds' => [
                    'refersTo' => ['catIds'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => "LIKE"
                    ],
                    'description' => 'Gets users by the user catIds, pass in one id at a time.',
                    'example' => ['catIds=4']
                ], 
                'emailAddress' => [
                    'refersTo' => ['emailAddress'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => 'like'
                    ],
                    'validation' => [
                        'name'=>'User Email Address',
                        'type' => 'str', // type of string
                        'min'=> 1, // string length
                        'max' => 150, // string length
                        'html' => 'yes'
                    ],
                    'description' => 'Gets users by email',
                    'example' => ['emailAddress=@gmail']
                ],
                'firstName' => [
                    'refersTo' => ['firstName'],
                    'type' => ['str', 'list'],
                    'connection' => [
                        'str' => 'like',
                        'list' => 'like'
                    ],
                    'description' => 'Gets users by first name',
                    'example' => ['firstName=Jim', 'firstName=Jim,sam']
                ], 
                'labelIds' => [
                    'refersTo' => ['labelIds'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "LIKE",
                        'list' => 'like'
                    ],
                    'description' => 'Gets users by the user labelIds, pass in one id at a time.',
                    'example' => ['labelIds=4', 'labelIds=4,18,16']
                ],  
                'lastName' => [
                    'refersTo' => ['lastName'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => 'like'
                    ],
                    'description' => 'Gets users by last name',
                    'example' => ['lastName=Smith']
                ],
                'mediaContentId'=>[
                    'refersTo' => ['mediaContentId'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => "="
                    ],
                    'description' => 'Gets users by the user mediaContentId.',
                    'example' => ['mediaContentId=4']
                ], 
                'note' => [
                    'refersTo' => ['note'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => 'like'
                    ],
                    'validation' => [
                        'name'=>'User Note',
                        'type' => 'str', // type of string
                        'min'=> 1, // string length
                        'max' => 255, // string length
                        'html' => 'no'
                    ],
                    'description' => 'Gets users by note',
                    'example' => ['note=developer']
                ],   
                'phoneNumber' => [
                    'refersTo' => ['phoneNumber'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => 'like'
                    ],
                    'validation' => [
                        'name'=>'User Phone Number',
                        'type' => 'str', // type of string
                        'min'=> 1, // string length
                        'max' => 25, // string length
                        'html' => 'yes'
                    ],
                    'description' => 'Gets users by phone number',
                    'example' => ['phoneNumber=801']
                ],
                'search' => [
                    'refersTo' => ['title', 'note', 'firstName', 'lastName', 'phoneNumber'],
                    'type' => ['str', 'list'],
                    'connection' => [
                        'str' => 'like',
                        'list' => 'like'
                    ],
                    'validation' => [
                        'name'=>'Search',
                        'required' => true,
                        'type' => 'str', // type of string
                        'min'=> 2, // string length
                        'max' => 50, // string length
                        'html' => 'no'
                    ],
                    'description' => 'Gets users by search parameters. Search will bring users that match the given string in title, note, firstName, lastName, and the phoneNumber field',
                    'example' => ['search=developer', 'search=sales,Sam,@gmail']
                ], 
                'tagIds' => [
                    'refersTo' => ['tagIds'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => "LIKE"
                    ],
                    'description' => 'Gets users by the user tagIds, pass in one id at a time.',
                    'example' => ['tagIds=1']
                ], 
                'title' => [
                    'refersTo' => ['title'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => 'like'
                    ],
                    'description' => 'Gets users by last job title',
                    'example' => ['title=developer']
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

            // testing constructing an array for the api
            public static function publicApiParameters_dev() {
                $arrayInfo = self::$publicApiParameters;
                $arrayInfo['address'] = [
                    'refersTo' => ['address'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => "LIKE"
                    ],
                    'validation' => [
                        'name'=>'User Address',
                        'type' => 'str', // type of string
                        'min'=> 1, // string length
                        'max' => 150, // string length
                        'html' => 'no'
                    ],
                    'description' => 'Gets users by the user address.',
                    'example' => ['address=1264 Mt. view']
                ]; 
                $arrayInfo['adminNote'] = [
                    'refersTo' => ['adminNote'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => "LIKE"
                    ],
                    'description' => 'Gets users by the user adminNote.',
                    'example' => ['adminNote=Need to chat!!!']
                ]; 
                $arrayInfo['showOnWeb'] = [
                    'refersTo' => ['showOnWeb'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => "="
                    ],
                    'description' => 'Gets users by the user showOnWeb 1 = yes, 0 = no.',
                    'example' => ['showOnWeb=0']
                ]; 
                $arrayInfo['createdBy'] = [
                    'refersTo' => ['createdBy'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => "LIKE"
                    ],
                    'description' => 'Gets users by the user createdBy id.',
                    'example' => ['createdBy=4']
                ]; 
                $arrayInfo['greaterThen'] = [
                    'refersTo' => ['createdDate'],
                    'type' => ['date'],
                    'connection' => [
                        'date' => '>='
                    ],
                    'description' => 'Gets users that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan parameter to get dates in posts with createdDates between the two values, see examples',
                    'customExample' => [ 
                        'greaterThan' => 'greaterThan=2018-02-01',
                        'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    ]
                ]; 
                $arrayInfo['lessThen'] = [
                    'refersTo' => ['createdDate'],
                    'type' => ['date'],
                    'connection' => [
                        'date' => '<='
                    ],
                    'description' => 'Gets users that have a createdDate <= the date given with the lessThan parameter. May be used with the greaterThan parameter to get dates in posts with createdDates between the two values, see examples',
                    'customExample' => [ 
                        'lessThan' => 'lessThan=2019-03-01',
                        'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    ]
                ]; 
                return $arrayInfo;
            }
            
            // * post_api_parameters, located at: root/private/rules_docs/reference_information.php
            static public $devPostParameters = [
                'id' => [
                    'type' => ['int'],
                    'description' => 'id'
                ], 
                'address' => [
                    'type' => ['str'],
                    'description' => 'This field is for the users address.'
                ], 
                'adminNote' => [
                    'type' => ['str'],
                    'description' => 'This field is for specific notes that only admin can see.'
                ], 
                'catIds' => [
                    'type' => ['str'],
                    'description' => 'This field is for creating a list of category ids.'
                ], 
                'createdBy' => [
                    'type' => ['int'],
                    'description' => 'This field is for who created the user, pass in there id.'
                ], 
                'createdDate' => [
                    'type' => ['date'],
                    'description' => 'This field is for when the record was created.'
                ],
                'emailAddress' => [
                    'type' => ['str'],
                    'description' => 'This field is for the users email.'
                ],
                'firstName' => [
                    'required' => true,
                    'type' => ['str'],
                    'description' => 'This field is for the users first name.'
                ], 
                'imageName' => [
                    'type' => ['str'],
                    'description' => 'This field is for the users image name. ex profile-picture.png.'
                ], 
                'labelIds' => [
                    'type' => ['str'],
                    'description' => 'This field is for creating a list of label ids.'
                ],  
                'lastName' => [
                    'type' => ['str'],
                    'description' => 'This field is for the users last name.'
                ],
                'mediaContentId'=>[
                    'type' => ['str'],
                    'description' => 'This field is for the the mediaContent id for the user. Id to their profile picture.'
                ], 
                'note' => [
                    'type' => ['str'],
                    'description' => 'This field is for a general note about the user.'
                ],  
                'password' => [
                    'type' => ['str'],
                    'description' => 'This field is for creating or updating a password'
                ],  
                'phoneNumber' => [
                    'type' => ['str'],
                    'description' => 'This field is for the users phone number'
                ], 
                'showOnWeb'=>[
                    'type' => ['str'],
                    'description' => 'This field is for whether or not a user should be shown on the web. 0 = no, and 1 = yes'
                ],  
                'tagIds' => [
                    'type' => ['str'],
                    'description' => 'This field is for creating a list of tags ids.'
                ], 
                'title' => [
                    'type' => ['str'],
                    'description' => 'This field is for the user job title.',
                    'validation' => [
                        'name'=>'User Job Title',
                        'required' => true,
                        'type' => 'str', // type of string
                        'min'=> 2, // string length
                        'max' => 45, // string length
                        'html' => 'no', // mostly just to allow special characters like () []
                        'contains' => '!!!'
                    ],
                ],
                'username' => [
                    'type' => ['str'],
                    'description' => 'This field is for the users username.'
                ]
            ];

            // testing constructing an array for the api
            public static function devPostParameters_put() {
                $arrayInfo = self::$devPostParameters;
                $arrayInfo['title']['validation'] = [
                    'name'=>'User Job Title',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 45, // string length
                    'html' => 'no', // mostly just to allow special characters like () []
                    'contains' => '@@@'
                ];
                $arrayInfo['title']['required'] = true;
                return $arrayInfo;
            }
            
            // testing constructing an array for the api
            public static function devPostParameters_patch() {
                $arrayInfo = self::$devPostParameters;
                unset($arrayInfo['title']);
                unset($arrayInfo['firstName']);
                $arrayInfo['username']['required'] = true;
                // $arrayInfo['title']['validation'] = [
                //     'name'=>'User Job Title',
                //     'required' => true,
                //     'type' => 'str', // type of string
                //     'min'=> 2, // string length
                //     'max' => 45, // string length
                //     'html' => 'no', // mostly just to allow special characters like () []
                //     'contains' => '@@@'
                // ];
                // $arrayInfo['title']['required'] = true;
                return $arrayInfo;
            }
        // @ class api end   
    }
?>