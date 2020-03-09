<?php
    trait LabelSeeder {

        // get seeder default record count
        static public $seederDefaultRecordCount = 50;

        // sql feeder
        static public function seeder_setter(object $Seeder) {
            // build array
            $seederInfo = [
                'title' =>  ucfirst($Seeder->max_char($Seeder->min_char($Seeder->words(rand(1,3)), 2), 50)),
                'note' =>  $Seeder->max_char($Seeder->sentences(rand(0,3)), 255, "."),
                'useLabel' => rand(1,4)
            ];
             
            // return data
            return $seederInfo;
        }
    }
?>