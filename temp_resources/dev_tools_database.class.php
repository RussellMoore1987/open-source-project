<?php
// The dev tools used for our project, mostly for database management

// ============ START OF DB DEV TOOLS CLASS ===================
class Database {

    private $SERVERNAME;
    private $USERNAME;
    private $PASSWORD;
    private $DBNAME;

    public $errors_array = [];
    public $dbConnection;

    public function __construct($args) {
        // Set the variables for the connection
        $this->SERVERNAME = (is_null($args['servername']) ? NULL : $args['servername']);
        $this->USERNAME = (is_null($args['username']) ? NULL : $args['username']);
        $this->PASSWORD = (is_null($args['password']) ? NULL : $args['password']);
        $this->DBNAME = (is_null($args['dbname']) ? NULL : $args['dbname']);

        // Connect to the database
        $this->connect_to_database();

        // If the database name is null then create a database name to use;
        if (is_null($this->DBNAME)) {
            $this->create_database_name();
        }

        $this->set_use_database();
    }

    private function connect_to_database() {
        // Check if we have any null values
        if (is_null($this->SERVERNAME) || is_null($this->USERNAME) || is_null($this->PASSWORD)) {
            array_push($this->errors_array, "Could not connect to the database. Missing one of these values: Servername, Username, Password,");
            return false;

        // Connect to the database without a name
        } else if (is_null($this->DBNAME)) {
            $this->dbConnection = new mysqli($this->SERVERNAME, $this->USERNAME, $this->PASSWORD);
            if ($this->dbConnection->connect_error) {
                die($this->dbConnection);
                array_push($this->errors_array, "Database Connection Error");
                return false;
            }

        // Connect to the database with a name
        } else {
            $this->dbConnection = new mysqli($this->SERVERNAME, $this->USERNAME, $this->PASSWORD, $this->DBNAME);
            if ($this->dbConnection->connect_error) {
                die($this->dbConnection);
                array_push($this->errors_array, $this->dbConnection->connect_error);
                return false;
            }
        }
    }

    // Function to show all tables
    public function show_all_tables() {
        $sql = "SHOW tables";
        $result = $this->dbConnection->query($sql);
        if ($result !== FALSE) {
            $tableList = [];
            while($row = $result->fetch_array()) {
                array_push($tableList, $row[0]);
            }
            return $tableList;
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // Function to create the database name
    private function create_database_name() {
        $sql = "CREATE DATABASE developmentdb";
        if ($this->dbConnection->query($sql) === TRUE) {
            return true;
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // Function to set the database that will be used by subsiquent requests.
    private function set_use_database() {
        $sql = "USE " . $this->DBNAME;
        if ($this->dbConnection->query($sql) === TRUE) {
            return true;
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // TABLE CREATION FUNCTIONS
    // POSTS
    public function createPostsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS posts ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "author INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "comments INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "content TEXT NOT NULL, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL, ";
        $sql .= "authorName VARCHAR(50), ";
        $sql .= "createdDate DATE, ";
        $sql .= "postDate DATE, ";
        $sql .= "status TINYINT(1), ";
        $sql .= "title VARCHAR(50) NOT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table posts Created Successfully!";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // TAGS
    public function createTagsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS tags ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table tags Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // LABELS
    public function createLabelsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS labels ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table labels Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // USERS
    public function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "username VARCHAR(35) NOT NULL, ";
        $sql .= "password VARCHAR(50) NOT NULL, ";
        $sql .= "firstName VARCHAR(25) NOT NULL, ";
        $sql .= "lastName VARCHAR(25) NOT NULL, ";
        $sql .= "address VARCHAR(150) NOT NULL, ";
        $sql .= "phoneNumber VARCHAR(25) NOT NULL, ";
        $sql .= "emailAddress VARCHAR(150) NOT NULL, ";
        $sql .= "title VARCHAR(35) DEFAULT NULL, ";
        $sql .= "mediaContent INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "adminNote VARCHAR(255) DEFAULT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL, ";
        $sql .= "showOnWeb TINYINT(1) DEFAULT 1, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0 )";


        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table labels Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // CATEGORIES
    public function createCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS categories ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "sudCatId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table labels Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }
    

    // TABLE DROP FUNCTION
    public function dropTable($tablename) {
        $sql = "DROP TABLE IF EXISTS " . $tablename;

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table " . $tablename . " Dropped Successfully!";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    
}
// ================= END OF DB DEV TOOLS CLASS ==============================
?>

