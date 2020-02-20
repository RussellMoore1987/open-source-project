<?php
    // ! start here, How to make it work *************************************************
    // ! make page and run it
    trait UserSeeder {
        // SQL structure
        static protected $seederInfo = [
            'defaultRecordCount' => 50,
            'id' => "id",
            'address' => "address",
            'adminNote' => "sentence(50)",
            'catIds' => "",
            'createdBy' => "rand(1,20)",
            'createdDate' => "date()",
            'emailAddress' => "email",
            'firstName' => "FName",
            'imageName' => "",
            'labelIds' => "",
            'lastName' => "LName",
            'mediaContentId' => "",
            'note' => "sentence(50,200)",
            'password' => "",
            'phoneNumber' => "phoneNumber",
            'showOnWeb' => "rand(0,1)",
            'tagIds' => "",
            'title' => "jobTitle",
            'username' => "username",
        ];
    }
?>