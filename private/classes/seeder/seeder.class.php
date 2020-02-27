<?php
    // include seeder traits, additional functionality
    require_once("seederPS.trait.php");
    require_once("seederPS.russian.trait.php");
    require_once("seederId.trait.php");
    require_once("seederDate.trait.php");
    require_once("seederAddress.trait.php");
    require_once("seederCity.trait.php");

    class Seeder {
        // to get a max character count
        public function max_count(string $string, int $max = 25, string $ending = "") {
            // cut string to size
            $string = substr($string, 0, $max); 
            //remove trailing spaces
            $string = trim($string);
            // add ending and remove previous ending if there  
            if (substr($string, -1) == "." && $ending != "" && $ending != ".") {
                $string = substr($string, 0, strlen($string) - 1) . $ending;
            } elseif (substr($string, -1) != "." && $ending != "") {
                $string .= $ending;
            }
            return $string;   
        } 
        
        // @ class traits start
            use SeederPS; // word, words, sentence, sentences, paragraph, paragraphs
            use SeederPSRussian; // in Russian, word, words, sentence, sentences, paragraph, paragraphs
            use SeederId; // get an id
            use SeederDate; // get a date
            use SeederAddress; // get a address
            use SeederCity; // get a city
        // @ class traits end
    }
?>