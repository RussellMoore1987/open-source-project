<?php
    // todo: add new database fields catIds, tagIds, labelIds
    class Post {
        // @ ----- START OF ACTIVE RECORD CODE -----
            // todo: finish ???? ***list of ids or lots of query's
            // possible extended info
            // get possible tags
            static public function get_possible_tags() {
                // if not set get info
                if (!isset(self::$possibleTags)) {
                    // get all possible post tags 
                    $sql = "SELECT id, title FROM tags";
                    $result = self::$database->query($sql);
                    // error handling
                    if (!$result) {
                        exit("Query Failed!!!: " . self::$database->error);
                    } 
                    // turn results into an array of key value pairs
                    $tag_array = [];
                    // loop through query
                    while ($record = $result->fetch_assoc()) {
                        $id = $record['id']; 
                        $title = $record['title']; 
                        $tag_array[$id] = $title; 
                    }
                    // sort array alphabetically by title
                    natcasesort($tag_array);
                    //free up query result
                    $result->free();
                    // store array in static property $possibleTags
                    $tag_array = self::$possibleTags;
                }
                // return possible tags
                $possibleTags_array = self::$possibleTags;
                return $possibleTags_array;
            }
            // possible tags
            static protected $possibleTags;
            // get possible labels
            // possible labels
            static protected $possibleLabels;
            // get possible categories
            // possible categories
            static protected $possibleCategories;


            // database connection
            static protected $database;
            static protected $db_columns = ['id', 'author', 'authorName', 'comments', 'content', 'createdBy', 'createdDate', 'postDate', 'status', 'title'];

            // set up local reference for the database
            static public function set_database($database) {
                self::$database = $database;
            }

            // Helper function, object creator
            static protected function instantiate($record) {
                // load the object
                $object = new self($record);
                // return the object
                return $object;
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

            // find all posts
            static public function find_all() {
                $sql = "SELECT * FROM posts ORDER BY post_date DESC";
                return self::find_by_sql($sql);
            }

            // find post by id
            static public function find_by_id($id) {
                // just in case someone navigates to the page, check whether there is a id
                if (strlen(trim($id)) > 0) {
                    $sql = "SELECT * FROM posts ";
                    $sql .= "WHERE post_id='" . self::db_escape($id) . "'";
                } else {
                    // get the newest post
                    $sql = "SELECT * FROM posts ORDER BY post_date DESC LIMIT 1 ";
                }
                // get object array
                $obj_array = self::find_by_sql($sql);
                // check to see if $obj_array is empty
                if (!empty($obj_array)) {
                    // send back only one object, it will only have one
                    return array_shift($obj_array);
                } else {
                    return false;
                }
            }

            // latest posts feed
            static public function latest_posts_feed() {
                $sql = "SELECT id, title, postDate, comments, authorName FROM posts WHERE status = 1 ORDER BY postDate DESC LIMIT 4";
                return self::find_by_sql($sql);    
            }

            // Create a new instance/record
            protected function create() {
                // get attributes
                $attributes = $this->sanitized_attributes();
                // sql
                $sql = "INSERT INTO posts (";
                $sql .= join(", ", array_keys($attributes));
                $sql .= ") VALUES ('";
                $sql .= join("', '", array_values($attributes));
                $sql .= "')";
                // query here because we go through a different process than the other queries about
                $result = self::$database->query($sql);
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } else {
                    // add the new id to the obj
                    $this->id = self::$database->insert_id;
                }
                return $result;
            }

            // update existing record
            protected function update() {
                // get attributes
                $attributes = $this->sanitized_attributes();
                $attribute_pairs = [];
                foreach ($attributes as $key => $value) {
                    if (property_exists($this, $key) && !is_null($value)) {
                        $attribute_pairs = "{$key}='{$value}'";
                    }
                }
                // sql
                $sql = "";
                $sql .= "UPDATE posts SET ";
                $sql .= join(', ', $attribute_pairs);
                $sql .= " WHERE id='" . self::db_escape($this->id) . "'";
                $sql .= " LIMIT 1";
                $result = self::$database->query($sql);
                return $result;
            }

            // this allows you to add or update a record
            public function save()
            {
                if (isset($this->id)) {
                    return $this->update();
                } else {
                    return $this->create();
                }  
            }

            // merge properties
            public function merge_attributes($args=[]) {
                foreach ($args as $key => $value) {
                    if (property_exists($this, $key) && !is_null($value)) {
                        $this->$key = $value;
                    }
                }
            }

            // create an associative array, key value pair from the self::$db_columns excluding id
            public function attributes() {
                $attributes = [];
                foreach (self::$db_columns as $column) {
                    // skip id
                    if ($column == 'id') { continue; }
                    // construct attribute list with object values
                    $attributes = [$column] = $this->$column;
                }
                return $attributes;
            }

            // sanitizes attributes, for MySQL queries, and to protect against my SQL injection
            protected function sanitized_attributes() {
                $sanitized_array = [];
                foreach ($this->attributes() as $key => $value) {
                    $sanitized_array[$key] = self::db_escape($value);
                }
                return $sanitized_array;
            }

            // stands for database escape, you sanitized data, and to protect against my SQL injection
            static protected function db_escape($db_field){
                return self::$database->escape_string($db_field);
            }
        // @ ----- END OF ACTIVE RECORD CODE -----

        // @ properties start
            // main properties
            public $id;
            public $author;
            public $authorName;
            public $comments;
            public $content;
            public $postDate;
            public $status;
            public $title;
            // secondary properties
            public $fullDate;
            public $shortDate;
            // protected properties, read only, use getters  
            protected $createdBy; // get_createdBy()
            protected $createdDate; // get_createdDate()
        // @ properties end
        
        // @ methods start
            // constructor method
            public function __construct($args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->author = $args['author'] ?? NULL;   
                $this->authorName = $args['authorName'] ?? NULL;   
                $this->comments = $args['comments'] ?? NULL;    
                $this->content = $args['content'] ?? NULL;     
                $this->createdBy = $args['createdBy'] ?? NULL;     
                $this->createdDate = $args['createdDate'] ?? NULL;     
                // Format dates 
                if (strlen(trim($args['postDate'])) > 0) {
                    // Turn date to time string
                    $postDateStr = strtotime($args['postDate']);
                    // set date types
                    $shortDate = date("m/d/Y", $postDateStr);
                    $postFullDate = date("F d, Y", $postDateStr);
                    // set dates
                    // database date
                    $this->postDate = $args['postDate'];
                    // abbreviated date
                    $this->date = $shortDate;
                    // nicely formatted date
                    $this->fullDate = $postFullDate;
                } else {
                    // No date was found set defaults
                    $this->postDate = NULL;
                    $this->shortDate = NULL;
                    $this->fullDate = NULL;
                } 
                $this->status = $args['status'] ?? NULL;  
                $this->title = $args['title'] ?? NULL;      
            }

            // methods
            // get createdDate property
            public function get_createdDate() {
                return $this->createdDate;
            }

            // get createdBy property
            public function get_createdBy() {
                return $this->createdBy;
            }

            // todo: finish up get methods
            // get extended info
            // get image
            // get images
            // get tags
            // get labels
            // get categories


            // layouts
            // latest post layout
            public function layout_latestPosts() {
                // global path to layouts
                include PRIVATE_PATH . "/layouts/latestPosts.php";
            }

            // Post page layout
            public function layout_postPage() {
                // global path to layouts
                include PRIVATE_PATH . "/layouts/postPage.php";
            }
        // @ methods end
    }
?>