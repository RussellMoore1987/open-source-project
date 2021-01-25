<?php
    // include traits
    require_once("userApi.trait.php");
    require_once("userSql.trait.php");
    require_once("userSeeder.trait.php");
    require_once("userContext.trait.php");

    class User extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "users";
            // db columns
            static protected $columns = ['id', 'address', 'adminNote', 'catIds', 'createdBy', 'createdDate', 'emailAddress', 'firstName', 'imageName', 'labelIds', 'lastName', 'mediaContentId', 'note', 'hashedPassword', 'phoneNumber', 'showOnWeb', 'tagIds', 'title', 'username'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = ['imagePath_array', 'fullName'];
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static protected $collectionTypeReference = 3;
            // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'User id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'address' => [
                    'name'=>'User Address',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 150, // string length
                    'html' => 'no'
                ], 
                'adminNote' => [
                    'name'=>'User Admin Note',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'catIds' => [
                    'name'=>'User catIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'createdBy' => [
                    'name'=>'User createdBy',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'User createdDate',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min'=> 6, // string length
                    'max' => 10, // string length
                    'date' => true
                ],
                'emailAddress' => [
                    'name'=>'User Email Address',
                    'type' => 'str', // type of string
                    'min'=> 6, // string length
                    'max' => 150, // string length
                    'email' => true,
                    'html' => 'yes'
                ],
                'firstName' => [
                    'name'=>'User First Name',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 25, // string length
                    'html' => 'no'
                ], 
                'imageName' => [
                    'name'=>'User imageName',
                    'type' => 'str', // type of string
                    'max' => 150 // string length
                ], 
                'labelIds' => [
                    'name'=>'User labelIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ],  
                'lastName' => [
                    'name'=>'User Last Name',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 25, // string length
                    'html' => 'no'
                ],
                'mediaContentId'=>[
                    'name'=>'User mediaContent id',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'max' => 10 // string length
                ], 
                'note' => [
                    'name'=>'User Note',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 255, // string length
                    'html' => 'no'
                ],  
                'hashedPassword' => [
                    'name'=>'User Password',
                    'type' => 'str', // type of string
                    'min'=> 8, // string length
                    'max' => 50, // string length
                    'html' => 'full'
                ],  
                'phoneNumber' => [
                    'name'=>'User Phone Number',
                    'type' => 'str', // type of string
                    'min'=> 7, // string length
                    'max' => 25, // string length
                    'html' => 'yes'
                ], 
                'showOnWeb'=>[
                    'name'=>'User Show On Web',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'num_max'=> 1, // number max value
                ],  
                'tagIds' => [
                    'name'=>'User tagIds',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'title' => [
                    'name'=>'User Job Title',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 45, // string length
                    'html' => 'no' // mostly just to allow special characters like () []
                ],
                'username' => [
                    'name'=>'User Username',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 35, // string length
                    'html' => 'no' // mostly just to allow special characters like () []
                ]
            ];
        // @ class database information end

        // @ class traits start
            use UserApi;
            use UserSql;
            use UserSeeder;
            use UserContext;
            use Authentication;
        // @ class traits end
        
        // @ class specific queries start

            // get all users for select
            static public function get_users_for_select() {
                $sql = "SELECT id, firstName, lastName FROM users ";
                return static::find_by_sql($sql);
            }
            
            // # for a *single user* querys start
                // get all extended info
                public function get_extended_info() {
                    // empty array to hold potential extended information
                    $extendedInfo_array = [];
                    // get all images
                    $extendedInfo_array['image'] = $this->get_user_image();
                    // get tags
                    $extendedInfo_array['tags'] = $this->get_user_tags();
                    // get labels
                    $extendedInfo_array['labels'] = $this->get_user_labels();
                    // get categories
                    $extendedInfo_array['categories'] = $this->get_user_categories();
                    // return data
                    return $extendedInfo_array;    
                }
                
                // get image, main queries for editing
                public function get_user_image() {
                    $sql = "SELECT id, name ";
                    $sql .= "FROM media_content ";
                    $sql .= "WHERE id = '" . self::db_escape($this->mediaContentId) . "' ";
                    $sql .= "LIMIT 1 ";
                    // return data
                    return MediaContent::find_by_sql($sql);    
                }

                // get tags, main queries for editing
                public function get_user_tags() {
                    $sql = "SELECT t.id, t.title ";
                    $sql .= "FROM tags AS t ";
                    $sql .= "INNER JOIN users_to_tags AS utt ";
                    $sql .= "ON utt.tagId = t.id ";
                    $sql .= "WHERE utt.userId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Tag::find_by_sql($sql);     
                }

                // get labels, main queries for editing
                public function get_user_labels() {
                    $sql = "SELECT l.id, l.title ";
                    $sql .= "FROM labels AS l ";
                    $sql .= "INNER JOIN users_to_labels AS utl ";
                    $sql .= "ON utl.labelId = l.id ";
                    $sql .= "WHERE utl.userId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Label::find_by_sql($sql);    
                }

                // get categories, main queries for editing
                public function get_user_categories() {
                    $sql = "SELECT c.id, c.title ";
                    $sql .= "FROM categories AS c ";
                    $sql .= "INNER JOIN users_to_categories AS utc ";
                    $sql .= "ON utc.categoryId = c.id ";
                    $sql .= "WHERE utc.userId = '" . self::db_escape($this->id) . "' ";
                    // return data
                    return Category::find_by_sql($sql);    
                }
            // # single post querys end
        // @ class specific queries end
        
        // @ methods start

            // methods
            // extra constructor information
            public function extended_constructor(array $args=[]) {
                // hash password
                if ($this->hashedPassword != NULL) {
                    $this->hashedPassword = self::hash_password($this->hashedPassword);
                }
                // TODO: to use this on a lot of different classes eventually make it common or in the database object **DRY**
                // path array
                $this->imagePath_array = [];
                // check to see if we have an image name
                if (strlen(Trim($this->imageName)) > 0) {
                    $this->imagePath_array = [$this->get_image_path('thumbnail'), $this->get_image_path('small'), $this->get_image_path('medium'), $this->get_image_path('large'), $this->get_image_path('original')];  
                }
                // needed to wait until last name was set
                $this->fullName = $this->firstName . " " . $this->lastName;
            }

            // get image path with recorded reference image name
            public function get_image_path($type = 'small') {
                // get path // * image_paths located at: root/private/rules_docs/reference_information.php
                $path = get_image_path($type);
                // return image path with name
                return "{$path}/{$this->imageName}";
            }
        // @ methods end

    }
?>