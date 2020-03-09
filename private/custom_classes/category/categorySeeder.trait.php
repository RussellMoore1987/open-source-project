<?php
    trait CategorySeeder {

        // get seeder default record count
        static public $seederDefaultRecordCount = 50;

        // sql feeder
        static public function seeder_setter(object $Seeder) {
            // SQL helper builders
                // help find sub ID
                $sudId = 0;
                if (rand(0,100) > 60) {
                    $catCount = Category::count_all();
                    // check to make sure we got back a record
                    $CatObject = NULL;
                    $trigger = true;
                    while ($trigger) {
                        // check to see we have a real object
                        if (is_object($CatObject) && $CatObject != NULL) {
                            $trigger = false;
                        } else {
                            $catId = rand(1, $catCount);
                            $CatObject = Category::find_by_id($catId);
                        }
                    }
                    // get class ID
                    $idName = Category::get_id_name();
                    $sudId = $CatObject->$idName;
                    $useCat = $CatObject->useCat;
                    
                }
                //  get use cat if not set
                $useCat = $useCat ?? rand(1,4);

            // build array
            $seederInfo = [
                'title' =>  ucfirst($Seeder->max_char($Seeder->min_char($Seeder->words(rand(1,3)), 2), 50)),
                'subCatId' => $sudId,
                'note' =>  $Seeder->max_char($Seeder->sentences(rand(0,3)), 255, "."),
                'useCat' => $useCat
            ];
            
            // return data
            return $seederInfo;
        }
    }
?>