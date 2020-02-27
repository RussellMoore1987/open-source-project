<?php
    // seeder for id's 
    trait SeederId {
        public $id = 1;
        
        // to get an id
        public function id(int $startAtId = 0) {
            // set ID, check it were starting with a higher number
            if ($startAtId > $this->id) {
                $this->id = $startAtId;
            }
            // get number 
            $id = $this->id;
            // increment id counter
            $this->id++;
            // return data
            return $id; 
        }
    }
?>