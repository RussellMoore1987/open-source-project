<?php
    // include traits
    require_once('devToolGetter.trait.php');
    require_once('devToolSetter.trait.php');
    class DevTool {
        // @ use traits start
         use DevToolGetter;
         use DevToolSetter;
         use MainSettings;
        // @ use traits end

        // @ main methods start
            // # devTool_login
            static public function devTool_login($requestData) {
                // var_dump($requestData);
                // set up default variables
                $requestInfo = [];
                
                // get request password
                $requestPassword = $requestData['data']['password'] ?? '';
                // get request username
                $requestUsername = $requestData['data']['username'] ?? '';
                // get devTool password
                // devTool password, has to be at least eight characters long and have one capital letter, one lowercase letter, one number, and one special symbol, otherwise it doesn't work
                $devToolPassword = self::$mainContextInfo['devTool']['password'];
                // get devTool username
                $devToolUsername = self::$mainContextInfo['devTool']['username'];
                // is the password set
                if (trim(strlen($devToolPassword)) > 0) {
                    // is the system password set correctly, validation requirements, if no errors let it pass
                    if (!validate_password($devToolPassword)) {
                        // password check
                        if ($requestPassword === $devToolPassword && $requestUsername === $devToolUsername) {
                            // TODO: set up session, do session protection as well
                            // set message
                            $requestInfo['message'][] = "You have been logged in successfully.";
                        } else {
                            // pass back error message
                            $requestInfo['errors'][] = 'The password and/or username is incorrect.';
                        }
                    } else {
                        // pass back an error message
                        $requestInfo['errors'][] = 'The password and/or username have not been set up correctly within the system.';
                        // ? uncomment at this line below to see the real error message(s)
                        // exit(print_r(validate_password($devToolPassword)));
                    }
                } else {
                    // pass back error message
                    $requestInfo['errors'][] = 'The password and/or username have not been set up for this corporation. No access will be granted to the dev tools until it is set up.';
                }
                
                // return request info
                return $requestInfo;
            }
        // @ main methods end

        // @ dev tool helper functions start
            // # devTool_session_check
            static public function devTool_session_check() {
               
                // TODO: check if devToolSession is there
                // right now just let it pass
                $pass = true;
                // return data
                return $pass; 
            } 
            
            // # devTool Default Message
            static protected $devToolDefaultMessage = "Access to the devTool functions can only be accessed through the request access type of devTool, and you must also be logged into the devTool.";

            // # devTool_get_other_tables_class_list
            static public function devTool_get_other_tables_class_list() {
                // get correct list
                // this list comes from the main settings trait, first check $otherTablesClassList, if nothing is there use the normal $classList
                $otherTablesClassList = isset(self::$otherTablesClassList) && COUNT(self::$otherTablesClassList) > 0 ? self::$otherTablesClassList : self::$classList;
                // return data
                return $otherTablesClassList; 
            } 
            
        // @ dev tool helper functions end

        // @ layouts start
            // latest post layout
            public function layout_devTool() {
                include PRIVATE_PATH . "/classes/devTool/devTool.php";
            }
        // @ layouts end  
    }
?>