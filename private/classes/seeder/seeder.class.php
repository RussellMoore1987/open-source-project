<?php
    class Seeder {
        // to get a max character count
        public function max_count(string $string, int $max = 25, string $ending = "") {
            return $string = substr($string, 0, $max) . $ending;   
        }      
    }
?>