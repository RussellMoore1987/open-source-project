<?php
    // ! start here, How to make it work *************************************************
    // ! make page and run it
    trait UserSeeder {
        // SQL structure
        static protected $seederInfo = [
            'defaultRecordCount' => 50,
            'id' => "id",
            'address' => "address",
            'adminNote' => "sentences(3)",
            'catIds' => "",
            'createdBy' => "rand(1,20)",
            'createdDate' => "date()",
            'emailAddress' => "email",
            'firstName' => "FName",
            'imageName' => "",
            'labelIds' => "",
            'lastName' => "LName",
            'mediaContentId' => "",
            'note' => "sentences(3)",
            'password' => "TEST!@#$1234",
            'phoneNumber' => "phoneNumber",
            'showOnWeb' => "rand(0,1)",
            'tagIds' => "",
            'title' => "jobTitle",
            'username' => "username",
        ];

        // sql feeder
        static public function seeder_info(object $Seeder) {
            // build array
            $seederInfo = [
                'defaultRecordCount' => 50,
                'id' => $Seeder->id(),
                'address' => $Seeder->address(),
                'adminNote' => $Seeder->sentences(3),
                'catIds' => "",
                'createdBy' => rand(1,20),
                'createdDate' => $Seeder->date($min='1/01/19' , $max='1/01/20'),
                'emailAddress' => $Seeder->email(),
                'firstName' => $Seeder->first_name(),
                'imageName' => "",
                'labelIds' => "",
                'lastName' => $Seeder->last_name(),
                'mediaContentId' => "",
                'note' => $Seeder->sentences(3),
                'password' => $Seeder->password(),
                'phoneNumber' => $Seeder->phone_number(),
                'showOnWeb' => rand(0,1),
                'tagIds' => "",
                'title' => $Seeder->job_title(),
                'username' => $Seeder->username(),
            ];
             
            // return
            return $seederInfo;
        }
    }
?>