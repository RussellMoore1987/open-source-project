<?php
    class Category extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DatabaseObject Class
            // table name
            static protected $tableName = "categories";
            // db columns
            static protected $columns = ['id', 'note', 'subCatId', 'title', 'useCat'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
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
                // we expect a number between one and four // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
                if ($type <= 4 && $type <= 1) {
                    $sql .= "WHERE useCat = '{$type}'";
                }
                return self::find_by_sql($sql);    
            }

            static public function get_all_categories_sorted() {
                // check to see if we are to have the array set
                if (count(self::$allCategoriesSorted) <= 0) {
                    // get all categories, then sort them
                    $allCategories_array = Category::find_all();

                    // filter array, get back array of arrays 
                    $Categories_array = Category::filter_all_categories($allCategories_array);

                    self::$allCategoriesSorted = $Categories_array;
                }

                // return the data
                return self::$allCategoriesSorted;
            }

            // this function is overwritten from the databaseObject class, do category checks and then this allows you to add or update a record
            public function save($check = "yes") {
                // see whether or not we need to do extra checks, if we are updating subs we don't need to do an extra check
                if ($check == "yes") {
                    // check and see if we need to do any extra work, validation checks on category layers and transferring children to the correct categories
                    if ($this->subCatIdOld != $this->subCatId || $this->useCatOld != $this->useCat) {
                        // echo "main update check ran!!!!!";
                        // set localized reference
                            // get all categories 
                            $Categories_array = Category::find_all();
                            // change the active record in the object arrays, so the information is accurate to validate all of
                            foreach ($Categories_array as $Category) {
                                // find it and change it
                                if ($Category->get_id() == $this->get_id()) {
                                    // change that record
                                    $Category->subCatId = $this->subCatId;
                                    $Category->useCat = $this->useCat;
                                }
                                // put everything into a new array
                                $CategoriesNew_array[] = $Category;
                            }
                        // make sure that the category is not deeper than five layers
                        // get parents
                            // set parent counter
                            $parents = $this->get_parents($CategoriesNew_array);
                            
                        // get subs
                            $subData = $this->get_subs($CategoriesNew_array);
                            // set sub counter
                            $sudLayers = $subData[0];
                            // list of child IDs
                            $subIdList_array = $subData[1];
                            
                        // check to see if parent and sub count is higher than five
                            $totalLayerCount = $parents + $sudLayers;
                            if ($totalLayerCount > 5) {
                                // setting up some message variables
                                $messageCountOver = $totalLayerCount - 5;
                                $messageLayerName = $messageCountOver == 1 ? "layer": "layers";
                                $this->errors[] = "Your category structure can only be 6 layers deep, your attempted update was {$messageCountOver} {$messageLayerName} to deep.";
                                // return false
                                return false;
                            }
                    }
                }

                // save 
                Parent::save();

                // see whether or not we need to do extra updates, if we are updating a sub we don't need to do extra updates
                if ($check == "yes") {
                    // check and see if we need to run extra updates, updating subs
                    if (isset($subIdList_array) && count($subIdList_array) > 1 && $this->useCatOld != $this->useCat) {
                        // echo "sub update ran!!!!!";
                        // loop over and update all other subs
                        foreach ($subIdList_array as $id) {
                            // find sub
                            $SubToUpdate = Category::find_by_id($id);
                            // set value for update
                            $SubToUpdate->useCat = $this->useCat;
                            // save that record
                            $SubToUpdate->save("no");
                        }
                    }
                }
            }

            // delete category this function is overwritten from the databaseObject class, do category checks and then this allows you to delete a record or records
            public function delete($check = "yes") {
                // see whether or not we need to do extra checks, if we are updating subs we don't need to do an extra check
                if ($check == "yes") {
                    // get all categories 
                    $Categories_array = Category::find_all();
                    // get subs
                    $subData = $this->get_subs($Categories_array);
                    // make array for cleaning records
                    $cleanUpId_array = array_merge([$this->get_id()], $subData);
                }

                // delete record
                Parent::delete();

                

                // if subs delete them
                if ($check == "yes") {
                    // loop over and delete all other subs
                    foreach ($subData[1] as $id) {
                        // find sub
                        $SubToUpdate = Category::find_by_id($id);
                        // delete record with out checks
                        $SubToUpdate->delete("no");
                    }
                }


                // todo: working here, clean up records, clean up list of IDs.
                // // run cleanup, for corresponding ctr's 1, 3, 4 // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
                // if ($check == "yes" && ($this->useCat == 1 || $this->useCat == 3 || $this->useCat == 4)) {
                //     // find its collection type reference
                //     switch ($this->useCat) {
                //         case 3: $class = "Post"; break;
                //         case 3: $class = "User"; break;
                //         case 4: $class = "Content"; break;
                //     }
                //     // loop over IDs and correct information
                //     foreach ($cleanUpId_array as $id) {
                //         // find sub
                //         $Obj = $class::find_by_sql($id);
                //         // get id list
                //         $idList = $Obj->get_catIds();
                //         // remove the ID   
                //         $Obj->merge_attributes(["catIds" => ""]);
                //     }
                // }
            }

            // # query helpers starts
                // get category parents
                protected function get_parents(array $Categories_array){
                    // get parents
                    // set parent counter
                    $parents = 0;
                    // see if we are the parent
                    if (!($this->subCatId == 0)) {
                        // set up an initial sub ID
                        $subCatId = $this->subCatId;
                        // whether or not there are more parents, default true
                        $moreParents = true;
                        // loop over the array until all parents are found
                        while ($moreParents == true) {
                            // if we get a zero we are at the top
                            if ($subCatId == 0) {
                                $moreParents = false;
                            } else {
                                // loop over array find parent
                                foreach ($Categories_array as $Parent) {
                                    if ($Parent->get_id() == $subCatId) {
                                        $subCatId = $Parent->subCatId;
                                        $parents++;
                                    }
                                }
                            }
                        }
                    }
                    // return data
                    return $parents;
                }

                // get category suds 
                protected function get_subs(array $Categories_array) {
                    // get subs
                    // set sub counter
                    $sudLayers = 0;
                    // list of child IDs
                    $subIdList_array = [$this->get_id()];
                    // get subs 
                    $result = [true, [$this->get_id()]];
                    // keep looking until we get a false
                    while ($result[0]) {
                        $result = $this->get_sub_categories($result[1], $Categories_array);
                        // if we get some IDs merged them with the master list, set new layer as well
                        if ($result[0]) {
                            $subIdList_array = array_merge($subIdList_array, $result[1]);
                            $sudLayers++;
                        }
                    }
                    // remove the first number as it will be the ID of the parent
                    array_shift($subIdList_array);
                    // return data
                    return $subData = [$sudLayers,$subIdList_array];
                }
            // # query helpers ends
        // @ class specific queries end

        // @ properties start
            // main properties
                public $note;
                public $subCatId;
                public $title;
                public $useCat;
            // secondary properties
                // this stores the sorted category information
                static public $allCategoriesSorted = [];
            // form helpers/update helper
                protected $subCatIdOld;
                protected $useCatOld;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $id; // get_id()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // clean up form information coming in
                $args = self::cleanFormArray($args);
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->note = $args['note'] ?? NULL;   
                $this->subCatId = $args['subCatId'] ?? NULL;  
                $this->subCatIdOld = $args['subCatIdOld'] ?? NULL;  
                $this->title = $args['title'] ?? NULL;
                $this->useCat = $args['useCat'] ?? NULL;    
                $this->useCatOld = $args['useCatOld'] ?? NULL;    
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
                $postCategories_array = full_natural_key_sort($postCategories_array);
                $mediaContentCategories_array = full_natural_key_sort($mediaContentCategories_array);
                $usersCategories_array = full_natural_key_sort($usersCategories_array);
                $contentCategories_array = full_natural_key_sort($contentCategories_array);

                // all parents
                $postParentCategories_array = full_natural_key_sort($postParentCategories_array);
                $mediaContentParentCategories_array = full_natural_key_sort($mediaContentParentCategories_array);
                $usersParentCategories_array = full_natural_key_sort($usersParentCategories_array);
                $contentParentCategories_array = full_natural_key_sort($contentParentCategories_array);

                // all subs
                $postSubCategories_array = full_natural_key_sort($postSubCategories_array);
                $mediaContentSubCategories_array = full_natural_key_sort($mediaContentSubCategories_array);
                $usersSubCategories_array = full_natural_key_sort($usersSubCategories_array);
                $contentSubCategories_array = full_natural_key_sort($contentSubCategories_array);

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

            protected function get_sub_categories(array $layerId_array, array $CategoriesNew_array) {
                // set default
                $addLayer = false; 
                // empty by default
                $subIdList = [];  
                foreach ($layerId_array as $id) { 
                    // set parent id
                    $parentCatId = $id;
                    // loop over children's children
                    foreach ($CategoriesNew_array as $Sub) {
                        // if matches set as child
                        if ($Sub->subCatId == $parentCatId) {
                            // add them to array
                            $subIdList[] = $Sub->get_id();
                            // echo $Sub->title . "-" . $Sub->get_id() . "<br>";
                            // add a layer
                            $addLayer = true;
                        }
                    }
                }
                // return data
                return $array = [$addLayer, $subIdList];
            }

        // @ methods end

        // @ layouts start
            // add/edit structure for categories layout
                // ? it expects $array, retrieved from get_all_categories_sorted() or Category::filter_all_categories(); and a ctr, ctr = collection_type_reference
                // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static public function layout_categoryStructure($Categories_array = [], $ctr = 1) {
                // global path to layouts
                include PRIVATE_PATH . "/layouts/categoryStructure.php";
            }
        // @ layouts end
    }
?>