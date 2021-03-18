<?php
    // get seeder
    $Seeder = new Seeder();
    
    var_dump(remove_char_from_str(['.','/','\\'], $Seeder->job_title()));
    var_dump(remove_char_from_str([' '], 'The big bear can jump.'));
    var_dump(remove_char_from_str(['.','/','\\'], "fun.to/goto\\"));
    var_dump(remove_char_from_str(['f','t','\\'], "fun.to/goto\\"));
    // TODO: make it so that you can pass in just strings that you want to replace as well, can do likewise for removing characters if you have just one character you'd like to remove
    var_dump(replace_char_in_str(['.','/','\\'], [" ", " ", " "], "fun.to/goto\\"));
    var_dump(replace_char_in_str(['f','t','\\'], ["(!)", "&*()", "(90)"], "fun.to/goto\\"));

    // // get 5 records
    // for ($i=0; $i < 5; $i++) { 
    //     $userRecords[] = User::seeder_setter($Seeder);
    // }
    // var_dump($userRecords);
    // get 5 records
    for ($i=0; $i < 5; $i++) { 
        $postRecords[] = Post::seeder_setter($Seeder);
    }
    // var_dump($postRecords);
    echo '<pre>';
    print_r($postRecords);
    echo '</pre>';
    
?>