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
    public function createPostsTable() {
        $sql = "CREATE TABLE posts IF NOT EXISTS ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO INCREMENT PRIMARY KEY, ";
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
            array_push($this->errors_array, "Error creating table posts");
            return false;
        }
    }

    public function createTagsTable() {
        $sql = "CREATE TABLE tags IF NOT EXISTS ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table tags Created Successfully";
        } else {
            array_push($this->errors_array, "Error creating table tags");
            return false;
        }
    }

    public function createLabelsTable() {
        $sql = "CREATE TABLE labels IF NOT EXISTS ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table labels Created Successfully";
        } else {
            array_push($this->errors_array, "Error creating table labels");
            return false;
        }
    }

    // TABLE DROP FUNCTION
    public function dropTable($tablename) {
        $sql = "DROP TABLE " . $tablename . " IF EXISTS";

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

