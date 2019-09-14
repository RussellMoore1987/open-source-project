<?php 

    class Session {

        public function __construct() {
            session_start(['cookie_lifetime' => 60*60]); //start session that lasts for 1 hour
            session_regenerate_id(); //prevent session fixation attacks
        }
        
        public static function add_var(string $name, $value) {
            //store value as session variable with entered name 
            if (!self::check_var_exists($name)) {
                $_SESSION[$name] = $value;
            } else { //what to do if a session variable already exists with the given name. Leave commented out to update value
                //return error that name is already in use.
                // exit('A session variable with that name already exists.');
            }
        }

        public static function unset_var(string $name='all') {
            if($name == 'all') { //if no value is given unset all session variables
                session_unset();
            } else {
                //unset session variable with given name
                unset($_SESSION[$name]);
            }
        }

        public static function get_var(string $name) {
            //get session variable with name. 
            return $_SESSION[$name] ?? false;
        }

        //check if variable with the given value exists
        private static function check_var_exists(string $name) {
            if(self::get_var($name)) {
                return true;
            } else {
                return false;
            }
            
        }
    }

?>