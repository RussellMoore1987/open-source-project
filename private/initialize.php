<?php 
    ob_start(); // Output buffering is turned on, deals with whitespace and header redirects.
    // Assign file paths to PHP constants
    // dirname() returns the path to the parent directory
    define("PRIVATE_PATH", dirname(__FILE__)); // C:\wamp64\www\open_source_project\private
    define("PROJECT_PATH", dirname(PRIVATE_PATH)); // C:\wamp64\www\open_source_project
    define("PUBLIC_PATH", PROJECT_PATH . '/public'); // C:\wamp64\www\open_source_project/public
    
    // setting up variables to check whether or not were local or live
    $localTest = [
        // IPv4 address
        '127.0.0.1', 
        // IPv6 address
        '::1'
    ];

    // defining link path
    // check to see if your local or live
    if (in_array($_SERVER['REMOTE_ADDR'], $localTest)) {
        define("MAIN_LINK_PATH", "http://localhost/open_source_project");
    } else {
        define("MAIN_LINK_PATH", "https://mooredigitalsolutions.com"); // todo: change to server variable
    }
    define("PUBLIC_LINK_PATH", MAIN_LINK_PATH . "/public");
    define("IMAGE_LINK_PATH", PUBLIC_LINK_PATH . '/images'); 

    // set default time MST and MDT = America/Denver = daylight savings (MST or America/Denver)
    date_default_timezone_set('America/Denver'); // todo: find a better method so that this will switch automatically

    // version for CSS and JavaScript, increment this number upwards to reset CSS and JavaScript caches
    $assetVersion = 12372;

    // Autoload class and trait definitions
    function my_autoload($class) {
        // Make sure it's okay to come in
        if(preg_match('/\A\w+\Z/', $class)) {
            // creating class and trait path and class trait path
            $class = strtolower($class);

            // class path
            $classPath = PRIVATE_PATH . "//classes/{$class}.class.php";

            // custom class path
            $customClassPath = PRIVATE_PATH . "//custom_classes/{$class}.class.php";

            // class folder path
            $classFolderPath = PRIVATE_PATH . "//classes/{$class}/{$class}.class.php";

            // custom class folder path
            $customClassFolderPath = PRIVATE_PATH . "//custom_classes/{$class}/{$class}.class.php";

            // trait path
            $traitPath =  PRIVATE_PATH . "//traits/{$class}.trait.php";
            
            // see if we can find the trait or class, In order of use case
            if (file_exists($classPath)) {
                include('classes/' . $class . '.class.php');
            } elseif (file_exists($customClassPath)) {
                include('custom_classes/' . $class . '.class.php');
            } elseif (is_file($traitPath)) {
                include('traits/' . $class . '.trait.php');
            } elseif (file_exists($classFolderPath)) {
                include("classes/{$class}/{$class}.class.php");
            }  elseif (file_exists($customClassFolderPath)) {
                include("custom_classes/{$class}/{$class}.class.php");
            } 
        }   
    }
    
    spl_autoload_register('my_autoload');

    // get default functions
    require_once('functions/functions.php');
    require_once('security/validation_functions.php');
    require_once('db/db_functions.php');
    // for api documentation
    require_once('rules_docs/reference_information.php');
    
    // db connection
    require_once('db/db_credentials.php');
    $db = db_connect();
    // set db connection
    DatabaseObject::set_database($db);

    //start session
    Session::start();
?>
