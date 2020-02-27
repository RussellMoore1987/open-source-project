<?php
    // seeder for dates 
    trait SeederDate {
        // to get a date
        public function date($min='1970', $max='now', $dateFormat='m-d-Y') {
            // convert dates to timestamp
            $min = strtotime($min);
            $max = strtotime($max);
            // get random date
            $date = mt_rand($min, $max);
            // return data
            return $date = date($dateFormat, $date); 
        }
    }
?>