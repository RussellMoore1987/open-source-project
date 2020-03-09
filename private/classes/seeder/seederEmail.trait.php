<?php
    // seeder for email 
    trait SeederEmail {
        // create a unique email counter
        public $emailCounter = 0;

        // to get a email 
        public function email(string $text="") {
            // create default variables
            $emailText = "";
            // used text if provided
            if (strlen(trim($text)) > 0) {
                $emailText = $text;
            } else {
                // if no text provided, make something up
                $count = rand(10,35);
                for ($i=0; $i < $count; $i++) { 
                    $emailText .= $this->emailRandString[rand(0, count($this->emailRandString) - 1)];
                }
            }
            // create the for sure unique identifier of the email
            $emailText .= "_";
            $emailText .= $this->emailCounter;
            // increment counter
            $this->emailCounter++;
            // finish email, return data
            return $emailText .= $this->emailEndings[rand(0, count($this->emailEndings) - 1)]; 
        }

        // an array of email endings
        public $emailEndings = [
            '@gmail.com', '@yahoo.com', '@hotmail.com'
        ]; 

        // an array of characters
        public $emailRandString = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']; 
    }    
?> 