<?php
    // For testing the creation of users

    // Initialize all the autoloader
    require_once('../../private/initialize.php');

    // If a signup form was recieved
    if(isset($_POST['signup'])) {
        // Get the form data
        $signup_array = $_POST['signup'];
        $email = $signup_array['email'];
        $password = $signup_array['password'];
        $verifyPass = $signup_array['verify'];

        // Errors array
        $errors_array = [];

        // Array for holding the information to create the user
        $newUser_array = [];

        // Check the data that we got in the form
        // Username
        if(!empty($email)) {
            // TODO: Verify that the username has not been taken already
            $checkExists = User::get_users_by_email($email);

            // If the user exists then add to the errors array
            if(!empty($checkExists)) {
                $errors_array[] = "A user with that name/email already exists!";
            } else {
                $newUser_array['email'] = $email;
            }

        } else {
            $errors_array[] = "An Email is required!";
        }

        // Password
        if(!empty($password) && !empty($verifyPass)) {

            // Make sure the passwords match
            if($password == $verifyPass) {
                // Add the password to the new user array
                $newUser_array['password'] = $password;

            } else {
                $errors_array[] = "Password and Verify Password must match!";
            }

        } else {
            $errors_array[] = "Password and Verify Password are required!";
        }

        // If no errors then add the user to the DB
        if(empty($errors_array)) {
            $check = User::create_user($newUser_array);

            if(!$check) {
                $errors_array[] = "Error creating new user!";
            } else {
                redirect_to('admin/home'); //REVIEW where to redirect to
            }
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

    <!-- TEST: Sign Up Form -->
    <form style="border: 1px solid black; display: flex; flex-direction: column; justify-content: space-around; align-items: center; width: 30vw; height: 30vh;" action="login.php" method="post">
        <h3 style="display: block;">Sign Up Form</h3>
        <input style="display: block; padding: 10px;" type="email" placeholder="Username" name="signup[email]" required>
        <input style="display: block; padding: 10px;" type="password" placeholder="Password" name="signup[password]" required>
        <input style="display: block; padding: 10px;" type="password" placeholder="Verify Password" name="signup[verify]" required>
        <button style="padding: 10px;" type="submit">Sign Up</button>
    </form>
</div>