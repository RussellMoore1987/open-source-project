<?php
// The dev tools used for our project, mostly for database management
require_once("../vendor/fzaninotto/faker/src/autoload.php");

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

        // Create a Faker object for user with populating random data
        $this->Faker = Faker\Factory::create();
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

    // Function to execute truncate table sql queries
    private function executeTruncateQuery($query, $tablename=NULL) {
        // DISABLE Foreign key checks in preparation
        $this->toggleForeignKeyChecks(FALSE);

        if ($this->dbConnection->query($query) === TRUE) {
            // ENABLE Foreign key checks when finished
            $this->toggleForeignKeyChecks(TRUE);

            return  $tablename .  " truncated successfully!";
        } else {
            // ENABLE Foreign key checks when finished
            $this->toggleForeignKeyChecks(TRUE);

            array_push($this->errors_array, $tablename . ": " . $this->dbConnection->error);
            return false;
        }
    }

    // Function to execute insert sql queries
    private function executeInsertQuery($query, $tablename=NULL) {
        // DISABLE Foreign key checks in preparation
        $this->toggleForeignKeyChecks(FALSE);

        if ($this->dbConnection->query($query) === TRUE) {
            // ENABLE Foreign key checks when finished
            $this->toggleForeignKeyChecks(TRUE);

            return $this->dbConnection->affected_rows . " rows inserted into " . $tablename .  " successfully!";
        } else {
            // ENABLE Foreign key checks when finished
            $this->toggleForeignKeyChecks(TRUE);

            array_push($this->errors_array, $tablename . ": " . $this->dbConnection->error);
            return false;
        }
    }

    // Function to get a list of IDs from a table
    private function getTableIds($tablename = NULL) {
        $id_array = [];

        if ($tablename == NULL) {
            $id_array[0] = 1;
            return $id_array;
        } else {
            $sql = "SELECT id FROM " . $tablename;

            $result = $this->dbConnection->query($sql);

            if ($result) {
                // Loop through the records and store the ids in our array
                while ($row = $result->fetch_assoc()) {
                    array_push($id_array, $row['id']);
                }
            }
            return $id_array;
        }
    }

    // Function to randomize the number of connections in a lookup table
    // Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
    private function createRandomConnections($connections = 2, $relationships = 3, $lookupTable) {
        // Set the initial argument values
        $args['connections'] = $connections;
        $args['relationships'] = $relationships;
        $args['tablename'] = $lookupTable;

        if ($lookupTable == 'posts_to_media_content') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('posts');
            $args['table2_ids'] = getTableIds('media_content');
            $args['field1'] = 'postId';
            $args['field2'] = 'mediaContentId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'posts_to_tags') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('posts');
            $args['table2_ids'] = getTableIds('tags');
            $args['field1'] = 'postId';
            $args['field2'] = 'tagId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'posts_to_labels') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('posts');
            $args['table2_ids'] = getTableIds('labels');
            $args['field1'] = 'postId';
            $args['field2'] = 'labelId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'posts_to_categories') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('posts');
            $args['table2_ids'] = getTableIds('categories');
            $args['field1'] = 'postId';
            $args['field2'] = 'categoryId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'content_to_tags') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('content');
            $args['table2_ids'] = getTableIds('tags');
            $args['field1'] = 'contentId';
            $args['field2'] = 'tagId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'content_to_categories') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('content');
            $args['table2_ids'] = getTableIds('categories');
            $args['field1'] = 'contentId';
            $args['field2'] = 'categoryId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'media_content_to_tags') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('media_content');
            $args['table2_ids'] = getTableIds('tags');
            $args['field1'] = 'mediaContentId';
            $args['field2'] = 'tagId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'media_content_to_categories') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('media_content');
            $args['table2_ids'] = getTableIds('categories');
            $args['field1'] = 'mediaContentId';
            $args['field2'] = 'categoryId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable == 'content_to_labels') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('content');
            $args['table2_ids'] = getTableIds('labels');
            $args['field1'] = 'contentId';
            $args['field2'] = 'labelId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        } elseif ($lookupTable = 'users_to_permissions') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = getTableIds('users');
            $args['table2_ids'] = getTableIds('permissions');
            $args['field1'] = 'userId';
            $args['field2'] = 'permissionId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insertIntoLookupTable($args);

        }
    }

    // Function to escape strings
    public function escape($string) {
        return $this->dbConnection->escape_string($string);
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

    public function selectFromTable($tablename, $numRecords = 10) {

        $sql = "SELECT * FROM " . $tablename . " LIMIT " . $numRecords;

        $result = $this->dbConnection->query($sql);

        if ($result) {

            // Loop through the records and store them in our array
            while ($row = $result->fetch_assoc()) {
                array_push($this->latest_selection_array, $row);
            }

            return "Selected " . $this->dbConnection->affected_rows . " rows from " . $tablename .  " successfully!";

        } else {
            array_push($this->errors_array, $tablename . ": " . $this->dbConnection->error);
            return false;
        }
    }

    // ===================================================== TABLE CLEAR DATA FUNCTIONS ==================================================================

    public function truncateTable($tablename) {
        $sql = "TRUNCATE TABLE ";

        if ($tablename == 'all') {
            $tables = $this->show_all_tables();
            foreach($tables as $table) {
                if ($table == end($tables)) {
                    $sql .= $table;
                } else {
                    $sql .= $table . ", ";
                }
            }
        } else {
            $sql = "TRUNCATE TABLE " . $tablename;
        }

        $this->executeTruncateQuery($sql, $tablename);
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

            $author = $this->Faker->numberBetween(1, $maxId);
            $authorName = $this->escape($this->Faker->name);
            $comments = $this->Faker->numberBetween(1, $maxId);
            $content = $this->escape($this->Faker->paragraph($this->Faker->numberBetween(1, $maxId)));
            $createdBy = $this->Faker->numberBetween(1, $maxId);
            $createdDate = $this->Faker->dateTimeThisYear($max = 'now')->format('Y-m-d');
            $postDate = $this->Faker->dateTimeThisYear($max = 'now')->format('Y-m-d');
            $status = $this->Faker->numberBetween(0, 1);
            $title = $this->escape($this->Faker->sentence($nbWords = 3, $variableNbWords = true));


            $sql .= "( " . $author . ", ";
            $sql .= "'" . $authorName . "', " . $comments . ", ";
            $sql .= "'" . $content . "', ";
            $sql .=  $createdBy . ", " . "'" .  $createdDate . "', ";
            $sql .= "'" . $postDate . "', " . $status . ", ";
            $sql .= "'" . $title . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'posts');
    }

    // INSERT INTO bookmarks
    public function insertIntoBookmarks($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO bookmarks ( ";
        $sql .= "userId, url, name ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $userId = $this->Faker->numberBetween(1, $maxId);
            $url = $this->escape($this->Faker->url());
            $name = $this->escape($this->Faker->domainword());

            $sql .= "( " . $userId . ", ";
            $sql .= "'" . $url . "', ";
            $sql .= "'" . $name . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'bookmarks');
    }

    // INSERT INTO categories
    public function insertIntoCategories($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO categories ( ";
        $sql .= "title, sudCatId, note ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $title = $this->escape($this->Faker->numberBetween(1, $maxId));
            $sudCatId = $this->Faker->numerBetween(1, $maxId);
            $note = $this->escape($this->Faker->sentence());

            $sql .= "( '" . $title . "', ";
            $sql .= "" . $sudCatId . ", ";
            $sql .= "'" . $note . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'categories');
    }

    // INSERT INTO comments
    public function insertIntoComments($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO comments ( ";
        $sql .= "title, createdDate, comment, status, name, createdBy, approvedBy, postId ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $title = $this->escape($this->Faker->title());
            $createdDate = $this->escape($this->Faker->dateTime($max = 'now'));
            $comment = $this->escape($this->Faker->sentence());
            $status = $this->Faker->boolean();
            $name = $this->escape($this->Faker->title());
            $createdBy = $this->Faker->numberBetween(1, $maxId);
            $approvedBy = $this->Faker->numberBetween(1, $maxId);
            $postId = $this->Faker->numberBetween(1, $maxId);

            $sql .= "( '" . $title . "', ";
            $sql .= "'" . $createdDate . "', ";
            $sql .= "'" . $comment . "', ";
            $sql .= $status . ", ";
            $sql .= "'" . $name . "', ";
            $sql .= $createdBy . ", ";
            $sql .= $approvedBy . ", ";
            $sql .= $postId . " )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'comments');
    }

    // INSERT INTO content
    public function insertIntoContent($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO content ( ";
        $sql .= " createdBy, changedDate, content ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $changedDate = $this->escape($this->Faker->dateTime($max = 'now'));
            $content = "{}";
            $createdBy = $this->Faker->numberBetween(1, $maxId);

            $sql .= "( " . $createdBy . ", ";
            $sql .= "'" . $changedDate . "', ";
            $sql .= $content . " )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'content');
    }

    // INSERT INTO labels OR tags
    public function insertIntoLabelsOrTags($tablename= 'labels', $numRecords = 10, $maxId = 3) { // $tablename can be 'labels or tags'
        if ($tablename != 'labels' || $tablename != 'tags') {
            return FALSE;
        }

        $sql = "INSERT INTO " . $tablename . " ( ";
        $sql .= "title, note ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $title = $this->escape($this->Faker->title());
            $note = $this->escape($this->Faker->sentence());

            $sql .= "( '" . $title . "', ";
            $sql .= "'" . $note . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, $tablename);
    }

    // INSERT INTO media_content
    public function insertIntoMediaContent($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO media_content ( ";
        $sql .= " name, type, note, alt, createdBy, createdDate ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $name = $this->escape($this->Faker->name());
            $type = $this->escape($this->Faker->word());
            $note = $this->escape($this->Faker->sentence());
            $alt = $this->escape($this->Faker->word());
            $createdBy = $this->Faker->numberBetween(1, $maxId);
            $createdDate = $this->escape($this->Faker->dateTime($max = 'now'));

            $sql .= "( '" . $name . "', ";
            $sql .= "'" . $type . "', ";
            $sql .= "'" . $note . "', ";
            $sql .= "'" . $alt . "', ";
            $sql .= $createdBy . ", ";
            $sql .= "'" . $createdDate . "' ) ";


            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'media_content');
    }

    // INSERT INTO users
    public function insertIntoUsers($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO users ( ";
        $sql .= " username, password, firstName, lastName, address, phoneNumber, emailAddress, title, mediaContent, adminNote, note, showOnWeb, createdBy ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $username = $this->escape($this->Faker->username());
            $password = $this->escape($this->Faker->password());
            $firstName = $this->escape($this->Faker->firstName());
            $lastName = $this->escape($this->Faker->lastName());
            $address = $this->escape($this->Faker->address());
            $phoneNumber = $this->escape($this->Faker->phoneNumber());
            $emailAddress = $this->escape($this->Faker->email());
            $title = $this->escape($this->Faker->title());
            $mediaContent = $this->Faker->numberBetween(1, $maxId);
            $adminNote = $this->escape($this->Faker->sentence());
            $note = $this->escape($this->Faker->sentence());
            $showOnweb = $this->Faker->boolean();
            $createdBy = $this->Faker->numberBetween(1, $maxId);

            $sql .= "( '" . $name . "', ";
            $sql .= "'" . $type . "', ";
            $sql .= "'" . $note . "', ";
            $sql .= "'" . $alt . "', ";
            $sql .= $createdBy . ", ";
            $sql .= "'" . $createdDate . "' ) ";


            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, 'media_content');
    }

    // INSERT INTO any lookuptable. Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
    // Connections are between one table and another. eg: 1 to 2, 1 to 1, 1 to 3, are all one connection because they come from the same table
    // Relationships are between 1 id and another. eg 1 to 2, 1 to 1, 1 to 3 are each a separate relationship. 3 relationships are listed
    // One connection can contain one or more relationships
    private function insertIntoLookupTable($args = []) {
        // Check to see if we have enough ids to form the number of requested connections
        if ($args['connections'] > max($args['table1_ids'])) {
            $connections = max($args['table1_ids']);
        } else {
            $connectons = $args['connections'];
        }

        // Check to see if we have enough ids to form the number of requested relationships
        if ($args['relationships'] > max($args['table2_ids'])) {
            $relationships = max($args['table2_ids']);
        } else {
            $relationships = $args['relationships'];
        }


        $sql = "INSERT INTO " . $args['tablename'] . " ( ";
        $sql .= $args['field1'] . ", " . $args['field2'] . ") ";
        $sql .= "VALUES ";

        for ($i = 0; $i < $connections; $i++) {

            // Add a comma between each connection
            if ($i != 0) {
                $sql .= ", ";
            }

            for ($j = 0; $j < $relationships; $j++) {

                $sql .= "( " . $args['table1_ids'][$i] . ", " . $args['table2_ids'][$j] . " )";

                // Insert a comma between each value
                if ($j == $relationships - 1) {
                    $sql .= ", ";
                }
            }
        }

        // Execute the query
        return  $this->executeInsertQuery($sql, $args['tablename']);
    }
}
    
// ================= END OF DB DEV TOOLS CLASS ==============================
?>

