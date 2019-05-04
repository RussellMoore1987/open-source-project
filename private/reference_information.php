<?php
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

    // @ validation_options
        // # examples of parameters
            // val_validation(
            //     'the coolest post ever', 
            //     [
            //         'name' => 'Post Title', 
            //         'type' => 'num'/'str'/'int', 
            //         'num_min' => 1, 
            //         'num_max' => 10, 
            //         'min' => 3, 
            //         'max' => 5, 
            //         'exact' => 5, 
            //         'required' => 'yes'/'no', 
            //         'html' => 'yes'/'no'/'full'
            //         'date' => 'yes'
            //     ]
            // )
            
        // # explanation of options
            // val_validation('$value', $options)
            // # $value
                // the value you are wishing to validate
            // # $options
                // potential options of validation
                    // * 'name' => 'Post Title'
                        // human readable name
                        // often use the form name or label 
                    // * 'date' => 'yes' 
                        // checks to see whether not it is a valid date
                    // * 'type' => 'num'/'str'/'int'
                        // num = determines whether or not it is a number, allows decimals
                        // str = determines whether or not it is a string
                        // int = determines whether or not it is a integer, does not allow decimals
                    // * 'num_min' => 1
                        // it makes sure that the number value is not less than the number set
                    // * 'num_max' => 10
                        // it makes sure that the number value is not more than the number set
                    // * 'min' => 3
                        // it makes sure that the string length is not less than the set value
                    // * 'max' => 5
                        // it makes sure that the string length is not more than the set value
                    // * 'exact' => 5 
                        // it makes sure that the string length is the same as the set value
                    // * 'required' => 'yes' 
                        // it makes sure a value is sent through: it is needed, can not be blank, can not be NULL
                        // we also use the required for the cleanFormArray() function in the databaseobject class.
                            // if it is required and it goes to the cleanFormArray() function, if it is NULL or an empty string it will be kicked off the returned $post_array.
                    // ? there are two different ways to work with form data, 
                        // get it or reject it
                            // to activate, you must have the validation parameter "required" set
                        // if I get it then validate it, if not past the blank through
                            // get it then validate it or let the blank pass through
                                // this is done by default, but you can set in a "min" parameter which allows you to give minimum length value if it is passed through, all other validations should work as well.
                            // let the blank pass through, done by default

                    // * 'html' => 'yes'/'no'/'full'
                        // yes = allows for HTML special characters but does not allow JavaScript characters. Excluded values: <script, ;, \
                        // no = dose not allow for HTML special characters and does not allow JavaScript characters. Excluded values: <>, (), [], {}, ;, \, /
                        // full = allows everything through
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

    // @ get_api_parameters
        // # parameters
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
                // 'refersTo' => ['extraOptions'] This special refersTo Allows you to create custom code to send back additional options in the API, see ***example 4***
            // * type (required, array)
                // example = 'type' => ['int', 'list']
                // Type refers to what type of content you're expecting to receive through the API
            // * connection (required, array)
                // example = 'connection' => [
                //    'int' => "=",
                //    'list' => 'in'
                // ],
                // (Connection)s make a reference to the (type)s to the different options available for querying in MySQL
                // Connection options available =, in, like, >=, <=, >, <, like::or
                    // what each option means
                        // =, equals
                        // in, find mach from list
                        // like, find a match like, %like%
                        // >=, Greater than or equal to
                        // <=, Less than or equal to
                        // >, Greater than or equal to
                        // <, Less than 
                        // like::or, find a match like, %like% or %like%, from list
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
                    // * validation_options located at: root/private/reference_information.php
                    // example = 'validation' => [
                    //         'name'=>'search',
                    //         'required' => 'yes',
                    //         'type' => 'str', // type of string
                    //         'min'=> 2, // string length
                    //         'max' => 50, // string length
                    //         'html' => 'no'
                    //     ],
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
            //     'description' => 'Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan paramter to get dates in posts with createdDates between the two values, see examples',
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
            //         'list' => 'like::or'
            //     ],
            //     'validation' => [
            //         'name'=>'search',
            //         'required' => 'yes',
            //         'type' => 'str', // type of string
            //         'min'=> 2, // string length
            //         'max' => 50, // string length
            //         'html' => 'no'
            //     ],
            //     'description' => 'Gets posts by search parameters. Search will bring Posts that match the given string in both the title and the content field',
            //     'example' => ['search=sale', 'search=sale,off,marked down']
            // ]

            // # example 4
            // 'extendedData' => [
            //     'refersTo' => ['extraOptions'],
            //     'type' => ['int'],
            //     'validation' => [
            //         'name'=>'extendedData',
            //         'required' => 'yes',
            //         'type' => 'int', // type of int
            //         'num_min'=> 0, // min num
            //         'num_max' => 1, // max num
            //     ],
            //     'description' => 'Returns all extended post data. 0 = Return basic post data, 1 = Return extended post data. Default is 0.  ',
            //     'example' => ['extendedData=1']
            // ]
?> 