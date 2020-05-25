<?php
    // @ class api end
        trait LabelApi {
            // * api_documentation, located at: root/private/rules_docs/reference_information.php
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
                                // 'whereConditions' => 'useLabel NOT IN(1,3,4)',
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

            // * get_api_parameters, located at: root/private/rules_docs/reference_information.php
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

            // * post_api_parameters, located at: root/private/rules_docs/reference_information.php
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
        }
    // @ class api end
    
?>