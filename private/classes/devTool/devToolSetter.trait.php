<?php
    trait DevToolSetter {
        // @ dev tool main setter requests start

            // # devTool_drop_all_tables
            static public function devTool_drop_all_tables($requestData) {
                // var_dump($requestData);
                // set up default variables
                $requestInfo = [];
                
                // get tables and corresponding data
                $classes = self::$classList;

                // EXEC sp_MSforeachtable @command1 = "DROP TABLE ?"
                
                // loop through each class and set up options
                foreach ($classes as $class) {
                    // get options
                    $classOptions['tableName'] = $class::get_table_name();
                    $classOptions['fakerData'] = $class::check_for_seeder();
                    $classOptions['sqlStructure'] = $class::check_sql_structure();
                    $classOptions['contextApi'] = $class::check_context_api();
                    $classOptions['restApi'] = $class::check_rest_api();
                    $classOptions['recordCount'] = $class::count_all();
                    // get column information
                    $result = $class::run_sql("SHOW COLUMNS FROM " . $classOptions['tableName']);
                    // turn results into an array
                    $tableStructure_array = [];
                    // loop through query
                    while ($record = $result->fetch_assoc()) {
                        $tableStructure_array[] = $record;    
                    }
                    $classOptions['tableStructure'] = $tableStructure_array ?? [];
                    // get table size in MB
                    $result = $class::run_sql("
                        SELECT table_name AS `Table`, 
                        round(((data_length + index_length) / 1024 / 1024), 2) `Size in MB` 
                            FROM information_schema.TABLES 
                            WHERE table_schema = 'developmentdb'
                                AND table_name = '{$classOptions['tableName']}';
                    ");
                    // loop through query
                    while ($record = $result->fetch_assoc()) {
                        $tableSize = $record['Size in MB'] . "MB";    
                    }
                    $classOptions['tableSize'] = $tableSize ?? "";
                    // set options
                    $requestInfo['tables'][] = $classOptions;
                }

                // return request info
                return $requestInfo;
            }

        // @ dev tool main setter requests end 

        // @ dev tool helper functions start
            // Function to disable/enable foreign key constraints for table creation and drop
            private function toggle_foreign_key_checks($toggle) {
                // Toggle the key checks OFF
                if ($toggle === false) {
                    $sql = "SET FOREIGN_KEY_CHECKS = 0";
                    $this->mysqli->query($sql);

                // Toggle the key checks ON
                } else {
                    $sql = "SET FOREIGN_KEY_CHECKS = 1";
                    $this->mysqli->query($sql);
                }
            }
        // @ dev tool helper functions end
    }
?>