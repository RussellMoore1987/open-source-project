<?php

    // FIXME: Adjust these pages to be autoloaded
    require_once('../../private/classes/session.class.php');
    require_once('../../private/security/validation_functions.php');
    require_once('../../private/classes/user.class.php');

    // TODO: Test the login functionality by creating a user and checking password

    // set page title
    $pageTitle = "Login";
    
    //if a user id is already stored in the session redirect to a different page
    if (Session::get_var('id')) {
        redirect_to('admin/home'); //REVIEW where to redirect to
    }
    
    if (isset($_GET['logout'])) {
        User::log_out();  
    }
    
    if (isset($_POST['login'])) {
        //retrieve form data
        $log_in_array = $_POST['login'];

        //set email for use in form 
        $username = $log_in_array['username'];
        //FIXME how to validate correctly
        $validate_email = val_validation($username, [
            'name' => 'Email', 'email' => 'yes', 'required' => 'yes'
        ]);
        $validate_password = val_validation($log_in_array['password'], [
            'name' => 'Password', 'required' => 'yes'
        ]);
        
        //authenticate user
        $auth = User::log_in($log_in_array);
       
        //if errors are present after submission //FIXME how to display hours 
        $errors =  implode(' <br>', $validate_email);
        $errors .=  ' <br>';
        $errors .=  implode(' <br>', $validate_password);
        echo $errors;
        
        //if user is authenticated
        if($auth){ 
            //redirect to admin home REVIEW  verify location to redirect to.
            redirect_to('admin/home');
        } else {
            //set error message for failed login 
            Session::add_var('message', 'Log in failed. Try again.');
        }
    }

?>

    <!-- TEST: Login Form -->
    <div style="display: flex; justify-content: center; align-items: center; width: 100vw; height: 100vh;">
        <form style="border: 1px solid black; display: flex; flex-direction: column; justify-content: space-around; align-items: center; width: 30vw; height: 30vh;" action="login.php" method="post">
            <h3 style="display: block;">Login Form</h3>
            <input style="display: block; padding: 10px;" type="text" placeholder="Username" name="login[username]" required>
            <input style="display: block; padding: 10px;" type="password" placeholder="Password" name="login[password]" required>
            <button style="padding: 10px;" type="submit">Login</button>
        </form>
    </div>