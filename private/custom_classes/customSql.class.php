<?php
    // for custom SQL
    class CustomSql {
        // @ creation methods start
            // on create all tables this function will run 
            static public function insert() {
                // set default variables
                $replyInfo = [];
                // this is a custom class built specifically for the system 
                // add Cartesian product insert for normal tables, this means the connection can be anything there is no specificity between tables
                $className = 'post';
                $tableNames = ['MediaContent' => 'posts_to_media_content'];
                $foreignKeyNames = ['postId','mediaContentId'];
                $commandResults = CustomSql::cartesian_insert($className, $tableNames, $foreignKeyNames);
                // check for errors/messages
                $replyInfo = merge_data_arrays($replyInfo, $commandResults);

                // add unique Cartesian product insert for unique tables, this means the connection between tables has some specific criteria. In our case it is the collection type reference.
                // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
                $uniqueClassName = 'post';
                $uniqueTableNames = ['Category::useCat::1' => 'posts_to_categories', 'Label::useLabel::1' => 'posts_to_labels', 'Tag::useTag::1' => 'posts_to_tags'];
                $uniqueForeignKeyNames = ['postId', 'categoryId', 'labelId', 'tagId'];
                $uniqueCommandResults = CustomSql::unique_cartesian_insert($uniqueClassName, $uniqueTableNames, $uniqueForeignKeyNames);
                // check for errors/messages
                $replyInfo = merge_data_arrays($replyInfo, $uniqueCommandResults);

                // return information
                return  $replyInfo;
            }
        // @ creation methods end

        // @ creation helper methods start
            // # generic Cartesian product for connecting tables
            static protected function cartesian_insert(string $className, array $connectionTables = [], array $foreignKeyNames = []) {
                // default variables
                $replyInfo = [];

                // make sure were getting what we really think you're getting
                if (class_exists($className) && in_array(ucfirst($className), DatabaseObject::get_class_list())) {
                    // get count all
                    $classCount = $className::count_all();
                    // check to see if any foreign keys were passed in
                    // get class ID
                    $foreignKeyNames[0] = $foreignKeyNames[0] ?? $className::get_id_name();
                    // check to make sure Is over 1
                    if ($classCount > 1) {
                        // split it based off of size
                        $data = self::offset_count($classCount);
                        // get offset the for main table
                        $times = (int) $data['times'];
                        $offset = (int) $data['offset'];
                        // counter for foreign keys
                        $foreignKeyCounter = 1;
                        // look over each table to make connections
                        foreach ($connectionTables as $otherClassName => $connectionTableName) {
                            // check to see if any foreign keys were passed in
                            // get class ID
                            $foreignKeyNames[1] = $foreignKeyNames[$foreignKeyCounter] ?? $otherClassName::get_id_name();
                            // get count all from other table
                            $otherTableCount = $otherClassName::count_all();
                            $otherTableCount = (int) $otherTableCount;
                            // get other table offset
                            $otherTableOffset = (int) floor($otherTableCount / $times);
                            // set all main arrays to zero
                            $mainIds = [];
                            $otherIds = [];
                            $idMix = [];
                            // because this is done in the create statement we know that all IDs start at one, mimic the Cartesian process
                            for ($i=0; $i < $times; $i++) { 
                                // main ids
                                for ($j=$offset * $i; $j < $offset * ($i + 1); $j++) { 
                                    $mainIds[$i][] = $j + 1;
                                }
                                // other ids
                                for ($j=$otherTableOffset * $i; $j < $otherTableOffset * ($i + 1); $j++) { 
                                    $otherIds[$i][] = $j + 1;
                                }
                                // create the Cartesian product
                                for ($j=0; $j < count($mainIds[$i]); $j++) { 
                                    // loop over other IDs
                                    for ($k=0; $k < count($otherIds[$i]); $k++) { 
                                        # code...
                                        $idMix[] = [$mainIds[$i][$j], $otherIds[$i][$k]];
                                    }
                                }
                            }
                            // insert all records
                            $sql = "INSERT INTO {$connectionTableName} ({$foreignKeyNames[0]}, {$foreignKeyNames[1]}) VALUES ";
                            for ($i=0; $i < count($idMix); $i++) { 
                                $sql .= " ({$idMix[$i][0]}, {$idMix[$i][1]})";
                                $sql .= $i + 1 < count($idMix) ? ',' : '';
                            }
                            // run sql
                            $result = DatabaseObject::run_sql($sql);
                            // increment four key counter
                            $foreignKeyCounter++;
                        }
                        // set message
                        $replyInfo['message'][] = "The generic cartesian insert was successfully completed.";
                    } else {
                        // set errors
                        $replyInfo['errors'][] = "The class with the name of \"{$className}\" must have a record count higher than one to perform this action.";
                    }
                } else {
                    // set errors
                    $replyInfo['errors'][] = "There is no class with the name of \"{$className}\" that has cartesian insert functionality. No action was performed.";
                }
        
                // return information
                return $replyInfo;
            }

            // # unique Cartesian product for connecting tables
            static protected function unique_cartesian_insert(string $className, array $connectionTables = [], array $foreignKeyNames = []) {
                // the purpose of unique_cartesian_insert() over the cartesian_insert() is that the unique_cartesian_insert() will find specific IDs, as our tags, categories, and labels are connected to specific classes
                // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
                // default variables
                $replyInfo = [];

                // make sure were getting what we really think you're getting
                if (class_exists($className) && in_array(ucfirst($className), DatabaseObject::get_class_list())) {
                    // get count all
                    $classCount = $className::count_all();
                    // check to see if any foreign keys were passed in
                    // get class ID
                    $foreignKeyNames[0] = $foreignKeyNames[0] ?? $className::get_id_name();
                    // check to make sure Is over 1
                    if ($classCount > 1) {
                        // split it based off of size
                        $data = self::offset_count($classCount);
                        // get offset the for main table
                        $times = (int) $data['times'];
                        $offset = (int) $data['offset'];
                        // counter for foreign keys
                        $foreignKeyCounter = 1;
                        // look over each table to make connections
                        foreach ($connectionTables as $classInfo => $connectionTableName) {
                            // separate out logic
                            $otherClassName_array = explode("::", $classInfo);
                            // set class info
                            $otherClassName = $otherClassName_array[0];
                            $findField = $otherClassName_array[1];
                            $findValue = $otherClassName_array[2];
                            // get other class ID
                            $otherClassId = $otherClassName::get_id_name();
                            // check to see if any foreign keys were passed in
                            // get class ID
                            $foreignKeyNames[1] = $foreignKeyNames[$foreignKeyCounter] ?? $otherClassName::get_id_name();
                            // get all from other table that matches criteria
                            $sqlOptions['columnOptions'] = [$otherClassId];
                            $sqlOptions['whereOptions'] = "{$findField} = {$findValue}";
                            $Object_array = $otherClassName::find_where($sqlOptions);
                            // set default variable
                            $allOtherIds = [];
                            // get ids
                            foreach ($Object_array as $Object) {
                                $allOtherIds[] = $Object->$otherClassId;
                            }
                            // get count all from other table
                            $otherTableCount = count($allOtherIds);
                            // check to make sure the count is larger than one
                            if ($otherTableCount > 1) {
                                // get other table offset
                                $otherTableOffset = floor($otherTableCount / $times);
                                // set all main arrays to zero
                                $mainIds = [];
                                $otherIds = [];
                                $idMix = [];
        
                                // because this is done in the create statement we know that all IDs start at one, mimic the Cartesiann process
                                for ($i=0; $i < $times; $i++) { 
                                    // main ids
                                    for ($j=$offset * $i; $j < $offset * ($i + 1); $j++) { 
                                        $mainIds[$i][] = $j + 1;
                                    }
                                    // other ids
                                    for ($j=$otherTableOffset * $i; $j < $otherTableOffset * ($i + 1); $j++) { 
                                        $otherIds[$i][] = $allOtherIds[$j];
                                    }
                                    // create the Cartesian product
                                    // reset $idMix
                                    for ($j=0; $j < count($mainIds[$i]); $j++) { 
                                        // loop over other IDs
                                        for ($k=0; $k < count($otherIds[$i]); $k++) { 
                                            # code...
                                            $idMix[] = [$mainIds[$i][$j], $otherIds[$i][$k]];
                                        }
                                    }
                                }
                                // insert all records
                                $sql = "INSERT INTO {$connectionTableName} ({$foreignKeyNames[0]}, {$foreignKeyNames[1]}) VALUES ";
                                for ($i=0; $i < count($idMix); $i++) { 
                                    $sql .= " ({$idMix[$i][0]}, {$idMix[$i][1]})";
                                    $sql .= $i + 1 < count($idMix) ? ',' : '';
                                }
                                $result = DatabaseObject::run_sql($sql);
                                // increment four key counter
                                $foreignKeyCounter++;
                            } else {
                                // set errors
                                $replyInfo['errors'][] = "The class with the name of \"{$otherClassName}\" must have a record count higher than one to perform this action!";
                            }
                        }
                        // set message
                        $replyInfo['message'][] = "The unique cartesian insert was successfully completed.";
                    } else {
                        // set errors
                        $replyInfo['errors'][] = "The class with the name of \"{$className}\" must have a record count higher than one to perform this action.";
                    }
                } else {
                    // set errors
                    $replyInfo['errors'][] = "There is no class with the name of \"{$className}\" that has cartesian insert functionality. No action was performed.";
                }
        
                // return information
                return $replyInfo;
            }
        // @ creation helper methods end
    
        // @ helper functionality starts
            // # get me offset and number of times
            static protected function offset_count(int $count) {
                // Split account based off of size
                if ($count > 1) {
                    if ($count < 200) {
                        $times = 2;
                        $offset = floor($count / $times);
                    } elseif ($count < 500) {
                        $times = 3;
                        $offset = floor($count / $times);
                    } elseif ($count < 1000) {
                        $times = 5;
                        $offset = floor($count / $times);
                    } elseif ($count < 2000) {
                        $times = 10;
                        $offset = floor($count / $times);
                    }
                    $replyInfo['times'] = $times;
                    $replyInfo['offset'] = $offset;
                }
        
                // return information
                return $replyInfo ?? false;
            }
        // @ helper functionality ends
    }
?>