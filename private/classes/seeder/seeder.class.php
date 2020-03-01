<?php
    // include seeder traits, additional functionality
    require_once("seederPS.trait.php");
    require_once("seederPS.russian.trait.php");
    require_once("seederId.trait.php");
    require_once("seederDate.trait.php");
    require_once("seederAddress.trait.php");
    require_once("seederCity.trait.php");
    require_once("seederState.trait.php");
    require_once("seederFirstName.trait.php");
    require_once("seederLastName.trait.php");
    require_once("seederEmail.trait.php");
    require_once("seederZip.trait.php");
    require_once("seederJobTitle.trait.php");
    require_once("seederUsername.trait.php");
    require_once("seederPhoneNumber.trait.php");
    require_once("seederOption.trait.php");

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
            use SeederState; // get a State
            use SeederFirstName; // get a first name
            use SeederLastName; // get a last name
            use SeederEmail; // get a email
            use SeederZip; // get a zip
            use SeederJobTitle; // get a job title
            use SeederUsername; // get a username
            use SeederPhoneNumber; // get a phone number
            use SeederOption; // get an option
        // @ class traits end
    }
?>