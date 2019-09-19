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
        $email = $log_in_array['email'];

        $valTest = val_validation($email, [
            'name' => 'Email', 'email' => 'yes', 'required' => 'yes'
        ]);

        // If email not valid add to errors array
        if(!empty($valTest)) {
            $errors_array[] = "Entry is not a valid email!";
        }

        $valTest = val_validation($log_in_array['password'], [
            'name' => 'Password', 'required' => 'yes'
        ]);

        // If the password is not valid then add to errors array
        if(!empty($valTest)){
            $errors_array[] = "Entry is not a valid password!";
        }

        // If no errors were detected then authenticate the user
        if(empty($errors_array)){
            //authenticate user
            $auth = User::log_in($log_in_array);
        }
        
        // TODO: Where to move this?
        //if user is authenticated
        if(isset($auth) && $auth == true){ 
            //redirect to admin home REVIEW  verify location to redirect to.
            redirect_to('admin/home');
        } else {
            //set error message for failed login 
            Session::add_var('error', 'Log in failed. Try again.');
            // Add to the errors array
            array_push($errors_array, "The Email and/or password you entered is not correct!");
        }
    }

?>

    <div style="display: flex; flex-direction: column; justify-content: space-around; align-items: center; width: 100vw; height: 100vh;">
    
        <!-- TEST: Messages -->
        <div style="display: flex; flex-direction: column; justify-content: space-around; align-items: center; border: 1px solid black;">
        <?php

            // Display the errors if there are any
            echo "<h4>ERROR LIST</h4>";
            if(!empty($errors_array)){
                foreach($errors_array as $error) {
                    echo "<h5>" . $error . "</h5>";
                }
            } else {
                echo "<h5>No Errors to display</h5>";
            }
        
        ?></div>

        <!-- TEST: Session Info -->
            <div style="display: flex; flex-direction: column; justify-content: space-around; align-items: center;">
        <?php

            // Display the session info
            if(!empty($_SESSION)){
                
                // Draw the table for the session variables
                $table = "
                <table border='1'>
                    <tr>
                        <th>Session Variable Name</th>
                        <th>Session Variable Value</th>
                    </tr>";

                // list each variable in the table
                foreach($_SESSION as $key => $var) {

                    $table .= "
                            <tr>
                                <th>" . $key . "</th>
                                <th>" . $var . "</th>
                            </tr>
                    ";
                }

                // Echo out the table
                $table .= "</table>";
                echo $table;
            } else {
                echo "<h4>No Session variables set!</h4>";
            }
        
        ?></div>

        <!-- TEST: Login Form -->
        <form style="border: 1px solid black; display: flex; flex-direction: column; justify-content: space-around; align-items: center; width: 30vw; height: 30vh;" action="login.php" method="post">
            <h3 style="display: block;">Login Form</h3>
            <input style="display: block; padding: 10px;" type="email" placeholder="Email" name="login[email]" required>
            <input style="display: block; padding: 10px;" type="password" placeholder="Password" name="login[password]" required>
            <button style="padding: 10px;" type="submit">Login</button>
        </form>
    </div>