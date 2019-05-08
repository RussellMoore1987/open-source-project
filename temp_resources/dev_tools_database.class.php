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
    public $mysqli;
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

        // Create a Faker object for user with populating random data
        $this->Faker = Faker\Factory::create();
    }

    private function connect_to_database() {
        // Check if we have any null values
        if (is_null($this->SERVERNAME) || is_null($this->USERNAME) || is_null($this->PASSWORD)) {
            $this->errors_array[] = "Could not connect to the database. Missing one of these values: Servername, Username, Password,";
            return false;

        // Connect to the database without a name
        } else if (is_null($this->DBNAME)) {
            $this->mysqli = new mysqli($this->SERVERNAME, $this->USERNAME, $this->PASSWORD);
            if ($this->mysqli->connect_error) {
                die($this->mysqli);
                $this->errors_array[] = "Database Connection Error";
                return false;
            }

        // Connect to the database with a name
        } else {
            $this->mysqli = new mysqli($this->SERVERNAME, $this->USERNAME, $this->PASSWORD, $this->DBNAME);
            if ($this->mysqli->connect_error) {
                die($this->mysqli);
                $this->errors_array[] = $this->mysqli->connect_error;
                return false;
            }
        }
    }

    // Function to show all tables
    public function show_all_tables() {
        $sql = "SHOW tables";
        $result = $this->mysqli->query($sql);
        if ($result !== false) {
            $tableList = [];
            while($row = $result->fetch_array()) {
                $tableList[] = $row[0];
            }
            return $tableList;
        } else {
            $this->errors_array[] = $this->mysqli->error;
            return false;
        }
    }

    // Function to disable/enable foreign key constraints for table creation and drop
    private function toggle_foreign_key_checks($toggle) {
        // Toggle the key checks OFF
        if ($toggle === false) {
            $sql = "SET FOREIGN_KEY_CHECKS = 0";
            $this->mysqli->query($sql);

        // Toggle the key checks ON
        } else {
            $sql = "SET FOREIGN_KEY_CHECKS = 1";
            $this->mysqli->query($sql);
        }
    }

    // Function to execute table create sql queries
    private function execute_create_query($query, $tablename=NULL) {
        // DISABLE Foreign key checks in preparation
        // # just a consistency note, lowercase true and false I would prefer, uppercase NULL ******** todo *********
        $this->toggle_foreign_key_checks(false);

        if ($this->mysqli->query($query) === true) {
            // ENABLE Foreign key checks when finished
            $this->toggle_foreign_key_checks(true);

            return "Table " . $tablename . " created successfully!";
        } else {

            $this->errors_array[] = $tablename . " CREATE ERROR : " . $this->mysqli->error;

            // ENABLE Foreign key checks when finished
            $this->toggle_foreign_key_checks(true);

            return false;
        }
    }

    // Function to execute truncate table sql queries
    private function execute_truncate_query($query, $tablename=NULL) {
        // DISABLE Foreign key checks in preparation
        $this->toggle_foreign_key_checks(false);

        if ($this->mysqli->query($query) === true) {
            // ENABLE Foreign key checks when finished
            $this->toggle_foreign_key_checks(true);

            return  $tablename .  " truncated successfully!";
        } else {

            $this->errors_array[] = $tablename . " TRUNCATE ERROR: " . $this->mysqli->error;

            // ENABLE Foreign key checks when finished
            $this->toggle_foreign_key_checks(true);

            return false;
        }
    }

    // Function to execute insert sql queries
    private function execute_insert_query($query, $tablename=NULL, $numRecords = 10) {
        // DISABLE Foreign key checks in preparation
        $this->toggle_foreign_key_checks(false);

        if ($this->mysqli->query($query) === true) {

            $message = $this->mysqli->affected_rows . " out of " . $numRecords . " rows inserted/updated in " . $tablename .  " successfully!";

            // ENABLE Foreign key checks when finished
            $this->toggle_foreign_key_checks(true);

            return $message;
        } else {

            $this->errors_array[] = $tablename . " INSERT ERROR: " . $this->mysqli->error;

            // ENABLE Foreign key checks when finished
            $this->toggle_foreign_key_checks(true);

            return false;
        }
    }

    // Function to get a list of IDs from a table
    public function get_table_ids($tablename = NULL) {
        $id_array = [];

        if ($tablename == NULL) {
            $id_array[0] = 1;
        } else {
            // run sql
            $sql = "SELECT id FROM " . $tablename;
            $result = $this->mysqli->query($sql);
            if ($result) {
                // Loop through the records and store the ids in our array
                while ($row = $result->fetch_assoc()) {
                    $id_array[] = $row['id'];
                }
            }
        }
        // return data
        return $id_array;
    }

    // Function to randomize the number of connections in a lookup table
    // Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
    private function create_lookup_table_connections($lookupTable, $connections = 2, $relationships = 3) {
        // Set the initial argument values
        $args['connections'] = $connections;
        $args['relationships'] = $relationships;
        $args['tablename'] = $lookupTable;

        if ($lookupTable == 'posts_to_media_content') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('posts');
            $args['table2_ids'] = $this->get_table_ids('media_content');
            $args['field1'] = 'postId';
            $args['field2'] = 'mediaContentId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'posts_to_tags') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('posts');
            $args['table2_ids'] = $this->get_table_ids('tags');
            $args['field1'] = 'postId';
            $args['field2'] = 'tagId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'posts_to_labels') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('posts');
            $args['table2_ids'] = $this->get_table_ids('labels');
            $args['field1'] = 'postId';
            $args['field2'] = 'labelId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'posts_to_categories') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('posts');
            $args['table2_ids'] = $this->get_table_ids('categories');
            $args['field1'] = 'postId';
            $args['field2'] = 'categoryId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'content_to_tags') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('content');
            $args['table2_ids'] = $this->get_table_ids('tags');
            $args['field1'] = 'contentId';
            $args['field2'] = 'tagId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'content_to_categories') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('content');
            $args['table2_ids'] = $this->get_table_ids('categories');
            $args['field1'] = 'contentId';
            $args['field2'] = 'categoryId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'media_content_to_tags') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('media_content');
            $args['table2_ids'] = $this->get_table_ids('tags');
            $args['field1'] = 'mediaContentId';
            $args['field2'] = 'tagId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'media_content_to_categories') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('media_content');
            $args['table2_ids'] = $this->get_table_ids('categories');
            $args['field1'] = 'mediaContentId';
            $args['field2'] = 'categoryId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'media_content_to_labels') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('media_content');
            $args['table2_ids'] = $this->get_table_ids('labels');
            $args['field1'] = 'mediaContentId';
            $args['field2'] = 'labelId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable == 'content_to_labels') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('content');
            $args['table2_ids'] = $this->get_table_ids('labels');
            $args['field1'] = 'contentId';
            $args['field2'] = 'labelId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        } elseif ($lookupTable = 'users_to_permissions') {

            // Get the ids and set the field values of the lookup table
            $args['table1_ids'] = $this->get_table_ids('users');
            $args['table2_ids'] = $this->get_table_ids('permissions');
            $args['field1'] = 'userId';
            $args['field2'] = 'permissionId';

            // Create the connection in the lookup table by inserting the IDs
            return $this->insert_into_lookup_table($args);

        }
    }

    // Function to escape strings
    public function escape($string) {
        return $this->mysqli->escape_string($string);
    }

    // ========================================== TABLE CREATION FUNCTIONS ===============================================================
    // CREATE ALL TABLES
    public function create_all_tables() {
        $results = [];

        // Base Tables
        $results[] = $this->create_categories_table();
        $results[] = $this->create_comments_table();
        $results[] = $this->create_posts_table();
        $results[] = $this->create_tags_table();
        $results[] = $this->create_labels_table();
        $results[] = $this->create_users_table();
        $results[] = $this->create_media_content_table();
        $results[] = $this->create_todo_table();
        $results[] = $this->create_main_settings_table();
        $results[] = $this->create_style_settings_table();
        $results[] = $this->create_personal_settings_table();
        $results[] = $this->create_content_table();
        $results[] = $this->create_bookmarks_table();
        $results[] = $this->create_permissions_table();

        // Lookup Tables
        $results[] = $this->create_posts_to_media_content_table();
        $results[] = $this->create_posts_to_tags_table();
        $results[] = $this->create_posts_to_labels_table();
        $results[] = $this->create_posts_to_categories_table();
        $results[] = $this->create_media_content_to_tags_table();
        $results[] = $this->create_media_content_to_categories_table();
        $results[] = $this->create_media_content_to_labels_table();
        $results[] = $this->create_content_to_tags_table();
        $results[] = $this->create_content_to_labels_table();
        $results[] = $this->create_content_to_categories_table();
        $results[] = $this->create_users_to_permsissions_table();

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
    public function create_posts_table() {
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
        return $this->execute_create_query($sql, 'posts');
    }

    // TAGS
    public function create_tags_table() {
        $sql = "CREATE TABLE IF NOT EXISTS tags ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'tags');
    }

    // LABELS
    public function create_labels_table() {
        $sql = "CREATE TABLE IF NOT EXISTS labels ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'labels');
    }

    // USERS
    public function create_users_table() {
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
        return $this->execute_create_query($sql, 'users');
    }

    // CATEGORIES
    public function create_categories_table() {
        $sql = "CREATE TABLE IF NOT EXISTS categories ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "title VARCHAR(50) NOT NULL, ";
        $sql .= "subCatId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "note VARCHAR(255) DEFAULT NULL )";
        // TODO: What does the SudCatId reference?
        // $sql .= "FOREIGN KEY (sudCatId) REFERENCES ?(?) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'categories');
    }

    // MEDIA CONTENT
    public function create_media_content_table() {
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
        return $this->execute_create_query($sql, 'media_content');
    }
    

    // COMMENTS
    public function create_comments_table() {
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
        return $this->execute_create_query($sql, 'comments');
    }

    // TODO
    public function create_todo_table() {
        $sql = "CREATE TABLE IF NOT EXISTS todo ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "todo VARCHAR(255), ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'todo');
    }

    // MAIN SETTINGS
    public function create_main_settings_table() {
        $sql = "CREATE TABLE IF NOT EXISTS main_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "changedDate DATE, ";
        $sql .= "apiUse VARCHAR(255) NOT NULL, ";
        $sql .= "apiKey VARCHAR(255), ";
        $sql .= "commentKey VARCHAR(255), ";
        $sql .= "contentKey VARCHAR(255), ";
        $sql .= "mainSettings JSON NOT NULL )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'main_settings');
    }

    // PERSONAL SETTINGS
    public function create_personal_settings_table() {
        $sql = "CREATE TABLE IF NOT EXISTS personal_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "changedDate DATE, ";
        $sql .= "personalSettings JSON NOT NULL )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'personal_settings');
    }

    // STYLE SETTINGS
    public function create_style_settings_table() {
        $sql = "CREATE TABLE IF NOT EXISTS style_settings ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "changedDate DATE, ";
        $sql .= "styleSettings JSON NOT NULL, ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'style_settings');
    }

    // CONTENT
    public function create_content_table() {
        $sql = "CREATE TABLE IF NOT EXISTS content ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "createdBy INT(10) UNSIGNED NOT NULL, ";
        $sql .= "changedDate DATE, ";
        $sql .= "content JSON, ";
        $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'content');
    }

    // BOOKMARKS
    public function create_bookmarks_table() {
        $sql = "CREATE TABLE IF NOT EXISTS bookmarks ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
        $sql .= "url VARCHAR(255), ";
        $sql .= "name VARCHAR(50), ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'bookmarks');
    }

    // PERMISSIONS
    public function create_permissions_table() {
        $sql = "CREATE TABLE IF NOT EXISTS permissions ( ";
        $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $sql .= "name VARCHAR(50), ";
        $sql .= "description VARCHAR(255) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'permissions');
    }

    // ------ Lookup Table Creation ----------

    // POSTS TO MEDIA CONTENT
    public function create_posts_to_media_content_table() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_media_content ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (postId, mediaContentId), ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'posts_to_media_content');
    }

    // POSTS TO TAGS
    public function create_posts_to_tags_table() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_tags ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (postId, tagId), ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'posts_to_tags');
    }

    // POSTS TO LABELS
    public function create_posts_to_labels_table() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_labels ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (postId, labelId), ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'posts_to_labels');
    }

    // POSTS TO CATEGORIES
    public function create_posts_to_categories_table() {
        $sql = "CREATE TABLE IF NOT EXISTS posts_to_categories ( ";
        $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (postId, categoryId), ";
        $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
        $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'posts_to_categories');
    }

    // MEDIA CONTENT TO TAGS
    public function create_media_content_to_tags_table() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content_to_tags ( ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (mediaContentId, tagId), ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
        $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'media_content_to_tags');
    }

    // MEDIA CONTENT TO LABELS
    public function create_media_content_to_labels_table() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content_to_labels ( ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (mediaContentId, labelId), ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
        $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'media_content_to_labels');
    }

    // MEDIA CONTENT TO CATEGORIES
    public function create_media_content_to_categories_table() {
        $sql = "CREATE TABLE IF NOT EXISTS media_content_to_categories ( ";
        $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (mediaContentId, categoryId), ";
        $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
        $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'media_content_to_categories');
    }

    // CONTENT TO TAGS
    public function create_content_to_tags_table() {
        $sql = "CREATE TABLE IF NOT EXISTS content_to_tags ( ";
        $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (contentId, tagId), ";
        $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
        $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'content_to_tags');
    }

    // CONTENT TO LABELS
    public function create_content_to_labels_table() {
        $sql = "CREATE TABLE IF NOT EXISTS content_to_labels ( ";
        $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (contentId, labelId), ";
        $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
        $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'content_to_labels');
    }

    // CONTENT TO CATEGORIES
    public function create_content_to_categories_table() {
        $sql = "CREATE TABLE IF NOT EXISTS content_to_categories ( ";
        $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (contentId, categoryId), ";
        $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
        $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'content_to_categories');
    }

    // USER TO PERMISSIONS
    public function create_users_to_permsissions_table() {
        $sql = "CREATE TABLE IF NOT EXISTS users_to_permissions ( ";
        $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "permissionId INT(10) UNSIGNED NOT NULL, ";
        $sql .= "PRIMARY KEY (userId, permissionId), ";
        $sql .= "FOREIGN KEY (userId) REFERENCES users(id), ";
        $sql .= "FOREIGN KEY (permissionId) REFERENCES permissions(id) )";

        // Execute the query then return the result
        return $this->execute_create_query($sql, 'users_to_permissions');
    }

    // ===================================================== TABLE DROP FUNCTIONS ==================================================================
    public function drop_table($tablename) {
        // Remove foreign key checks in preparation to drop the tables
        $this->toggle_foreign_key_checks(false);

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

        if ($this->mysqli->query($sql) === true) {
            // Turn on foreign key checks after dropping the tables
            $this->toggle_foreign_key_checks(true);

            return "Table(s) " . $tablename . " Dropped Successfully!";

        } else {
            $this->errors_array[] = $this->mysqli->error;
            return false;
        }
    }

    // ===================================================== TABLE SELECT FUNCTIONS ==================================================================

    public function select_from_table($tablename, $numRecords = 10) {

        $sql = "SELECT * FROM " . $tablename . " LIMIT " . $numRecords;

        $result = $this->mysqli->query($sql);

        if ($result) {

            // Loop through the records and store them in our array
            while ($row = $result->fetch_assoc()) {
                $this->latest_selection_array[] = $row;
            }

            return "Selected " . $this->mysqli->affected_rows . " rows from " . $tablename .  " successfully!";

        } else {
            $this->errors_array[] = $tablename . ": " . $this->mysqli->error;
            return false;
        }
    }

    // ===================================================== TABLE CLEAR DATA FUNCTIONS ==================================================================

    public function truncate_table($tablename) {
        
        $messages = [];

        if ($tablename == 'all') {
            $tables = $this->show_all_tables();
            foreach($tables as $table) {
                $sql = "TRUNCATE TABLE " . $table;
                $messages[] = $this->execute_truncate_query($sql, $table);
            }

            return $messages;
        } else {
            $sql = "TRUNCATE TABLE " . $tablename;
            return $this->execute_truncate_query($sql, $tablename);
        }

    }

    // ===================================================== TABLE INSERT FUNCTIONS ==================================================================
    
    // INSERT INTO ALL TABLES
    public function insert_into_all_tables($numRecords = 50, $maxId = 50) {
        $results = [];

        $results[] = $this->insert_into_posts($numRecords, $maxId);
        $results[] = $this->insert_into_bookmarks($numRecords, $maxId);
        $results[] = $this->insert_into_categories($numRecords, $maxId);
        $results[] = $this->insert_into_comments($numRecords, $maxId);
        $results[] = $this->insert_into_content($numRecords, $maxId);
        $results[] = $this->insert_into_labels_or_tags('labels', $numRecords, $maxId);
        $results[] = $this->insert_into_labels_or_tags('tags', $numRecords, $maxId);
        $results[] = $this->insert_into_media_content($numRecords, $maxId);
        $results[] = $this->insert_into_users($numRecords, $maxId);
        $results[] = $this->insert_into_permissions($numRecords, $maxId);

        // Use the lookupTablesArray to go through and create the connections by inserting into each lookup table
        $lookupTablesArray = ['posts_to_media_content', 'posts_to_tags', 'posts_to_labels', 'posts_to_categories', 'media_content_to_tags', 'media_content_to_labels', 'media_content_to_categories', 'content_to_tags', 'content_to_labels', 'content_to_categories', 'users_to_permissions'];

        foreach($lookupTablesArray as $table) {
            $results[] = $this->create_lookup_table_connections($table);
        }

        foreach ($results as $result) {
            if ($result === false) {
                return false;
                break;
            } else {
                // Do Nothing
            }
        }
        
        return "All data inserted successfully!";
    }

    // Insert into posts
    public function insert_into_posts($numRecords = 10, $maxId = 3) {

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
            $title = $this->escape($this->Faker->word());


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
        return  $this->execute_insert_query($sql, 'posts', $numRecords);
    }

    // INSERT INTO bookmarks
    public function insert_into_bookmarks($numRecords = 10, $maxId = 3) {

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
        return  $this->execute_insert_query($sql, 'bookmarks', $numRecords);
    }

    // INSERT INTO categories
    public function insert_into_categories($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO categories ( ";
        $sql .= "title, subCatId, note ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $title = $this->escape($this->Faker->word());
            $subCatId = $this->Faker->numberBetween(1, $maxId);
            $note = $this->escape($this->Faker->sentence());

            $sql .= "( '" . $title . "', ";
            $sql .= "" . $subCatId . ", ";
            $sql .= "'" . $note . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->execute_insert_query($sql, 'categories', $numRecords);
    }

    // INSERT INTO comments
    public function insert_into_comments($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO comments ( ";
        $sql .= "title, createdDate, comment, status, name, createdBy, approvedBy, postId ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $title = $this->escape($this->Faker->word());
            $createdDate = $this->Faker->dateTime($max = 'now')->format('Y-m-d');
            $comment = $this->escape($this->Faker->sentence());
            $status = (int) $this->Faker->boolean();
            $name = $this->escape($this->Faker->sentence(rand(1, 3)));
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
        return  $this->execute_insert_query($sql, 'comments', $numRecords);
    }

    // INSERT INTO content
    public function insert_into_content($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO content ( ";
        $sql .= " createdBy, changedDate ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $changedDate = $this->Faker->dateTime($max = 'now')->format('Y-m-d');
            $createdBy = $this->Faker->numberBetween(1, $maxId);

            $sql .= "( " . $createdBy . ", ";
            $sql .= "'" . $changedDate . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->execute_insert_query($sql, 'content', $numRecords);
    }

    // INSERT INTO labels OR tags
    public function insert_into_labels_or_tags($tablename= 'labels', $numRecords = 10, $maxId = 3) { // $tablename can be 'labels or tags'

        $sql = "INSERT INTO " . $tablename . " ( ";
        $sql .= "title, note ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $title = $this->escape($this->Faker->sentence(rand(1, 2)));
            $note = $this->escape($this->Faker->sentence());

            $sql .= "( '" . $title . "', ";
            $sql .= "'" . $note . "' )";

            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->execute_insert_query($sql, $tablename, $numRecords);
    }

    // INSERT INTO media_content
    public function insert_into_media_content($numRecords = 10, $maxId = 3) {

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
            $createdDate = $this->Faker->dateTime($max = 'now')->format('Y-m-d');

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
        return  $this->execute_insert_query($sql, 'media_content', $numRecords);
    }

    // INSERT INTO users
    public function insert_into_users($numRecords = 10, $maxId = 3) {

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
            $phoneNumber = $this->escape($this->Faker->tollFreePhoneNumber());
            $emailAddress = $this->escape($this->Faker->email());
            $title = $this->escape($this->Faker->title());
            $mediaContent = $this->Faker->numberBetween(1, $maxId);
            $adminNote = $this->escape($this->Faker->sentence());
            $note = $this->escape($this->Faker->sentence());
            $showOnWeb = (int) $this->Faker->boolean();
            $createdBy = $this->Faker->numberBetween(1, $maxId);

            $sql .= "( '" . $username . "', ";
            $sql .= "'" . $password . "', ";
            $sql .= "'" . $firstName . "', ";
            $sql .= "'" . $lastName . "', ";
            $sql .= "'" . $address . "', ";
            $sql .= "'" . $phoneNumber . "', ";
            $sql .= "'" . $emailAddress . "', ";
            $sql .= "'" . $title . "', ";
            $sql .= $mediaContent . ", ";
            $sql .= "'" . $adminNote . "', ";
            $sql .= "'" . $note . "', ";
            $sql .= $showOnWeb . ", ";
            $sql .= $createdBy . " )";


            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->execute_insert_query($sql, 'users', $numRecords);
    }

    // INSERT INTO permissions
    public function insert_into_permissions($numRecords = 10, $maxId = 3) {

        $sql = "INSERT INTO permissions ( ";
        $sql .= " name, description ) ";
        $sql .= "VALUES ";

        // Populate the dynamic data into the query
        for ($i = 0; $i < $numRecords; $i++) {

            $name = $this->escape($this->Faker->word());
            $description = $this->escape($this->Faker->sentence());

            $sql .= "( '" . $name . "', ";
            $sql .= "'" . $description . "' )";


            // If we are not on the last iteration then add a comma for the next statement to be inserted
            if ($i != $numRecords - 1) {
                $sql .= ", ";
            }
        }

        // Execute the query
        return  $this->execute_insert_query($sql, 'permissions', $numRecords);
    }

    // INSERT INTO any lookuptable. Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
    // Connections are between one table and another. eg: 1 to 2, 1 to 1, 1 to 3, are all one connection because they come from the same table
    // Relationships are between 1 id and another. eg 1 to 2, 1 to 1, 1 to 3 are each a separate relationship. 3 relationships are listed
    // One connection can contain one or more relationships
    public function insert_into_lookup_table($args = []) {
        $connections = NULL;
        $relationships = NULL;
        $errorMessage = false;

        // Check if our table ids are defined and contain data
        if (empty($args['table1_ids'])) {
            $this->errors_array[] = "No IDs in Table 1!";
            $errorMessage = true;
        } 
        
        if (empty($args['table2_ids'])) {
            $this->errors_array[] = "No IDs in Table 2!";
            $errorMessage = true;
        }

        // Return the error message if we have one and do not continue the function
        if ($errorMessage) {
            return false;
        }

        // Sort the IDs initially in ascending order
        sort($args['table1_ids']);
        sort($args['table2_ids']);

        // Check to see if we have enough ids to form the number of requested connections
        if ($args['connections'] > max($args['table1_ids'])) {
            $connections = max($args['table1_ids']);
        } else {
            $connections = $args['connections'];
        }

        // Check to see if we have enough ids to form the number of requested relationships
        if ($args['relationships'] > max($args['table2_ids'])) {
            $relationships = max($args['table2_ids']);
        } else {
            $relationships = $args['relationships'];
        }

        // Using the ignore statement to ignore inserting
        $sql = "INSERT INTO " . $args['tablename'] . " ( ";
        $sql .= $args['field1'] . ", " . $args['field2'] . ") ";
        $sql .= "VALUES ";

        for ($i = 0; $i < $connections; $i++) {

            for ($j = 0; $j < $relationships; $j++) {

                $sql .= "( " . $args['table1_ids'][$i] . ", " . $args['table2_ids'][$j] . " )";

                // Insert a comma between each value
                if ($j != $relationships - 1) {
                    $sql .= ", ";
                }
            }

            // Add a comma between each connection
            if ($i != $connections - 1) {
                $sql .= ", ";
            }
        }

        // Tag on the ON DUPLICATE KEY to the end of our query
        $sql .= " ON DUPLICATE KEY UPDATE " .  $args['field1'] . " = " . $args['field1'];

        // Execute the query
        return  $this->execute_insert_query($sql, $args['tablename'], ($connections * $relationships));
    }
}
    
// ================= END OF DB DEV TOOLS CLASS ==============================
?>

