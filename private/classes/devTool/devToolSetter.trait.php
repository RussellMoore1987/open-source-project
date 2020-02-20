<?php
    trait DevToolSetter {
        // TODO: Look over all queries, in setter and getter trait
        // @ dev tool main setter requests start

            // # devTool_drop_all_tables
            static public function devTool_drop_all_tables($requestData) {
                // default variables
                $replyInfo = [];

                // var_dump($requestData);
                // get all tables
                $dbName = DB_NAME;
                $result = DatabaseObject::run_sql("
                    SELECT concat('DROP TABLE IF EXISTS `', table_name, '`;') AS dropStatement
                        FROM information_schema.tables
                        WHERE table_schema = '{$dbName}';
                ");
                // make default variable
                $sqlDropStatements = [];
                // loop through query
                while ($record = $result->fetch_assoc()) {
                    // var_dump($record['dropStatement']);
                    $sqlDropStatements[] = $record['dropStatement'];
                }

                // check to see if we have any tables to drop
                if ($sqlDropStatements) {
                    // drop all tables
                    self::devTool_sql_runner($sqlDropStatements);
                    // set info
                    $replyInfo = ['message' => 'All tables were successfully dropped.'];
                } else {
                    // set info
                    $replyInfo = ['message' => 'There were no tables in the database, could not perform desired action.'];
                }
                
                // return request info
                return $replyInfo;
            }

            // # devTool_create_all_tables
            static public function devTool_create_all_tables($requestData) {
                // default variables
                $replyInfo = [];
                $sqlStatements = [];

                // get SQL for class tables 1st
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
                    // check to see if they have any other tables
                    if ($otherTableSqlStatements) {
                        // we have some tables combined them together with other ones
                        foreach ($otherTableSqlStatements as $otherTableSql) {
                            $sqlStatements[] = $otherTableSql;
                        }
                    }
                }

                // var_dump($sqlStatements);

                // check to see if we have any tables to create
                if ($sqlStatements) {
                    // drop all tables
                    self::devTool_sql_runner($sqlStatements);
                    // set info
                    $replyInfo = ['message' => 'All tables were successfully created.'];
                } else {
                    // set info
                    $replyInfo = ['message' => 'There is no tables SQL structures, could not perform desired action.'];
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