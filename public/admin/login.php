<?php

    // Initialize all the autoloader
    require_once('../../private/initialize.php');

    // TODO: Test the login functionality by creating a user and checking password

    // set page title
    $pageTitle = "Login";

    // Array for holding errors
    $errors_array = [];
    
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

        //set username 
        $username = $log_in_array['username'];

        //FIXME: Ensure that validation is working correctly
        $validate_email = val_validation($username, [
            'name' => 'Email', 'email' => 'yes', 'required' => 'yes'
        ]);

        // If email not valid add to errors array
        if(!$validate_email) {
            array_push($errors_array, "Entry is not a valid email!");
        }

        $validate_password = val_validation($log_in_array['password'], [
            'name' => 'Password', 'required' => 'yes'
        ]);

        // If the password is not valid then add to errors array
        if(!$validate_password){
            array_push($errors_array, "Entry is not a valid password!");
        }

        // If no errors were detected then authenticate the user
        if(!empty($errors_array)){
            //authenticate user
            $auth = User::log_in($log_in_array);
        }
        
        // TODO: Where to move this?
        //if user is authenticated
        if($auth){ 
            //redirect to admin home REVIEW  verify location to redirect to.
            redirect_to('admin/home');
        } else {
            //set error message for failed login 
            Session::add_var('message', 'Log in failed. Try again.');
            // Add to the errors array
            array_push($errors_array, "The Username and/or password you entered is not correct!")
        }
    }

?>

    <div style="display: flex; justify-content: center; align-items: center; width: 100vw; height: 100vh;">
    <!-- TEST: Messages -->
        <div style="display: flex; justify-content: center; align-items: center; border: 1px solid black;">
        <?php

            // Display the errors if there are any
            if(!empty($errors_array)){
                foreach($errors_array as $error) {
                    echo "<h4>" . $error . "</h4>";
                    echo "<br>"
                }
            }
        
        ?></div>
    <!-- TEST: Login Form -->
        <form style="border: 1px solid black; display: flex; flex-direction: column; justify-content: space-around; align-items: center; width: 30vw; height: 30vh;" action="login.php" method="post">
            <h3 style="display: block;">Login Form</h3>
            <input style="display: block; padding: 10px;" type="text" placeholder="Username" name="login[username]" required>
            <input style="display: block; padding: 10px;" type="password" placeholder="Password" name="login[password]" required>
            <button style="padding: 10px;" type="submit">Login</button>
        </form>
    </div>