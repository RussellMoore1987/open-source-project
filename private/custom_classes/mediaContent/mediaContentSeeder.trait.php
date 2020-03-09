<?php
    trait MediaContentSeeder {

        // get seeder default record count
        static public $seederDefaultRecordCount = 17;

        // image helper
        // static protected $imageData = [
        //     ["imageName"=>"shay_profile_pic.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"stephanie_profile_pic.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-1.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-2.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-3.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-4.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-5.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-6.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"abd_profile_pic.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"2018-03-19_14-56-54.png","fileType"=>"PNG"],
        //     ["imageName"=>"adilas_e-mail_featured_image-1.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"adilas_university_featured_image.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"code_featured_image.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Full-5.mp4","fileType"=>"MP4"],
        //     ["imageName"=>"news_and_update.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"php_cms_featured_image.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"that_herb_shop_featured_image.jpg","fileType"=>"JPG"],
        //     ["imageName"=>"Untitled-Project.mp4","fileType"=>"MP4"],
        //     ["imageName"=>"wicked_cute_boutique_featured_image.jpg","fileType"=>"JPG"]
        // ];
        static protected $imageData = [
            ["imageName"=>"shay_profile_pic.jpg","fileType"=>"JPG"],
            ["imageName"=>"stephanie_profile_pic.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-1.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-2.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-3.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-4.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-5.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-6.jpg","fileType"=>"JPG"],
            ["imageName"=>"abd_profile_pic.jpg","fileType"=>"JPG"],
            ["imageName"=>"2018-03-19_14-56-54.png","fileType"=>"PNG"],
            ["imageName"=>"adilas_e-mail_featured_image-1.jpg","fileType"=>"JPG"],
            ["imageName"=>"adilas_university_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"code_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"news_and_update.jpg","fileType"=>"JPG"],
            ["imageName"=>"php_cms_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"that_herb_shop_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"wicked_cute_boutique_featured_image.jpg","fileType"=>"JPG"]
        ];
        static protected $imageCounter = 0;

        // sql feeder
        static public function seeder_setter(object $Seeder) {
            // setting some variables
                // get images
                $counter = self::$imageCounter;
                // increment a counter
                if ($counter < 17) {
                    self::$imageCounter++;
                } else {
                    self::$imageCounter = 0;
                }
                $imageData = self::$imageData[$counter];
                // get users
                $user = rand(1, User::count_all());
                
            // build array
            $seederInfo = [
                'name' => $imageData["imageName"],
                'type' => $imageData["fileType"],
                'note' =>  $Seeder->max_char($Seeder->sentences(rand(1,3)), 255, "."),
                'alt' => "",
                'createdBy' => $user,
                'createdDate' => $Seeder->date($min='1/01/19' , $max='1/01/20')
            ];
             
            // return data
            return $seederInfo;
        }
    }
?>