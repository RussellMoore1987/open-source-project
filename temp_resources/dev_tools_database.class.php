<?php
// The dev tools used for our project, mostly for database management

// Get the faker library for inserting data
require_once("../vendor/fzaninotto/faker/src/autoload.php");
require_once("../vendor/fzaninotto/faker/src/Faker/Factory.php");

// ============ START OF DB DEV TOOLS CLASS ===================
class Database {

    private $SERVERNAME;
    private $USERNAME;
    private $PASSWORD;
    private $DBNAME;

    private $Faker;

    public $errors_array = [];
    public $dbConnection;
    public $latest_selection_array = [];
    public $select_table_name = NULL;

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

        // Create the faker object for loading random data
        $this->$Faker = Faker\Factory::create();
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

    // Function to disable/enable foreign key constraints for table creation and drop
    private function toggleForeignKeyChecks($toggle) {
        // Toggle the key checks OFF
        if ($toggle === false) {
            $sql = "SET FOREIGN_KEY_CHECKS = 0";
            $this->dbConnection->query($sql);

        // Toggle the key checks ON
        } else {
            $sql = "SET FOREIGN_KEY_CHECKS = 1";
            $this->dbConnection->query($sql);
        }
    }

    // Function to execute table create sql queries
    private function executeCreateQuery($query, $tablename=NULL) {
        // DISABLE Foreign key checks in preparation
        $this->toggleForeignKeyChecks(FALSE);

        if ($this->dbConnection->query($query) === TRUE) {
            // ENABLE Foreign key checks when finished
            $this->toggleForeignKeyChecks(TRUE);

            return "Table " . $tablename . " created successfully!";
        } else {
            // ENABLE Foreign key checks when finished
            $this->toggleForeignKeyChecks(TRUE);

            array_push($this->errors_array, $tablename . ": " . $this->dbConnection->error);
            return false;
        }
    }

    // Function to execute insert sql queries
    private function executeInsertQuery($query, $tablename=NULL) {
        if ($this->dbConnection->query($query) === TRUE) {
            return mysql_affected_rows() . " rows inserted into " . $tablename .  " successfully!";
        } else {
            array_push($this->errors_array, $tablename . ": " . $this->dbConnection->error);
            return false;
        }
    }

    // ========================================== TABLE CREATION FUNCTIONS ===============================================================
    // CREATE ALL TABLES
    public function createAllTables() {
        $results = [];

        // Base Tables
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
        array_push($results, $this->createPersonalSettingsTable());
        array_push($results, $this->createContentTable());
        array_push($results, $this->createBookmarksTable());
        array_push($results, $this->createPermissionsTable());

        // Lookup Tables
        array_push($results, $this->createPostsToMediaContentTable());
        array_push($results, $this->createPostsToTagsTable());
        array_push($results, $this->createPostsToLabelsTable());
        array_push($results, $this->createPostsToCategoriesTable());
        array_push($results, $this->createMediaContentToTagsTable());
        array_push($results, $this->createMediaContentToCategoriesTable());
        array_push($results, $this->createContentToTagsTable());
        array_push($results, $this->createContentToLabelsTable());
        array_push($results, $this->createContentToCategoriesTable());
        array_push($results, $this->createUserToPermissionsTable());

        foreach ($results as $result) {
            if ($result === false) {
                return false;
                break;
            } else {
                // Do Nothing
            }
        }
        
        return "All tables created successfully!";
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
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "FOREIGN KEY (author) REFERENCES users(id), ";
        $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'posts');
    }

    // TAGS
    public function createTagsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS tags ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'tags');
    }

    // LABELS
    public function createLabelsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS labels ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'labels');
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
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id), ";
        $sql .= "FOREIGN KEY (mediaContent) REFERENCES media_content(id) )";


        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'users');
    }

    // CATEGORIES
    public function createCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS categories ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "subCatId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";
        // TODO: What does the SudCatId reference?
        // $sql .= "FOREIGN KEY (sudCatId) REFERENCES ?(?) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'categories');
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
        $sql .= "createdDate DATE, ";
        $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'media_content');
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

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'comments');
    }

    // TODO
    public function createTodoTable() {
        $sql = "CREATE TABLE IF NOT EXISTS todo ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "todo VARCHAR(255), ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'todo');
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

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'main_settings');
    }

    // PERSONAL SETTINGS
    public function createPersonalSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS personal_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "changedDate DATE, ";
        $sql .= "personalSettings JSON NOT NULL )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'personal_settings');
    }

    // STYLE SETTINGS
    public function createStyleSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS style_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "changedDate DATE, ";
        $sql .= "styleSettings JSON NOT NULL, ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'style_settings');
    }

    // CONTENT
    public function createContentTable() {
        $sql = "CREATE TABLE IF NOT EXISTS content ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL, ";
        $sql .= "changedDate DATE, ";
        $sql .= "content JSON NOT NULL, ";
        $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'content');
    }

    // BOOKMARKS
    public function createBookmarksTable() {
        $sql = "CREATE TABLE IF NOT EXISTS bookmarks ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "url VARCHAR(255), ";
        $sql .= "name VARCHAR(50), ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'bookmarks');
    }

    // PERMISSIONS
    public function createPermissionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS permissions ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "name VARCHAR(50), ";
        $sql .= "description VARCHAR(255) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'permissions');
    }

    // ------ Lookup Table Creation ----------

    // POSTS TO MEDIA CONTENT
    public function createPostsToMediaContentTable() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_media_content ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'posts_to_media_content');
    }

    // POSTS TO TAGS
    public function createPostsToTagsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_tags ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'posts_to_tags');
    }

    // POSTS TO LABELS
    public function createPostsToLabelsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_labels ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'posts_to_labels');
    }

    // POSTS TO CATEGORIES
    public function createPostsToCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_categories ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'posts_to_categories');
    }

    // MEDIA CONTENT TO TAGS
    public function createMediaContentToTagsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content_to_tags ( ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
        $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'media_content_to_tags');
    }

    // MEDIA CONTENT TO LABELS
    public function createMediaContentToLabelsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content_to_labels ( ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
        $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'media_content_to_labels');
    }

    // MEDIA CONTENT TO CATEGORIES
    public function createMediaContentToCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content_to_categories ( ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
        $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'media_content_to_categories');
    }

    // CONTENT TO TAGS
    public function createContentToTagsTable() {
        // Turn OFF foreign key checks
        $this->toggleForeignKeyChecks(FALSE);

        $sql = "CREATE TABLE IF NOT EXISTS content_to_tags ( ";
        $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
        $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'content_to_tags');
    }

    // CONTENT TO LABELS
    public function createContentToLabelsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS content_to_labels ( ";
        $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
        $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'content_to_labels');
    }

    // CONTENT TO CATEGORIES
    public function createContentToCategoriesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS content_to_categories ( ";
        $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
        $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'content_to_categories');
    }

    // USER TO PERMISSIONS
    public function createUserToPermissionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users_to_permissions ( ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "permissionId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id), ";
        $sql .= "FOREIGN KEY (permissionId) REFERENCES permissions(id) )";

        // Execute the query then return the result
        return $this->executeCreateQuery($sql, 'users_to_permissions');
    }

    // ===================================================== TABLE DROP FUNCTIONS ==================================================================
    public function dropTable($tablename) {
        // Remove foreign key checks in preparation to drop the tables
        $this->toggleForeignKeyChecks(FALSE);

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
            // Turn on foreign key checks after dropping the tables
            $this->toggleForeignKeyChecks(TRUE);

            return "Table(s) " . $tablename . " Dropped Successfully!";

        } else {
            array_push($this->errors_array, $this->dbConnection->error);
            return false;
        }
    }

    // ===================================================== TABLE SELECT FUNCTIONS ==================================================================

    public function selectFromTable($numRecords = 10, $tablename = NULL) {

        $sql = "SELECT * FROM " . $tablename . " LIMIT " . $numRecords;

        $result = $this->dbConnection->query($query);

        if ($this->dbConnection->query($query) === TRUE) {

            // Loop through the records and store them in our array
            while ($row = $result->fetch_assoc()) {
                array_push($this->latest_selection_array, $row);
            }

            return "Selected " . mysql_affected_rows() . " rows from " . $tablename .  " successfully!";

        } else {
            array_push($this->errors_array, $tablename . ": " . $this->dbConnection->error);
            return false;
        }
    }

    // ===================================================== TABLE INSERT FUNCTIONS ==================================================================
    
    // Insert into posts
    public function insertIntoPosts($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO posts ( ";
        $sql .= "author, authorName, comments, content, createdBy, ";
        $sql .= "createdDate, postDate, status, title ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {
            $sql .= "( " . $this->$Faker->numberBetween(1, $maxId) . ", ";
            $sql .= $this->$Faker->name . ", " . $this->$Faker->snumberBetween(1, $maxId) . ", ";
            $sql .= $this->$Faker->paragraph($this->$Faker->numberBetween(1, $maxId)) . ", ";
            $sql .= $this->$Faker->snumberBetween(1, $maxId) . $this->$Faker->dateTimeThisYear($max = 'now') . ", ";
            $sql .= $this->$Faker->dateTimeThisYear($max = 'now') . ", " . $this->$Faker->snumberBetween(0, 1) . ", ";
            $sql .= $this->$Faker->sentence($nbWords = 3, $variableNbWords = true) . " )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'posts');
    }
}
// ================= END OF DB DEV TOOLS CLASS ==============================
?>

