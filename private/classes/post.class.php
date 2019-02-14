<?php
    class Post {
        // @ ----- START OF ACTIVE RECORD CODE -----
        // database connection
        static protected $database;
        static protected $db_columns = ["post_id", "post_cat_id", "post_title", "post_author", "post_date", "post_image", "post_content", "post_tags", "post_comment_count", "post_status"];

        // set up local reference for the database
        static public function set_database($database) {
            self::$database = $database;
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

        // latest posts
        static public function latest_posts() {
            $sql = "SELECT post_image, post_title, post_date, post_id FROM posts ORDER BY post_date DESC LIMIT 4";
            return self::find_by_sql($sql);    
        }

        // latest project posts, just faking for it right now
        static public function latest_project_posts() {
            $sql = "SELECT post_image, post_title, post_date, post_id FROM posts WHERE post_tags LIKE '%Graphics%'  ORDER BY post_date DESC LIMIT 4";
            return self::find_by_sql($sql);    
        }

        // Helper function, object creator
        static protected function instantiate($record) {
            // load the object
            $object = new self($record);
            // return the object
            return $object;
        }

        // todo: Need to finish this section, has lots of pieces that will need to be updated elsewhere, Video link: https://www.lynda.com/PHP-tutorials/Dynamic-attribute-list/669547/706418-4.html?autoplay=true
        // Create a new instance/record
        public function create() {
            $sql = "INSERT INTO posts (";
            $sql .= join(", ", self::$db_columns);
            $sql .= ") VALUES (";
            $sql .= "";
            $sql .= ")";
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

        public function attributes() {
            # code...
        }
        // @ ----- END OF ACTIVE RECORD CODE -----

        // @ Properties start
        public $id;    
        public $catId;    
        public $title;    
        public $author;    
        public $date;    
        public $fullDate;    
        public $image;    
        public $content;    
        public $tags;    
        public $commentCount;    
        public $status;
        public $linkToPost;
        // gives count of posts on the page, reliable only in some cases
        public static $instance_count; 
        // @ Properties end
        
        // @ methods start
        // constructor method
        public function __construct($args=[]) {
            // Increment count
            self::$instance_count++;
            // Set up properties
            $this->id = $args['post_id'] ?? "";    
            $this->catId = $args['post_cat_id'] ?? "";    
            $this->title = $args['post_title'] ?? "";    
            $this->author = $args['post_author'] ?? "";
            // Format dates 
            if (strlen(trim($args['post_date'])) > 0) {
                // Turn date to time string
                $post_date_str = strtotime($args['post_date']);
                // set date types
                $post_date = date("m/d/Y", $post_date_str);
                $post_full_date = date("F d, Y", $post_date_str);
                // set dates
                $this->date = $post_date;
                $this->fullDate = $post_full_date;
            } else {
                // No date was found set defaults
                $this->date = "";
                $this->fullDate = "";
            } 
            // Format image path
            if (strlen(trim($args['post_image'])) > 0) {
                $post_image = $args['post_image']; 
                $this->image = "../images/{$post_image}";
            } else {
                $this->image = "";
            } 
            // formatting post path
            $this->linkToPost = MAIN_LINK_PATH . "/public/site/post.php?id=" . $this->id;
            $this->content = $args['post_content'] ?? "";    
            $this->tags = $args['post_tags'] ?? "";    
            $this->commentCount = $args['post_comment_count'] ?? "";    
            $this->status = $args['post_status'] ?? "";  
        }
        // methods
        // stands for database escape
        static protected function db_escape($db_field){
            return self::$database->escape_string($db_field);
        }

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