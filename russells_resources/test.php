<?php
    $data = file_get_contents('https://mooredigitalsolutions.com/wp-json/wp/v2/posts');
    $data = json_decode($data);
    var_dump($data);
?>


<?php
    protected $numRecords_bookMarks = 34;
    protected $numRecords_categories = 18; 
    protected $numRecords_comments = 209; 
    protected $numRecords_contents = 69; 
    protected $numRecords_mediaContents = 179; 
    protected $numRecords_labels = 22; 
    protected $numRecords_permissions = 18; 
    protected $numRecords_posts = 294; 
    protected $numRecords_tags = 294; 
    protected $numRecords_todos = 116; 
    protected $numRecords_users = 25; 







    for ($i=0; $i < 25; $i++) {
        if (!($i == 1 || $i == 2 || $i == 3 || $i == 4 || $i == 5)) {
            if (!($i % 2)) {
                echo $i ." parent \n";
            } else {
                echo $i ." sub \n";
            }
        } else {
            echo $i ." parent \n";
        }
    }
?>