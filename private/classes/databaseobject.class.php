<?php

    // TODO:
        // possibly store images after query
        // possibly function for extended data
            // main data
            // media content
            // class specific
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
        // Use the api trait
        use Api;
        // @ set up section start
            // * api_documentation located at: root/private/reference_information.php
            // set over arching API keys, use function to get the key
            static protected $mainApiKey = ''; // use get_main_api_key()
            // you can specify individual class API keys in the databaseObject class for post and get
            static protected $mainGetApiKey = ''; // use get_main_get_api_key()
            static protected $mainPostApiKey = ''; // use get_main_post_api_key()
            // class list, specify routs
            static protected $classList = [
                "Category" => ['categories', 'categories/dev'],
                // TODO: this class dose not exist right now
                // "Content" => ['content', 'content/dev'],
                "Label" => ['labels', 'labels/dev'],
                "MediaContent" => ['mediaContent', 'mediaContent/dev'],
                "Post" => ['posts', 'posts/dev'],
                "Tag" => ['tags', 'tags/dev'],
                "User" => ['users', 'users/dev']
            ]; // use get_class_list()
        // @ set up section end    
        // database connection
        static protected $database;
        // database information
        // table name
        static protected $tableName;
        // db columns
        static protected $columns = [];
        // values to exclude on normal updates, should always include id
        static protected $columnExclusions = ['id'];
        // name specific properties you wish to included in the API
        static protected $apiProperties = [];
        // default collection type reference 0 equals all possible // * collection_type_reference, located at: root/private/reference_information.php
        static protected $collectionTypeReference = 0;
        // db validation, // * validation_options located at: root/private/reference_information.php
        static protected $validation_columns = []; // use get_validation_columns()
        static protected $apiInfo = []; // use get_api_class_info()
        public $message = [];
        public $errors = [];
        
        // @ active record code start
             // set up local reference for the database
             static public function set_database(object $database) {
                self::$database = $database;
            }
            
            // # possible extended info start
                // get all possible tags, // * collection_type_reference, located at: root/private/reference_information.php
                    static public function get_possible_tags() {
                        // if not set get info
                        if (!isset(static::$possibleTags)) {
                            // get all possible tags 
                            $result = Tag::find_all_tags(static::$collectionTypeReference);
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
                            $result = Label::find_all_labels(static::$collectionTypeReference);
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
                            $result = Category::find_all_categories(static::$collectionTypeReference);
                            // create an id indexed array, this is a global function, store array in static property
                            static::$possibleCategories = get_key_value_array($result);
                        }
                        // return possibilities
                        return static::$possibleCategories;
                    }
                    // possible categories
                    static protected $possibleCategories;
            // # possible extended info end

            // #things only in reference to collection_type_reference start
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

                // delete connecting record
                public function delete_connection_records($tableName, $NameOfId, $id) {
                    $sql = "DELETE FROM {$tableName} ";
                    $sql .= "WHERE {$NameOfId}='{$id}' ";
                    // perform query
                    $result = self::$database->query($sql);
                    // error handling
                    $result = self::db_error_check($result);
                    // return result
                    return $result;
                }

                // make connecting record
                public function insert_connection_record($tableName, array $NameOfColumns_array, array $values_array) {
                    // set variables
                    $column1 = $NameOfColumns_array[0];
                    $column2 = $NameOfColumns_array[1];
                    $columnValue1 = $values_array[0];
                    $columnValue2 = $values_array[1];

                    // make sql
                    $sql = "INSERT INTO {$tableName} ({$column1}, {$column2}) ";
                    $sql .= "VALUES ({$columnValue1}, {$columnValue2}) ";
                    // perform query
                    $result = self::$database->query($sql);
                    // error handling
                    $result = self::db_error_check($result);
                    // return result
                    return $result;
                }
            // #things only in reference to collection_type_reference start
            
            // Helper function, object creator
            static protected function instantiate(array $record) {
                // load the object
                $object = new static($record);
                // return the object
                return $object;
            }

            // * sql_queries located at: root/private/reference_information.php
            // run sql
            static public function run_sql($sql) {
                // make a query
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check($result);
                // return an array of populated objects
                return  $result;   
            }
            
            // * sql_queries located at: root/private/reference_information.php
            // find by sql
            static public function find_by_sql($sql) {
                // make a query
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check($result);
                // turn results into an array of objects
                $object_array = [];
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    $object_array[] = static::instantiate($record);    
                }
                // return an array of populated objects
                return $object_array;   
            }

            // * sql_queries located at: root/private/reference_information.php
            // find all
            static public function find_all(array $sqlOptions = []) {
                // Submit the query to the find_where with no options
                return static::find_where($sqlOptions);
            }

            // * sql_queries located at: root/private/reference_information.php
            // find by id
            static public function find_by_id(int $id, $sqlOptions = []) {
                // get options
                // check for regular string coming in, set $sqlOptions['columnOptions']
                if (!is_array($sqlOptions)) {
                    // make it an array
                    $sqlOptionsTemp = explode(",", $sqlOptions);
                    // clean array
                    $sqlOptions = [];
                    // reset array
                    $sqlOptions['columnOptions'] = $sqlOptionsTemp;
                } else {
                    // check to see if the array is empty, or if it has columnOptions
                    $sqlOptions['columnOptions'] = $sqlOptions['columnOptions'] ?? ["*"];
                }

                // sql for id
                $idSql = "id = " . self::db_escape($id);

                // Prep the SQL options
                $sqlOptions['whereOptions'] = $idSql;

                // check to see if we got A result, if not send back false
                $result = static::find_where($sqlOptions)[0] ?? false;

                // should only get back one object, so select the one object from the array of objects 
                return $result;
            }
    
            // * sql_queries located at: root/private/reference_information.php
            // find where
            static public function find_where($sqlOptions = []) {
                // get options
                    // check to see if the array is empty
                    $columnOptions_array = $sqlOptions['columnOptions'] ?? ["*"];
                    $whereOptions_array = $sqlOptions['whereOptions'] ?? [];
                    $sortingOptions_array = $sqlOptions['sortingOptions'] ?? [];

                    // check for regular string coming in, set to whereOptions_array
                    if (!(is_array($sqlOptions) && (isset($sqlOptions['columnOptions']) || isset($sqlOptions['whereOptions']) || isset($sqlOptions['sortingOptions'])))) {
                        // set whereOptions_array
                        $whereOptions_array = $sqlOptions;
                    }

                    // make sure we're getting what we think were getting, need arrays, if strings passed and switched into arrays
                    if (!is_array($columnOptions_array)) { $columnOptions_array = explode(",", $columnOptions_array); }
                    if (!is_array($whereOptions_array)) { $whereOptions_array = explode(",", $whereOptions_array); }
                    if (!is_array($sortingOptions_array)) { $sortingOptions_array = explode(",", $sortingOptions_array); }

                // Begin building the SQL
                    // build SELECT
                    $sql = "SELECT " . implode(", ", $columnOptions_array) . " ";

                    // build FROM
                    $sql .= "FROM " . static::$tableName . " ";

                    // build WHERE, make sure to check whether it is an AND or an OR statement, AND by default OR has to be specified
                    for ($i=0; $i < count($whereOptions_array); $i++) { 
                        // add WHERE
                        if ($i == 0) { $sql .= "WHERE "; }
                        // set option
                        $whereConnector = "AND";
                        $whereOption = $whereOptions_array[$i];
                        // check to see if it is an OR or AND
                        if (strpos($whereOption, "::OR")) {
                            $whereConnector = "OR";
                            // remove the ::OR
                            $whereOption = str_replace("::OR", "", $whereOption);
                        }
                        // add WHERE option
                        $sql .= $whereOption;
                        // add AND or OR or end
                        if (!($i >= count($whereOptions_array) - 1)) { $sql .= " {$whereConnector} "; } else { $sql .= " "; }
                    }

                    // Add the sorting options if defined
                    foreach($sortingOptions_array as $option) {
                        $sql .= "{$option} ";
                    }

                // make the query
                $result = static::find_by_sql($sql);

                // return the data
                return $result;
            }

            // * sql_queries located at: root/private/reference_information.php
            // count all records
            static public function count_all($sqlOptions = []) {
                // check to see if the array is empty
                $whereOptions_array = $sqlOptions['whereOptions'] ?? [];

                // check for regular string coming in, set to whereOptions_array
                if ($sqlOptions && !(is_array($sqlOptions) && isset($sqlOptions['whereOptions']))) {
                    // set string to whereOptions_array
                    $whereOptions_array = $sqlOptions;
                }

                // make sure we're getting what we think were getting, need an array, if the string change to array
                if (!is_array($whereOptions_array)) { $whereOptions_array = explode(",", $whereOptions_array); }

                $sql = "SELECT COUNT(*) FROM " . static::$tableName . " ";
                // build WHERE, make sure to check whether it is an AND or an OR statement, AND by default OR has to be specified
                for ($i=0; $i < count($whereOptions_array); $i++) { 
                    // add WHERE
                    if ($i == 0) { $sql .= "WHERE "; }
                    // set option
                    $whereConnector = "AND";
                    $whereOption = $whereOptions_array[$i];
                    // check to see if it is an OR or AND
                    if (strpos($whereOption, "::OR")) {
                        $whereConnector = "OR";
                        // remove the ::OR
                        $whereOption = str_replace("::OR", "", $whereOption);
                    }
                    // add WHERE option
                    $sql .= $whereOption;
                    // add AND or OR or end
                    if (!($i >= count($whereOptions_array) - 1)) { $sql .= " {$whereConnector} "; } else { $sql .= " "; }
                }
                $result = self::$database->query($sql);
                // error handling
                self::db_error_check($result);
                // get row, only one there
                $row = $result->fetch_array();
                // return count 
                return array_shift($row);
            }

            // runs validation on all possible columns in create, null properties excluded on update
            protected function validate($type = "update"){
                // reset error array for a clean slate
                $this->errors = [];
                // get class attributes, brings back an associative array
                $attributes = $this->attributes($type);
                // get validation column info
                $validation_columns = static::$validation_columns;
                // loop over and validate
                foreach ($attributes as $key => $value) {
                    // if in create mode expect all values to be there
                    if ($type === "create" && property_exists($this, $key)) {
                        // run validation on property value
                        // echo $key . "%%%%%%%%%<br>";
                        $errors_array = val_validation($value, $validation_columns[$key]);
                        // check to see if there are any errors in the array, if yes merge it with the errors array
                        if (count($errors_array) > 0) {
                            // merge arrays
                            $this->errors = array_merge($this->errors, $errors_array);
                        }
                    // this assumes that were running an update
                    } elseif (property_exists($this, $key) && !is_null($value)) {
                        // run validation on property value
                        $errors_array = val_validation($value, $validation_columns[$key]);
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
              
            // * sql_queries located at: root/private/reference_information.php
            // Create a new instance/record
            protected function create() {
                // perform class specific pre-custom code if desired, pre queries and checks are possible including validation.
                $this->per_create();
                // validate all attributes
                $this->validate("create");
                // if errors return false, don't continue/add record
                if (!empty($this->errors)) { return false; }
                // TODO: after create/validation function goes here
                $this->after_create_validation();
                // get all attributes sanitized
                $attributes = $this->sanitized_attributes("create");
                // echo "just before up date create() ***********";
                // var_dump($attributes);  
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
                // perform class specific cleanup, post, user, tag, ect.
                $this->class_clean_up_update($this->attributes('create'));
                // return true
                return $result;
            }

            // * sql_queries located at: root/private/reference_information.php
            // update existing record
            protected function update() {
                // perform class specific pre-custom code if desired, pre queries and checks are possible including validation.
                $this->per_update();
                // validate, all attributes that we were given
                $this->validate();
                // if errors return false, don't continue
                if (!empty($this->errors)) { return false; }
                // Perform class specific after update validation has run
                $this->after_update_validation();
                // get attributes sanitized, we should only be given at this point what needs to be updated, all NULLs
                $attributes = $this->sanitized_attributes();
                // echo "just before up date ***********";
                // var_dump($attributes);   
                $attribute_pairs = [];
                $attributePairsToUpDate_array = [];
                // all validation was done previously
                foreach ($attributes as $key => $value) {
                    // double checking the trim, just in case
                    $value = trim($value);
                    $attribute_pairs[] = "{$key}='{$value}'";
                    // add to this array so we know exactly what was updated in a key value array
                    $attributePairsToUpDate_array[$key] = $value;
                }

                // sql
                $sql = "";
                $sql .= "UPDATE " . static::$tableName . " SET ";
                $sql .= join(', ', $attribute_pairs);
                $sql .= " WHERE id='" . self::db_escape($this->id) . "'";
                $sql .= " LIMIT 1";

                // make a query
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check($result);
                // perform class specific cleanup, post, user, tag, ect.
                $this->class_clean_up_update($attributePairsToUpDate_array);
                // return result
                return $result;
            }

            // class clean up update, after an update or creation is performed
            protected function class_clean_up_update(array $array = []){
                // write code in specific class if needed. Enables you to run cleanup information/queries based off of what was updated
            }

            // perform class specific pre-custom code for create
            protected function per_create(){
                // write code in specific class if needed. pre-custom code if desired, pre queries and checks are possible including validation.
            }

            // TODO: two new functions run after 1- creation/validation 2- update/validation
            protected function after_create_validation() {
                // Write code in specific class if needed, after validation has run this code is run
            }

            // perform class specific pre-custom code for update
            protected function per_update(){
                // write code in specific class if needed. pre-custom code if desired, pre queries and checks are possible including validation.
            }

            protected function after_update_validation() {
                // Write code in specific class if needed, after validation has run this code is run
            }

            // * sql_queries located at: root/private/reference_information.php
            // this allows you to add or update a record
            public function save(){
                
                if (isset($this->id) && !is_blank($this->id)) {
                    return $this->update();
                } else {
                    return $this->create();
                }  
            }

            // * sql_queries located at: root/private/reference_information.php
            // delete record
            public function delete() {
                $sql = "DELETE FROM " . static::$tableName . " ";
                $sql .= " WHERE id='" . self::db_escape($this->id) . "'";
                $sql .= " LIMIT 1";
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check($result);
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
            public function attributes($type = "update") {
                $attributes = [];
                foreach (static::$columns as $column) {
                    // skip class column exclusions
                    if (in_array($column, static::$columnExclusions)) { continue; }
                    // if in type = update mode do not add values with NULL
                    if ($type === "update") {
                        if ($this->$column === NULL) { continue; }
                    }
                    // construct attribute list with object values
                    $attributes[$column] = $this->$column;
                }
                // echo "attributes ***********";
                // var_dump($attributes); 
                // return array of attributes
                return $attributes;
            }

            // sanitizes attributes, for MySQL queries, and to protect against my SQL injection
            protected function sanitized_attributes($type = "update") {
                $sanitized_array = [];
                foreach ($this->attributes($type) as $key => $value) {
                    $sanitized_array[$key] = self::db_escape($value);
                }
                // echo "sanitized_attributes ***********";
                // var_dump($sanitized_array); 
                return $sanitized_array;
            }

        // @ active record code end

        // @ class functionality methods start
            // # stands for database escape, you sanitized data, and to protect against my SQL injection
            static protected function db_escape($db_field){
                return self::$database->escape_string($db_field);
            }

            // # ctr()
            // * collection_type_reference, located at: root/private/reference_information.php
            public function ctr() {
                return static::$collectionTypeReference;
            }

            // # checks for database errors and frees up result, can return true
            static protected function db_error_check($result){
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } 
                // return result
                return $result;
            }

            // # cleanFormArray should be on in constructor of all classes that extend the databaseObject class
            static public function cleanFormArray(array $array){
                // echo "just got info to clean up ***********";
                // var_dump($array);
                // get and store class specific validation columns to check if we need to clean up
                $cleanUpInfo_array = static::$validation_columns;
                // default array, fill with appropriate applicable form data
                $post_array = [];
                // loop through array and filter accordingly
                foreach ($array as $key => $value) {
                    // If I want to change it, I needed it, get a value or no go
                    if (isset($cleanUpInfo_array[$key]) && isset($cleanUpInfo_array[$key]['required'])) {
                        // check to see if the information is blank or null, if it is do nothing, if it is not put in the array
                        if (!is_blank($value)) {
                            $post_array[$key] = trim($value);
                        }
                    // pass through everything else do validation later on
                    } else {
                        // let it pass through
                        $post_array[$key] = trim($value);
                    }
                }
                return $post_array;
            }

            // # get class list
            static public function get_class_list() {
                return self::$classList;
            }

            // # get validation columns
            static public function get_validation_columns() {
                return static::$validation_columns;
            }

            // # get post api parameters
            static public function get_api_class_info() {
                return static::$apiInfo;
            }
            
            // # get class db columns
            static public function get_class_db_columns() {
                return static::$columns;
            }

            // # get main api key 
            static public function get_main_api_key() {
                return self::$mainApiKey;
            }
            // # get main get api key
            static public function get_main_get_api_key() {
                return self::$mainGetApiKey;
            }
            // # get main post api key
            static public function get_main_post_api_key() {
                return self::$mainPostApiKey;
            }
        // @ class functionality methods end

        // @ @nameOfTool methods start
            // # get api data plus extended data
            protected function get_full_api_data(array $codeData_array = []) {
                // var_dump($codeData_array['data']);
                // var_dump($codeData_array['propertyExclusions']);
                // var_dump($codeData_array['prepApiData_array']);
                // var_dump($codeData_array['routInfo_array']);
                // var_dump($codeData_array['routName']);
                // get api data
                $data_array = $this->api_attributes($codeData_array['routInfo_array'], $codeData_array['propertyExclusions']);
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
                return $data_array ?? [];
            }
        // @ @nameOfTool methods end
    }
    
?>