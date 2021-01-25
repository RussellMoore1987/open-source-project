<?php 

    class Session {

        // add in login trait
        use Authentication;

        // public function __construct() {
        //     session_start(['cookie_lifetime' => 60*60]); //start session that lasts for 1 hour
        //     session_regenerate_id(); //prevent session fixation attacks
        // }
        public static function start() {
            session_start(['cookie_lifetime' => 60*60]); //start session that lasts for 1 hour
            session_regenerate_id(); //prevent session fixation attacks
        }
        
        // Adds a new session variable with the check to make sure Something else doesn't already exist with that name
        public static function add_var(string $name, $value) {
            //store value as session variable with entered name 
            if (!self::check_var_exists($name)) {
                $_SESSION[$name] = $value;
            } else { 
                exit("A session variable with the name \"{$name}\" already exists. If you wish to override that variable please use Session::override_var(name,value);. This one will over ride any variable.");
            }
        }

        // override_var variable, main purpose to override session variables
        public static function override_var(string $name, $value) {
            // override if there variable 
            if (self::check_var_exists($name)) {
                $_SESSION[$name] = $value;
            } else {
                exit("No session variable with the name \"{$name}\" exists. If you wish to add that variable please use Session::add_var(name,value);.");
            }
        }

        // unset session variable
        public static function unset_var(string $name='all') {
            if($name == 'all') { //if no value is given unset all session variables
                session_unset();
            } else {
                //unset session variable with given name
                unset($_SESSION[$name]);
            }
        }

        // get session variable
        public static function get_var(string $name) {
            //get session variable with name. 
            return $_SESSION[$name] ?? false;
        }

        //check if variable with the given value exists
        public static function check_var_exists(string $name) {
            if(self::get_var($name)) {
                return true;
            } else {
                return false;
            }
            
        }
    }

?>