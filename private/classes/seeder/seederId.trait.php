<?php
    // seeder for id's 
    trait SeederId {
        // create counter for ID
        public $id = 1;
        
        // to get an id
        public function id(int $startAtId = 0) {
            // set ID, check if starting with a higher number
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