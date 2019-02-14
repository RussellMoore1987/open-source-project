<?php
    // @ purpose: redirect for the private directory to help with security issues
    
    // redirect to the correct spot: public/index.php
    header("Location: ../../public/index.php");
    exit();
?>