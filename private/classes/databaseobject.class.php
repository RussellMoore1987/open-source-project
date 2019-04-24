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
        // Use the api trait
        use Api;
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
        // The default global parameters
        static protected $apiParameters = [];
        public $message = [];
        public $errors = [];

        // @ active record code start
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

            // set up local reference for the database
            static public function set_database(object $database) {
                self::$database = $database;
            }

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
            

            // find by sql
            static public function find_by_sql($sql) {

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

            // find all
            static public function find_all(array $sqlOptions = []) {
                // TODO: Add the second count sql query
                // Begin building the sql
                $sql = "SELECT ";
                // Add all the columns to select if defined
                if (isset($sqlOptions['columnOptions'])) {
                    foreach($sqlOptions['columnOptions'] as $col) {
                        $sql .= self::db_escape($col);
                        // Add the comma if not at the end of the array
                        if ($col !== end($sqlOptions['columnOptions'])) {
                            $sql .= ", ";
                        } else {
                            $sql .= " ";
                        }
                    }
                // If no custom columns given then add the *
                } else {
                    $sql .= "* ";
                }
                // Add the rest of our SQL statement
                $sql .= "FROM " . static::$tablename;
                // Add the options if defined
                if (isset($sqlOptions['sortingOptions'])) {
                    foreach($sqlOptions['sortingOptions'] as $optKey => $optValue) {
                        $sql .= " " . self::db_escape($optKey) . " = " . self::db_escape($optValue);
                    }
                }
                return static::find_by_sql($sql);
            }

            // find by id
            // TODO: will not work. Needs refactoring
            // Accepts single id or array of ids as well as options for columns
            static public function find_by_id($id, array $sqlOptions) {
                // Begin building the sql
                $sql = "SELECT ";
                // Add all the columns to select if defined
                if (isset($sqlOptions['columnOptions'])) {
                    foreach($sqlOptions['columnOptions'] as $col) {
                        $sql .= self::db_escape($col);
                        // Add the comma if not at the end of the array
                        if ($col !== end($sqlOptions['columnOptions'])) {
                            $sql .= ", ";
                        } else {
                            $sql .= " ";
                        }
                    }
                // If no custom columns given then add the *
                } else {
                    $sql .= "* ";
                }
                // Add the rest of our SQL statement
                $sql .= "FROM " . static::$tablename . " ";
                // Add the WHERE clause if it is an array
                if(is_array($id)) {
                    $sql .= "WHERE id IN ( ";
                    foreach($id as $singleId) {
                        $sql .= self::db_escape($singleId);
                        // Add the end parentheses if at the end otherwise add a comma separator
                        if($singleId === end($id)) {
                            $sql .= " ) ";
                        } else {
                            $sql .= ", ";
                        }
                    }
                // Add the WHERE clause if not an array
                } else {
                    $sql .= "WHERE id='" . self::db_escape($id) . "'";
                }

                // TODO: Do we need to still return an object array?
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

            // find where
            static public function find_where(array $sqlOptions) {
                // Set the array to hold our return values
                $returnValues_array = [];

                // Begin building the SQL
                $sql = "SELECT ";
                $sql2 = "SELECT ";

                // Add all the columns to select if defined
                if (isset($sqlOptions['columnOptions'])) {
                    $sql2 .= "COUNT(*) ";

                    foreach($sqlOptions['columnOptions'] as $col) {
                        $sql .= self::db_escape($col);
                        // Add the comma if not at the end of the array
                        if ($col !== end($sqlOptions['columnOptions'])) {
                            $sql .= ", ";
                        } else {
                            $sql .= " ";
                        }
                    }
                // If no custom columns given then add the *
                } else {
                    $sql .= "* ";
                    $sql2 .= "COUNT(*) ";
                }

                // Add the rest of our SQL statement
                $sql .= "FROM " . static::$tableName;
                $sql2 .= "FROM " . static::$tableName;

                // Add the where clauses if defined
                if (isset($sqlOptions['whereOptions'])) {
                    // Begin the WHERE SQL
                    $sql .= " WHERE ";
                    $sql2 .= " WHERE ";
                    // Loop through all of the where clauses given
                    foreach($sqlOptions['whereOptions'] as $where) {

                        $sql .= self::db_escape($where['column']) . " ";
                        $sql .= self::db_escape($where['operator']) . " ";
                        $sql .= self::db_escape($where['value']) . " ";

                        $sql2 .= self::db_escape($where['column']) . " ";
                        $sql2 .= self::db_escape($where['operator']) . " ";
                        $sql2 .= self::db_escape($where['value']) . " ";

                        // Add the AND if not at the end of the array
                        if ($where !== end($sqlOptions['whereOptions'])) {
                            $sql .= "AND ";
                            $sql2 .= "AND ";
                        }
                    }
                }
                // Add the sorting options if defined
                if (isset($sqlOptions['sortingOptions'])) {
                    foreach($sqlOptions['sortingOptions'] as $optKey => $optValue) {
                        $sql .= " " . self::db_escape($optKey) . " = " . self::db_escape($optValue);
                        $sql2 .= " " . self::db_escape($optKey) . " = " . self::db_escape($optValue);
                    }
                }
                // Submit the SQL query(s)
                $returnValues_array['data'] = static::find_by_sql($sql);
                $returnValues_array['count'] = static::find_by_sql($sql2);
            }

            // count all records
            static public function count_all() {
                $sql = "SELECT COUNT(*) FROM " . static::$tableName;
                $result = self::$database->query($sql);
                // get row, only one there
                $row = $result->fetch_array();
                // error handling
                self::db_error_check($result);
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
               
            // Create a new instance/record
            protected function create() {
                // validate all attributes
                $this->validate("create");
                // if errors return false, dont continue/add record
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

            // update existing record
            protected function update() {
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

            // class clean up update
            protected function class_clean_up_update(array $array = []){
                // write code in specific class if needed. Enables you to run up cleanup information/queries based off of what was updated
            }

            // this allows you to add or update a record
            public function save(){
                
                if (isset($this->id) && !is_blank($this->id)) {
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

            // create an associative array, key value pair from the static::$sqlOptions['columnOptions'] excluding id
            public function attributes($type = "update") {
                $attributes = [];
                foreach (static::$sqlOptions['columnOptions'] as $column) {
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

            // Get the objects api info and return it
            protected function get_obj_api_info() {

            }

        // @ active record code end

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
            static protected function db_error_check($result){
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } 
                // return result
                return $result;
            }

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
        // @ class functionality methods end
    }
    
?>