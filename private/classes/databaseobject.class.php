<?php

    // todo:
        // possibly store images after query
        // possibly function for extended data
            // main data
            // media content
            // class spesific
            // update checks to make sure were not overwriting things
            // push notifications, or check if post is being edited by somebody else ***
                // what if they leave the page or or close down the browser
            
            // possible persistent session data to be stored in MySQL what users are logged in so on and so forth, what is being edited
                // json object, use reference date to see it needs to be updated
                // users actively using the system
                // user profile being edited
                // posted being edited
                // content being edited
                // media content being edited??? 




    abstract class DatabaseObject {
        // database connection
        static protected $database;
        // database information
        static protected $tableName;
        static protected $columns = [];
        static protected $columnExclusions = [];
        static protected $apiProperties = [];
        // default collection type reference 0 equals all possible // * collection_type_reference, located at: root/private/reference_information.php
        static protected $collectionTypeReference = 0;
        // db validation, // * validation_options located at: root/private/reference_information.php
        static protected $validation_columns = [];
        public $errors = [];

        // @ active record code start
            // possible extended info
            // get all possible tags, // * collection_type_reference, located at: root/private/reference_information.php
                static public function get_possible_tags() {
                    // if not set get info
                    if (!isset(static::$possibleTags)) {
                        // get all possible tags 
                        $result = Tag::$find_all_tags(static::$collectionTypeReference);
                        // create an id indexed array, this is a global function, store array in static property
                        static::$possibleTags = get_key_value_array($result);
                    }
                    // return possibilities
                    return static::$possibleTags;
                }
                // possible tags
                static protected $possibleTags;
            // get all possible labels, // * collection_type_reference, located at: root/private/reference_information.php 
                static public function get_possible_labels() {
                    // if not set get info
                    if (!isset(static::$possibleLabels)) {
                        // get all possible Labels 
                        $result = Label::$find_all_labels(static::$collectionTypeReference);
                        // create an id indexed array, this is a global function, store array in static property
                        static::$possibleLabels = get_key_value_array($result);
                    }
                    // return possibilities
                    return static::$possibleLabels;
                }
                // possible labels
                static protected $possibleLabels;
            // get all possible categories, // * collection_type_reference, located at: root/private/reference_information.php
                static public function get_possible_categories() {
                    // if not set get info
                    if (!isset(static::$possibleCategories)) {
                        // get all possible categories
                        $result = Category::$find_all_categories(static::$collectionTypeReference);
                        // create an id indexed array, this is a global function, store array in static property
                        static::$possibleCategories = get_key_value_array($result);
                    }
                    // return possibilities
                    return static::$possibleCategories;
                }
                // possible categories
                static protected $possibleCategories;

            // set up local reference for the database
            static public function set_database(object $database) {
                self::$database = $database;
            }

            // get object categories, tags, or labels
            public function get_obj_categories_tags_labels($type = NULL) {
                // blank array, set below
                $data_array = [];
                // find if there are any ids attached to the object
                if (($type == 'categories' && !is_blank($this->catIds)) || ($type == 'tags' && !is_blank($this->tagIds)) || ($type == 'labels' && !is_blank($this->labelIds))) {
                    // take object list of ids and create an array
                    switch ($type) {
                        case 'categories': $id_array = explode(',',$this->catIds); break;
                        case 'tags': $id_array = explode(',',$this->tagIds); break;
                        case 'labels': $id_array = explode(',',$this->labelIds); break;
                    }
                    // get possibilities for the object
                    switch ($type) {
                        case 'categories': $possibilities_array = $this->get_possible_categories(); break;
                        case 'tags': $possibilities_array = $this->get_possible_tags(); break;
                        case 'labels': $possibilities_array = $this->get_possible_labels(); break;
                    }
                    // loop over $id_array
                    foreach ($id_array as $id) {
                        // see if the category exists
                        if (isset($possibilities_array[$id])) {
                            $data_array[$id] = $possibilities_array[$id];
                        }
                    }
                }
                // return all tags connected to the object in a key value array
                return $data_array;
            }
            
            // Helper function, object creator
            static protected function instantiate(array $record) {
                // load the object
                $object = new static($record);
                // return the object
                return $object;
            }

            // find by sql
            static public function find_by_sql($sql) {
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check_and_free_result($result);
                // turn results into an array of objects
                $object_array = [];
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    $object_array[] = static::instantiate($record);    
                }
                // return an array of populated objects
                return $object_array;   
            }

            // find all
            static public function find_all() {
                $sql = "SELECT * FROM " . static::$tableName;
                return static::find_by_sql($sql);
            }

            // count all records
            static public function count_all() {
                $sql = "SELECT COUNT(*) FROM " . static::$tableName;
                $result = self::$database->query($sql);
                // get row, only one there
                $row = $result->fetch_array();
                // error handling
                self::db_error_check_and_free_result($result);
                // return count 
                return array_shift($row);
            }

            // find by id
            static public function find_by_id(int $id) {
                // sql
                $sql = "SELECT * FROM " . static::$tableName . " ";
                $sql .= "WHERE id='" . self::db_escape($id) . "'";
                // get object array
                $obj_array = static::find_by_sql($sql);
                // check to see if $obj_array is empty
                if (!empty($obj_array)) {
                    // send back only one object, it will only have one
                    return array_shift($obj_array);
                } else {
                    return false;
                }
            }

            // runs validation on all possible columns in create, null properties excluded on update
            protected function validate($type = "update"){
                // reset error array for a clean slate
                $this->errors = [];
                // get class attributes, brings back an associative array
                $attributes = $this->attributes();
                // loop over and validate
                foreach ($attributes as $key => $value) {
                    if ($type == "create" && property_exists($this, $key)) {
                        // run validation on property value
                        $errors_array = val_validation($value, static::$validation_columns[$key]);
                        // check to see if there are any errors in the array, if yes merge it with the errors array
                        if (count($errors_array) > 0) {
                            // merge arrays
                            $this->errors = array_merge($this->errors, $errors_array);
                        }
                    } elseif (property_exists($this, $key) && !is_null($value)) {
                        // run validation on property value
                        $errors_array = val_validation($value, static::$validation_columns[$key]);
                        // check to see if there are any errors in the array, if yes merge it with the errors array
                        if (count($errors_array) > 0) {
                            // merge arrays
                            $this->errors = array_merge($this->errors, $errors_array);
                        }
                    }
                }

                // good practice to always return something, in most cases this will not be used
                return  $this->errors;
            }
               
            // Create a new instance/record
            protected function create() {
                // validate
                $this->validate("create");
                // if errors return false, don't continue
                if (!empty($this->errors)) { return false; }

                // get attributes
                $attributes = $this->sanitized_attributes();
                // sql
                $sql = "INSERT INTO " . static::$tableName . " (";
                $sql .= join(", ", array_keys($attributes));
                $sql .= ") VALUES ('";
                $sql .= join("', '", array_values($attributes));
                $sql .= "')";
                // query here because we go through a different process than the other queries about
                $result = self::$database->query($sql);
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } else {
                    // add the new id to the obj
                    $this->id = self::$database->insert_id;
                }
                // saving result so we can free up connection
                $tempResult = $result;
                //free up query result
                $result->free();
                // return true
                return $tempResult;
            }

            // update existing record
            protected function update() {
                // validate
                $this->validate();
                // if errors return false, don't continue
                if (!empty($this->errors)) { return false; }

                // get attributes
                $attributes = $this->sanitized_attributes();
                $attribute_pairs = [];
                foreach ($attributes as $key => $value) {
                    if (property_exists($this, $key) && !is_null($value)) {
                        $attribute_pairs = "{$key}='{$value}'";
                    }
                }
                // sql
                $sql = "";
                $sql .= "UPDATE " . static::$tableName . " SET ";
                $sql .= join(', ', $attribute_pairs);
                $sql .= " WHERE id='" . self::db_escape($this->id) . "'";
                $sql .= " LIMIT 1";
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check_and_free_result($result);
                // return result
                return $result;
            }

            // this allows you to add or update a record
            public function save(){
                if (isset($this->id)) {
                    return $this->update();
                } else {
                    return $this->create();
                }  
            }

            // delete record
            public function delete() {
                $sql = "DELETE FROM " . static::$tableName . " ";
                $sql .= " WHERE id='" . self::db_escape($this->id) . "'";
                $sql .= " LIMIT 1";
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check_and_free_result($result);
                // return result
                return $result;
            }

            // merge properties
            public function merge_attributes(array $args=[]) {
                foreach ($args as $key => $value) {
                    if (property_exists($this, $key) && !is_null($value)) {
                        $this->$key = $value;
                    }
                }
            }

            // create an associative array, key value pair from the static::$columns excluding id
            public function attributes() {
                $attributes = [];
                foreach (static::$columns as $column) {
                    // skip class column exclusions
                    if (in_array($column, static::$columnExclusions)) { continue; }
                    // construct attribute list with object values
                    $attributes[$column] = $this->$column;
                }
                // return array of attributes
                return $attributes;
            }

            // sanitizes attributes, for MySQL queries, and to protect against my SQL injection
            protected function sanitized_attributes() {
                $sanitized_array = [];
                foreach ($this->attributes() as $key => $value) {
                    $sanitized_array[$key] = self::db_escape($value);
                }
                return $sanitized_array;
            }

        // @ active record code end

        // @ API specific queries start
            // create an associative array, key value pair from the static::$columns excluding id
            public function api_attributes() {
                // empty array to be filled below
                $attributes = [];
                // column and API attributes merge arrays
                $apiAttributes_array = array_merge(static::$columns, static::$apiProperties);
                // loop over and make a key value pair array of api attributes
                foreach ($apiAttributes_array as $attribute) {
                    // construct attribute list with object values
                    $attributes[$attribute] = $this->$attribute;
                }
                // return array of attributes
                return $attributes;
            }

            // get api data plus extended data
            public function get_full_api_data() {
                // get api data
                $data_array['properties'] = $this->api_attributes();
                // if of the correct type get categories, tags, or labels
                if ($this->ctr() == 1 || $this->ctr() == 2 || $this->ctr() == 3 || $this->ctr() == 4) {
                    $data_array['categories'] = $this->get_obj_categories_tags_labels('categories');
                    $data_array['tags'] = $this->get_obj_categories_tags_labels('tags');
                    $data_array['labels'] = $this->get_obj_categories_tags_labels('labels');
                }
                // if of the correct type get all images
                if ($this->ctr() == 1 || $this->ctr() == 3) {
                    // set blank array, set below
                    $image_array = [];
                    // get image(s)
                    if ($this->ctr() == 1) {
                        $temp_array = $this->get_post_images();
                    } else {                                               
                        $temp_array = $this->get_user_image();
                    }
                    // loop over info to make new array
                    $image_array = obj_array_api_prep($temp_array);
                    // put images into the correct spot
                    $data_array['images'] = $image_array;
                }
                // return data
                return $data_array;
            }
        
            // get api data
            public function get_basic_api_data() {
                // get api data
                $data_array['properties'] = $this->api_attributes();
                // return data
                return $data_array;
            }

            // get data and turn it into json
            public function get_api_data($type = 'basic') {
                // check to see which api data to use
                if ($type == 'basic') {
                    $data_array = $this->get_basic_api_data();
                } else {
                    $data_array = $this->get_full_api_data();
                }
                // turn array into Jason
                $jsonData_array = json_encode($data_array);
                // return data
                return $jsonData_array;
            }
        // @ API specific queries end

        // @ class functionality methods start
            // stands for database escape, you sanitized data, and to protect against my SQL injection
            static protected function db_escape($db_field){
                return self::$database->escape_string($db_field);
            }

            // * collection_type_reference, located at: root/private/reference_information.php
            public function ctr() {
                return static::$collectionTypeReference;
            }

            // checks for database errors and frees up result, can return true
            static protected function db_error_check_and_free_result(object $result){
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } 
                // saving result so we can free up connection
                $tempResult = $result;
                //free up query result
                $result->free();
                // return result
                return $tempResult;
            }
        // @ class functionality methods end
    }
    
?>