<?php
    class Label extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DatabaseObject Class
            // Name of the table
            static protected $tableName = 'labels';
            // db columns
            static protected $columns = ['id', 'note', 'title', 'useLabel'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static public $validation_columns = [
                'id' => [
                    'name' => 'Label id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min' => 1, // number min value
                    'max' => 10 // string length
                ],
                'note' => [
                    'name' => 'Label Note',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ],
                'title' => [
                    'name' => 'Label Title',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min' => 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes'
                ],
                'useLabel' => [
                    'name'=>'Label useLabel',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'num_max'=> 4, // number max value
                ]
            ];
        // @ class database information end

        // @ class api test start
            // * api_documentation, located at: root/private/reference_information.php
            static protected $apiInfo = [
                // class key
                'classKey' => 'T3$$tK3y!2456',
                // routes available
                'routes' => [
                    'labels' => [
                        // rout specific documentation, optional
                        'routDocumentation' => 'This is for public access only.',
                        // rout specific validation
                        'routKey' => 'T3$$tK3y!2456',
                        // name specific properties you wish to exclude in the API
                        // 'apiPropertyExclusions' => ['note', 'id', 'useLabel'],
                        // specify httpMethods available for this rout 
                        'httpMethods' => [
                            'get' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456!',
                                // specified array to use
                                'arrayInfo' => 'getApiParameters2',
                                // this anabels you to set whereConditions to limit or gide the api feed // * only for GET  
                                'whereConditions' => 'useLabel NOT IN(1,3,4)',
                                // this field/property allows you to show or not show the GET examples
                                // show or not to show that is the question
                                "apiShowGetExamples" => 'yes', // can use no, default is yes if not set // * only for GET
                                // method documentation
                                'methodDocumentation' => 'method specific documentation'
                            ]
                        ]
                    ],
                    "labels/dev" => [
                        // rout specific documentation, optional
                        'routDocumentation' => 'This route is for developer use only.',
                        // rout specific validation
                        'routKey' => 'T3$$tK3y!2456',
                        // specify httpMethods available for this rout 
                        'httpMethods' => [
                            // get does not need a password in any form, but if mainApiKey, mainGetApiKey, classKey, routKey or the get methodKey is set the key is required on the rout
                            'get' => [
                                // specified array to use
                                'arrayInfo' => 'getApiParameters2',
                                // method documentation
                                'methodDocumentation' => 'This is specific documentation for the GET dev method.'
                            ],
                            // post like httpMethods
                            'post' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456',
                                'arrayInfo' => 'postApiParameters2',
                                // method documentation
                                'methodDocumentation' => 'This is specific documentation for the POST method.'
                            ],
                            'put' => [
                                // specified array to use
                                'arrayInfo' => 'postApiParameters2',
                                // opens the option to update where a condition is met // * see apiIndex.php for documentation on putWhere located at root/public/api/v1/apiIndex.php
                                'putWhere' => true
                            ],
                            'patch' => ['arrayInfo' => 'postApiParameters2'],
                            'delete' => [
                                // opens the option to delete where the condition is met // * see apiIndex.php for documentation on deleteWhere located at root/public/api/v1/apiIndex.php
                                'deleteWhere' => true,
                                // method documentation
                                'methodDocumentation' => 'This is specific documentation for the DELETE method.'
                            ]
                        ]
                    ]
                ]
            ];
            // * get_api_parameters, located at: root/private/reference_information.php
            static public $getApiParameters2 = [
                // ...api/v1/posts/?id=22,33,5674,1,2,43,27,90,786 // ...api/v1/posts/?id=22
                'id'=>[
                    'refersTo' => ['id'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "=",
                        'list' => 'in'
                    ],
                    'description' => 'Gets labels by the label id or list of label ids.',
                    'example' => ['id=1', 'id=1,2,3,4,5']
                ],
                'title'=>[
                    'refersTo' => ['title'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => "LIKE"
                    ],
                    'description' => 'Gets labels by the label title.',
                    'example' => ['title=GoGo!']
                ]
            ];
            // * post_api_parameters, located at: root/private/reference_information.php
            static public $postApiParameters2 = [
                'note' => [
                    'type' => ['str'],
                    'description' => 'Add a description about the label'
                ],
                'title' => [
                    'type' => ['str'],
                    'description' => 'This field expects an author/user id',
                    'validation' => [
                        'name' => 'Label Title222222',
                        'required' => true,
                        'type' => 'str', // type of string
                        'min' => 2, // string length
                        'max' => 50, // string length
                        'contains' => '!',
                        'html' => 'yes' 
                    ]
                ],
                'useLabel' => [
                    'type' => ['int'],
                    'description' => '1-4'
                ]
            ];
        // @ class api test end

        // @ class specific queries start
            // Find all the labels associated with the collection type parameter
            static public function find_all_labels(int $type = 0) {
                $sql = "SELECT id, note, title, useLabel FROM labels ";
                // we expect a number between one and four // * collection_type_reference, located at: root/private/reference_information.php
                if ($type <= 4 && $type <= 1) {
                    $sql .= "WHERE useLabel = '{$type}'";
                }  
                return self::find_by_sql($sql);
            }
        // @ class specific queries end

        // @ properties start
            // main properties
            public $note;
            public $title;
            public $useLabel;

            //protected properties
            protected $id;

        // @ properties end

        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // clean up form information coming in
                $args = self::cleanFormArray($args);
                $this->id = $args['id'] ?? NULL;
                $this->note = $args['note'] ?? NULL;
                $this->title = $args['title'] ?? NULL;
                $this->useLabel = $args['useLabel'] ?? NULL;
            }

            // methods
            // get the id
            public function get_id() {
                return $this->id;
            }

        // @ methods end
    }
    
?>