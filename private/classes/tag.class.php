<?php
    class Tag extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DabaseObject Class
            // Name of the table
            static protected $tablename = 'tags';
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'note', 'title'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/reference_information.php
            // get all post tags
            static protected $validation_columns = [
                'id' => [
                    'name' => 'Tag id',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min' => 0, // number min value
                    'max' => 10 // string length
                ],
                'note' => [
                    'name' => 'Tag Note',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ],
                'title' => [
                    'name' => 'Tag Title',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min' => 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes'
                ]
            ];

        // @ class database information end

        // @ class specific queries start
            // Find all the tags associated with the collection type parameter // * collection_type_reference, located at: root/private/reference_information.php
            static public function find_all_tags(int $type = 0) {
                $sql = "SELECT id, note, title, useTag FROM tags ";
                if (!($type > 4 && $type < 0)) {
                    $sql .= "WHERE useTag = {$type}";
                }  
                return self::find_by_sql($sql);
            }
        // @ class specific queries end

        // @ properties start
            // main properties
            public $title;
            public $note;
            public $useTag;

            //protected properties
            protected $id;

        // @ properties end

        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                $this->id = $args['id'] ?? NULL;
                $this->note = $args['note'] ?? NULL;
                $this->title = $args['title'] ?? NULL;
                $this->useTag = $args['useTag'] ?? NULL;
            }

            // methods
            // get the id
            public function get_id() {
                return $this->id;
            }

        // @ methods end
    }
    
?>