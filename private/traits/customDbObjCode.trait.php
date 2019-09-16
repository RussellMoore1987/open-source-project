<?php
    trait CustomDbObjCode {
        // # possible extended info start
            // get all possible tags, // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
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
            // get all possible labels, // * collection_type_reference, located at: root/private/rules_docs/reference_information.php 
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
            // get all possible categories, // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
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
    }
    
?>