<?php
    trait DevToolGetter {
        // @ dev tool main getter requests start
            // # devTool_get_all_class_tables
            static public function devTool_get_all_class_tables($requestData) {
                // set up default variables
                $requestInfo = [];
                
                // get tables and corresponding data
                $classes = self::$classList;
                
                // loop through each class and set up options
                foreach ($classes as $class) {
                    // get options
                    $classOptions['tableName'] = $class::get_table_name();
                    $classOptions['seeder'] = $class::check_for_seeder();
                    $classOptions['sqlStructure'] = $class::check_sql_structure();
                    $classOptions['contextApi'] = $class::check_context_api();
                    $classOptions['restApi'] = $class::check_rest_api();
                    $classOptions['className'] = $class;
                    $classOptions['defaultCount'] = $class::$seederDefaultRecordCount ?? 10;

                    // Check to see if the table exist in the database
                    $dbName = DB_NAME;
                    $result = $class::run_sql("
                        SELECT * 
                            FROM information_schema.tables
                            WHERE table_schema = '{$dbName}' 
                                AND table_name = '{$classOptions['tableName']}'
                            LIMIT 1
                    ");
                    // check to see if we have anything
                    $tableThere = $result->num_rows === 0 ? false : true;
                    $classOptions['tableExists'] = $tableThere;

                    // if the table is not there do not show the additional information
                    if ($tableThere) {
                        // this assumes that we have a table, set the corresponding information
                        $classOptions['recordCount'] = $class::count_all();
                        // get column information
                        $result = $class::run_sql("SHOW COLUMNS FROM " . $classOptions['tableName']);
                        // turn results into an array
                        $tableStructure_array = [];
                        // loop through query
                        while ($record = $result->fetch_assoc()) {
                            $tableStructure_array[] = $record;    
                        }
                        // set to structure options
                        $classOptions['tableStructure'] = $tableStructure_array;
                        // get table size in MB
                        $result = $class::run_sql("
                            SELECT table_name AS `Table`, 
                            round(((data_length + index_length) / 1024 / 1024), 2) `Size in MB` 
                                FROM information_schema.TABLES 
                                WHERE table_schema = '{$dbName}'
                                    AND table_name = '{$classOptions['tableName']}';
                        ");
                        // loop through query
                        while ($record = $result->fetch_assoc()) {
                            $tableSize = $record['Size in MB'] . "MB";    
                        }
                        // Set option
                        $classOptions['tableSize'] = $tableSize ?? "";
                    } else {
                        // it's not there
                        $classOptions['recordCount'] = 0;
                        $classOptions['tableStructure'] = [];
                        $classOptions['tableSize'] = "0MB";
                    }
                    
                    // set options for single table
                    $requestInfo['tables'][] = $classOptions;
                }

                // return request info
                return $requestInfo;
            }

            // # devTool_get_all_non_class_tables
            static public function devTool_get_all_non_class_tables($requestData) {
                // set up default variables
                $requestInfo = [];
                
                // get class table names, so we can distinguish later which table come from classes and which ones do not
                $classes = self::$classList;
                $classTableNames = [];
                // loop through each class and set up options
                foreach ($classes as $class) {
                    // get options
                    $tableName = $class::get_table_name();
                    // set options
                    $classTableNames['tables'][] = $tableName;
                }

                // get all table names
                $tableNames = [];
                $dbName = DB_NAME;
                // get all tables
                $result = DatabaseObject::run_sql("
                    SELECT table_name FROM information_schema.tables
                    WHERE table_schema = '{$dbName}';
                ");
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    // set table names
                    $tableNames['tables'][] = $record['table_name'];    
                }

                // remove class tables
                if (isset($tableNames['tables']) && isset($classTableNames['tables'])) {
                    $tableNames = array_diff($tableNames['tables'], $classTableNames['tables']);
                }

                // check to see if we have SQL for the other tables
                // set default variable
                $otherTablesSql = [];
                // get class list for other tables SQL
                $otherTablesClassList = self::devTool_get_other_tables_class_list();
                // loop over each class and see if we can find any other SQL table names
                foreach ($otherTablesClassList as $className) {
                    // grab the other tables, if there are any, from each class
                    $otherTableNames = $className::get_sql_other_table_names();
                    // check to see if they have any other tables
                    if ($otherTableNames) {
                        // we have some tables combined them together with other ones
                        foreach ($otherTableNames as $otherTableName) {
                            $otherTablesSql[$otherTableName] = true;
                        }
                    }
                }

                // making sure to include all tables even if they are not constructed
                foreach ($otherTablesSql as $tableName => $value) {
                        $tableNames[] = $tableName;
                }
                // make sure the array does not have any duplicates
                $tableNames = array_unique($tableNames);

                // get non class table data
                foreach ($tableNames as $tableName) {
                    // check to see if the table is made
                    $result = DatabaseObject::run_sql("
                        SELECT * 
                            FROM information_schema.tables
                            WHERE table_schema = '{$dbName}' 
                                AND table_name = '{$tableName}'
                            LIMIT 1
                    ");
                    // check to see if we have anything
                    $tableThere = $result->num_rows === 0 ? false : true;
                    $tempTable_array[$tableName]['tableName'] = $tableName;
                    $tempTable_array[$tableName]['tableExists'] = $tableThere;
                    $tempTable_array[$tableName]['sql'] = $otherTablesSql[$tableName] ?? false;

                    // check to see if the the table is there
                    if ($tableThere) {
                        $result = DatabaseObject::run_sql("
                            SELECT COUNT(*) 
                                FROM {$tableName}
                        ");
                        // get row, only one there
                        $row = $result->fetch_array();
                        // get count 
                        $count = array_shift($row);
                        // set count option
                        $tempTable_array[$tableName]['count'] = $count;
                    } else {
                        $tempTable_array[$tableName]['count'] = 0;
                    }

                    // put table in array
                    $requestInfo['tables'][] =  $tempTable_array[$tableName];
                }

                // return request info
                return $requestInfo;
            }

            // # devTool_get_table_records
            static public function devTool_get_table_records($requestData) {
                // var_dump($request);
                // set up default variables
                $requestInfo = [];
                
                // return request info
                return $requestInfo;
            }

            // # devTool_find_record
            static public function devTool_find_record($requestData) {
                // var_dump($request);
                // set up default variables
                $requestInfo = [];

                // return request info
                return $requestInfo;
            }  
        // @ dev tool main getter requests end 

        // @ dev tool helper functions start
            
        // @ dev tool helper functions end

        
    }

    // TODO: self::devTool_session_check()
    // pass back error message
    // $requestInfo['errors'][] = 'Access to the devTool functions can only be accessed through the request access type of devTool.';
?>