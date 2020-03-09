<?php
    // ? got Russian words from https://www.lipsum.com/

    // in Russian, seeder for words, sentences, and paragraphs  
    trait SeederPSRussian {
        
        // to get a Russian word
        public function word_russian() {
            // get a Russian word, return data
            return $this->russianWords[rand(0,count($this->russianWords) - 1)]; 
        }

        // to get words
        public function words_russian(int $words = 1) {
            // get words
            $wordsStr = "";
            for ($i=0; $i < $words; $i++) { 
                $wordsStr .= $this->word_russian(); 
                $wordsStr .= $i + 1 === $words ? "" : " "; 
            }
            // return data
            return $wordsStr;    
        } 

        // to get a sentence
        public function sentence_russian() {
            // get a sentence, return data
           return $sentenceString = ucfirst($this->words_russian(rand(5,25))) . ".";   
        } 

        // to get sentences
        public function sentences_russian(int $sentences = 1) {
            // get sentences
            $sentencesStr = "";
            for ($i=0; $i < $sentences; $i++) { 
                $sentencesStr .= $this->sentence_russian(); 
                $sentencesStr .= $i + 1 === $sentences ? "" : " "; 
            }
            // return data
            return $sentencesStr;
        }

        // to get a paragraph
        public function paragraph_russian() {
            // get a paragraph, return data
            return $paragraphString = $this->sentences_russian(rand(2,25));
        } 

        // to get paragraphs
        public function paragraphs_russian(int $paragraphs = 1, string $separator = "") {
            // get paragraphs
            $paragraphsString = "";
            for ($i=0; $i < $paragraphs; $i++) { 
                $paragraphsString .= $this->paragraph_russian(); 
                $paragraphsString .= $i + 1 === $paragraphs ? "" : "{$separator}"; 
            }
            // return data
            return $paragraphsString;
        } 

        // an array of Russian words
        public $russianWords = [
            'Давно', 'выяснено', 'что', 'при', 'оценке', 'дизайна', 'и', 'композиции', 'читаемый', 'текст', 'мешает', 'сосредоточиться', 'используют', 'потому', 'что', 'тот', 'обеспечивает', 'более', 'или', 'менее', 'стандартное', 'заполнение', 'шаблона', 'а', 'также', 'реальное', 'распределение', 'букв', 'и', 'пробелов', 'в', 'абзацах', 'которое', 'не', 'получается', 'при', 'простой', 'дубликации', 'Здесь', 'ваш', 'текст', 'Здесь', 'ваш', 'текст', 'Здесь', 'ваш', 'текст', 'Многие', 'программы', 'электронной', 'вёрстки', 'и', 'редакторы', 'используют', 'в', 'качестве', 'текста', 'по', 'умолчанию', 'так', 'что', 'поиск', 'по', 'ключевым', 'словам', 'сразу', 'показывает', 'как', 'много', 'веб-страниц', 'всё', 'ещё', 'дожидаются', 'своего', 'настоящего', 'рождения', 'За', 'прошедшие', 'годы', 'текст', 'получил', 'много', 'версий', 'Некоторые', 'версии', 'появились', 'по', 'ошибке', 'некоторые', 'намеренно', 'например', 'юмористические', 'варианты', 'Есть', 'много', 'вариантов', 'но', 'большинство', 'из', 'них', 'имеет', 'не', 'всегда', 'приемлемые', 'модификации', 'например', 'юмористические', 'вставки', 'или', 'слова', 'которые', 'даже', 'отдалённо', 'не', 'напоминают', 'латынь', 'Если', 'вам', 'нужен', 'для', 'серьёзного', 'проекта', 'вы', 'наверняка', 'не', 'хотите', 'какой-нибудь', 'шутки', 'скрытой', 'в', 'середине', 'абзаца', 'Также', 'все', 'другие', 'известные', 'генераторы', 'используют', 'один', 'и', 'тот', 'же', 'текст', 'который', 'они', 'просто', 'повторяют', 'пока', 'не', 'достигнут', 'нужный', 'объём', 'Это', 'делает', 'предлагаемый', 'здесь', 'генератор', 'единственным', 'настоящим', 'генератором', 'Он', 'использует', 'словарь', 'из', 'более', 'чем', 'латинских', 'слов', 'а', 'также', 'набор', 'моделей', 'предложений', 'В', 'результате', 'сгенерированный', 'выглядит', 'правдоподобно', 'не', 'имеет', 'повторяющихся', 'абзацей', 'или', 'невозможных', 'слов' 
        ];
    }
?>