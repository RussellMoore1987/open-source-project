<?php

    // # is blank
    // is_blank('abcd')
    // validate data presence
    // uses trim() so empty spaces don't count
    // uses === to avoid false positives
    // better than empty() which considers "0" to be empty
    function is_blank($value) {
        return !isset($value) || trim($value) === '';
    }

    // # has presence
    // has_presence('abcd')
    // validate data presence
    // reverse of is_blank()
    // I prefer validation names with "has_"
    function has_presence($value) {
        return !is_blank($value);
    }

    // # has length greater than
    // has_length_greater_than('abcd', 3)
    // validate string length
    // spaces count towards length
    // use trim() if spaces should not count
    function has_length_greater_than($value, $min) {
        $length = strlen($value);
        return $length > $min;
    }

    // # has length less than
    // has_length_less_than('abcd', 5)
    // validate string length
    // spaces count towards length
    // use trim() if spaces should not count
    function has_length_less_than($value, $max) {
        $length = strlen($value);
        return $length < $max;
    }

    // # has length exactly
    // has_length_exactly('abcd', 4)
    // validate string length
    // spaces count towards length
    // use trim() if spaces should not count
    function has_length_exactly($value, $exact) {
        $length = strlen($value);
        return $length == $exact;
    }

    // # has length
    // has_length('abcd', ['min' => 3, 'max' => 5])
    // validate string length
    // combines functions_greater_than, _less_than, _exactly
    // spaces count towards length
    // use trim() if spaces should not count
    function has_length($value, $options) {
        if(isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
        return false;
        } elseif(isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
        return false;
        } elseif(isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
        return false;
        } else {
        return true;
        }
    }

    // # has inclusion of
    // has_inclusion_of( 5, [1,3,5,7,9] )
    // validate inclusion in a set
    function has_inclusion_of($value, $set) {
        return in_array($value, $set);
    }

    // # has exclusion of
    // has_exclusion_of( 5, [1,3,5,7,9] )
    // validate exclusion from a set
    function has_exclusion_of($value, $set) {
        return !in_array($value, $set);
    }

     
    // has_string('nobody@nowhere.com', '.com')
    // validate inclusion of character(s)
    // strpos returns string start position or false
    // uses !== to prevent position 0 from being considered false
    // strpos is faster than preg_match()
    function has_string($value, $required_string) {
        return strpos($value, $required_string) !== false;
    }

    // # has valid email format
    // has_valid_email_format('nobody@nowhere.com')
    // validate correct format for email addresses
    // format: [chars]@[chars].[2+ letters]
    // preg_match is helpful, uses a regular expression
    //    returns 1 for a match, 0 for no match
    //    http://php.net/manual/en/function.preg-match.php
    function has_valid_email_format($value) {
        $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
        return preg_match($email_regex, $value) === 1;
    }

    // TODO: add to val validation function
    // # validate password
    // validate password
    function validate_password($user_password) {
        // set up default values
        $errors = [];

        // Extra validation on password. At least one uppercase letter, lowercase letter, number, and symbol
        // check for a uppercase letter
        if (!preg_match('/[A-Z]/', $user_password)) {
            $errors[] = "The password field must contain at least one uppercase letter.";
        }
        // check for a lowercase letter
        if (!preg_match('/[a-z]/', $user_password)) {
            $errors[] = "The password field must contain at least one lowercase letter.";
        } 
        // check for a number
        if (!preg_match('/[0-9]/', $user_password)) {
            $errors[] = "The password field must contain at least one number.";
        } 
        // check for a symbol
        if (!preg_match('/[^A-Za-z0-9\s]/', $user_password)) {
            $errors[] = "The password field must contain at least one symbol.";
        }

        // return data
        return $errors;
    }   

    // # has unique username
    // has_unique_username('johnqpublic')
    // Validates uniqueness of admins.username
    // For new records, provide only the username.
    // For existing records, provide current ID as second argument
    //   has_unique_username('johnqpublic', 4)
    function has_unique_username($username, $current_id="0") {
        // Need to re-write for OOP
    }

    // # is list
    // Determine the string is a comma separated list
    function is_list(string $data,string $separator=",") {
        // Check if it is a comma separated list inside the string
        if(strpos($data, $separator) !== false) {
            return true;
        } else {
            return false;
        }
    }

    // # contains
    // Determine if the string contains a specific word or group of characters
    function contains($string, $contains) {
        // The regular expression to match against
        $regEx = "/" . $contains . "/";
        // Use RegEx to determine if the word is contained in the string
        if (preg_match($regEx, $string)) {
            return true;
        } else {
            return false;
        }
    }

    // # val validation
    // @ val validation start
        // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
        function val_validation($value, $options = [], $valueName = "value") {

            // * Set defaults
            $val_length = strlen(Trim($value));
            $value = Trim($value);
            $name = Trim($options['name']) ?? "Unknown Name";
            $errors = [];

            // * check to see if we can allow a blank string to pass through, no need to continue doing validation
            if ($val_length === 0 && !isset($options['required'])) {
                // return error array, no errors, let the blank string pass through
                return $errors;
            }

            // * Validate length min
            if(isset($options['min']) && $val_length < Trim($options['min'])) {
                $option_min = Trim($options['min']);
                // error message
                $errors[] = "The {$valueName} \"{$name}\" needs to be at least {$option_min} characters long.";
            }

            // * Validate length max
            if(isset($options['max']) && $val_length > Trim($options['max'])) {
                $option_max = Trim($options['max']);
                $length_over = $val_length - $option_max;
                // error message 
                $errors[] = "The {$valueName} \"{$name}\" has a max length of {$option_max} characters. You are {$length_over} character(s) too long.";
            } 

            // * Validate length exact
            if(isset($options['exact']) && !($val_length == Trim($options['exact']))) { 
                $option_exact = Trim($options['exact']);
                // echo $val_length;
                // echo $option_exact;
                // error message
                $errors[] = "The {$valueName} \"{$name}\" requires an exact length of {$option_exact} characters. You have only {$val_length} of the characters.";
            }
            
            // TODO: need to test, contains and matchOptions
            // * Validating contains, send in spaces if you are looking for an exact word
            if(isset($options['contains'])) {
                $contains = $options['contains'];
                $string = $value;
                $regEx = "/{$contains}/";
                // Use RegEx to determine if the word is contained in the string
                if (!preg_match($regEx, $string)) {
                    // error message
                    $errors[] = "The {$valueName} \"{$name}\" requires that the value passed in contains the text \"{$contains}\".";
                }
            }

            // * Validating matchOptions
            if(isset($options['matchOptions'])) {
                $options_array = $options['matchOptions'];
                // check to see if it is an array, if not make
                if (!is_array($options_array)) {
                    $options_array = explode(",", $options_array);
                }
                // check to see if the word is in the array of options
                if (!in_array($value, $options_array)) {
                    // error message
                    echo "The {$valueName} \"{$name}\" requires that the value passed in contains one of the text values found in this list, '" . implode(",", $options_array) . "'. This validation is case sensitive.";
                }
            }

            // * Validating required
            if (isset($options['required']) && $options['required'] == true) {
                if (!isset($value) || trim($value) === '') {
                    // error message
                    $errors[] = "The {$valueName} \"{$name}\" is a required {$valueName}. This {$valueName} was either not submitted or is blank.";
                }
            }

            // * Validating is date
            if (isset($options['date']) && $options['date'] == true) {
                try {
                    new \DateTime($value);
                    // Passed validation message
                } catch (\Exception $e) {
                    // return error message
                    $errors[] =  "The {$valueName} of \"{$name}\" must be a valid date. For example 1/6/18 or 2017-01-06.";
                }
            }

            // * Validating is email
            if (isset($options['email']) && $options['email'] == true) {
                $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
                // check to see if it passes
                if (preg_match($email_regex, $value) === 0) {
                    // return error message
                    $errors[] =  "The value of \"{$name}\" must be a valid email. For example name@gmail.com. You sent in \"{$value}\".";
                }
            }

            // * Validating type
            if (isset($options['type']) && $options['type'] == "num") {
                if (!is_numeric($value)) {
                    // error message
                    $errors[] = "The {$valueName} \"{$name}\" must be a number.";
                }
            } elseif (isset($options['type']) && $options['type'] == "int") {
                // Convert string to number
                if (is_numeric($value)) {
                    $value = strpos($value, '.') === false ? intval($value) : floatval($value);
                    if (!is_int($value)) {
                        // error message
                        $errors[] = "The {$valueName} \"{$name}\" must be a number and have no decimals.";
                    }
                } else {
                    // error message
                    $errors[] = "The {$valueName} \"{$name}\" must be a number and have no decimals.";
                }
            } elseif (isset($options['type']) && $options['type'] == "str") {
                if (!is_string($value)) {
                    // error message
                    $errors[] = "The {$valueName} \"{$name}\" must be a string/text.";
                }
            }

            // * Validating number value max
            if (isset($options['num_max']) && $value > $options['num_max']) {
                    $num_max = $options['num_max'];
                    // error message
                    $errors[] = "The {$valueName} of \"{$name}\" can not be larger than {$num_max}.";
            } 

            // * Validating number value min
            if (isset($options['num_min']) && $value < $options['num_min']) {
                $num_min = $options['num_min'];
                // error message
                $errors[] = "The {$valueName} of \"{$name}\" can not be less than {$num_min}.";
            }
            
            // * Validating HTML I don't need to check it if it's blank
            if (trim($value) !== '') {
                // allowed HTML, not JavaScript
                if (isset($options['html']) && $options['html'] == "yes" ) {
                    // Checking for restricted characters
                    // uses !== to prevent position 0 from being considered false
                    if (strpos($value, '<script') !== false) { $errors[] = no_html_message($name, "<script> tag", $valueName);}
                    if (strpos($value, ';') !== false) { $errors[] = no_html_message($name, ";", $valueName);}
                    if (strpos($value, '\\') !== false) { $errors[] = no_html_message($name, "\\", $valueName);}
                } elseif (isset($options['html']) && $options['html'] == "no") {
                    // Checking for HTML characters
                    // uses !== to prevent position 0 from being considered false
                    if (strpos($value, '>') !== false || strpos($value, '<') !== false) { 
                        $errors[] = no_html_message($name, "<", $valueName);
                    }
                    if (strpos($value, ')') !== false || strpos($value, '(') !== false) { 
                        $errors[] = no_html_message($name, "(", $valueName);
                    }
                    if (strpos($value, '[') !== false || strpos($value, ']') !== false) { 
                        $errors[] = no_html_message($name, "[", $valueName);
                    }
                    if (strpos($value, '{') !== false || strpos($value, '}') !== false) { 
                        $errors[] = no_html_message($name, "{", $valueName);
                    }
                    if (strpos($value, '/') !== false) { 
                        $errors[] = no_html_message($name, "/", $valueName);
                    }
                    if (strpos($value, '\\') !== false) { 
                        $errors[] = no_html_message($name, "\\", $valueName);
                    }
                    if (strpos($value, ';') !== false) { 
                        $errors[] = no_html_message($name, ";", $valueName);
                    }
                }
            }
        
            // return error array
            return $errors; 
        }
    // @ val validation end

    // # no html message
    // Quick message for no html
    function no_html_message($name, $val_in_question, $valueName = "value") {
        // return error message
        return "The {$valueName} \"{$name}\" excludes certain types of html. The text in question is \" {$val_in_question} \".";
    }

    // # isDate
    // Checking to see if it is a date
    function isDate($value, $name) {
        try {
            new \DateTime($value);
            // Passed validation message
            return NULL; 
        } catch (\Exception $e) {
            // return error message
            return "The {$valueName} of \"{$name}\" must be a valid date. For example 1/6/18 or 2017-01-06.";
        }
    }
?>
