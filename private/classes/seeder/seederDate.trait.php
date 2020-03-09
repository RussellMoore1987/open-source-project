<?php
    // seeder for dates 
    trait SeederDate {
        // to get a date
        public function date(string $min='1970', string $max='now', string $dateFormat='Y-m-d') {
            // convert dates/string to timestamp
            $min = strtotime($min);
            $max = strtotime($max);
            // get random date
            $date = mt_rand($min, $max);
            // return data
            return $date = date($dateFormat, $date); 
        }
    }
?>