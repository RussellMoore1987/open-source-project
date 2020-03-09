<?php
    // include api trait
    require_once("mediaContentSql.trait.php");
    require_once("mediaContentSeeder.trait.php");

    class MediaContent extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "media_content";
            // db columns
            static protected $columns = ['id', 'alt', 'createdBy', 'createdDate', 'name', 'note', 'type'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = ['imagePath_array'];
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static protected $collectionTypeReference = 2;
            // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Media Content id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ],
                'alt' => [
                    'name'=>'Media Content alt tag Name',
                    'type' => 'str', // type of string
                    'max' => 75 // string length
                ], 
                'createdBy' => [
                    'name'=>'Media Content createdBy',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'Media Content createdDate',
                    'required' => true,
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => true
                ],
                'name' => [
                    'name'=>'Media Content File Name',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min' => 5, // string length, example: 1.png
                    'max' => 150 // string length
                ], 
                'note' => [
                    'name'=>'Media Content Note',
                    'type' => 'str', // type of string
                    'max' => 255 // string length
                ], 
                'type' => [
                    'name'=>'Media Content Type',
                    'required' => true,
                    'type' => 'str', // type of string
                    'max' => 25 // string length
                ]
            ];
        // @ class database information end

        // @ class traits start
            use MediaContentSql;
            use MediaContentSeeder;
        // @ class traits end
        
        // @ class specific queries start
            // # for a *single post* query's start
                // todo: look over and clean up
                // get all extended info
                // public function get_extended_info() {
                //     // empty array to hold potential extended information
                //     $extendedInfo_array = [];
                //     // get all images
                //     $extendedInfo_array['images'] = $this->get_post_images();
                //     // get tags
                //     $extendedInfo_array['tags'] = $this->get_post_tags();
                //     // get labels
                //     $extendedInfo_array['labels'] = $this->get_post_labels();
                //     // get categories
                //     $extendedInfo_array['categories'] = $this->get_post_categories();
                //     // return data
                //     return $extendedInfo_array;    
                // }
                
                // // get image, main queries for editing
                // public function get_post_image() {
                //     $sql = "SELECT mc.alt, mc.name ";
                //     $sql .= "FROM media_content AS mc ";
                //     $sql .= "INNER JOIN posts_to_media_content AS ptmc ";
                //     $sql .= "ON ptmc.mediaContentId = mc.id ";
                //     $sql .= "WHERE ptmc.postId = '" . self::db_escape($this->id) . "' ";
                //     $sql .= "AND mc.sort = 1 ";
                //     $sql .= "LIMIT 1 ";
                //     // return data
                //     return MediaContent::find_by_sql($sql);    
                // }

                // // get images, main queries for editing
                // public function get_post_images() {
                //     $sql = "SELECT mc.alt, mc.name ";
                //     $sql .= "FROM media_content AS mc ";
                //     $sql .= "INNER JOIN posts_to_media_content AS ptmc ";
                //     $sql .= "ON ptmc.mediaContentId = mc.id ";
                //     $sql .= "WHERE ptmc.postId = '" . self::db_escape($this->id) . "' ";
                //     // return data
                //     return MediaContent::find_by_sql($sql);    
                // }

                // // get tags, main queries for editing
                // public function get_post_tags() {
                //     $sql = "SELECT t.id, t.title ";
                //     $sql .= "FROM tags AS t ";
                //     $sql .= "INNER JOIN posts_to_tags AS ptt ";
                //     $sql .= "ON ptt.tagId = t.id ";
                //     $sql .= "WHERE ptt.postId = '" . self::db_escape($this->id) . "' ";
                //     // return data
                //     return Tag::find_by_sql($sql);     
                // }

                // // get labels, main queries for editing
                // public function get_post_labels() {
                //     $sql = "SELECT l.id, l.title ";
                //     $sql .= "FROM labels AS l ";
                //     $sql .= "INNER JOIN posts_to_labels AS ptl ";
                //     $sql .= "ON ptl.labelId = l.id ";
                //     $sql .= "WHERE ptl.postId = '" . self::db_escape($this->id) . "' ";
                //     // return data
                //     return Label::find_by_sql($sql);    
                // }

                // // get categories, main queries for editing
                // public function get_post_categories() {
                //     $sql = "SELECT c.id, c.title ";
                //     $sql .= "FROM categories AS c ";
                //     $sql .= "INNER JOIN posts_to_categories AS ptc ";
                //     $sql .= "ON ptc.categoryId = c.id ";
                //     $sql .= "WHERE ptc.postId = '" . self::db_escape($this->id) . "' ";
                //     // return data
                //     return Category::find_by_sql($sql);    
                // }
            // # single post query's end
        // @ class specific queries end
        
        // @ methods start
            // methods
            // extra constructor information
            public function extended_constructor(array $args=[]) {
                $this->catIdsOld = $args['catIdsOld'] ?? NULL;
                $this->imagePath_array = [];
                $this->labelIdsOld = $args['labelIdsOld'] ?? NULL;
                $this->tagIdsOld = $args['tagIdsOld'] ?? NULL;
                // check to see if we have an image name, needs to run after $this->name is set
                if (strlen(Trim($this->name)) > 0) {
                    $this->imagePath_array = [$this->get_image_path('thumbnail'), $this->get_image_path('small'), $this->get_image_path('medium'), $this->get_image_path('large'), $this->get_image_path('original')];  
                }
            }

            // get image path with recorded reference image name
            public function get_image_path($type = 'small') {
                // get path // * image_paths located at: root/private/rules_docs/reference_information.php
                $path = get_image_path($type);
                // return image path with name
                return "{$path}/{$this->name}";
            }
        // @ methods end
    }
?>