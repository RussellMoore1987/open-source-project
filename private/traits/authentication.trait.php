<?php
    trait Authentication {
        // # hash password
            static protected function hash_password(string $password) {
                return password_hash($password, PASSWORD_BCRYPT);  
            } 

        // @ login functionality start
            // # login
            static public function login(array $login_data_array) {
                // get class and fields, there should only 4 values in this array
                    $authentication_array = DatabaseObject::get_authentication_settings();
                    // test for 4 needed values array
                    self::test_login($authentication_array);
                    $className = $authentication_array[0];
                    $findByFiled = $authentication_array[1];
                    $passwordFiled = $authentication_array[2];
                    $identifierFiledName = $authentication_array[3];
                // get field1 and password from the form array
                    $username = DatabaseObject::db_escape(trim($login_data_array['field1']));
                    $password = trim($login_data_array['password']);
                // find user
                    echo "{$findByFiled} = '{$username}'";
                    $User = $className::find_where("{$findByFiled} = '{$username}'");
                    // error handling, if not there, throw an error
                    if (!$User) {
                        echo 'Got Here!!!';
                        return "Sorry, {$findByFiled} or password were incorrect.";
                    }
                // check to see if password matches
                if (password_verify($password, $User->$passwordFiled)) {
                    Session::add_var('userIdentifier', $User->$identifierFiledName);
                } else {
                    return "Sorry, {$findByFiled} or password were incorrect.";
                }
                $defaultHomepage = DatabaseObject::$defaultHomepage;
                redirect_to(PUBLIC_LINK_PATH . $defaultHomepage);
                exit();
            } 
            
            // # check login
            static public function check_login() {
                // get username and password
                if (Session::check_var_exists('userIdentifier')) {
                    return true;
                } else { 
                    redirect_to(PUBLIC_LINK_PATH . '/login.php');
                }
            } 

            // # logout
            static public function logout() {
                // get username and password
                Session::unset_var();
                redirect_to(PUBLIC_LINK_PATH . '/login.php');
            } 
        // @ login functionality end

        // @ helper functions start
            static private function test_login(array $array) {
                if (count($array) == 4) {
                    return true;
                } else { 
                    exit("The login method for the Login trait must have an array with 4 options. See authentication in mainSettings.trait.php");
                }
            }
        // @ helper functions end
    }
?>