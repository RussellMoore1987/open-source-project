<?php
    class User extends DatabaseObject {
        // @ class database information start
            // table name
            static protected $tableName = "users";
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'address', 'adminNote', 'catIds', 'createdBy', 'createdDate', 'emailAddress', 'firstName', 'imageName', 'labelIds', 'lastName', 'mediaContentId', 'note', 'password', 'phoneNumber', 'showOnWeb', 'tagIds', 'title', 'username'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = ['imagePath_array', 'fullName'];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 3;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'User id',
                    'required' => 'yes',
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
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'createdDate' => [
                    'name'=>'User createdDate',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'exact' => 10, // string length
                    'date' => 'yes'
                ],
                'emailAddress' => [
                    'name'=>'User Email Address',
                    'type' => 'str', // type of string
                    'min'=> 6, // string length
                    'max' => 150, // string length
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
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'max' => 10 // string length
                ], 
                'note' => [
                    'name'=>'User Note',
                    'type' => 'str', // type of string
                    'min'=> 10, // string length
                    'max' => 255, // string length
                    'html' => 'no'
                ],  
                'password' => [
                    'name'=>'User Password',
                    'type' => 'str', // type of string
                    'min'=> 8, // string length
                    'max' => 50, // string length
                    'html' => 'no'
                ],  
                'phoneNumber' => [
                    'name'=>'User Phone Number',
                    'type' => 'str', // type of string
                    'min'=> 7, // string length
                    'max' => 25, // string length
                    'html' => 'no'
                ], 
                'showOnWeb'=>[
                    'name'=>'User Show On Web',
                    'required' => 'yes',
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
            // * get_api_parameters, located at: root/private/reference_information.php
            static protected $getApiParameters = [
                // ...api/v1/label/?id=22,33,5674,1,2,43,27,90,786 // ...api/v1/posts/?id=22
                'id'=>[
                    'refersTo' => ['id'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "=",
                        'list' => 'in'
                    ],
                    'description' => 'Gets users by the user id or list of user ids',
                    'example' => ['id=1', 'id=1,2,3,4,5']
                ]
            ];
        // @ class database information end
        
        // @ class specific queries start

            // get all users for select
            static public function get_users_for_select() {
                $sql = "SELECT id, firstName, lastName FROM users ";
                return static::find_by_sql($sql);
            }
            
            // # for a *single user* query's start
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
            // # single post query's end
        // @ class specific queries end

        // @ properties start
            // main properties
                public $address;
                public $adminNote;
                public $emailAddress;
                public $firstName;
                public $lastName;
                public $note;
                public $phoneNumber;
                public $showOnWeb;
                public $title;
                public $username;
                // secondary properties
                public $fullName;
                // used primarily for the API, if you just need a image path you can just call get_image_path('small') found bellow
                public $imagePath_array;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $catIds; // get_catIds()
                protected $createdBy; // get_createdBy()
                protected $createdDate; // get_createdDate()
                protected $id; // get_id()
                protected $imageName; // get_imageName()
                protected $labelIds; // get_labelIds()
                protected $mediaContentId; // get_mediaContentId()
                protected $tagIds; // get_tagIds()
                protected $password; // get_password()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->address = $args['address'] ?? NULL;
                $this->adminNote = $args['adminNote'] ?? NULL;
                $this->catIds = $args['catIds'] ?? NULL;
                $this->createdBy = $args['createdBy'] ?? NULL;
                $this->createdDate = $args['createdDate'] ?? NULL;
                $this->emailAddress = $args['emailAddress'] ?? NULL;
                $this->firstName = $args['firstName'] ?? NULL;
                $this->imageName = $args['imageName'] ?? NULL;
                $this->imagePath_array = [];
                // check to see if we have an image name
                if (strlen(Trim($this->imageName)) > 0) {
                    $this->$imagePath_array = [get_image_path('thumbnail'), get_image_path('small'), get_image_path('medium'), get_image_path('large'), get_image_path('original')];  
                }
                $this->labelIds = $args['labelIds'] ?? NULL;
                $this->lastName = $args['lastName'] ?? NULL;
                $this->mediaContentId = $args['mediaContentId'] ?? NULL;
                $this->note = $args['note'] ?? NULL;
                $this->password = $args['password'] ?? NULL;
                $this->phoneNumber = $args['phoneNumber'] ?? NULL;
                $this->showOnWeb = $args['showOnWeb'] ?? NULL;
                $this->tagIds = $args['tagIds'] ?? NULL;
                $this->title = $args['title'] ?? NULL;
                $this->username = $args['username'] ?? NULL;  
                      
                // needed to wait until last name was set
                $this->fullName = $this->firstName . " " . $this->lastName;
            }

            // methods
            // get catIds property
            public function get_catIds() {
                return $this->catIds;
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
            
            // get mediaContentId property
            public function get_mediaContentId() {
                return $this->mediaContentId;
            }

            // get tagIds property
            public function get_tagIds() {
                return $this->tagIds;
            
            }
            // get password property
            public function get_password() {
                return $this->password;
            }

            // get image path with recorded reference image name
            public function get_image_path($type = 'small') {
                // get path // * image_paths located at: root/private/reference_information.php
                $path = get_image_path($type);
                // return image path with name
                return "{$path}/{$this->imageName}";
            }
        // @ methods end

    }
?>