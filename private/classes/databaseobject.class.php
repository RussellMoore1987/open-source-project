<?php
    class DatabaseObject {
        // database connection
        static protected $database;
        static protected $tableName;
        static protected $columns = [];
        public $errors = [];

        // @ ----- START OF ACTIVE RECORD CODE -----
            // todo: ???? ***list of ids or lots of query's or list of post ids then an OR JOIN?????
            // possible extended info, list loop method
            // todo: ?????? type = 0 = all tags, 1 = post, 2 = media content, 3 = users, 4 = content
            // get all possible tags, type = 0 = all tags, 1 = post, 2 = media content, 3 = users, 4 = content
                static public function get_possible_tags($type = 0) {
                    // if not set get info
                    if (!isset(static::$possibleTags)) {
                        // get all possible tags 
                        $result = Tag::$find_all_tags($type);
                        // create an id indexed array, this is a global function, store array in static property
                        static::$possibleTags = get_key_value_array($result);
                    }
                    // return possibilities
                    return static::$possibleTags;
                }
                // possible tags
                static protected $possibleTags;
            // get all possible labels, type = 0 = all tags, 1 = post, 2 = media content, 3 = users, 4 = content
                static public function get_possible_labels($type = 0) {
                    // if not set get info
                    if (!isset(static::$possibleLabels)) {
                        // get all possible Labels 
                        $result = Label::$find_all_labels($type);
                        // create an id indexed array, this is a global function, store array in static property
                        static::$possibleLabels = get_key_value_array($result);
                    }
                    // return possibilities
                    return static::$possibleLabels;
                }
                // possible labels
                static protected $possibleLabels;
            // get all possible categories, type = 0 = all tags, 1 = post, 2 = media content, 3 = users, 4 = content
                static public function get_possible_categories($type = 0) {
                    // if not set get info
                    if (!isset(static::$possibleCategories)) {
                        // get all possible categories
                        $result = Category::$find_all_categories($type);
                        // create an id indexed array, this is a global function, store array in static property
                        static::$possibleCategories = get_key_value_array($result);
                    }
                    // return possibilities
                    return static::$possibleCategories;
                }
            // possible categories
            static protected $possibleCategories;

            // set up local reference for the database
            static public function set_database($database) {
                self::$database = $database;
            }

            // Helper function, object creator
            static protected function instantiate($record) {
                // load the object
                $object = new static($record);
                // return the object
                return $object;
            }

            // find by sql
            static public function find_by_sql($sql) {
                $result = self::$database->query($sql);
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } 
                // turn results into an array of objects
                $object_array = [];
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    $object_array[] = static::instantiate($record);    
                }
                //free up query result
                $result->free();
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
                // error handling
                if (!$result) {
                    exit("Query Failed!!!: " . self::$database->error);
                } 
                // get row, only one there
                $row = $result->fetch_array();
                //free up query result
                $result->free();
                // return count 
                return array_shift($row);
            }

            // find by id
            static public function find_by_id($id) {
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

            protected function validate(){
                // reset error array for a clean slate
                $this->errors = [];
                // get class attributes, brings back an associative array
                $attributes = $this->attributes();
                // loop over and validate
                foreach ($attributes as $key => $value) {
                    if (property_exists($this, $key) && !is_null($value)) {
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
                $this->validate();
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
                // return true
                return $result;
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
                // return true
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
                return $result;
            }

            // merge properties
            public function merge_attributes($args=[]) {
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
                    // skip id
                    if ($column == 'id') { continue; }
                    // construct attribute list with object values
                    $attributes = [$column] = $this->$column;
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

            // stands for database escape, you sanitized data, and to protect against my SQL injection
            static protected function db_escape($db_field){
                return self::$database->escape_string($db_field);
            }
        // @ ----- END OF ACTIVE RECORD CODE -----
    }
    
?>