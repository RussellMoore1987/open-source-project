<?php
    // check to see if main user exist
    $User = User::find_where("username = 'admin'");
    if (!$User) {
        // main user details
        $mainUserDetails = ['username' => 'admin', 'hashedPassword' => 'test', 'firstName' => 'Admin', 'lastName' => 'Person', 'emailAddress' => 'someone@gmail.com'];
        // add main user 
        $User = new User($mainUserDetails);
        // save main user
        $User->save();
    }

    echo "Username: {$User->username}";
    echo "<br>";
    echo "Name: {$User->firstName} {$User->lastName}";
    echo "<br>";
    echo "EmailAddress: {$User->emailAddress}";
    echo "<br>";
    echo "HashedPassword:{$User->hashedPassword}";
?>