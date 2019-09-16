<?php
    class User extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "users";
            // db columns
            static protected $columns = ['id', 'address', 'adminNote', 'catIds', 'createdBy', 'createdDate', 'emailAddress', 'firstName', 'imageName', 'labelIds', 'lastName', 'mediaContentId', 'note', 'password', 'phoneNumber', 'showOnWeb', 'tagIds', 'title', 'username'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = ['imagePath_array', 'fullName'];
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static protected $collectionTypeReference = 3;
            // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'User id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'address' => [
                    'name'=>'User Address',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 150, // string length
                    'html' => 'no'
                ], 
                'adminNote' => [
                    'name'=>'User Admin Note',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'catIds' => [
                    'name'=>'User catIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'createdBy' => [
                    'name'=>'User createdBy',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'User createdDate',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min'=> 6, // string length
                    'max' => 10, // string length
                    'date' => true
                ],
                'emailAddress' => [
                    'name'=>'User Email Address',
                    'type' => 'str', // type of string
                    'min'=> 6, // string length
                    'max' => 150, // string length
                    'email' => true,
                    'html' => 'yes'
                ],
                'firstName' => [
                    'name'=>'User First Name',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 25, // string length
                    'html' => 'no'
                ], 
                'imageName' => [
                    'name'=>'User imageName',
                    'type' => 'str', // type of string
                    'max' => 150 // string length
                ], 
                'labelIds' => [
                    'name'=>'User labelIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ],  
                'lastName' => [
                    'name'=>'User Last Name',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 25, // string length
                    'html' => 'no'
                ],
                'mediaContentId'=>[
                    'name'=>'User mediaContent id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'note' => [
                    'name'=>'User Note',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 255, // string length
                    'html' => 'no'
                ],  
                'password' => [
                    'name'=>'User Password',
                    'type' => 'str', // type of string
                    'min'=> 8, // string length
                    'max' => 50, // string length
                    'html' => 'full'
                ],  
                'phoneNumber' => [
                    'name'=>'User Phone Number',
                    'type' => 'str', // type of string
                    'min'=> 7, // string length
                    'max' => 25, // string length
                    'html' => 'yes'
                ], 
                'showOnWeb'=>[
                    'name'=>'User Show On Web',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'num_max'=> 1, // number max value
                ],  
                'tagIds' => [
                    'name'=>'User tagIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'title' => [
                    'name'=>'User Job Title',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 45, // string length
                    'html' => 'no' // mostly just to allow special characters like () []
                ],
                'username' => [
                    'name'=>'User Username',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 35, // string length
                    'html' => 'no' // mostly just to allow special characters like () []
                ]
            ];
        // @ class database information end

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
                ],
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
        
        // @ class specific queries start

            // get all users for select
            static public function get_users_for_select() {
                $sql = "SELECT id, firstName, lastName FROM users ";
                return static::find_by_sql($sql);
            }
            
            // # for a *single user* query's start
                // get all extended info
                public function get_extended_info() {
                    // empty array to hold potential extended information
                    $extendedInfo_array = [];
                    // get all images
                    $extendedInfo_array['image'] = $this->get_user_image();
                    // get tags
                    $extendedInfo_array['tags'] = $this->get_user_tags();
                    // get labels
                    $extendedInfo_array['labels'] = $this->get_user_labels();
                    // get categories
                    $extendedInfo_array['categories'] = $this->get_user_categories();
                    // return data
                    return $extendedInfo_array;    
                }
                
                // get image, main queries for editing
                public function get_user_image() {
                    $sql = "SELECT id, name ";
                    $sql .= "FROM media_content ";
                    $sql .= "WHERE id = '" . self::db_escape($this->mediaContentId) . "' ";
                    $sql .= "LIMIT 1 ";
                    // return data
                    return MediaContent::find_by_sql($sql);    
                }

                // get tags, main queries for editing
                public function get_user_tags() {
                    $sql = "SELECT t.id, t.title ";
                    $sql .= "FROM tags AS t ";
                    $sql .= "INNER JOIN users_to_tags AS utt ";
                    $sql .= "ON utt.tagId = t.id ";
                    $sql .= "WHERE utt.userId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Tag::find_by_sql($sql);     
                }

                // get labels, main queries for editing
                public function get_user_labels() {
                    $sql = "SELECT l.id, l.title ";
                    $sql .= "FROM labels AS l ";
                    $sql .= "INNER JOIN users_to_labels AS utl ";
                    $sql .= "ON utl.labelId = l.id ";
                    $sql .= "WHERE utl.userId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Label::find_by_sql($sql);    
                }

                // get categories, main queries for editing
                public function get_user_categories() {
                    $sql = "SELECT c.id, c.title ";
                    $sql .= "FROM categories AS c ";
                    $sql .= "INNER JOIN users_to_categories AS utc ";
                    $sql .= "ON utc.categoryId = c.id ";
                    $sql .= "WHERE utc.userId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Category::find_by_sql($sql);    
                }
            // # single post query's end
        // @ class specific queries end

        // @ properties start
            // main properties
                public $address;
                public $adminNote;
                public $emailAddress;
                public $firstName;
                public $lastName;
                public $note;
                public $phoneNumber;
                public $showOnWeb;
                public $title;
                public $username;
                // secondary properties
                public $fullName;
                // used primarily for the API, if you just need a image path you can just call get_image_path('small') found bellow
                public $imagePath_array;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $catIds; // get_catIds()
                protected $createdBy; // get_createdBy()
                protected $createdDate; // get_createdDate()
                protected $id; // get_id()
                protected $imageName; // get_imageName()
                protected $labelIds; // get_labelIds()
                protected $mediaContentId; // get_mediaContentId()
                protected $tagIds; // get_tagIds()
                protected $password; // get_password()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->address = $args['address'] ?? NULL;
                $this->adminNote = $args['adminNote'] ?? NULL;
                $this->catIds = $args['catIds'] ?? NULL;
                $this->createdBy = $args['createdBy'] ?? NULL;
                $this->createdDate = $args['createdDate'] ?? NULL;
                $this->emailAddress = $args['emailAddress'] ?? NULL;
                $this->firstName = $args['firstName'] ?? NULL;
                $this->imageName = $args['imageName'] ?? NULL;
                $this->imagePath_array = [];
                // check to see if we have an image name
                if (strlen(Trim($this->imageName)) > 0) {
                    $this->imagePath_array = [$this->get_image_path('thumbnail'), $this->get_image_path('small'), $this->get_image_path('medium'), $this->get_image_path('large'), $this->get_image_path('original')];  
                }
                $this->labelIds = $args['labelIds'] ?? NULL;
                $this->lastName = $args['lastName'] ?? NULL;
                $this->mediaContentId = $args['mediaContentId'] ?? NULL;
                $this->note = $args['note'] ?? NULL;
                $this->password = $args['password'] ?? NULL;
                $this->phoneNumber = $args['phoneNumber'] ?? NULL;
                $this->showOnWeb = $args['showOnWeb'] ?? NULL;
                $this->tagIds = $args['tagIds'] ?? NULL;
                $this->title = $args['title'] ?? NULL;
                $this->username = $args['username'] ?? NULL;  
                      
                // needed to wait until last name was set
                $this->fullName = $this->firstName . " " . $this->lastName;
            }

            // methods
            // get catIds property
            public function get_catIds() {
                return $this->catIds;
            }

            // get createdBy property
            public function get_createdBy() {
                return $this->createdBy;
            }

            // get createdDate property
            public function get_createdDate() {
                return $this->createdDate;
            }

            // get id property
            public function get_id() {
                return $this->id;
            }

            // get imageName property
            public function get_imageName() {
                return $this->imageName;
            }

            // get labelIds property
            public function get_labelIds() {
                return $this->labelIds;
            }
            
            // get mediaContentId property
            public function get_mediaContentId() {
                return $this->mediaContentId;
            }

            // get tagIds property
            public function get_tagIds() {
                return $this->tagIds;
            
            }
            // get password property
            public function get_password() {
                return $this->password;
            }

            // get image path with recorded reference image name
            public function get_image_path($type = 'small') {
                // get path // * image_paths located at: root/private/rules_docs/reference_information.php
                $path = get_image_path($type);
                // return image path with name
                return "{$path}/{$this->imageName}";
            }
        // @ methods end

    }
?>