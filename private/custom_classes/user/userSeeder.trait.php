<?php
    trait UserSeeder {

        // get seeder default record count
        static public $seederDefaultRecordCount = 42;

        // sql feeder
        static public function seeder_setter(object $Seeder) {
            // setting some variables
            $fName = $Seeder->first_name();
            $lName = $Seeder->last_name();
            // build array
            $seederInfo = [
                'address' => $Seeder->address(),
                'adminNote' =>  $Seeder->max_char($Seeder->sentences(rand(0,3)), 255, "."),
                'createdBy' => rand(1,20),
                'createdDate' => $Seeder->date($min='1/01/19' , $max='1/01/20'),
                'emailAddress' => remove_char_from_str([' '], $Seeder->email($Seeder->min_char("{$fName}{$lName}",4))),
                'firstName' => $fName,
                'lastName' => $lName,
                'mediaContentId' => 0,
                'note' => $Seeder->max_char($Seeder->sentences(rand(1,3)), 255, "."),
                'password' => "Sdkvldsg$#@!%$!!!",
                'phoneNumber' => $Seeder->phone_number(),
                'showOnWeb' => rand(0,1),
                'title' => remove_char_from_str(['.','/','\\'], $Seeder->job_title()),
                'username' => $Seeder->username()
            ];
             
            // return data
            return $seederInfo;
        }
    }
?>