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

    // ========================================== TABLE CREATION FUNCTIONS ===============================================================
    // CREATE ALL TABLES
    public function createAllTables() {
        $results = [];
        array_push($results, $this->createCategoriesTable());
        array_push($results, $this->createCommentsTable());
        array_push($results, $this->createPostsTable());
        array_push($results, $this->createTagsTable());
        array_push($results, $this->createLabelsTable());
        array_push($results, $this->createUsersTable());
        array_push($results, $this->createMediaContentTable());
        array_push($results, $this->createTodoTable());
        array_push($results, $this->createMainSettingsTable());
        array_push($results, $this->createStyleSettingsTable());
        array_push($results, $this->createContentTable());
        array_push($results, $this->createBookmarksTable());
        array_push($results, $this->createPermissionsTable());

        foreach ($results as $result) {
            if ($result === false) {
                return false;
                break;
            } else {
                continue;
            }

            return "All tables created successfully!";
        }
    }

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
            return "Table users Created Successfully";
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
            return "Table categories Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // MEDIA CONTENT
    public function createMediaContentTable() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "name VARCHAR(150) NOT NULL, ";
        $sql .= "type VARCHAR(25) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL, ";
        $sql .= "alt VARCHAR(30) DEFAULT NULL, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "createdDate DATE )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table mediaContent Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }
    

    // COMMENTS
    public function createCommentsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS comments ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "createdDate DATE, ";
        $sql .= "comment VARCHAR(255) DEFAULT NULL, ";
        $sql .= "status TINYINT(1) NOT NULL DEFAULT 0, ";
        $sql .= "name VARCHAR(50) DEFAULT NULL, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "approvedBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table comments Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // TODO
    public function createTodoTable() {
        $sql = "CREATE TABLE IF NOT EXISTS todo ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "todo VARCHAR(255) )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table todo Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // MAIN SETTINGS
    public function createMainSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS main_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "changedDate DATE, ";
        $sql .= "apiUse VARCHAR(255) NOT NULL, ";
        $sql .= "apiKey VARCHAR(255), ";
        $sql .= "commentKey VARCHAR(255), ";
        $sql .= "contentKey VARCHAR(255), ";
        $sql .= "mainSettings JSON NOT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table main_settings Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // PERSONAL SETTINGS
    public function createPersonalSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS personal_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "changedDate DATE, ";
        $sql .= "personalSettings JSON NOT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table personal_settings Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // STYLE SETTINGS
    public function createStyleSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS style_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "changedDate DATE, ";
        $sql .= "styleSettings JSON NOT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table style_settings Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // CONTENT
    public function createContentTable() {
        $sql = "CREATE TABLE IF NOT EXISTS content ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL, ";
        $sql .= "changedDate DATE, ";
        $sql .= "content JSON NOT NULL )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table content Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // BOOKMARKS
    public function createBookmarksTable() {
        $sql = "CREATE TABLE IF NOT EXISTS bookmarks ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "url VARCHAR(255), ";
        $sql .= "name VARCHAR(50) )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table bookmarks Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // PERMISSIONS
    public function createPermissionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS permissions ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "name VARCHAR(50), ";
        $sql .= "description VARCHAR(255) )";

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table permissions Created Successfully";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // ===================================================== TABLE DROP FUNCTIONS ==================================================================
    public function dropTable($tablename) {
        if ($tablename === 'all') {
            $listOfTables = $this->show_all_tables();
            $sql = "DROP TABLE IF EXISTS ";
            $i = 0;
            foreach ($listOfTables as $table ) {
                if ($i == (sizeof($listOfTables) - 1)) {
                    $sql .= $table;
                } else {
                    $sql .= $table . ", ";
                }
                $i++;
            }
        } else {
            $sql = "DROP TABLE IF EXISTS " . $tablename;
        }

        if ($this->dbConnection->query($sql) === TRUE) {
            return "Table(s) " . $tablename . " Dropped Successfully!";
        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    
}
// ================= END OF DB DEV TOOLS CLASS ==============================
?>

