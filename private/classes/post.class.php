<?php
    class Post extends DatabaseObject {
        
        // @ class database information
            static protected $tableName = "posts";
            static protected $columns = ['id', 'author', 'authorName', 'catIds', 'comments', 'content', 'createdBy', 'createdDate', 'labelIds', 'postDate', 'status', 'tagIds', 'title'];
            // todo: make validation array validations for each column???
            // validation information for columns, see val_validation() in validation_functions.php for reference information
            // todo: start here **********************************************************
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Post id',
                    'required' => 'yes',
                    'num_min'=>0,
                    'max' => 10
                ], 
                'author', 
                'authorName', 
                'catIds', 
                'comments', 
                'content', 
                'createdBy', 
                'createdDate', 
                'labelIds', 
                'postDate', 
                'status', 
                'tagIds', 
                'title'
            ];
        
        // @ class specific queries
        // latest posts feed
        static public function latest_posts_feed() {
            $sql = "SELECT id, title, postDate, comments, authorName FROM posts WHERE status = 1 ORDER BY postDate DESC LIMIT 4";
            return self::find_by_sql($sql);    
        }


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
            protected $catIds; // get_catIds()
            protected $createdBy; // get_createdBy()
            protected $createdDate; // get_createdDate()
            protected $labelIds; // get_labelIds()
            protected $tagIds; // get_tagIds()
        // @ properties end
        
        // @ methods start
            // constructor method
            public function __construct($args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->author = $args['author'] ?? NULL;   
                $this->authorName = $args['authorName'] ?? NULL;  
                $this->catIds = $args['catIds'] ?? NULL;
                $this->comments = $args['comments'] ?? NULL;    
                $this->content = $args['content'] ?? NULL;     
                $this->createdBy = $args['createdBy'] ?? NULL;     
                $this->createdDate = $args['createdDate'] ?? NULL;  
                $this->labelIds = $args['labelIds'] ?? NULL;
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
                $this->tagIds = $args['tagIds'] ?? NULL; 
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

            // get catIds property
            public function get_catIds() {
                return $this->catIds;
            }

            // get tagIds property
            public function get_tagIds() {
                return $this->tagIds;
            }

            // get labelIds property
            public function get_labelIds() {
                return $this->labelIds;
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

        // @ validation
        protected function validate(){
            // reset error array for a clean slate
            $this->errors = [];

            // example validation
            if(is_blank($this->title)) {
                $this->errors[] = "error message!!!, You must have a title.";
            }
            // good practice to always return something, in most cases this will not be used
            return  $this->errors;
        }
    }
?>