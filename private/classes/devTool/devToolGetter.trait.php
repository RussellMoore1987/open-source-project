<?php
    trait DevToolGetter {
        // @ dev tool main getter requests start
        // ! start here ************************************************ get data to pull through

            // # devTool_get_all_tables
            static public function devTool_get_all_tables($requestData) {
                // var_dump($requestData);
                // set up default variables
                $requestInfo = [];
                
                // get tables and corresponding data
                $classes = self::$classList;
                
                // loop through each class and set up options
                foreach ($classes as $class => $routes) {
                    // get options
                    $classOptions['tableName'] = $class::get_table_name() ?? false;
                    $classOptions['fakerData'] = $class::check_for_seeder();
                    $classOptions['sqlStructure'] = $class::check_sql_structure();
                    $classOptions['contextApi'] = $class::check_context_api();
                    $classOptions['restApi'] = $class::check_rest_api();
                    // set options
                    $requestInfo['tables'][] = $classOptions;
                }

                // get all other tables these should be connecting tables
                

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
            // # devTool_session_check
            static public function devTool_session_check() {
               
                // TODO: check if devToolSession is there
                // right now just let it pass
                $pass = true;
                // return data
                return $pass; 
            } 
            
            static protected $devToolDefaultMessage = "Access to the devTool functions can only be accessed through the request access type of devTool, and you must also be logged into the devTool.";
        // @ dev tool helper functions end
    }

    // self::devTool_session_check()

    // pass back error message
    // $requestInfo['errors'][] = 'Access to the devTool functions can only be accessed through the request access type of devTool.';
?>