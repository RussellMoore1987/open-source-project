<?php
    trait PostSeeder {

        // get seeder default record count
        static public $seederDefaultRecordCount = 640;

        // sql feeder
        static public function seeder_setter(object $Seeder) {
            // setting some variables
                // get users
                $author = rand(1, User::count_all());
            // build array
            $seederInfo = [
                'author' => $author,
                'authorName' => 'notSaving',
                'comments' => 0,
                'content' =>  $Seeder->max_char($Seeder->paragraphs(rand(1,5)), 65000, "."),
                'createdBy' => $author,
                'createdDate' => $Seeder->date($min='1/01/19' , $max='1/01/20'),
                'postDate' => $Seeder->date($min='1/01/19' , $max='1/01/20'),
                'status' => rand(0,1),
                'title' => ucfirst($Seeder->max_char($Seeder->min_char($Seeder->words(rand(1,3)), 2), 50))
            ];

            // return data
            return $seederInfo;
        }
    }
?>