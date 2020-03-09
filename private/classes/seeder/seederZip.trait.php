<?php
    // seeder for zip 
    trait SeederZip {
        // to get a zip 
        public function zip() {
            // create default variables
            $zip = "";
            // make zip
            for ($i=0; $i < 5; $i++) { 
                $zip .= $this->zipRandString[rand(0, count($this->zipRandString) - 1)];
            }
            // make number, return data
            return $zip; 
        }

        // an array of numbers
        public $zipRandString = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0']; 
    }    
?> 