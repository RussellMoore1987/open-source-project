<?php
    // include main logic for all pages
    require_once('../private/initialize.php');
    // try to login
    var_dump($_POST);
    if (is_post_request() && isset($_POST["login"])) {
        $message = Session::login($_POST["login"]);
    }
    $message = $message ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <?php echo $message; ?>
    </div>
    <form method="post">
        <label for="username">Username</label>
        <input type="text" id='username' name="login[field1]" maxlength='50' minlength='2' required >
        <label for="password">Password</label>
        <input type="password" id='password' name="login[password]" maxlength='50' minlength='2' required >
        <button type="submit">Login</button>
    </form>

    <?php
        $User = User::find_where("username = 'admin'");
        if (!$User) {
            // main user details
            $mainUserDetails = ['username' => 'admin', 'hashedPassword' => 'test', 'firstName' => 'Admin', 'lastName' => 'Person', 'emailAddress' => 'someone@gmail.com'];
            // add main user 
            $User = new User($mainUserDetails);
            // save main user
            $User->save();
            var_dump($User->errors);
        }
        echo "Username: {$User->username}";
        echo "<br>";
        echo "Name: {$User->firstName} {$User->lastName}";
        echo "<br>";
        echo "EmailAddress: {$User->emailAddress}";
        echo "<br>";
        echo "HashedPassword:{$User->hashedPassword}";
    ?>

</body>
</html>