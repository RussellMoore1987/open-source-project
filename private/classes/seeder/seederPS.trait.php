<?php
    // ? got Russian words from https://www.lipsum.com/

    // seeder for words, sentences, and paragraphs
    trait SeederPS {
        
        // to get a word
        public function word() {
            // get a word
            return $this->words[rand(0, count($this->words) - 1)]; 
        }

        // to get words
        public function words(int $words = 1) {
            // get words
            $wordsStr = "";
            for ($i=0; $i < $words; $i++) { 
                $wordsStr .= $this->word(); 
                $wordsStr .= $i + 1 === $words ? "" : " "; 
            }
            // return data
            return $wordsStr;    
        } 

        // to get a sentence
        public function sentence() {
            // get a sentence
           return $sentenceString = ucfirst($this->words(rand(5,25))) . ".";   
        } 

        // to get sentences
        public function sentences(int $sentences = 1) {
            // get sentences
            $sentencesStr = "";
            for ($i=0; $i < $sentences; $i++) { 
                $sentencesStr .= $this->sentence(); 
                $sentencesStr .= $i + 1 === $sentences ? "" : " "; 
            }
            return $sentencesStr;
        }

        // to get a paragraph
        public function paragraph() {
            // get a paragraph
            return $paragraphString = $this->sentences(rand(2,25));
        } 

        // to get paragraphs
        public function paragraphs(int $paragraphs = 1, string $separator = "") {
            // get paragraphs
            $paragraphsString = "";
            for ($i=0; $i < $paragraphs; $i++) { 
                $paragraphsString .= $this->paragraph(); 
                $paragraphsString .= $i + 1 === $paragraphs ? "" : "{$separator}"; 
            }
            return $paragraphsString;
        } 

        // an array of Lorem ipsum words
        public $words = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'Phasellus', 'fermentum', 'enim', 'nec', 'metus', 'mollis', 'non', 'ultrices', 'est', 'fermentum', 'Sed', 'consectetur', 'interdum', 'ante', 'eu', 'fringilla', 'Vivamus', 'eu', 'purus', 'sed', 'neque', 'tincidunt', 'fermentum', 'Pellentesque', 'blandit', 'nisi', 'sit', 'amet', 'volutpat', 'ornare', 'lorem', 'turpis', 'rhoncus', 'lacus', 'eu', 'vehicula', 'nibh', 'arcu', 'vel', 'erat', 'In', 'hac', 'habitasse', 'platea', 'dictumst', 'Pellentesque', 'pharetra', 'mi', 'lobortis', 'justo', 'ornare', 'eu', 'dictum', 'sem', 'aliquet', 'Aliquam', 'efficitur', 'fermentum', 'elit', 'eu', 'volutpat', 'Integer', 'finibus', 'ultrices', 'ligula', 'at', 'vestibulum', 'Quisque', 'tortor', 'ante', 'convallis', 'et', 'lacus', 'a', 'ultrices', 'pretium', 'odio', 'Duis', 'suscipit', 'consequat', 'ipsum', 'vitae', 'dignissim', 'Cras', 'tempus', 'auctor', 'mi', 'at', 'pretium', 'Donec', 'porta', 'finibus', 'dui', 'vitae', 'placerat', 'lacus', 'aliquet', 'vitae', 'Donec', 'eu', 'lectus', 'lobortis', 'sodales', 'lorem', 'et', 'congue', 'turpis', 'Suspendisse', 'faucibus', 'mauris', 'porta', 'viverra', 'purus', 'id', 'venenatis', 'diam', 'Class', 'aptent', 'taciti', 'sociosqu', 'ad', 'litora', 'torquent', 'per', 'conubia', 'nostra', 'per', 'inceptos', 'himenaeos', 'Suspendisse', 'finibus', 'enim', 'dolor', 'eu', 'pretium', 'lacus', 'imperdiet', 'vitae', 'Praesent', 'sed', 'tellus', 'urna', 'Nulla', 'venenatis', 'vestibulum', 'odio', 'at', 'scelerisque', 'magna', 'finibus', 'vitae', 'Suspendisse', 'in', 'luctus', 'lorem', 'et', 'gravida', 'metus', 'Donec', 'suscipit', 'leo', 'eu', 'malesuada', 'ultricies', 'Sed', 'nec', 'libero', 'eget', 'tortor', 'euismod', 'porta', 'sed', 'ac', 'metus', 'Sed', 'suscipit', 'diam', 'dolor', 'quis', 'semper', 'sapien', 'feugiat', 'id', 'Praesent', 'ac', 'diam', 'iaculis', 'suscipit', 'enim', 'vitae', 'porttitor', 'nulla', 'In', 'pretium', 'dolor', 'vitae', 'erat', 'accumsan', 'varius', 'In', 'vitae', 'orci', 'augue', 'Nulla', 'elit', 'augue', 'accumsan', 'vel', 'metus', 'vel', 'imperdiet', 'ultricies', 'ipsum', 'Maecenas', 'imperdiet', 'tincidunt', 'ultrices', 'Aenean', 'in', 'nulla', 'urna', 'Morbi', 'purus', 'sem', 'sollicitudin', 'et', 'mattis', 'interdum', 'auctor', 'in', 'tellus', 'Cras', 'a', 'fermentum', 'nisi', 'Pellentesque', 'facilisis', 'libero', 'eu', 'luctus', 'vehicula', 'Cras', 'eget', 'pharetra', 'arcu', 'Aliquam', 'cursus', 'nibh', 'in', 'sapien', 'consectetur', 'eleifend', 'Suspendisse', 'volutpat', 'semper', 'turpis', 'eu', 'vehicula', 'nisl', 'ullamcorper', 'et', 'Suspendisse', 'tempus', 'sem', 'sed', 'orci', 'ultricies', 'quis', 'suscipit', 'ligula', 'volutpat', 'Curabitur', 'massa', 'lorem', 'ullamcorper', 'et', 'odio', 'id', 'eleifend', 'egestas', 'libero', 'Mauris', 'sed', 'nulla', 'mauris', 'Aenean', 'scelerisque', 'diam', 'vitae', 'fermentum', 'sagittis', 'Sed', 'dolor', 'eros', 'viverra', 'non', 'nibh', 'eu', 'consectetur', 'efficitur', 'nunc', 'Donec', 'ac', 'mauris', 'risus', 'Phasellus', 'sed', 'auctor', 'tortor', 'Proin', 'posuere', 'quam', 'et', 'iaculis', 'fringilla', 'velit', 'libero', 'posuere', 'est', 'sed', 'lobortis', 'arcu', 'nulla', 'sed', 'dui', 'Donec', 'a', 'hendrerit', 'purus', 'Fusce', 'sit', 'amet', 'iaculis', 'neque', 'Nunc', 'et', 'nunc', 'nec', 'ipsum', 'molestie', 'placerat', 'at', 'sit', 'amet', 'libero', 'Donec', 'placerat', 'lacus', 'nec', 'venenatis', 'finibus', 'justo', 'ante', 'pretium', 'diam', 'id', 'rutrum', 'nisi', 'lacus', 'et', 'purus', 'Vestibulum', 'tempus', 'consectetur', 'lorem', 'id', 'dictum', 'sapien', 'ultricies', 'ac', 'Fusce', 'et', 'dapibus', 'nulla', 'Nunc', 'fringilla', 'sed', 'lacus', 'in', 'blandit', 'Nullam', 'tincidunt', 'fringilla', 'urna', 'gravida', 'dignissim', 'Etiam', 'vitae', 'semper', 'justo', 'In', 'sit', 'amet', 'rutrum', 'ante', 'Donec', 'a', 'dictum', 'erat', 'Cras', 'eu', 'sollicitudin', 'nisl', 'eu', 'posuere', 'mauris', 'Sed', 'nec', 'lorem', 'ut', 'ante', 'dictum', 'aliquam', 'Aliquam', 'et', 'risus', 'mauris', 'Proin', 'tempor', 'odio', 'non', 'quam', 'feugiat', 'nec', 'tincidunt', 'libero', 'consectetur'
        ];
    }
?>