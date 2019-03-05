<?php
    class Tag {
        // @ ----- START OF ACTIVE RECORD CODE -----
            // database connection
            static protected $database;
            static protected $db_columns = ['id', 'note', 'title'];

            // set up local reference for the database
            static public function set_database($database) {
                self::$database = $database;
            }

            // get all post tags
            static public function find_all_post_tags() {
                $sql = "SELECT id, title FROM tags WHERE useTag = 1 ORDER BY title";
                return self::find_by_sql($sql);
            }

            // find by sql
            static public function find_by_sql($sql) {
                $result = self::$database->query($sql);
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } 
                // turn results into an array of objects
                $object_array = [];
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    $object_array[] = self::instantiate($record);    
                }
                //free up query result
                $result->free();
                // return an array of populated objects
                return $object_array;   
            }
        // @ ----- END OF ACTIVE RECORD CODE -----
        
    }
    
?>