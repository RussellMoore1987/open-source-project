<?php 
  ob_start(); // Output buffering is turned on, deals with whitespace and header redirects.
  session_start(); // Turn on session
  // Assign file paths to PHP constants
  // dirname() returns the path to the parent directory
  define("PRIVATE_PATH", dirname(__FILE__)); // C:\wamp64\www\open_source_project\private
  define("PROJECT_PATH", dirname(PRIVATE_PATH)); // C:\wamp64\www\open_source_project
  define("PUBLIC_PATH", PROJECT_PATH . '/public'); // C:\wamp64\www\open_source_project/public
  // todo: make image path

  
  // setting up variables to check whether or not were local or live
  $localTest = [
      // IPv4 address
      '127.0.0.1', 
      // IPv6 address
      '::1'
  ];

  // check to see if your local or live
  if (in_array($_SERVER['REMOTE_ADDR'], $localTest)) {
    define("MAIN_LINK_PATH", "http://localhost/russellphp/cms_OOP");
  } else {
    define("MAIN_LINK_PATH", "https://mooredigitalsolutions.com"); // todo: change with real domain
  }

  // set default time MST and MDT = America/Denver = daylight savings (MST or America/Denver)
  date_default_timezone_set('America/Denver'); // todo: find a better method so that this will switch automatically

  // Autoload class definitions
  function my_autoload($class) {
    if(preg_match('/\A\w+\Z/', $class)) {
      include('classes/' . $class . '.class.php');
    }
  }
  spl_autoload_register('my_autoload');

  // get default functions
  require_once('functions/functions.php');
  require_once('functions/validation_functions.php');
  require_once('db/db_functions.php');
  
  // db connection
  require_once('db/db_credentials.php');
  $db = db_connect();
  // set db connection
  DatabaseObject::set_database($db);
?>
