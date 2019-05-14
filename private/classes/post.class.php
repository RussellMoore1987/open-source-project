<?php
    class Post extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "posts";
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'author', 'authorName', 'catIds', 'comments', 'content', 'createdBy', 'createdDate', 'imageName', 'labelIds', 'mediaContentIds', 'postDate', 'status', 'tagIds', 'title'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id', 'comments'];
            // name specific class properties you wish to included in the API
            static protected $apiProperties = ['fullDate', 'shortDate', 'imagePath_array'];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 1;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Post id',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
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
                    'name'=>'Post AuthorName Stamp',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 4, // string length, 2 first name, 2 last name
                    'max' => 50, // string length
                    'html' => 'no'
                ], 
                'catIds' => [
                    'name'=>'Post catIds',
                    'type' => 'str', // type of string
                    'max' => 255 // string length
                ], 
                'comments' => [
                    'required' => 'yes',
                    'name'=>'Post Comment Count',
                    'type' => 'int', // type of int
                    'max' => 10 // string length
                ], 
                'content' => [
                    'name'=>'Post Content',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 65000, // string length
                    'html' => 'full'
                ], 
                'createdBy' => [
                    'name'=>'Post createdBy',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'Post createdDate',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => 'yes'
                ],
                'imageName' => [
                    'name'=>'Post imageName',
                    'type' => 'str', // type of string
                    'max' => 150, // string length
                    'min' => 5 // string length
                ], 
                'labelIds' => [
                    'name'=>'Post labelIds',
                    'type' => 'str', // type of string
                    'max' => 255 // string length
                ], 
                'mediaContentIds' => [
                    'name'=>'Post mediaContentIds',
                    'type' => 'str', // type of string
                    'max' => 255 // string length
                ], 
                'postDate' => [
                    'name'=>'Post Date',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => 'yes'
                ], 
                'status' => [
                    'name'=>'Post status',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'num_max'=> 1, // number max value
                ], 
                'tagIds' => [
                    'name'=>'Post tagIds',
                    'type' => 'str', // type of string
                    'max' => 255 // string length
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


            // ! temp
            // * get_api_parameters, located at: root/private/reference_information.php
            static protected $getApiParameters = [
                // ...api/v1/posts/?id=22,33,5674,1,2,43,27,90,786 // ...api/v1/posts/?id=22
                'id'=>[
                    'refersTo' => ['id'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "=",
                        'list' => 'in'
                    ],
                    'description' => 'Gets posts by the post id or list of post ids',
                    'example' => ['id=1', 'id=1,2,3,4,5']
                ],
                // ...api/v1/posts/?greaterThen=1/37/2010
                'greaterThen' => [
                    'refersTo' => ['postDate'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => '>'
                    ],
                    'description' => 'Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan paramter to get dates in posts with createdDates between the two values, see examples',
                    'customExample' => [ 
                        'greaterThan' => 'greaterThan=2018-02-01',
                        'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    ]
                ], 
                // ...api/v1/posts/?lessThen=1/37/2010
                'lessThen' => [
                    'refersTo' => ['postDate'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => '<'
                    ],
                    'description' => 'Gets posts that have a createdDate >= the date given with the greaterThan parameter. May be used with the lessThan paramter to get dates in posts with createdDates between the two values, see examples',
                    'customExample' => [ 
                        'lessThan' => 'lessThan=2019-03-01',
                        'between' => 'greaterThan=2018-02-01&lessThan=2019-03-01'
                    ]
                ],
                // ...api/v1/posts/?search=sale // ? ...api/v1/posts/?search=sale,off,marked down     more then one value!???
                'search' => [
                    'refersTo' => ['title', 'content'],
                    'type' => ['str', 'list'],
                    'connection' => [
                        'str' => 'like',
                        'list' => 'like::or'
                    ],
                    'validation' => [
                        'name'=>'search',
                        'required' => 'yes',
                        'type' => 'str', // type of string
                        'min'=> 2, // string length
                        'max' => 50, // string length
                        'html' => 'no'
                    ],
                    'description' => 'Gets posts by search parameters. Search will bring Posts that match the given string in both the title and the content field',
                    'example' => ['search=sale', 'search=sale,off,marked down']
                ],
                'postDate' => [
                    'refersTo' => ['postDate'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => '='
                    ],
                    'description' => 'Gets posts by the post date',
                    'example' => ['postDate=2019-02-01']
                ],
                // ...api/v1/posts/?createdDate=1910
                'createdDate' => [
                    'refersTo' => ['createdDate'],
                    'type' => ['str'],
                    'connection' => [
                        'str' => '='
                    ],
                    'description' => 'Gets posts by the date they were created',
                    'example' => ['createdDate=2019-02-01']
                ],
                // ...api/v1/posts/?status=0
                'status' => [
                    'refersTo' => ['status'],
                    'type' => ['int'],
                    'connection' => [
                        'int' => '='
                    ],
                    'description' => 'Gets posts by the current post status. 0 = draft, 1 = published',
                    'example' => ['status=1']
                ],
                // ...api/v1/posts/?status=0
                // TODO: 
                // ! need to add ??? how to make custom yet standerd
                'extendedData' => [
                    'refersTo' => ['extraOptions'],
                    'type' => ['int'],
                    'validation' => [
                        'name'=>'extendedData',
                        'required' => 'yes',
                        'type' => 'int', // type of int
                        'num_min'=> 0, // min num
                        'num_max' => 1, // max num
                    ],
                    'description' => 'Returns all extended post data. 0 = Return basic post data, 1 = Return extended post data. Default is 0. extended data includes all images attached to the post ',
                    'example' => ['extendedData=1']
                ],
            ];

            // TODO: 
            // ! not sure what it should look like
            // * post_api_parameters, located at: root/private/reference_information.php
            static protected $postApiParameters = [
                // has to be set, and the value has to be yes to be activated
                'postApiActions' => [
                    'insert' => "yes",
                    'update' => "yes",
                    'delete' => "yes",
                    // from field where = id::12, where = title::fun Stuff
                    'updateWhere' => "yes",
                    'deleteWhere' => "yes",
                ],
                'id' => [
                    'type' => ['int'],
                    'description' => 'If updating post data must have an id, else it will make a new post'
                ], 
                'author' => [
                    'type' => ['int'],
                    'description' => 'This field expects an offer id'
                ], 
                'authorName' => [
                    'type' => ['int'],
                    'description' => 'This is a place holder for the author\'s name, quick reference to the author\'s name'
                ], 
                'catIds' => [
                    'type' => ['str'],
                    'description' => 'list of category id\'s, quick reference'
                ],
                'content' => [
                    'type' => ['str'],
                    'description' => 'This field is the main content for the given post, it does accept HTML'
                ], 
                'createdBy' => [
                    'type' => ['int'],
                    'description' => 'This field expects the id of the user who created the post'
                ], 
                'createdDate' => [
                    'type' => ['str'],
                    'description' => 'This field expects a date, specifically the date it was created on'
                ],
                'imageName' => [
                    'type' => ['str'],
                    'description' => 'This is a placeholder for the featured image, quick reference'
                ], 
                'labelIds' => [
                    'type' => ['str'],
                    'description' => 'list of label id\'s, quick reference'
                ], 
                'mediaContentIds' => [
                    'type' => ['str'],
                    'description' => 'list of media content id\'s, quick reference'
                ], 
                'postDate' => [
                    'type' => ['str'],
                    'description' => 'This field expects a post date, when the post should be displayed'
                ], 
                'status' => [
                    'type' => ['int'],
                    'description' => 'This field expects a post status. 0 = draft, 1 = published'
                ], 
                'tagIds' => [
                    'type' => ['str'],
                    'description' => 'list of tag id\'s, quick reference'
                ], 
                'title' => [
                    'type' => ['str'],
                    'description' => 'This field expects a post title, what the post will be called and referenced as'
                ]
            ];

            // page, perPage, and perhapses others should be global
            // ! temp

            // TODO: Complete code for Faker Data
            // A guide for how the faker data should be populating
            static protected $fakerDataParameters = [
                'id' => [

                ],
                'author', 
                'authorName', 
                'catIds', 
                'comments', 
                'content', 
                'createdBy', 
                'createdDate', 
                'imageName', 
                'labelIds', 
                'mediaContentIds', 
                'postDate', 
                'status', 
                'tagIds', 
                'title'
            ];

            // The SQL code used for creating the table for this class
            static protected $devToolKit_CreateTableCode = "CREATE TABLE IF NOT EXISTS posts ( 
            id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            author INT(10) UNSIGNED NOT NULL DEFAULT 0, 
            authorName VARCHAR(50) NOT NULL, 
            comments INT(10) UNSIGNED NOT NULL DEFAULT 0, 
            content TEXT NOT NULL, 
            createdBy INT(10) UNSIGNED NOT NULL, 
            createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', 
            postDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', 
            status TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, 
            title VARCHAR(50) NOT NULL, 
            catIds VARCHAR(255) DEFAULT NULL, 
            tagIds VARCHAR(255) DEFAULT NULL, 
            labelIds VARCHAR(255) DEFAULT NULL, 
            imageName VARCHAR(150) DEFAULT NULL, 
            mediaContentIds VARCHAR(255) DEFAULT NULL, 
            FOREIGN KEY (author) REFERENCES users(id), 
            FOREIGN KEY (createdBy) REFERENCES users(id) ) ENGINE=InnoDB";
        // @ class database information end
        
        // @ class specific queries start
            // latest posts feed
            static public function latest_posts_feed() {
                $sql = "SELECT id, title, postDate, comments, authorName FROM posts WHERE status = 1 ORDER BY postDate DESC LIMIT 4";
                return self::find_by_sql($sql);    
            }

            // class clean up update
            protected function class_clean_up_update(array $array = []){
                // check properties, only update necessary ones 
                // echo "class_clean_up_update info ***********";
                // var_dump($array); 
                // check to see if catIds were passed in
                if (isset($array['catIds'])) {
                    // check to see if the new list and the old list are the same
                    if (!($this->catIds == $this->catIdsOld)) {
                        // delete all old connections
                        $this->delete_connection_records("posts_to_categories", "postId", $this->id);
                        // if string is blank don't update
                        if (!(is_blank($this->catIds))) {
                            // make the id list into an array
                            $id_array = explode(",", $this->catIds);
                            // loop through and make a record for each id
                            foreach ($id_array as $value) {
                                $this->insert_connection_record("posts_to_categories", ["postId", "categoryId"], [$this->id, $value]);
                            }
                            // echo "updated!!! posts_to_categories *********** <br>";
                        }
                    } 
                }
                // check to see if labelIds were passed in
                if (isset($array['labelIds'])) {
                    // check to see if the new list and the old list are the same
                    if (!($this->labelIds == $this->labelIdsOld)) {
                        // delete all old connections 
                        $this->delete_connection_records("posts_to_labels", "postId", $this->id);
                        // if string is blank don't update
                        if (!(is_blank($this->labelIds))) {
                            // make the id list into an array
                            $id_array = explode(",", $this->labelIds);
                            // loop through and make a record for each id
                            foreach ($id_array as $value) {
                                $this->insert_connection_record("posts_to_labels", ["postId", "labelId"], [$this->id, $value]);
                            }
                            // echo "updated!!! posts_to_labels *********** <br>";
                        }
                    } 
                }
                // check to see if mediaContentIds were passed in
                if (isset($array['mediaContentIds'])) {
                    // check to see if the new list and the old list are the same
                    if (!($this->mediaContentIds == $this->mediaContentIdsOld)) {
                        // delete all old connections 
                        $this->delete_connection_records("posts_to_media_content", "postId", $this->id);
                        // if string is blank don't update, no need to data is accurate
                        if (!(is_blank($this->mediaContentIds))) {
                            // make the id list into an array
                            $id_array = explode(",", $this->mediaContentIds);
                            // loop through and make a record for each id
                            foreach ($id_array as $value) {
                                $this->insert_connection_record("posts_to_media_content", ["postId", "mediaContentId"], [$this->id, $value]);
                            }
                            // echo "updated!!! posts_to_media_content *********** <br>";
                        }
                    } 
                }
                // check to see if tagIds were passed in
                if (isset($array['tagIds'])) {
                    // check to see if the new list and the old list are the same
                    if (!($this->tagIds == $this->tagIdsOld)) {
                        // delete all old connections 
                        $this->delete_connection_records("posts_to_tags", "postId", $this->id);
                        // if string is blank don't update
                        if (!(is_blank($this->tagIds))) {
                            // make the id list into an array
                            $id_array = explode(",", $this->tagIds);
                            // loop through and make a record for each id
                            foreach ($id_array as $value) {
                                $this->insert_connection_record("posts_to_tags", ["postId", "tagId"], [$this->id, $value]);
                            }
                            // echo "updated!!! posts_to_tags *********** <br>";
                        }
                    } 
                }
            }

            // # for *multiple posts*, if you need there tags, categories, labels and featured image in a fast manner use the post references info as your go to methods
                // methods
                    // get_obj_categories_tags_labels('categories') in DatabaseObject class for categories, tags, labels
                    // get_image_path('small') in Post class for getting path to referenced post image name ($imageName)
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
                    // return data
                    return $extendedInfo_array;    
                }
                
                // get image, main queries for editing
                public function get_post_image() {
                    $sql = "SELECT mc.id, mc.alt, mc.name ";
                    $sql .= "FROM media_content AS mc ";
                    $sql .= "INNER JOIN posts_to_media_content AS ptmc ";
                    $sql .= "ON ptmc.mediaContentId = mc.id ";
                    $sql .= "WHERE ptmc.postId = '" . self::db_escape($this->id) . "' ";
                    $sql .= "AND mc.sort = 1 ";
                    $sql .= "LIMIT 1 ";
                    // return data
                    return MediaContent::find_by_sql($sql);    
                }

                // get images, main queries for editing
                public function get_post_images() {
                    $sql = "SELECT mc.id, mc.alt, mc.name ";
                    $sql .= "FROM media_content AS mc ";
                    $sql .= "INNER JOIN posts_to_media_content AS ptmc ";
                    $sql .= "ON ptmc.mediaContentId = mc.id ";
                    $sql .= "WHERE ptmc.postId = '" . self::db_escape($this->id) . "' ";
                    $sql .= "AND mc.type IN ('PNG', 'JPEG', 'JPG', 'GIF') ";
                    // return data
                    return MediaContent::find_by_sql($sql);    
                }

                // get tags, main queries for editing
                public function get_post_tags() {
                    $sql = "SELECT t.id, t.title ";
                    $sql .= "FROM tags AS t ";
                    $sql .= "INNER JOIN posts_to_tags AS ptt ";
                    $sql .= "ON ptt.tagId = t.id ";
                    $sql .= "WHERE ptt.postId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Tag::find_by_sql($sql);     
                }

                // get labels, main queries for editing
                public function get_post_labels() {
                    $sql = "SELECT l.id, l.title ";
                    $sql .= "FROM labels AS l ";
                    $sql .= "INNER JOIN posts_to_labels AS ptl ";
                    $sql .= "ON ptl.labelId = l.id ";
                    $sql .= "WHERE ptl.postId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Label::find_by_sql($sql);    
                }

                // get categories, main queries for editing
                public function get_post_categories() {
                    $sql = "SELECT c.id, c.title ";
                    $sql .= "FROM categories AS c ";
                    $sql .= "INNER JOIN posts_to_categories AS ptc ";
                    $sql .= "ON ptc.categoryId = c.id ";
                    $sql .= "WHERE ptc.postId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Category::find_by_sql($sql);    
                }
            // # single post querys end
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
            // form helpers/update helper
                protected $catIdsOld;
                protected $labelIdsOld;
                protected $mediaContentIdsOld;
                protected $tagIdsOld;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $authorName; // get_authorName()
                protected $catIds; // get_catIds()
                protected $comments; // get_comments()
                protected $createdBy; // get_createdBy()
                protected $createdDate; // get_createdDate()
                protected $id; // get_id()
                protected $imageName; // get_imageName()
                protected $labelIds; // get_labelIds()
                protected $mediaContentIds; // get_mediaContentIds()
                protected $tagIds; // get_tagIds()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // clean up form information coming in
                $args = self::cleanFormArray($args);
                // echo "just got new post info ***********";
                // var_dump($args);
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->author = $args['author'] ?? NULL;   
                $this->authorName = $args['authorName'] ?? NULL;  
                $this->catIds = $args['catIds'] ?? NULL;
                $this->catIdsOld = $args['catIdsOld'] ?? NULL;
                $this->comments = $args['comments'] ?? NULL;    
                $this->content = $args['content'] ?? NULL;     
                $this->createdBy = $args['createdBy'] ?? NULL;     
                $this->createdDate = $args['createdDate'] ?? NULL;
                $this->imageName = $args['imageName'] ?? NULL;
                $this->imagePath_array = [];
                // check to see if we have an image name
                if (strlen(Trim($this->imageName)) > 0) {
                    $this->imagePath_array = [$this->get_image_path('thumbnail'), $this->get_image_path('small'), $this->get_image_path('medium'), $this->get_image_path('large'), $this->get_image_path('original')];  
                }
                $this->labelIds = $args['labelIds'] ?? NULL;
                $this->labelIdsOld = $args['labelIdsOld'] ?? NULL;
                $this->mediaContentIds = $args['mediaContentIds'] ?? NULL;
                $this->mediaContentIdsOld = $args['mediaContentIdsOld'] ?? NULL;
                // Format dates 
                if (isset($args['postDate']) && strlen(trim($args['postDate'])) > 0) {
                    // Turn date to time string
                    $postDateStr = strtotime($args['postDate']);
                    // set date types
                    $shortDate = date("m/d/Y", $postDateStr);
                    $postFullDate = date("F d, Y", $postDateStr);
                    // set dates
                    // database date
                    $this->postDate = date("Y-m-d", $postDateStr);
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
                $this->tagIdsOld = $args['tagIdsOld'] ?? NULL; 
                $this->title = $args['title'] ?? NULL; 

            }

            // methods
            // get authorName property
            public function get_authorName() {
                return $this->authorName;
            }

            // get catIds property
            public function get_catIds() {
                return $this->catIds;
            }

            // get comments property
            public function get_comments() {
                return $this->comments;
            }

            // get createdBy property
            public function get_createdBy() {
                return $this->createdBy;
            }

            // get createdDate property
            public function get_createdDate() {
                return $this->createdDate;
            }

            // get id property
            public function get_id() {
                return $this->id;
            }

            // get imageName property
            public function get_imageName() {
                return $this->imageName;
            }

            // get labelIds property
            public function get_labelIds() {
                return $this->labelIds;
            }
            
            // get mediaContentIds property
            public function get_mediaContentIds() {
                return $this->mediaContentIds;
            }

            // get tagIds property
            public function get_tagIds() {
                return $this->tagIds;
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

            // DevToolKit add drop tables
            public function layout_devToolKit_addDropTables() {
                // global path to layouts
                include PRIVATE_PATH . "/layouts/devToolKit_addDropTable.layout.php";
            }
        // @ layouts end
    }
?>