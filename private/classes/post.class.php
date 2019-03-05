<?php
    class Post extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "posts";
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'author', 'authorName', 'catIds', 'comments', 'content', 'createdBy', 'createdDate', 'imageName', 'labelIds', 'postDate', 'status', 'tagIds', 'title'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id', 'comments'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = ['fullDate', 'shortDate', 'imagePath_array'];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 1;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Post id',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'max' => 10 // string length
                ], 
                'author' => [
                    'name'=>'Post Author',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'authorName' => [
                    'name'=>'AuthorName Stamp',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 4, // string length
                    'max' => 50, // string length
                    'html' => 'no'
                ], 
                'catIds' => [
                    'name'=>'CatIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'comments' => [
                    'name'=>'comment Count',
                    'type' => 'int', // type of int
                    'max' => 10 // string length
                ], 
                'content' => [
                    'name'=>'Post Content',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 50, // string length
                    'html' => 'full'
                ], 
                'createdBy' => [
                    'name'=>'CreatedBy',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'CreatedDate',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => 'yes',
                ],
                'imageName' => [
                    'name'=>'imageName',
                    'type' => 'str', // type of string
                    'max' => 50 // string length
                ], 
                'labelIds' => [
                    'name'=>'LabelIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'postDate' => [
                    'name'=>'Post Date',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => 'yes',
                ], 
                'status', 
                'tagIds' => [
                    'name'=>'TagIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'title' => [
                    'name'=>'Post Title',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes' // mostly just to allow special characters like () []
                ]
            ];
        // @ class database information end
        
        // @ class specific queries start
            // latest posts feed
            static public function latest_posts_feed() {
                $sql = "SELECT id, title, postDate, comments, authorName FROM posts WHERE status = 1 ORDER BY postDate DESC LIMIT 4";
                return self::find_by_sql($sql);    
            }

            // # for *multiple posts*, if you need there tags, categories, labels and featured image in a fast manner use the post references info as your go to methods
                // methods
                    // get_obj_categories_tags_labels('categories') in DatabaseObject class for categories, tags, labels
                    // get_image_path('small') in Post class for getting path to referenced post image name (imageName)
                        // if you want all images for a post use get_post_images() in Post class

            
            // # for a *single post* query's start
                // get all extended info
                public function get_extended_info() {
                    // empty array to hold potential extended information
                    $extendedInfo_array = [];
                    // get all images
                    $extendedInfo_array['images'] = $this->get_post_images();
                    // get tags
                    $extendedInfo_array['tags'] = $this->get_post_tags();
                    // get labels
                    $extendedInfo_array['labels'] = $this->get_post_labels();
                    // get categories
                    $extendedInfo_array['categories'] = $this->get_post_categories();
                    return $extendedInfo_array;    
                }
                
                // get image, main queries for editing
                public function get_post_image() {
                    $sql = "SELECT mc.alt, mc.name ";
                    $sql .= "FROM media_content AS mc ";
                    $sql .= "INNER JOIN posts_to_media_content AS ptmc ";
                    $sql .= "ON ptmc.mediaContentId = mc.id";
                    $sql .= "WHERE ptmc.postId = '" . self::db_escape($this->id) . "' ";
                    $sql .= "AND mc.sort = 1 ";
                    $sql .= "LIMIT 1 ";
                    return MediaContent::find_by_sql($sql);    
                }

                // get images, main queries for editing
                public function get_post_images() {
                    $sql = "SELECT mc.alt, mc.name ";
                    $sql .= "FROM media_content AS mc ";
                    $sql .= "INNER JOIN posts_to_media_content AS ptmc ";
                    $sql .= "ON ptmc.mediaContentId = mc.id";
                    $sql .= "WHERE ptmc.postId = '" . self::db_escape($this->id) . "' ";
                    return MediaContent::find_by_sql($sql);    
                }

                // get tags, main queries for editing
                public function get_post_tags() {
                    $sql = "SELECT t.id, t.title";
                    $sql .= "FROM tags AS t ";
                    $sql .= "INNER JOIN posts_to_tags AS ptt ";
                    $sql .= "ON ptt.tagId = t.id";
                    $sql .= "WHERE ptt.postId = '" . self::db_escape($this->id) . "' ";
                    return Tag::find_by_sql($sql);     
                }

                // get labels, main queries for editing
                public function get_post_labels() {
                    $sql = "SELECT l.id, l.title";
                    $sql .= "FROM labels AS l ";
                    $sql .= "INNER JOIN posts_to_labels AS ptl ";
                    $sql .= "ON ptl.labelId = l.id";
                    $sql .= "WHERE ptl.postId = '" . self::db_escape($this->id) . "' ";
                    return Label::find_by_sql($sql);    
                }

                // get categories, main queries for editing
                public function get_post_categories() {
                    $sql = "SELECT c.id, c.title";
                    $sql .= "FROM categories AS c ";
                    $sql .= "INNER JOIN posts_to_categories AS ptc ";
                    $sql .= "ON ptc.categoryId = c.id";
                    $sql .= "WHERE ptc.postId = '" . self::db_escape($this->id) . "' ";
                    return Category::find_by_sql($sql);    
                }
            // # single post query's end
        // @ class specific queries end

        // @ properties start
            // main properties
                public $author;
                public $content;
                public $postDate;
                public $status;
                public $title;
            // secondary properties
                public $fullDate;
                public $shortDate;
                // used primarily for the API, if you just need a image path you can just call get_image_path('small') found bellow
                public $imagePath_array;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $authorName; // get_authorName()
                protected $catIds; // get_catIds()
                protected $comments; // get_comments()
                protected $createdBy; // get_createdBy()
                protected $createdDate; // get_createdDate()
                protected $id; // get_id()
                protected $imageName; // get_imageName()
                protected $labelIds; // get_labelIds()
                protected $tagIds; // get_tagIds()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->author = $args['author'] ?? NULL;   
                $this->authorName = $args['authorName'] ?? NULL;  
                $this->catIds = $args['catIds'] ?? NULL;
                $this->comments = $args['comments'] ?? NULL;    
                $this->content = $args['content'] ?? NULL;     
                $this->createdBy = $args['createdBy'] ?? NULL;     
                $this->createdDate = $args['createdDate'] ?? NULL;
                $this->imageName = $args['imageName'] ?? NULL;
                $this->$imagePath_array = [];
                // check to see if we have an image name
                if (strlen(Trim($this->imageName)) > 0) {
                    $this->$imagePath_array = [get_image_path('thumbnail'), get_image_path('small'), get_image_path('medium'), get_image_path('large'), get_image_path('original')];  
                }
                $this->labelIds = $args['labelIds'] ?? NULL;
                // Format dates 
                if (isset($args['postDate']) && strlen(trim($args['postDate'])) > 0) {
                    // Turn date to time string
                    $postDateStr = strtotime($args['postDate']);
                    // set date types
                    $shortDate = date("m/d/Y", $postDateStr);
                    $postFullDate = date("F d, Y", $postDateStr);
                    // set dates
                    // database date
                    $this->postDate = $args['postDate'];
                    // abbreviated date
                    $this->shortDate = $shortDate;
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
            // get authorName property
            public function get_authorName() {
                return $this->authorName;
            }

            // get comments property
            public function get_comments() {
                return $this->comments;
            }

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

            // get id property
            public function get_id() {
                return $this->id;
            }

            // get imageName property
            public function get_imageName() {
                return $this->imageName;
            }

            // get tagIds property
            public function get_tagIds() {
                return $this->tagIds;
            }

            // get labelIds property
            public function get_labelIds() {
                return $this->labelIds;
            }

            // get image path with recorded reference image name
            public function get_image_path($type = 'small') {
                // get path // * image_paths located at: root/private/reference_information.php
                $path = get_image_path($type);
                // return image path with name
                return "{$path}/{$this->imageName}";
            }
        // @ methods end

        // ! not using at the moment
        // @ layouts start
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
        // @ layouts end
    }
?>