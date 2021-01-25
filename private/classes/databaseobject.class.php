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
        // use the api trait
        use Api;
        // use main settings
        use MainSettings;
        // use custom code trait
        // * custom_code_spots located at: root/private/rules_docs/reference_information.php
        use CustomDbObjCode;
        // database connection
        static protected $database;
        // database information
        // table name
        static protected $tableName;
        // id name, specified in the particular class if it is different
        static protected $idName = "id";
        // db columns
        static protected $columns = [];
        // values to exclude on normal updates, should always include id
        static protected $columnExclusions = ['id'];
        // name specific properties you wish to included in the API
        static protected $apiProperties = [];
        // default collection type reference 0 equals all possible // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
        static protected $collectionTypeReference = 0;
        // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
        static protected $validation_columns = []; // use get_validation_columns()
        public $message = [];
        public $errors = [];

        // @ generic instructor code start
            public function __construct(array $args=[]) {
                // clean up form information coming in
                $args = self::cleanFormArray($args);
                foreach (static::$columns as $column) {
                    $this->{$column} = $args[$column] ?? NULL;
                }
                // run extended constructor for custom class needs
                $this->extended_constructor($args);
            }

            // extra constructor information, should be specific to a class
            public function extended_constructor(array $args=[]) {
                // put code in individual class
            }
        // @ generic instructor code end
        
        // @ active record code start
             // set up local reference for the database
             static public function set_database(object $database) {
                self::$database = $database;
            }
            
            // Helper function, object creator
            static protected function instantiate(array $record) {
                // load the object
                $object = new static($record);
                // return the object
                return $object;
            }

            // * sql_queries located at: root/private/rules_docs/reference_information.php
            // run sql
            static public function run_sql($sql) {
                // make a query
                $result = self::$database->query($sql);
                // error handling
                $result = self::db_error_check($result);
                // return an array of populated objects
                return  $result;   
            }
            
            // * sql_queries located at: root/private/rules_docs/reference_information.php
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

            // * sql_queries located at: root/private/rules_docs/reference_information.php
            // find all
            static public function find_all(array $sqlOptions = []) {
                // Submit the query to the find_where with no options
                return static::find_where($sqlOptions);
            }

            // * sql_queries located at: root/private/rules_docs/reference_information.php
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
                $idSql = static::$idName . " = " . self::db_escape($id);

                // Prep the SQL options
                $sqlOptions['whereOptions'] = $idSql;

                // check to see if we got A result, if not send back false
                $result = static::find_where($sqlOptions)[0] ?? false;

                // should only get back one object, so select the one object from the array of objects 
                return $result;
            }
    
            // * sql_queries located at: root/private/rules_docs/reference_information.php
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

            // * sql_queries located at: root/private/rules_docs/reference_information.php
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

            // TODO: need to update
            // * sql_queries located at: root/private/rules_docs/reference_information.php
            // count all records
            static public function find_max_id() {
                // find max id
                $sql = "SELECT MAX(" . static::$idName . ") FROM " . static::$tableName;
                
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
              
            // * sql_queries located at: root/private/rules_docs/reference_information.php
            // Create a new instance/record
            protected function create() {
                // perform class specific pre-custom code if desired, pre queries and checks are possible including validation.
                $this->per_create();
                // validate all attributes
                $this->validate("create");
                // if errors return false, don't continue/add record
                if (!empty($this->errors)) { return false; }
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

            // * sql_queries located at: root/private/rules_docs/reference_information.php
            // update existing record
            protected function update() {
                // perform class specific pre-custom code if desired, pre queries and checks are possible including validation.
                $this->per_update();
                // validate, all attributes that we were given
                $this->validate();
                // if errors return false, don't continue
                if (!empty($this->errors)) { return false; }

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

            // TODO:add to docs
            // class clean up update, after an update or creation is performed
            protected function class_clean_up_update(array $array = []){
                // write code in specific class if needed. Enables you to run cleanup information/queries based off of what was updated
            }

            // TODO:add to docs
            // perform class specific pre-custom code for create
            protected function per_create(){
                // write code in specific class if needed. pre-custom code if desired, pre queries and checks are possible including validation.
            }

            // TODO:add to docs
            // perform class specific pre-custom code for update
            protected function per_update(){
                // write code in specific class if needed. pre-custom code if desired, pre queries and checks are possible including validation.
            }

            // * sql_queries located at: root/private/rules_docs/reference_information.php
            // this allows you to add or update a record
            public function save(){
                if (isset($this->id) && !is_blank($this->id)) {
                    return $this->update();
                } else {
                    return $this->create();
                }  
            }

            // * sql_queries located at: root/private/rules_docs/reference_information.php
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
            static public function db_escape($db_field){
                return self::$database->escape_string($db_field);
            }

            // # ctr()
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
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

            // # check for rest api
            static public function check_rest_api() {
                // past back true or false
                return $passed = isset(static::$apiInfo);
            }

            // # check for context api
            static public function check_context_api() {
                // past back true or false
                return $passed = isset(static::$contextInfo);
            }
            
            // # check for seeder
            static public function check_for_seeder() {
                // past back true or false
                return $passed = method_exists(get_called_class(), 'seeder_setter');
            }

            // # check for sql structure
            static public function check_sql_structure() {
                // past back true or false
                return $passed = isset(static::$sqlStructure);
            }

            // # check for sql structure
            static public function get_sql_structure() {
                // past back sql structure or false
                return $sqlStructure = isset(static::$sqlStructure) ? static::$sqlStructure : false;
            }

            // # get class table name
            static public function get_table_name() {
                return static::$tableName;
            }

            // # get class id name
            static public function get_id_name() {
                // get the name of the ID for the class, default is id 
                return static::$idName;
            }

            // # get class list
            static public function get_class_list() {
                // gets class list from main settings
                return self::$classList;
            }

            // # get other tables class list
            // this is mostly used for SQL processing, DevTool
            static public function get_other_tables_class_list() {
                return self::$otherTablesClassList;
            }

            // // # get sql creation commands
            // // this is used for SQL processing, DevTool
            // static public function get_sql_creation_commands() {
            //     return self::$sqlCreationCommands;
            // }

            // # get sql insert commands
            // this is used for SQL processing, DevTool
            static public function get_sql_insert_commands() {
                return self::$sqlInsertCommands;
            }

            // # get authentication info
            static public function get_authentication_settings() {
                return self::$authenticationSettings;
            }

            // # get validation columns
            static public function get_validation_columns() {
                return static::$validation_columns;
            }

            // # get post api parameters
            static public function get_api_class_info() {
                return static::$apiInfo ?? [];
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

        // @ class dev tool helper functions start
            // # check for sql structure, other tables, give back table names
            static public function get_sql_other_table_names() {
                // set default variables
                $tableNames = [];

                // check to see if we even have other tables
                if (isset(static::$otherTables) && static::$otherTables) {
                    $otherTables = static::$otherTables;
                    // send back array of other table names
                    foreach ($otherTables as $tableName => $sql) {
                        $tableNames[] = $tableName;
                    }
                }

                // return data
                return $tableNames;
            }

             // # check and get sql structure for other tables
             static public function get_sql_other_tables() {
                // set default variables
                $tableSql = [];

                // check to see if we even have other tables
                if (isset(static::$otherTables) && static::$otherTables) {
                    $otherTables = static::$otherTables;
                    // send back array of other table sql
                    foreach ($otherTables as $sql) {
                        $tableSql[] = $sql;
                    }
                }

                // return data
                return $tableSql;
            }
        // @ class dev tool helper functions start

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