<?php
    // seeder for phone number 
    trait SeederPhoneNumber {

        // to get a phone number 
        public function phone_number(string $spacer='-') {
            // create phone number
            $phoneNumber = '';
            for ($i=0; $i < 10; $i++) { 
                // make sure the first digit of each section is not a zero
                if ($i === 0 || $i === 3 || $i === 6) {
                    $phoneNumber .= $this->numbers1[rand(0, count($this->numbers1) - 1)];
                } else {
                    $phoneNumber .= $this->numbers2[rand(0, count($this->numbers2) - 1)];
                    // add spacer at the correct time
                    if ($i === 2 || $i === 5) {
                        $phoneNumber .= $spacer;
                    }
                }
            }
            // return data
            return $phoneNumber; 
        }

        // an array of numbers
        public $numbers1 = [1,2,3,4,5,6,7,8,9]; 
        public $numbers2 = [1,2,3,4,5,6,7,8,9,0]; 
    }    
?> 