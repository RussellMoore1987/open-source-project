<?php
    // include api trait
    require_once("postApi.trait.php");
    require_once("postSql.trait.php");
    require_once("postSeeder.trait.php");

    class Post extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "posts";
            // db columns
            static protected $columns = ['id', 'author', 'authorName', 'catIds', 'comments', 'content', 'createdBy', 'createdDate', 'imageName', 'labelIds', 'mediaContentIds', 'postDate', 'status', 'tagIds', 'title'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id', 'comments'];
            // name specific class properties you wish to included in the API
            static protected $apiProperties = ['fullDate', 'shortDate', 'imagePath_array'];
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static protected $collectionTypeReference = 1;
            // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Post id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'author' => [
                    'name'=>'Post Author',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'authorName' => [
                    'name'=>'Post AuthorName Stamp',
                    'required' => true,
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
                    'required' => true,
                    'name'=>'Post Comment Count',
                    'type' => 'int', // type of int
                    'max' => 10 // string length
                ], 
                'content' => [
                    'name'=>'Post Content',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 65000, // string length
                    'html' => 'full'
                ], 
                'createdBy' => [
                    'name'=>'Post createdBy',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'Post createdDate',
                    'required' => true,
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => true
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
                    'required' => true,
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => true
                ], 
                'status' => [
                    'name'=>'Post status',
                    'required' => true,
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
                    'required' => true,
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes' // mostly just to allow special characters like () []
                ]
            ];
        // @ class database information end
        
        // @ class traits start
            use PostApi;
            use PostSql;
            use PostSeeder;
        // @ class traits end
        
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
                    $sql = "SELECT mc.* ";
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
                    $sql = "SELECT mc.* ";
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
        
        // @ methods start
            // extra constructor information
            public function extended_constructor(array $args=[]) {
                $this->imagePath_array = [];
                // check to see if we have an image name
                if (strlen(Trim($this->imageName)) > 0) {
                    $this->imagePath_array = [$this->get_image_path('thumbnail'), $this->get_image_path('small'), $this->get_image_path('medium'), $this->get_image_path('large'), $this->get_image_path('original')];  
                }
                $this->labelIdsOld = $args['labelIdsOld'] ?? NULL;
                $this->catIdsOld = $args['catIdsOld'] ?? NULL;
                $this->mediaContentIdsOld = $args['mediaContentIdsOld'] ?? NULL;
                $this->tagIdsOld = $args['tagIdsOld'] ?? NULL; 
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
            }

            // get image path with recorded reference image name
            public function get_image_path($type = 'small') {
                // get path // * image_paths located at: root/private/rules_docs/reference_information.php
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