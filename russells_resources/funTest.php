<?php
    $options = "2,4,5,6,jim,sam,Sam";

    $value = "SAm";

    // check to see if it is an array, if not make
    if (!is_array($options)) {
        $options = explode(",", $options);
    }

    $options_array = $options;
    print_r($options_array);
    // check to see if the word is in the array of options
    if (!in_array($value, $options_array)) {
        // error message
        echo "The value \"gender\" requires that the value passed in contains one of the text values found in this list, '" . implode(",", $options_array) . "'. This validation is case sensitive.";
    }

?>