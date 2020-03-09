<?php
    trait TagSeeder {

        // get seeder default record count
        static public $seederDefaultRecordCount = 100;

        // sql feeder
        static public function seeder_setter(object $Seeder) {
            // build array
            $seederInfo = [
                'title' =>  ucfirst($Seeder->max_char($Seeder->min_char($Seeder->words(rand(1,3)), 2), 50)),
                'note' =>  $Seeder->max_char($Seeder->sentences(rand(0,3)), 255, "."),
                'useTag' => rand(1,4)
            ];
             
            // return data
            return $seederInfo;
        }
    }
?>