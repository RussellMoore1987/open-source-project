<?php
    class MediaContent {
        // @ ----- START OF ACTIVE RECORD CODE -----
            // database connection
            static protected $database;
            static protected $db_columns = ['id', 'alt', 'createdBy', 'createdDate', 'name', 'note', 'type'];

            // set up local reference for the database
            static public function set_database($database) {
                self::$database = $database;
            }
        // @ ----- END OF ACTIVE RECORD CODE -----
        
    }
    
?>