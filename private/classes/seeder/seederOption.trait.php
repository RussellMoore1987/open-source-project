<?php
    // seeder for options 
    trait SeederOption {
        // to get a option 
        public function options(array $options=[]) {
            // get an option, return data
            return $option = $options[rand(0, count($options) - 1)]; 
        }
    }    
?> 










