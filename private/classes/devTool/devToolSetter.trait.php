<?php
    trait DevToolSetter {
        // @ dev tool main setter requests start
            // # devTool_drop_all_tables
            static public function devTool_drop_all_tables($requestData) {
                // default variables
                $replyInfo = [];

                // get all tables and drop statements
                $dbName = DB_NAME;
                $result = DatabaseObject::run_sql("
                    SELECT concat('DROP TABLE IF EXISTS `', table_name, '`;') AS dropStatement
                        FROM information_schema.tables
                        WHERE table_schema = '{$dbName}';
                ");
                // make default variable for sql drop commands
                $sqlDropStatements = [];
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    $sqlDropStatements[] = $record['dropStatement'];
                }

                // check to see if we have any tables to drop
                if ($sqlDropStatements) {
                    // drop all tables
                    self::devTool_sql_runner($sqlDropStatements);
                    // set message
                    $replyInfo['message'][] = 'All tables were successfully dropped.';
                } else {
                    // set errors
                    $replyInfo['errors'][] = 'There were no tables in the database, could not perform desired action.';
                }
                
                // return request info
                return $replyInfo;
            }

            // # devTool_drop_class_table
            static public function devTool_drop_class_table(string $requestData) {
                // default variables
                $replyInfo = [];
                $className = $requestData;

                // check to make sure it is a class and that it is in the $classList array
                if (class_exists($className) && in_array(ucfirst($className), self::$classList)) {
                    // get table name
                    $tableName = $className::get_table_name();
                    // prepare SQL statements
                    $sql = ["DROP TABLE IF EXISTS {$tableName}"];
                    // drop table
                    self::devTool_sql_runner($sql);
                    // set message
                    $replyInfo['message'][] = "The \"{$className}\" class table was successfully dropped.";
                } else {
                    // set errors
                    $replyInfo['errors'][] = "There is no class with the name of \"{$className}\" that has database functionality. No action was performed.";
                }

                // return request info
                return $replyInfo;
            }

            // # devTool_create_all_tables
            static public function devTool_create_all_tables($requestData) {
                // default variables
                $replyInfo = [];
                $sqlStatements = [];

                // get SQL for class tables first
                $classList = self::$classList;
                // check to see if they have SQL
                foreach ($classList as $className) {
                    // get SQL
                    $sqlStructure = $className::get_sql_structure();
                    // check to see if we got it
                    if ($sqlStructure) {
                        $sqlStatements[] = $sqlStructure;
                    }
                }

                // check to see if we have other SQL tables
                $otherTablesClassList = self::devTool_get_other_tables_class_list();
                // check to see if they have SQL
                foreach ($otherTablesClassList as $className) {
                    // grab the other tables, if there are any, from each class
                    $otherTableSqlStatements = $className::get_sql_other_tables();
                    // check to see if they have any "other tables"
                    if ($otherTableSqlStatements) {
                        // we have some tables combined them together with other ones
                        foreach ($otherTableSqlStatements as $otherTableSql) {
                            $sqlStatements[] = $otherTableSql;
                        }
                    }
                }

                // check to see if we have any tables to create
                if ($sqlStatements) {
                    // create all tables
                    self::devTool_sql_runner($sqlStatements);
                    // set message
                    $replyInfo['message'][] = 'All tables were successfully created.';
                } else {
                    // set errors
                    $replyInfo['errors'][] = 'There is no SQL structures, could not perform desired action.';
                }

                // return request info
                return $replyInfo;
            }

            // # devTool_create_class_table
            static public function devTool_create_class_table(string $requestData) {
                // default variables
                $replyInfo = [];
                $className = $requestData;

                // check to make sure it is a class and that it is in the $classList array
                if (class_exists($className) && in_array(ucfirst($className), self::$classList)) {
                    // check to see if they have SQL
                    $sqlStructure = $className::get_sql_structure();
                    // check to see if we got it
                    if ($sqlStructure) {
                        $sqlStatements[] = $sqlStructure;
                    }
                    
                    // check to see if we have a table to create
                    if ($sqlStatements) {
                        // create table
                        self::devTool_sql_runner($sqlStatements);
                        // set message
                        $replyInfo['message'][] = "The \"{$className}\" class table was successfully created.";
                    } else {
                        // set errors
                        $replyInfo['errors'][] = "There is no SQL structure in the \"{$className}\" class, could not perform desired action.";
                    }
                } else {
                    // set errors
                    $replyInfo['errors'][] = "The \"{$className}\" class dose not exist, could not perform desired action.";
                }

                // return request info
                return $replyInfo;
            }

            // # devTool_insert_records_all
            static public function devTool_insert_records_all($requestData) {
                // set default variables
                $replyInfo = [];
                $seederClassList = [];
                // get classList
                $classList = self::$classList; 

                // check to see if they have seeder code available
                foreach ($classList as $className) {
                    // check and build a new array of classes that have the seeder functionality
                    if (method_exists($className, "seeder_setter")) {
                        $seederClassList[] = $className;
                    }
                }

                // check to see if we have any classes with seeder capabilities
                if ($seederClassList) {
                    // run insert statements for all classes that have seeder capabilities
                    foreach ($seederClassList as $className) {
                        $insertResult = self::devTool_insert_seeder_data(['className' => $className]);
                        // check for errors and for a success message
                        $replyInfo = merge_data_arrays($replyInfo, $insertResult);
                    }
                } else {
                    $replyInfo['errors'][] = 'There were no classes with seeder capabilities. No action was performed.';
                }

                // check to see if we need to do extra functionality
                // get custom SQL insert commands
                $sqlInsertCommands = self::$sqlInsertCommands;
                // check to see if we need to run any other SQL insert commands
                if ($sqlInsertCommands) {
                    // we got some, make sure the real classes and real commands, then run them
                    foreach ($sqlInsertCommands as $class => $command) {
                        // check to see if the class and the command exist
                        if (class_exists($class) && method_exists($class, $command)) {
                            // run sql command
                            $commandResult = $class::$command();
                            // check for errors and for a success message
                            $replyInfo = merge_data_arrays($replyInfo, $commandResult);
                        } else {
                            $replyInfo['errors'][] = "the \"{$class}\" class does not exist or does not have the \"{$command}\" command/method and it.";
                        }
                    }
                }

                // return request info
                return $replyInfo;
             } 

            // # devTool_insert_seeder_data
            static public function devTool_insert_seeder_data(array $requestData) {
                // set default variables
                $replyInfo = [];
                // check to see if we got information, expecting a class name and possible seeder count
                $className = $requestData['className'] ?? "";
                $seederCount_temp = isset($requestData['seederCount']) ? (int) $requestData['seederCount'] : 0;
                // check to see if we should set the seeder count
                if ($seederCount_temp > 0) {
                    $seederCount = $seederCount_temp;
                }
                // check to make sure it is a class and that it is in the $classList array
                if (class_exists($className) && in_array(ucfirst($className), self::$classList)) {
                    // check to see if the class has seeder_setter()
                    if (method_exists($className, "seeder_setter")) {
                        // create seeder
                        $Seeder = new Seeder();
                        // get seeder data
                        $counter = 0;
                        // get class $seederCount, or seederDefaultRecordCount, if not there set the default to 10 records
                        $seederCount = $seederCount ?? $className::$seederDefaultRecordCount ?? 10;
                        // I put this in earlier to see if I could fix a problem may not need this anymore
                        $seederAddToSave = $className::$seederAddToSave ?? "";
                        // loop over seeder count and create records for the giving class
                        for ($i=0; $i < $seederCount ; $i++) { 
                            // get seeder info
                            $dataArray = $className::seeder_setter($Seeder);
                            // now run and do an insert statement with record
                            $Object = new $className($dataArray);
                            $Object->save($seederAddToSave);
                            // get class ID name
                            $idName = $className::get_id_name();
                            // check to see if we have any errors
                            if ($Object->errors) {
                                foreach ($Object->errors as $error) {
                                    $replyInfo['errors'][] = $error;
                                }
                            }
                            // check to see if we have an ID, increment counter if yes
                            if ($Object->$idName) {
                                $counter++;
                            } else {
                                // some objects are not updating, not sure why.
                                // var_dump($Object);
                            }
                        }
                        // if we don't have any errors send back the success message
                        if (!isset($replyInfo['errors'])) {
                            $replyInfo['message'][] = "{$counter} records were added from the \"{$className}\" class";
                        }
                    } else {
                        $replyInfo['errors'][] = "Unfortunately the class table you are trying to insert data into does not have needed functionality. No action was taken on the class with the name of \"{$className}\"";  
                    }
                } else {
                    $replyInfo['errors'][] = "Unfortunately the class table you are trying to insert data into does not exist or does not have needed functionality. No action was taken on the class with the name of \"{$className}\"";  
                }

                // return request info
                return $replyInfo;
            } 


        // @ dev tool main setter requests end 

        // @ dev tool helper functions start
            // Function to disable/enable foreign key constraints for table creation and drop
            static private function toggle_foreign_key_checks($toggle) {
                // Toggle the key checks OFF
                if ($toggle === false) {
                    $sql = "SET FOREIGN_KEY_CHECKS = 0";
                    DatabaseObject::run_sql($sql);

                // Toggle the key checks ON
                } else {
                    $sql = "SET FOREIGN_KEY_CHECKS = 1";
                    DatabaseObject::run_sql($sql);
                }
            }

            // runs SQL statements
            static private function devTool_sql_runner(array $sqlStatements = []) {
               // toggle foreign keys off
               self::toggle_foreign_key_checks(false);
               // loop over all SQL commands
               foreach ($sqlStatements as $sqlStatement) {
                   DatabaseObject::run_sql($sqlStatement);
               }
               // toggle foreign keys on
               self::toggle_foreign_key_checks(true);
            }
        // @ dev tool helper functions end
    }
?>