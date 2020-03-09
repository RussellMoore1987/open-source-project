<?php
    // include api trait
    require_once("labelApi.trait.php");
    require_once("labelSql.trait.php");
    require_once("labelSeeder.trait.php");

    class Label extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DatabaseObject Class
            // Name of the table
            static protected $tableName = 'labels';
            // db columns
            static protected $columns = ['id', 'note', 'title', 'useLabel'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/rules_docs/reference_information.php
            static public $validation_columns = [
                'id' => [
                    'name' => 'Label id',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min' => 1, // number min value
                    'max' => 10 // string length
                ],
                'note' => [
                    'name' => 'Label Note',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ],
                'title' => [
                    'name' => 'Label Title',
                    'required' => true,
                    'type' => 'str', // type of string
                    'min' => 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes'
                ],
                'useLabel' => [
                    'name'=>'Label useLabel',
                    'required' => true,
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'num_max'=> 4, // number max value
                ]
            ];
        // @ class database information end

        // @ class traits start
            use LabelApi;
            use LabelSql;
            use LabelSeeder;
        // @ class traits end

        // @ class specific queries start
            // Find all the labels associated with the collection type parameter
            static public function find_all_labels(int $type = 0) {
                $sql = "SELECT id, note, title, useLabel FROM labels ";
                // we expect a number between one and four // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
                if ($type <= 4 && $type <= 1) {
                    $sql .= "WHERE useLabel = '{$type}'";
                }  
                return self::find_by_sql($sql);
            }
        // @ class specific queries end
    }
    
?>