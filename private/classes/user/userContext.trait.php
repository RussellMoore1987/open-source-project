










<?php
// TODO: need to look at this more thoroughly and make sure that it connects and is cohesive with the regular rest API
// ! the overall thought is you should only be able to get access to the information you could if you are logged in, or have the correct passwords
// TODO: add delete where and update where
    trait userContext {
        // these instructions only work on registered classes ???
        static protected $contextInfo = [
            // establish connection to base code on
            'connection' => 'userPermission', // * required if wanting anything more than password security
            'registered' => [
                // ? passwords override permissions
                // overarching restrictions
                'overarchingRestrictions' => [
                    'whereConditions' => "",
                    'columnExclude' => "",
                    'propertyExclude' => "",
                    // session ID matches the ID they are passing in
                    // token ID matches the ID they are passing in
                    'exception' => '' // ??? 
                ], 

                // bypass options, // ? by default all functions, all columns, and all properties are restricted
                    // value to compare to see if they can bypass all restrictions/instruction, other allows and restricts will not apply to people who can bypass
                    'bypassAll' => 2, // can we string or number based on your database structure
                    // bypass based on password
                    'bypassAllOnPassword' => "ssodkfvm@#$!!!fkglFFgolIPIUYG",

                // allow all, future allows and restrictions will apply
                // 'allowAll' => true, // ? by default all functions, all columns, and all properties are restricted
                'allowAll' => [[2,6,8]], // you can also specify a rate of numbers or strings to compare with the connection to see if the allow all should be set for them
                'allowAllPassword' => "ssodkfvm@#$!!!fkglFFgolIPIUYG", // if password is set up and is found valid and allows access to all

                // details for getters, getter functions are find_by_id, find_where, count_all, find_all 
                // TODO: eventually need to add the join function to this list
                'getter' => [
                    // specify criteria for specific function/method
                    'find_by_id' => [
                        // allow, allow runs first
                        'allow' => [
                            // user has specific permission
                            'connectionCompare' => [
                                16 => [
                                    'columnAdd' => "",
                                    'propertyAdd' => ""
                                ]
                            ],
                            'passwordComparison' => [
                                "FFERTD!!!" => [
                                    'columnAdd' => "",
                                    'propertyAdd' => ""
                                ]
                            ]
                        ],
                        // restrict
                        'restrict' => [
                            // user has specific permission
                            'connectionCompare' => [
                                16 => [
                                    'whereConditions' => "",
                                    'columnExclude' => "",
                                    'propertyExclude' => "",
                                    // session ID matches the ID they are passing in
                                    // token ID matches the ID they are passing in
                                    'exception' => 'session::id=id' // ??? session::id=id, token::id=id, both session and token id=id 
                                ]
                            ],
                            'passwordComparison' => [
                                "FFERTD!!!" => [
                                    'whereConditions' => "",
                                    'columnExclude' => "",
                                    'propertyExclude' => ""
                                ]
                            ]
                        ]
                    ]
                ],

                // details for setters, setter functions are save and delete, // ??? possibly add deleteWhere, and saveWhere into the database object
                'setter' => [
                    // save method
                    'save' => [
                        // allow, allow runs first
                        'allow' => [
                            // user has specific permission
                            'connectionCompare' => [
                                16 => [
                                    'columnAdd' => "",
                                    'propertyAdd' => ""
                                ]
                            ],
                            'passwordComparison' => [
                                "FFERTD!!!" => [
                                    'columnAdd' => "",
                                    'propertyAdd' => ""
                                ]
                            ]
                        ],
                        // restrict
                        'restrict' => [
                            // user has specific permission
                            'connectionCompare' => [
                                16 => [
                                    'whereConditions' => "",
                                    'columnExclude' => "",
                                    'propertyExclude' => "",
                                    // session ID matches the ID they are passing in
                                    // token ID matches the ID they are passing in
                                    'exception' => 'session::id=id' // session::id=id, token::id=id, both session and token id=id, if this exception is true it will void the where conditions 
                                ]
                            ],
                            'passwordComparison' => [
                                "FFERTD!!!" => [
                                    'whereConditions' => "",
                                    'columnExclude' => "",
                                    'propertyExclude' => ""
                                ]
                            ]
                        ]
                    ],
                    'delete' => [
                        // this allows you to make this part smaller and put instructions down further in the trait
                        'callForInstructions' => 'deleteInstructions'
                    ]
                ]
            ],

            // other instructions, instructions for custom functions
            'otherInstructions' => []
        ];

        static public $deleteInstructions = [
            // allow, allow runs first
            'allow' => [
                // user has specific permission
                'connectionCompare' => [
                    16 => [
                        'columnAdd' => "",
                        'propertyAdd' => ""
                    ]
                ],
                'passwordComparison' => [
                    "FFERTD!!!" => [
                        'columnAdd' => "",
                        'propertyAdd' => ""
                    ]
                ]
            ],
            // restrict
            'restrict' => [
                // user has specific permission
                'connectionCompare' => [
                    16 => [
                        'whereConditions' => "",
                        'columnExclude' => "",
                        'propertyExclude' => "",
                        // session ID matches the ID they are passing in
                        // token ID matches the ID they are passing in
                        'exception' => 'session::id=id' // session::id=id, token::id=id, both session and token id=id, if this exception is true it will void the where conditions 
                    ]
                ],
                'passwordComparison' => [
                    "FFERTD!!!" => [
                        'whereConditions' => "",
                        'columnExclude' => "",
                        'propertyExclude' => ""
                    ]
                ]
            ]
        ];
    }
?>

<?php
    // TODO: stick in main settings
    // static protected
    $mainContextInfo = [ 
        // connections I use to create authentication pieces to base criteria off of for example users permissions located in the table versus an adjoining table
        'connections' => [
            'userPermission' => [
                // connection type of collection
                    'type' => 'collection',
                    // find by
                    'findBy' => "session::id||permissionConnection::userId", // session, token
                    // 'findBy' => "id||permissionConnection::userId", // this assumes either token or session, it will first look for session then for token
                    // ? example: session::id, session::jobTitle, session::permission, token::id
                    // for session and token it will go and get the specified values for comparison
                    // get info
                    'get' => 'permissionConnection::permissionId', // this will be turned into an array of values

                // connection type of specific
                    'type' => 'specific',
                    // find by
                    'findBy' => "session::id||user::id", // session, token
                    // get info
                    'get' => 'permission', // get a specific value for comparison

                // connection type of specificCollection
                    'type' => 'specificCollection',
                    // find by
                    'findBy' => "session::id||user::id", // session, token
                    // get info
                    'get' => 'permissions', // turns specific column value into an array for comparison
            ]
        ],
        // documentation password, has to be at least eight characters long and have one capital letter, one lowercase letter, one number, and one special symbol, otherwise it doesn't work
        'documentationPassword' => "",
        // list of viable functions for getters and setters
        'gettersAndSetters' => [
            'getters' => ['find_by_id', 'find_where', 'count_all', 'find_all', 'find_on_join'],
            'setters' => ['save', 'delete', 'saveWhere', 'deleteWhere']
        ],
        // allow Cross-Origin Resource Sharing (CORS), if you are only using the context api internally you should have this as false, if you are using it to populate outside products or pages make sure it is set to true
        // TODO: add this
        // ? you can also turn this on based off of what type of request they are making
        'cors' => false,




        // ???
        'getterRestrictions' => [
            'whereConditions' => "",
            'columnExclusions' => "",
            'apiPropertyExclusions' => ""
        ],
        'setterRestrictions' => [
            'whereConditions' => "",
            'columnExclusions' => "",
            'apiPropertyExclusions' => ""
        ],
        
    ];
?>