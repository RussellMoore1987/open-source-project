<?php
    class Category extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DatabaseObject Class
            // table name
            static protected $tableName = "categories";
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'note', 'subCatId', 'title', 'useCat'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Category id',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'max' => 10 // string length
                ], 
                'note' => [
                    'name'=>'Category Note',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'subCatId'=>[
                    'name'=>'Category subCatId',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'max' => 10 // string length
                ],
                'title' => [
                    'name'=>'Post Title',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes' // mostly just to allow special characters like () []
                ],
                'useCat'=>[
                    'name'=>'Category useCat',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'num_max'=> 4, // number max value
                ]
            ];
        // @ class database information end
        
        // @ class specific queries start
            // Find all the categories associated with the collection type parameter
            static public function find_all_categories(int $type = 0) {
                $sql = "SELECT id, note, subCatId, title, useCat FROM categories ";
                // we expect a number between one and four // * collection_type_reference, located at: root/private/reference_information.php
                if ($type <= 4 && $type <= 1) {
                    $sql .= "WHERE useCat = '{$type}'";
                }
                return self::find_by_sql($sql);    
            }

            // this function is overwritten from the databaseObject class, do category checks and then this allows you to add or update a record
            public function save(){
                // make sure that the category is not deeper than five layers
                    // get parents
                    if ($this->subCatId = 0) {
                        $parents = 0;
                    } else {
                        // set up an initial sub ID
                        $subCatId = $this->subCatId;
                        // whether or not there are more parents, default true
                        $moreParents = true;
                        // loop over queries until all parents are found
                        while ($moreParents == true) {
                            // if we get is zero we are at the top
                            if ($subCatId == 0) {
                                $moreParents = false;
                            } else {
                                $Parent = Category::find_by_id($subCatId);
                                if ($Parent) {
                                    $subCatId = $Parent->subCatId;
                                    $parents++;
                                }

                            }
                        }
                    }
                    
                    // get subs
                // if the category is a parent make sure to change the children as well
                // with children, is the category structure deeper than five
                
                // save 
                Parent::$save();
                // if (isset($this->id) && !is_blank($this->id)) {
                //     return $this->update();
                // } else {
                //     return $this->create();
                // }  
            }
        // @ class specific queries end

        // @ properties start
            // main properties
                public $note;
                public $subCatId;
                public $title;
                public $useCat;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $id; // get_id()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->note = $args['note'] ?? NULL;   
                $this->subCatId = $args['subCatId'] ?? NULL;  
                $this->title = $args['title'] ?? NULL;
                $this->useCat = $args['useCat'] ?? NULL;    
            }

            // methods
            // get id property
            public function get_id() {
                return $this->id;
            }

            // filter all categories, expects an array of objects
            static public function filter_all_categories(array $categories_array) {
                // make arrays of them below
                $postCategories_array = [];
                $mediaContentCategories_array = [];
                $usersCategories_array = [];
                $contentCategories_array = [];

                // all parents
                $postParentCategories_array = [];
                $mediaContentParentCategories_array = [];
                $usersParentCategories_array = [];
                $contentParentCategories_array = [];

                // all subs
                $postSubCategories_array = [];
                $mediaContentSubCategories_array = [];
                $usersSubCategories_array = [];
                $contentSubCategories_array = [];

                // sort them, they should fit into one of these arrays
                foreach ($categories_array as $Category) {
                    // get all category of a ctr type parents and subs
                    switch ($Category->useCat) {
                        // putting the title in first to use for sorting
                        case 1: $postCategories_array[$Category->title] = $Category; break;
                        case 2: $mediaContentCategories_array[$Category->title] = $Category; break;
                        case 3: $usersCategories_array[$Category->title] = $Category; break;
                        case 4: $contentCategories_array[$Category->title] = $Category; break;
                    }
                    
                    // get parent category
                    if ($Category->subCatId == 0) {
                        switch ($Category->useCat) {
                            // putting the title in first to use for sorting
                            case 1: $postParentCategories_array[$Category->title] = $Category; break;
                            case 2: $mediaContentParentCategories_array[$Category->title] = $Category; break;
                            case 3: $usersParentCategories_array[$Category->title] = $Category; break;
                            case 4: $contentParentCategories_array[$Category->title] = $Category; break;
                        }
                    // get subs
                    } else {
                        switch ($Category->useCat) {
                            // putting the title in first to use for sorting
                            case 1: $postSubCategories_array[$Category->title] = $Category; break;
                            case 2: $mediaContentSubCategories_array[$Category->title] = $Category; break;
                            case 3: $usersSubCategories_array[$Category->title] = $Category; break;
                            case 4: $contentSubCategories_array[$Category->title] = $Category; break;
                        }
                    }
                }
                // sort alphabetically all arrays
                ksort($postCategories_array);
                ksort($mediaContentCategories_array);
                ksort($usersCategories_array);
                ksort($contentCategories_array);

                // all parents
                ksort($postParentCategories_array);
                ksort($mediaContentParentCategories_array);
                ksort($usersParentCategories_array);
                ksort($contentParentCategories_array);

                // all subs
                ksort($postSubCategories_array);
                ksort($mediaContentSubCategories_array);
                ksort($usersSubCategories_array);
                ksort($contentSubCategories_array);

                // put it all into one array
                
                $sorted_array['postCategories_array'] = $postCategories_array;
                $sorted_array['mediaContentCategories_array'] = $mediaContentCategories_array;
                $sorted_array['usersCategories_array'] = $usersCategories_array;
                $sorted_array['contentCategories_array'] = $contentCategories_array;

                // all parents
                $sorted_array['postParentCategories_array'] = $postParentCategories_array;
                $sorted_array['mediaContentParentCategories_array'] = $mediaContentParentCategories_array;
                $sorted_array['usersParentCategories_array'] = $usersParentCategories_array;
                $sorted_array['contentParentCategories_array'] = $contentParentCategories_array;

                // all subs
                $sorted_array['postSubCategories_array'] = $postSubCategories_array;
                $sorted_array['mediaContentSubCategories_array'] = $mediaContentSubCategories_array;
                $sorted_array['usersSubCategories_array'] = $usersSubCategories_array;
                $sorted_array['contentSubCategories_array'] = $contentSubCategories_array;

                // return data
                return $sorted_array;
            }
        // @ methods end

        // @ layouts start
            // add/edit structure for categories layout
                // ? it expects $array, retrieved from Category::filter_all_categories(); and a ctr, ctr = collection_type_reference
                // * collection_type_reference, located at: root/private/reference_information.php
            static public function layout_categoryStructure($Categories_array = [], $ctr = 1) {
                // global path to layouts
                include PRIVATE_PATH . "/layouts/categoryStructure.php";
            }
        // @ layouts end
    }
?>