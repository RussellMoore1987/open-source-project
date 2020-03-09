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
        public function max_char(string $string, int $max=25, string $ending="") {
            // cut string to size
            $string = substr($string, 0, $max); 
            //remove trailing spaces
            $string = trim($string);
            // add ending and remove previous ending if there  
            if (strlen($string) > 0 && substr($string, -1) == "." && $ending != "" && $ending != ".") {
                $string = trim(substr($string, 0, strlen($string) - 1)) . $ending;
            } elseif (strlen($string) > 0 && substr($string, -1) != "." && $ending != "") {
                $string = trim(substr($string, 0, strlen($string) - 1)) . $ending;
            }
            
            // return data
            return $string;   
        } 

        // to get at lest a certain amount of characters characters
        public function min_char(string $string, int $min=2, string $ending="") {
            // check to see if string is longer than min
            if (!(strlen($string) >= $min)) {
                $addChrCount = $min - strlen($string);
                // loop over string and add characters onto the end 
                for ($i=0; $i < $addChrCount; $i++) { 
                    $string .= $this->randLetters[rand(0, count($this->randLetters) - 1)];
                }
                // check whether or not we have an ending
                if (strlen(trim($ending)) > 0) {
                    $string = trim(substr($string, 0, strlen($string) - 1)) . $ending;
                }
            }
            
            // return data
            return $string;   
        } 

        // an array of characters, mostly used for min_char()
        public $randLetters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        
        // @ class traits start
            use SeederPS; // get word, words, sentence, sentences, paragraph, paragraphs
            use SeederPSRussian; // in Russian get word, words, sentence, sentences, paragraph, paragraphs
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