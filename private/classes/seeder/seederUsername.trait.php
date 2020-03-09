<?php
    // ? Got usernames from https://jimpix.co.uk/words/random-username-list.asp, https://www.randomlists.com/random-words?dup=false&qty=125
    
    // seeder for username 
    trait SeederUsername {
        // create a unique username counter
        public $usernameCounter = 0;

        // to get a username 
        public function username() {
            // create the username
            $usernameText = $this->usernameRandPart[rand(0, count($this->usernameRandPart) - 1)];
            $usernameText .= $this->usernameRandPart[rand(0, count($this->usernameRandPart) - 1)];
            // create the for sure unique identifier of the username
            $usernameText .= $this->usernameCounter;
            // increment username counter
            $this->usernameCounter++;
            // return data
            return $usernameText; 
        }

        // an array of random words
        public $usernameRandPart = [
            'eagle','make','shift','nugget ','star ','fish','gracious','crush','umber','teething','laved','treachery','conjunction','schilling','thyme','dinner','baffle','meager','stucco','benign','training','hail','wham','pelican','keep','infinite','slides','hare','gristle','short','cut','luggage','rhinoceros','ferocity','cent','impede','finer','eight','audi','enigmatic','cottage','vast','rice','royal','liard','semi','cologne','bate','reburial','skye','swallow','stevens','shop','fletcher','color','infielder','strategy','scheme','cormorant','vibes','instagram','cork','drainer','halt','rhythm','neglector','risk','waltz','wal','addition','postwar','viewfinder','cream','edit','land','mine','natives','flump','sub','siding','yahoo','thicket','unlisted','wafers','gyrus','glistening','culture','sticky','theme','new','roving','bicycle','available','clock','ford','speedy','smile','swimmer','dial','tiny','moraine','bike','contort','distribution','equable','cookie','piddock','lansing','chimney','nurse','kite','koko','sniff','over','head','gerbil','mortgage','caboose','jockey','tailed','prognosis','unfailing','revered','suit','user','gypsy','sticks','daffodil','phalange','palm','samantha','effects','grimly','websites','glass','sets','loops','adult','hydrogen','trustful','journey','monsoon','must','foil','pry','flint','mor','quill','candy','awesome','barry','hope','moon','dev','star','searcher','guy','awesome','person','gerbil','acorn','school','master','scrum','harry','open','close','forward','writer','developer','truth','cantaloupe','aardvark','glass','risk','bird','closed','soft','polite','slimy','teeth','colorful','crow','seed','rhetorical','snakes','sail','encouraging','governor','cloth','reproduce','health','powder','holiday','ultra','attend','poised','round','aboriginal','amuse','decorous','overflow','grin','rely','spotless','vivacious','impress','dashing','messy','inquisitive','rabbit','van','imported','floor','action','seat','knowing','guide','bridge','debonair','misty','workable','wrap','spiky','unknown','charming','root','greet','impartial','fabulous','railway','hysterical','volcano','turkey','title','jail','sofa','puzzled','deep','reflective','profit','cushion','third','yellow','careful','drip','green','rabbits','useful','eight','price','drain','plug','automatic','quilt','open','callous','happen','ratty','improve','pass','voice','walk','wire','fireman','overconfident','jog'
        ]; 
    }    
?> 