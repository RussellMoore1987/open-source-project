<?php
# Trait for User Authentication

trait Auth {
    //variables to be set as session variables upon successful log in
    public static $session_var_array = ['id', 'fullName', 'userType'];

    //logs in user
    public static function log_in(array $log_in_array = []) {
        //verify user email and password
        $user = static::authenticate_password($log_in_array);

        if ($user) {
            
            //set session variables for variables specified in $session_var_array
            foreach (self::$session_var_array as $varName) {
                Session::add_var($varName, $user->$varName);
            }

           redirect_to('admin/home'); //TODO where to redirect after successful login.   

        } else { //What to do if log in attempt fails
            // echo 'failed login';
           return false;
        }
    }
    
    // log out user
    public static function log_out() {
        //unset all session variables
        session_unset();
        //destroy session
        session_destroy();
        //redirect to login page  
        redirect_to('/public/login'); //REVIEW  check on redirect site. 
    }


    // check email and password against database entry
    private static function authenticate_password($log_in_array) {
        $email = $log_in_array['username'];
        $password = $log_in_array['password'];
        
        //make sure that an email and password were both supplied
        if (!empty($email) && !empty($password)) {
            //find user information for the given email address
            $user_array = static::get_users_by_email($email); 
            
            if(count($user_array)) { //if there is a user found
                
                $user = $user_array[0];
                //verify the password given with the stored password
                if (password_verify($password, $user->hash)) {
                    
                    return $user;
                } else {
                    // Session::add_var('message', 'Username and password don\'t match');
                }
            } else { //no user found
                // Session::add_var('message', 'User doesn\'t exist');
                return false;       
            }
        } else { //if email or password weren't supplied
            Session::add_var('message', 'Username and password cannot be blank');
            return false;
        }
    }

    //check if the user has the necessary user privileges to enter webpage
    public static function check_permissions(array $required_permissions_array, string $redirect_address = "/public/login", bool $requireId = true) {
        //check if a userType is saved, if not assign a default value of none
        $currentUserType = Session::get_var('userType') ?? 'none';
        //if the id is required for the permissions get the id from the session
        if ($requireId) {
            $currentId = Session::get_var('id'); //if the id isn't set as session variable it will be set as false
        } else { //id isn't required set currentId as true
            $currentId = true;
        }

        //check if the currentUserType is in the requiredPermissions and if the id requirement is met
        if (!in_array($currentUserType, $required_permissions_array) || !$currentId ) {

            //if not in required permissions and doesn't match the currentId requirment
            
            //unset all session variable--essentially logging the user out
            // Session::unset_var(); 

            //set an error message
            Session::add_var('message', 'You do not have the required permissions to access that page. Please log in and try again.');

            //redirect to login page where error message will be displayed
            redirect_to($redirect_address); 
            exit();
        } else {
            //if user has privileges continue as usual
            return true;
        }
    } 
}


?>