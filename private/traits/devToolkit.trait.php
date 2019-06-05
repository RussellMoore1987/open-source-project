<?php
// This trait contains all of the functions for the Developer Toolkit. Functions in this trait leverage properties in the DataBaseObjectClass

// ? Prepend methods and properties with DTK for devToolKit?
trait DevToolKit {

    // @ Begin protected properties
    static protected $devToolKit_CreateTableCode;
    static protected $fakerDataParameters = [];
    // @ End protected properties

    // @ Begin public methods
    static public function create_and_drop_table() {
        // Drop the table
        static::drop_table();

        // Add the table
        static::create_table();
    }

    static public function add_data($numRecords) {
        // TODO: Add code for add_data
    }

    static public function select_records($id=NULL) {
        // Build the query for selecting the records
        if($record == NULL) {
            $sql = "SELECT * FROM {static::$tablename}";
        } else {
            $sql .= "SELECT * FROM {static::$tablename} WHERE id = {$record}";
        }

        // Execute the query
        $result = self::$database->query($sql);

        // Check for errors
        $result = self::db_error_check($result);

        // Return the result
        return $result;
    }

    static public function insert_records($records) {
        // TODO: Add code for insert records
    }

    static public function edit_record($id) {
        // TODO: Add code for edit_record
    }

    static public function delete_record($id) {
        // TODO: Add code for delete_record
    }
    // @ End public methods


    // @ Begin private methods
    static private function drop_table() {
        // Build the sql query
        $sql = "DROP TABLE IF EXISTS {static::$tablename}";

        // Execute the query
        $result = self::$database->query($sql);

        // Check for errors
        $result = self::db_error_check($result);

        // Return the result
        return $result;
    }

    // DEBUG: create_table method needs some debugging/testing
    static private function create_table() {
        // Build the query
        $sql = "CREATE TABLE IF NOT EXISTS {static::$tablename} ( ";

        // Get the column data from the class to create sql query
        foreach(static::$tableTemplate['columns'] as $colName => $colData) {
            $sql .= "{$colName} {$colData['type']} ";

            // Add the column attributes if there are any
            if(!empty($colData['attributes'])) {
                foreach($colData['attributes'] as $attribute) {
                    $sql .= "{$attribute} ";
                }
            }
            // Add the comma if not at the end of the array
            if($colData != end(static::$tableTemplate['columns'])) {
                $sql .= ", ";
            }
        }

        // if there are foreign keys in the table then add them
        if(!empty(static::$tableTemplate['foreignkeys'])) {
            // Add the comma in preparation for more sql
            $sql .= ", ";

            // Get the foreign key data from the class to create the sql query
            foreach(static::$tableTemplate['foreignkeys'] as $key) {
                $sql .= "FOREIGN KEY ({$key['key']}) REFERENCES {$key['reference']}";

                // If not at the end of the array add the comma
                if($key != end($key)) {
                    $sql .= ", ";

                // Else add the space
                } else {
                    $sql .= " ";
                }
            }
        }

        // Add the database engine for the table
        $sql .= ") ENGINE={static::$tableTemplate['engine']}";

        // Execute the query
        $result = self::$database->query($sql);

        // Check for errors
        $result = self::db_error_check($result);

        // Return the result
        return $result;
    }
    // @ End private methods
}

?>