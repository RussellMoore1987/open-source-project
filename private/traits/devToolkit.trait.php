<?php
// This trait contains all of the functions for the Developer Toolkit. Functions in this trait leverage properties in the DataBaseObjectClass

// ? Prepend methods and properties with DTK for devToolKit 
trait DevToolKit {

    // @ Begin protected properties
    static protected $devToolKit_CreateTableCode;
    static protected $fakerDataParameters = [];
    // @ End protected properties

    // @ Begin public methods
    static public function add_and_drop_table() {
        // TODO: Add code for add_and_drop_table
    }

    static public function add_data($numRecords) {
        // TODO: Add code for add_data
    }

    static public function select_records($id) {
        // TODO: Add code for select_records
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
        // TODO: Add code for drop_table
    }

    static private function create_table() {
        // TODO: Add code for create_table
    }
    // @ End private methods
}

?>