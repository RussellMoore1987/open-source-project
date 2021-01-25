<?php
    // @ class_list
        // registering a class allows you to use the REST API, Context API, and DevTool with its sql processor, and its graphical interfaces, and documentation 
        // ex:
        // static protected $classList = ["Category", "Label", "MediaContent", "Post", "Tag", "User"]; // public access use get_class_list() 
            // class list, if there is order importance, placed the classes in order of importance. 
                // This is especially important if you need SQL to be created in a certain order.
                    // the order for SQL processing is $sqlStructure from $classList, then $otherTables from $otherTablesClassList. If $otherTablesClassList is not available or left empty then it will look for $otherTables from $classList
                        // ex:
                        // * in main settings trait start
                            // static protected $classList = ["Category", "Label", "MediaContent", "Post", "Tag", "User"]; // public access use get_class_list() 
                            // static protected $otherTablesClassList = []; // use get_other_tables_class_list()
                        // * in main settings trait end
                        // * in a class start
                            // static protected $sqlStructure = "
                            //     CREATE TABLE IF NOT EXISTS users ( 
                            //     id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
                            //     username VARCHAR(35) NOT NULL UNIQUE, 
                            //     ...
                            // ";
                    
                            // // connecting tables
                            // static protected $otherTables = [
                            //     "users_to_tags" => "
                            //         CREATE TABLE IF NOT EXISTS users_to_tags ( 
                            //         userId INT(10) UNSIGNED NOT NULL 
                            //         tagId INT(10) UNSIGNED NOT NULL, 
                            //         PRIMARY KEY (userId, tagId), 
                            //         FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE, 
                            //         FOREIGN KEY (tagId) REFERENCES tags(id) ON DELETE CASCADE ) ENGINE=InnoDB
                            //     ",
                            //     "users_to_labels" => "
                            //         CREATE TABLE IF NOT EXISTS users_to_labels ( 
                            //         userId INT(10) UNSIGNED NOT NULL, 
                            //         labelId INT(10) UNSIGNED NOT NULL, 
                            //         PRIMARY KEY (userId, labelId), 
                            //         FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE, 
                            //         FOREIGN KEY (labelId) REFERENCES labels(id) ON DELETE CASCADE ) ENGINE=InnoDB
                            //     "
                            // ];
                        // * in a class end
                    // The order of creation of SQL is first > the order classes (ex: "Category", "Label", "MediaContent") > $sqlStructure array (for all classes, following $classList order) > then $otherTables (for all classes, following $classList order)
            // # for $otherTablesClassList
            // list the class they are in, the array in a class must be called $otherTables, 
                // "static protected $otherTables = ['sql...']". (example above) 
                // these tables are built last. You can specify which class you want them in. 
                // If left blank it will try to connect to the $classList to find SQL $otherTables.
            // static protected $otherTablesClassList = []; // use get_other_tables_class_list()
                // use get_sql_other_tables() to get $otherTables names from an individual class, used in DevTool
    // @ collection_type_reference
        // # reference number
        // 0 = none/get all 
        // 1 = posts 
        // 2 = media content
        // 3 = users
        // 4 = content

        // # for class methods: [type] = (tags || labels || categories)
        // Class->get_possible_tags($type)
        // Class->get_possible_labels($type)
        // Class->get_possible_categories($type)
            // $type = 0 = all [type]
                // ex: get_possible_tags(0) = get all tags available

            // 1 = all [type] available to posts
                // ex: get_possible_tags(1) = get all tags available to posts

            // 2 = all [type] available to media content
                // ex: get_possible_tags(2) = get all tags available to media content

            // 3 = all [type] available to users
                // ex: get_possible_tags(3) = get all tags available to users    
            
            // 4 = all [type] available to content
                // ex: get_possible_tags(4) = get all tags available to content

    // @ sql_queries
        // query's that are available by default
            // ClassName::save(), this query utilizes the create and update functions listed below, use the save function not to create and update function
                // ClassName::create()
                // ClassName::update()
            // ClassName::count_all() // * can utilize [whereOptions]
            // ClassName::find_all(array $sqlOptions = []) // * can utilize [whereOptions],[sortingOptions],[columnOptions]
            // ClassName::find_by_id(int $id, array $sqlOptions = []) // * can utilize [columnOptions]
            // ClassName::find_by_sql($sql) // * made for instantiating objects with custom SQL
            // ClassName::find_where(array $sqlOptions = []) // * can utilize [whereOptions],[sortingOptions],[columnOptions]
            // DatabaseObject::run_sql($sql), only run on the DatabaseObject // * made to run any type of SQL returns SQL results
        // sqlOptions will/can contain [whereOptions],[sortingOptions],[columnOptions]
            // whereOptions
                // ex: ['id IN ( 1, 2, 3, 4, 5 )']
            // sortingOptions
                // ex: ['ORDER BY postDate, id DESC LIMIT 50 OFFSET 102']
            // columnOptions
                // ex: ['id', 'tittle']

        // # example from Aggie cribs project, a page using queries,example of how queries can be used
            // * page title
            $pageTitle = "Listing Page";

            // ! we have to clean data
            // * find by id
                // $Listings_array = Listing::find_by_id(2);

                // array options
                // $sqlOptions['columnOptions'] = ['id', 'city', 'cost', 'housingType','leaseLength'];
                // $sqlOptions['columnOptions'] = 'id, city, cost, housingType, leaseLength';
                // $Listings_array = Listing::find_by_id(7, $sqlOptions);

                // string option
                // $Listings_array = Listing::find_by_id(10, "id, city, cost, housingType, leaseLength");

            // * where
                // arrays
                // $sqlOptions['columnOptions'] = ['city', 'cost', 'housingType','leaseLength', 'listingDescription', 'listingManagementNote', 'paymentType', 'propertyName', 'sqft', 'stateCode', 'streetAddress', 'subleasingApproved', 'unitBathrooms', 'unitBedrooms', 'userId', 'zipCode'];
                // $sqlOptions['whereOptions'] = ["unitBathrooms > 2", "unitBedrooms > 2"];
                // $sqlOptions['sortingOptions'][] = "ORDER BY propertyName LIMIT 10";
                // $Listings_array = Listing::find_where($sqlOptions);
                
                // lists
                // $sqlOptions['columnOptions'] = 'city,cost,housingType,leaseLength,listingDescription,listingManagementNote,paymentType,propertyName,sqft,stateCode,streetAddress,subleasingApproved,unitBathrooms,unitBedrooms,userId,zipCode';
                // $sqlOptions['whereOptions'] = "unitBathrooms > 2, unitBedrooms > 2";
                // // $sqlOptions['whereOptions'] = "unitBathrooms > 2 OR unitBedrooms > 2";
                // $sqlOptions['sortingOptions'] = "ORDER BY propertyName, LIMIT 10";
                // // $sqlOptions['sortingOptions'] = "ORDER BY propertyName LIMIT 10";
                // $Listings_array = Listing::find_where($sqlOptions);
                
                // string
                // $Listings_array = Listing::find_where("unitBathrooms > 3 AND unitBedrooms > 3");

            // * find all
                // find all
                // $Listings_array = Listing::find_all();

                // find all with options
                // $sqlOptions['columnOptions'] = 'city,cost,housingType,streetAddress,unitBathrooms,unitBedrooms,zipCode';
                // $sqlOptions['whereOptions'] = "unitBathrooms > 3, unitBedrooms > 4";
                // $sqlOptions['sortingOptions'] = "ORDER BY unitBathrooms DESC LIMIT 10";
                // $Listings_array = Listing::find_all($sqlOptions);
            // * count all
                // count all
                // $ListingsCount = Listing::count_all();

                // count all where array
                // $sqlOptions['whereOptions'] = "unitBathrooms > 3, unitBedrooms > 2";
                // $sqlOptions['whereOptions'] = ["unitBathrooms > 2","unitBedrooms > 2"];
                // $ListingsCount = Listing::count_all($sqlOptions);

                // count all where string
                // $ListingsCount = Listing::count_all("unitBathrooms > 3, unitBedrooms > 4");
            // * find by sql
                // $sql = "
                //     SELECT city, cost, housingType, leaseLength, listingDescription, listingManagementNote, paymentType, propertyName, sqft, stateCode, streetAddress, subleasingApproved, unitBathrooms, unitBedrooms, userId, zipCode 
                //     FROM listings WHERE unitBathrooms > 2 AND unitBedrooms > 2 
                //     ORDER BY propertyName LIMIT 10
                // ";
                // $Listings_array = Listing::find_by_sql($sql);
            // * run sql
                // $sql = "
                //     SELECT city, cost, housingType, leaseLength, listingDescription, listingManagementNote, paymentType, propertyName, sqft, stateCode, streetAddress, subleasingApproved, unitBathrooms, unitBedrooms, userId, zipCode 
                //     FROM listings WHERE unitBathrooms > 2 AND unitBedrooms > 2 
                //     ORDER BY propertyName LIMIT 10
                // ";
                // $listingQuery = DatabaseObject::run_sql($sql);
                // $listingQuery = $listingQuery->fetch_all(MYSQLI_ASSOC);
                // var_dump($listingQuery);

            // ! data is clean automatically
            // * save
                // create and update
                // $Listing = Listing::find_by_id(2);
                // $Listing->city = "Logan";
                // $Listing->save();
            // * delete
                // $Listing = Listing::find_by_id(2);
                // $Listing->delete();

    // @ validation_options
        // # examples of parameters
            // val_validation(
            //     'value to be validated', 
            //     [
            //         'name' => 'Post Title', 
            //         'type' => 'num'/'str'/'int', 
            //         'num_min' => 1, 
            //         'num_max' => 10, 
            //         'min' => 3, 
            //         'max' => 5, 
            //         'exact' => 5, 
            //         'required' => true/false, 
            //         'html' => 'yes'/'no'/'full'
            //         'date' => true
            //     ]
            // )
            
        // # explanation of options
            // val_validation('$value', $options)
            // # $value
                // the value you are wishing to validate
            // # $options
                // potential options of validation
                // comments for specific developer notes, values for API documentation
                function val_validation_documentation() {
                    // validation documentation
                    $validationDocumentation_array = [
                        // * 'name' => 'Post Title'
                        'name' => [
                            'Human readable name.',
                            'Often use the form name or label.'
                        ],
                        // * 'date' => true
                        'date' => "Checks to see whether or not it is a valid date.",
                        // * 'email' => true
                        'email' => "Checks to see whether or not it is a valid email.",
                        // * 'type' => 'num'/'str'/'int'
                        'type' => [
                            'num = Determines whether or not it is a number, allows decimals.',
                            'str = Determines whether or not it is a string.',
                            'int = Determines whether or not it is a integer, does not allow decimals.'
                        ],
                        // * 'num_min' => 1
                        'num_min' => 'It makes sure that the number being evaluated is not less than the number set on the "num_min".',
                        // * 'num_max' => 10
                        'num_max' => 'It makes sure that the number being evaluated is not more than the number set on the "num_max".',
                        // * 'min' => 3
                        'min' => 'It makes sure that the string length is not less than the number set on the "min".',
                        // * 'max' => 5
                        'max' => 'It makes sure that the string length is not more than the number set on the "max".',
                        // * 'exact' => 5
                        'exact' => 'It makes sure that the string length is the number set on the "exact".',
                        // * 'contains' => " Sam " (to find a word in string) or "Sam" (to find character set in string)
                        'contains' => 'It make sure that the string provided contains the string set on the "contains". This match is case sensitive.',
                        // * 'matchOptions' => ["Male","Female"] or "Male,Female" (comma "," delimited list)
                        'matchOptions' => 'It makes sure that the string matches a value set on the "matchOptions". This match is case sensitive.',
                        // * 'required' => true
                        // we also use the required for the cleanFormArray() function in the databaseobject class.
                        // if it is required and it goes to the cleanFormArray() function, if it is NULL or an empty string it will be kicked off the returned $post_array
                        // this means that the object in question will not accept I either have it, and it has something, or I reject
                        // ? there are two different ways to work with form data, 
                        // get it or reject it
                            // to activate, you must have the validation parameter "required" set
                        // if I get it then validate it, if not past the blank through
                            // get it then validate it or let the blank pass through
                                // this is done by default, but you can set in a "min" parameter which allows you to give minimum length value if it is passed through, all other validations should work as well.
                            // let the blank pass through, done by default
                        'required' => [
                            'It makes sure the value is sent through: it is needed, can not be blank, can not be NULL.',
                            'If the required is sent in as a blank string on an update, the form filters previous to validation will reject the value. It is either there and has a value, or it is rejected.',
                            'All parameters that are required, must be present on the insertion of a new record.'
                        ],
                        // * 'html' => 'yes'/'no'/'full'
                        'html' => [
                            'yes = allows for HTML special characters, but does not allow JavaScript characters. Excluded values: <script, ;, \\.',
                            'no = dose not allow for HTML special characters and does not allow JavaScript characters. Excluded values: <>, (), [], {}, ;, \, /.',
                            'full = allows everything through.'
                        ],
                    ];
                    // return data
                    return $validationDocumentation_array;
                }

    // @ image_paths
        // # constant variable
            // ... = the necessary folders to make it to that point
            // IMAGE_PATH = ...\public/images, it gets you to the image folder
            // from this point you need to add on the desired folder thumbnail, small, medium, large, and original
                // note that all images do not have all sizes available to them, check to make sure the desired photo is there
            // after the folder name you must add the name of the image
            // example of paths below 
        // # potential paths
            // ...\public/images/thumbnail/fake_image.jpg
            // ...\public/images/small/fake_image.jpg
            // ...\public/images/medium/fake_image.jpg
            // ...\public/images/large/fake_image.jpg
            // ...\public/images/original/fake_image.jpg
            // or 
            // IMAGE_PATH . /thumbnail/fake_image.jpg

    // @ api_documentation
        // ? If you wish to clean up the class from the api info you can stick the class into a folder (with the name of the class, lowercase) and then add an API trait (named: {ClassName}Api.trait.php, ex: userApi.trait.php) include the trait into the class with the require_once reference. If you do this you can put all API information into the new trait. The trait should be located right next to the class inside the folder with the class name. 
                // ? ex  class = root/private/classes/user/user.class.php, api trait = root/private/classes/user/userApi.trait.php
                // ? Inside the class file use these: require_once("userApi.trait.php"); in the class put: use UserApi;
        // # Index documentation
            // if the index documentation does not run as fast as you would like you can create a page in the directory of root/public/api/v1, called setIndex.php. Path: root/public/api/v1/setIndex.php. Populate this page with the dynamic index content, and then instead of going to the dynamic index it will go to the hard set index = setIndex.php. Make sure to create this page only after you have collected the dynamic index content. Once the page is created it will take over.
        // # reference for verbiage
        // post or post like http methods
            // POST
            // PUT
            // PATCH
            // DELETE
        // get
            // GET
        // # in mainSettings.trait start
            // set over arching API keys, use function to get the key
            // # password/key specificity general to specific, the order that keys matter
                // mainApiKey // over arching key, all will use it if they do not have a more specific key
                // mainPostApiKey or mainGetApiKey // this allows you to specify an over arching key for post or get paths
                // classKey // specific to the class being used
                // routKey // specific to the route being used
                // methodKey // specific to the method within the route being used
            // example for mainSettings trait set up of keys, located: traits/mainSettings.trait.php
                // static protected $mainApiKey = 'T3$$tK3y!2456'; // use get_main_api_key()
                // static protected $mainGetApiKey = 'T3$$tK3y!24561234'; // use get_main_get_api_key()
                // static protected $mainPostApiKey = 'T3$$tK3y!2456@#5^&'; // use get_main_post_api_key()
            // set up classList in the mainSettings.trait.php 
                // example bellow
                    // static protected $classList = ["Category", "Label", "MediaContent", "Post", "Tag", "User"]; // use get_class_list()
                    // all classes found in the class list will have the option of being used in the rest API
                        // in order to utilize specific routes you must specify them in the class itself
                        // example
                            // $apiInfo = [
                            //     'routes' => [
                            //         "users" => [...]
                            //         "users/full" => [...]
                            //     ]
                            // ];
                                // the routes "users" and "users/full" have been specified and are now made available.
                                // keep on reading for other required fields within a route
        // # in db class end

        // # in class start 
            // set up apiInfo to show and give options, apiInfo is required for documentation and for api use
            // static protected $apiInfo = []
            $apiInfo = [
                // class specific validation, used for all HTTP methods if specific methodKey is not available, otherwise will refer to // * password/key specificity
                'classKey' => 'T3$$tK3y!2456',
                // routes allow you to specify routes and the options available to those routes // * required
                'routes' => [
                    // first rout, you can name a rout what ever you would like, but it is best to be descriptive about your routes
                    // this rout shows all available properties/options
                    "users/full" => [
                        // rout specific documentation, optional
                        'routDocumentation' => 'rout specific documentation',
                        // rout specific validation
                        'routKey' => 'T3$$tK3y!2456',
                        
                        // specify httpMethods you would like to use
                        // available methods 
                            // get = GET
                            // post = POST
                            // put = PUT
                            // patch = PATCH
                            // delete = DELETE
                        // specify httpMethods available for this rout 
                        'httpMethods' => [
                            // get does not need a password in any form, but if mainApiKey, mainGetApiKey, classKey, routKey or the get methodKey is set the key is required on the rout
                            'get' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456',
                                // this field/property specifies the array in the class that contains the specific instructions for the httpMethod it is associated with, see // * get_get_api_parameters for info on how to construct that array
                                'arrayInfo' => 'public_access', // * required
                                // this enables you to set whereConditions to limit or gide the api feed // * only for GET
                                'whereConditions' => 'userType NOT IN(admin,SuperAdmin)', // * only for GET
                                // this field/property allows you to show or not show the GET examples
                                // show or not to show that is the question
                                "apiShowGetExamples" => 'no', // can use no, default is yes if not set // * only for GET
                                // method documentation
                                'methodDocumentation' => 'method specific documentation',
                                // name specific properties you wish to exclude in the API
                                'apiPropertyExclusions' => ['password', 'adminNote'], // * only for GET 
                                // only pull from these columns, db, it can help you on query performance, best to use this in tandem with apiPropertyExclusions for the most accurate results
                                'columnOptions' => ['id', 'address', 'firstName', 'lastName'] // * only for GET
                            ],
                            // post like httpMethods follow // * password/key specificity
                            // post like httpMethods must have some kind of key
                            'post' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456',
                                // limit or gide by the arrayInfo, set what is available, see // @ get_post_api_parameters for info on how to construct that array
                                // ! important information
                                // ? TIP: you can merge arrays and take away from arrays to get the combination you would like, as long as the result output is an array your good, to signify a function versus a normal array use an '_'. Do not use an underscore if you are using just a normal array.
                                // // constructing an array for the api
                                // public static function publicApiParameters_dev() {
                                //     $arrayInfo = self::$publicApiParameters;
                                //     $arrayInfo['firstName'] = [
                                //         'refersTo' => ['firstName'],
                                //         'type' => ['str'],
                                //         'connection' => [
                                //             'str' => "LIKE"
                                //         ],
                                //         'description' => 'Gets users by first name',
                                //         'example' => ['id=Jim']
                                //     ];
                                //     return $arrayInfo;
                                //     // return new self(); //goes to __construct();
                                // }
                                'arrayInfo' => 'public_post_access', // * required
                                // method documentation
                                'methodDocumentation' => 'method specific documentation'
                            ],
                            'put' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456',
                                // limit or gide by the arrayInfo, set what is available, see // @ get_post_api_parameters for info on how to construct that array
                                'arrayInfo' => 'public_put_access',
                                // opens the option to update where a condition is met // * see apiIndex.php for documentation on putWhere located at root/public/api/v1/apiIndex.php
                                'putWhere' => true,
                                // method documentation
                                'methodDocumentation' => 'method specific documentation'
                            ],
                            'patch' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456',
                                // limit or gide by the arrayInfo, set what is available, see // @ get_post_api_parameters for info on how to construct that array
                                'arrayInfo' => 'public_patch_access',
                                // method documentation
                                'methodDocumentation' => 'method specific documentation'
                            ],
                            'delete' => [
                                // method key, most specific key available
                                'methodKey' => 'T3$$tK3y!2456',
                                // opens the option to delete where the condition is met // * see apiIndex.php for documentation on deleteWhere located at root/public/api/v1/apiIndex.php
                                'deleteWhere' => true,
                                // method documentation
                                'methodDocumentation' => 'method specific documentation'
                            ]
                        ] // * required
                    ]
                ] // * required
            ];
        // # in class end

    // this array can be named whatever you would like, just make sure that matches the corresponding httpMethod arrayInfo
    // static public
    // @ get_get_api_parameters
        // # parameters
            // a specific parameter allows you to search based off of that parameter, if no parameters are provided only the default order by, page, and per page will be available
            // name parameters with lowercase characters, and try to avoid any special characters at the start of the parameters name, we sort parameters by PHP's built-in ksort
            // make a parameter
                // 'id'=>[]
            // add parameter options
                // 'id'=>[
                //     'refersTo' => ['id'],
                //     'type' => ['int', 'list'],
                //     'connection' => [
                //         'int' => "=",
                //         'list' => 'in'
                //     ],
                //     'description' => 'Gets posts by the post id or list of post ids',
                //     'example' => ['id=1', 'id=1,2,3,4,5']
                // ]
        
        // # parameter options
            // * refersTo (required, array)
                // example = 'refersTo' => ['id']
                // refersTo, makes a reference to which database column you wish to use for querying
                // refersTo, allow you to set multiple columns to use, If no custom validation is provided the first column will be used to instantiate/utilize it's validation 
                // 'refersTo' => ['extraOptions'] This special refersTo Allows you to create custom code to send back additional options in the API, also allow you to do a few other things // ! see *** example 4 and 5 ***
                    // add 'useFor' => '', to specify what action you would like to take
                        // 'useFor' => 'columns'   
                            // this allows a user of your API to specify which columns they desire, this can be overridden by columnOptions in the rout GET method 
                            // it comes prepared to receive a type of "str" and "list", and validation to make sure it matches the class database columns, you can also provide validation for the overall string that is sent in
                        // 'useFor' => 'propertyExclusions'
                            // this allows you to limit what properties are returned in the json object, this can be combined with the route GET method apiPropertyExclusions
                            // it comes prepared to receive a type of "str" and "list", and validation to make sure it matches the class properties, you can also provide validation for the overall string that is sent in
                        // 'useFor' => 'code::get_full_api_data'
                            // this feature is fairly complicated but once understood it can be very powerful 
                            // this allows you to perform custom code to return, for each object. 
                            // this is mostly for customizing the API experience in some fashion.
                            // you can do extra validation, attach more information extended data that is returned. This function or the function you specify after the code:: will perform for every object that is pulled back from the query
                            // you can do normal validation on the whole string passed to the property. Parsing the GET string to do more validation has to be done within the function
            // * required (not required, true/false)
                // specifies whether or not this field is required to be sent through in order to access the endpoint
            // * type (required, array)
                // types, use one and then list if you desirer, // ! there is ever only two options at a time
                    // str = string
                    // int = number integer
                    // date = a str but runs through a date formatter
                    // list = list separated by a ","
                        // list type must always be last in the array
                        // connection for a list must be IN or LIKE
                // example = 'type' => ['int', 'list']
                // Type refers to what type of content you're expecting to receive through the API
            // * connection (required, array)
                // example = 'connection' => [
                //    'int' => "=",
                //    'list' => 'in'
                // ]
                // (Connection)s make a reference to the (type)s to the different options available for querying in MySQL
                // Connection options available =, in, like, >=, <=, >, <
                    // what each option means
                        // =, equals
                        // in, find mach from list
                        // like, find a match like, %like%, or from a list like, %like% or %like%
                        // >=, Greater than or equal to
                        // <=, Less than or equal to
                        // >, Greater than or equal to
                        // <, Less than 
                // * description (required, str)
                    // example = 'description' => 'Gets posts by the post id or list of post ids'
                    // A description allows the consumers of your API to know what this parameter will do
                // * example (not required but strongly encouraged, array)
                    // example = 'example' => ['id=1', 'id=1,2,3,4,5']
                    // This allows the consumers of your API to know what a specific parameter option will look like in the URL, only include the parameter option and a valid value
                // * customExample (not required, associative array)
                    // example = 'customExample' => [ 
                    //         'greaterThan' => 'greaterThan=2018-02-01',
                    //         'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    //     ]
                    // This will take precedence over the normal example. It allows for custom example names to be displayed in the API documentation
                    // This allows the consumers of your API to know what a specific parameter option will look like in the URL, only include the associative array key and the parameter option and a valid value
                // * validation (not required, associative array)
                    // example = 'validation' => [
                    //         'name'=>'search',
                    //         'required' => true,
                    //         'type' => 'str', // type of string
                    //         'min'=> 2, // string length
                    //         'max' => 50, // string length
                    //         'html' => 'no'
                    //     ]
                    // Used in the same way as documented here // * validation_options located at: root/private/reference_information.php
                    // If the parameter you are wishing to set does not have validation for it you can specify how you would like to be validated. This will override the normal validation. see ***example 3 and example 4***

        // # examples
            // # example 1
            // 'id'=>[
            //     'refersTo' => ['id'],
            //     'type' => ['int', 'list'],
            //     'connection' => [
            //         'int' => "=",
            //         'list' => 'in'
            //     ],
            //     'description' => 'Gets posts by the post id or list of post ids',
            //     'example' => ['id=1', 'id=1,2,3,4,5']
            // ]

            // # example 2
            // 'greaterThen' => [
            //     'refersTo' => ['postDate'],
            //     'type' => ['str'],
            //     'connection' => [
            //         'str' => '>'
            //     ],
            //     'description' => 'Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan parameter to get dates in posts with createdDates between the two values, see examples',
            //     'customExample' => [ 
            //         'greaterThan' => 'greaterThan=2018-02-01',
            //         'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
            //     ]
            // ]

            // # example 3
            // 'search' => [
            //     'refersTo' => ['title', 'content'],
            //     'type' => ['str', 'list'],
            //     'connection' => [
            //         'str' => 'like',
            //         'list' => 'like'
            //     ],
            //     'validation' => [
            //         'name'=>'search',
            //         'required' => true,
            //         'type' => 'str', // type of string
            //         'min'=> 2, // string length
            //         'max' => 50, // string length
            //         'html' => 'no'
            //     ],
            //     'description' => 'Gets posts by search parameters. Search will bring Posts that match the given string in both the title and the content field',
            //     'example' => ['search=sale', 'search=sale,off,marked down']
            // ]

            // # example 4
            // 'columns' => [
            //     'refersTo' => ['extraOptions'],
            //     'type' => ['str','list'],
            //     'useFor' => 'columns', // * only for extraOptions
            //     'validation' => [
            //         'name'=>'extendedData',
            //         'required' => true,
            //         'type' => 'str',
            //         'min'=> 1,
            //         'max' => 150,
            //         'html' => 'no'
            //     ],
            //     'description' => 'What database columns you want to use',
            //     'example' => ['columns=firstName', 'columns=firstName,lastName']
            // ],
            // 'extendedData' => [
            //     'refersTo' => ['extraOptions'],
            //     'type' => ['str'],
            //     'useFor' => 'code::extended_api_data', // * only for extraOptions
            //     'validation' => [
            //         'name'=>'extendedData',
            //         'required' => true,
            //         'type' => 'str',
            //         'min'=> 1,
            //         'max' => 50,
            //         'html' => 'no'
            //     ],
            //     'description' => 'Returns all extended post data. 0 = Return basic post data, 1 = Return extended post data. Default is 0. extended data includes all images attached to the post ',
            //     'example' => ['extendedData=1']
            // ],
            // 'propertyExclusions' => [
            //     'refersTo' => ['extraOptions'],
            //     'type' => ['str', 'list'],
            //     'useFor' => 'propertyExclusions', // * only for extraOptions
            //     'validation' => [
            //         'name'=>'extendedData',
            //         'required' => true,
            //         'type' => 'str',
            //         'min'=> 1,
            //         'max' => 150,
            //         'html' => 'no'
            //     ],
            //     'description' => 'What properties do you want to exclude',
            //     'example' => ['propertyExclusions=firstName', 'propertyExclusions=firstName,lastName']
            // ]

            // # example 5, example of code function, code::get_full_api_data
            // class function for extraOptions code::, should be constructed something like this
            // protected function extended_api_data(array $codeData_array = []) {
            //     var_dump($codeData_array['data']);
            //     var_dump($codeData_array['propertyExclusions']);
            //     var_dump($codeData_array['prepApiData_array']);
            //     var_dump($codeData_array['routInfo_array']);
            //     var_dump($codeData_array['routName']);
            //     // return the data array
            //     return $data_array ?? [];
            // }

            // example of using it
            // get api data plus extended data
            // uncomment below to see better
            // protected function extended_api_data(array $codeData_array = []) {
            //     // var_dump($codeData_array['data']);
            //     // var_dump($codeData_array['propertyExclusions']);
            //     // var_dump($codeData_array['prepApiData_array']);
            //     // var_dump($codeData_array['routInfo_array']);
            //     // var_dump($codeData_array['routName']);
            //     // get api data
            //     $data_array = $this->api_attributes($codeData_array['routInfo_array'], $codeData_array['propertyExclusions']);
            //     // if of the correct type get categories, tags, or labels
            //     if ($this->ctr() == 1 || $this->ctr() == 2 || $this->ctr() == 3 || $this->ctr() == 4) {
            //         $data_array['categories'] = $this->get_obj_categories_tags_labels('categories');
            //         $data_array['tags'] = $this->get_obj_categories_tags_labels('tags');
            //         $data_array['labels'] = $this->get_obj_categories_tags_labels('labels');
            //     }
            //     // if of the correct type get all images
            //     if ($this->ctr() == 1 || $this->ctr() == 3) {
            //         // set blank array, set below
            //         $image_array = [];
            //         // get image(s)
            //         if ($this->ctr() == 1) {
            //             $temp_array = $this->get_post_images();
            //         } else {                                               
            //             $temp_array = $this->get_user_image();
            //         }
            //         // loop over info to make new array
            //         $image_array = obj_array_api_prep($temp_array);
            //         // put images into the correct spot
            //         $data_array['images'] = $image_array;
            //     }
            //     // return data
            //     return $data_array ?? [];
            // }

    // this array can be named whatever you would like, just make sure that matches the corresponding httpMethod arrayInfo
    // static public
    // @ get_post_api_parameters
        // # parameters
            // parameters specify what is allowed to be changed based off of what HTTP method you are using
            // ! id as of 6/14/19 is not a permissible parameter and will be ignored
                // available methods 
                    // post = POST
                        // parameters specify what fields are able to be filled.
                        // be careful to provide all required fields for update in some fashion as our internal checks force all requires to be instantiated on creation of a new record
                    // put = PUT
                        // parameters specify what fields are able to be updated
                        // id is required to be passed in to know what record to update
                        // what is passed in is all that will be updated
                    // patch = PATCH
                        // parameters specify what fields are able to be modified, PATCH allows you to copy another document and modify based off the parameters provided
                        // id is required to be passed in to know what record to copy
                    // delete = DELETE  
                        // id is required
            // make a parameter
                // name parameters with lowercase characters, and try to avoid any special characters at the start of the parameters name, we sort parameters by PHP's built-in ksort
                    // 'userType'=>[]
            // add parameter options
                // 'userType' => [
                //     'required' => false,
                //     'type' => ['str'],
                //     'description' => 'This field expects a date, specifically the date it was created on',
                //     'validation' => [
                //         'name'=>'userType',
                //         'required' => true,
                //         'type' => 'str',
                //         'matchOptions' ['salesRep', 'regularEmployee', 'projectManagers'] 
                //     ],
                // ]
        
        // # parameter options
            // * required (not required, true/false)
                // specifies whether or not this field is required to be sent through to access the endpoint 
            // * type (required, array)
                // types
                    // str = string
                    // int = number integer
                    // date = a str but runs through a date formatter
                // example = 'type' => ['int']
                // Type refers to what type of content you're expecting to receive through the API
            // * description (required, str)
                // example = 'description' => 'Put your description here.'
                // A description allows the consumers of your API to know what this parameter will do or excepts
            // * validation (not required, associative array)
                // example = 'validation' => [
                //         'name'=>'search',
                //         'required' => true,
                //         'type' => 'str', // type of string
                //         'min'=> 2, // string length
                //         'max' => 50, // string length
                //         'html' => 'no'
                //     ]
                // Used in the same way as documented here // * validation_options located at: root/private/reference_information.php
                // You can specify custom validation that will override the normal validation.

    // @ authentication
        // authentication allows a user to log into the system. This is the default set up and has the following options. 
            // # NameOfClass
                // this should be the name of the user class or the class in which you store users that will log into the system.
                // default: User
                // * value required
            // # filedToCompare
                // this should be the filed in the user object and database that you wish to select for verifying the user. For example that could be a username or emailAddress.
                // default: username
                // * value required
            // # hashedPasswordFiledName
                // this should be the name of the password field in which you want to verify your password. the reason it is called hashedPasswordFiledName is to remind the developer that all passwords should be saved as hashed passwords.
                // default: hashedPassword
                // * value required
            // # identifierFiledName
                // this filed should typically be the user ID, but it is up to you. it will be stored under the session variable userIdentifier when a user is logged in.
                    // ex: if userIdentifier is set in session the user is login. This user identifier is typically the user ID but can also be another filled in the user class/database
                // if you wish to set and maintain other session variables please see
                    // * custom_code_spots located at: root/private/rules_docs/reference_information.php
                // * value required
        // #How it works
            // it is part of the mainSettings.trait.php 
                // it is best to use the default until you understand how it works and then feel free to adjust accordingly
                // * default ex: static protected $authentication = ['User', 'username', 'hashedPassword', 'id'];
                // you may need to adjust the login.php page to fit the field names accordingly.
                    // root/public/login.php
                    // for example if you switch it from username to emailAddress you should change the labels on the form fields so the user knows what they need to input to login.

    // @ custom_code_spots
        // as of 1/21/2021 there are three custom code spots built in
            // # customDbObjCode.trait.php
                // this adds extra functionality to the databaseobject.class.php file.
                    // this is the preferred way to add functionality to this class. This will allow future updates to be made while, protecting custom code.
                // file located at root\private\traits\customDbObjCode.trait.php.
            // # after_session_check.php
                // this custom code spot is for all session protected pages. This page runs after the session check is done.
                    // this is the preferred way to add functionality to all session protected pages. This will allow future updates to be made while, protecting custom code.
                // file located at root\public\admin\all_pages\after_session_check.php.
            // # before_session_check.php
                // this custom code spot is for all session protected pages. This page runs before the session check is done.
                    // every view page in the protected environment runs this custom code each time a page is loaded
                    // this is the preferred way to add functionality to all session protected pages. This will allow future updates to be made while, protecting custom code.
                // file located at root\public\admin\all_pages\before_session_check.php.
?> 