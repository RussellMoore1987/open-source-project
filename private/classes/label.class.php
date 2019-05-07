<?php
    class Label extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DatabaseObject Class
            // Name of the table
            static protected $tableName = 'labels';
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'note', 'title', 'useLabel'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static public $validation_columns = [
                'id' => [
                    'name' => 'Label id',
                    'required' => 'yes',
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
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min' => 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes'
                ],
                'useLabel' => [
                    'name'=>'Label useLabel',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1, // number min value
                    'num_max'=> 4, // number max value
                ]
            ];
            // * get_api_parameters, located at: root/private/reference_information.php
            static protected $getApiParameters = [
                // ...api/v1/posts/?id=22,33,5674,1,2,43,27,90,786 // ...api/v1/posts/?id=22
                'id'=>[
                    'refersTo' => ['id'],
                    'type' => ['int', 'list'],
                    'connection' => [
                        'int' => "=",
                        'list' => 'in'
                    ],
                    'description' => 'Gets labels by the label id or list of label ids',
                    'example' => ['id=1', 'id=1,2,3,4,5']
                ]
            ];

        // @ class database information end

        // @ class specific queries start
            // Find all the labels associated with the collection type parameter
            static public function find_all_labels(int $type = 0) {
                $sql = "SELECT id, note, title, useLabel FROM labels ";
                // we expect a number between one and four // * collection_type_reference, located at: root/private/reference_information.php
                if ($type <= 4 && $type <= 1) {
                    $sql .= "WHERE useLabel = '{$type}'";
                }  
                return self::find_by_sql($sql);
            }
        // @ class specific queries end

        // @ properties start
            // main properties
            public $note;
            public $title;
            public $useLabel;

            //protected properties
            protected $id;

        // @ properties end

        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // clean up form information coming in
                $args = self::cleanFormArray($args);
                $this->id = $args['id'] ?? NULL;
                $this->note = $args['note'] ?? NULL;
                $this->title = $args['title'] ?? NULL;
                $this->useLabel = $args['useLabel'] ?? NULL;
            }

            // methods
            // get the id
            public function get_id() {
                return $this->id;
            }

        // @ methods end
    }
    
?>