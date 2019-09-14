<?php  

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
        $email = $log_in_array['email'];
        //FIXME how to validate correctly
        $validate_email = val_validation($email, [
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