<?php
    class Category extends DatabaseObject {
        // @ class database information start
            // Class specific properties. Overwritten from DatabaseObject Class
            // table name
            static protected $tableName = "categories";
            // db columns, if need to exclude particular column excluded in the database object attributes()
            static protected $columns = ['id', 'note', 'subCatId', 'title', 'useCat'];
            // values to exclude on normal updates, should always include id
            static protected $columnExclusions = ['id'];
            // name specific properties you wish to included in the API
            static protected $apiProperties = [];
            // * collection_type_reference, located at: root/private/reference_information.php
            static protected $collectionTypeReference = 0;
            // db validation, // * validation_options located at: root/private/reference_information.php
            static protected $validation_columns = [
                'id'=>[
                    'name'=>'Category id',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'max' => 10 // string length
                ], 
                'note' => [
                    'name'=>'Category Note',
                    'type' => 'str', // type of string
                    'max' => 255, // string length
                    'html' => 'no'
                ], 
                'subCatId'=>[
                    'name'=>'Category subCatId',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'max' => 10 // string length
                ],
                'title' => [
                    'name'=>'Post Title',
                    'required' => 'yes',
                    'type' => 'str', // type of string
                    'min'=> 2, // string length
                    'max' => 50, // string length
                    'html' => 'yes' // mostly just to allow special characters like () []
                ],
                'useCat'=>[
                    'name'=>'Category useCat',
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 0, // number min value
                    'num_max'=> 4, // number max value
                ]
            ];
        // @ class database information end
        
        // @ class specific queries start
            // Find all the categories associated with the collection type parameter
            static public function find_all_categories(int $type = 0) {
                $sql = "SELECT id, note, subCatId, title, useCat FROM categories ";
                // we expect a number between one and four // * collection_type_reference, located at: root/private/reference_information.php
                if ($type <= 4 && $type <= 1) {
                    $sql .= "WHERE useCat = '{$type}'";
                }
                return self::find_by_sql($sql);    
            }
        // @ class specific queries end

        // @ properties start
            // main properties
                public $note;
                public $subCatId;
                public $title;
                public $useCat;
            // protected properties, read only, use getters, they are sent by functions/methods when needed 
                protected $id; // get_id()
        // @ properties end
        
        // @ methods start
            // constructor method, type declaration of array
            public function __construct(array $args=[]) {
                // Set up properties
                $this->id = $args['id'] ?? NULL;    
                $this->note = $args['note'] ?? NULL;   
                $this->subCatId = $args['subCatId'] ?? NULL;  
                $this->title = $args['title'] ?? NULL;
                $this->useCat = $args['useCat'] ?? NULL;    
            }

            // methods
            // get id property
            public function get_id() {
                return $this->id;
            }
        // @ methods end
    }
?>