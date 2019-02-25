<?php
    // @ collection_type_reference
        // # reference number
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
                // * 'required' => 'yes'/'no' 
                    // it makes sure a value is sent through
                // * 'html' => 'yes'/'no'/'full'
                    // yes = allows for HTML special characters but does not allow JavaScript characters. Excluded values: <script, ;, \
                    // no = dose not allow for HTML special characters and does not allow JavaScript characters. Excluded values: <>, (), [], {}, ;, \, /
                    // full = allows everything through
    
?> 