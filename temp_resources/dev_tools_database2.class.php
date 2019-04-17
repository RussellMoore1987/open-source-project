<?php
// todo: add emphasis on to connecting cables as appropriate for final version of the regular install process ***
// The dev tools used for our project, mostly for database management
// todo: ???????
// require_once("../vendor/autoload.php");
require_once("../vendor/fzaninotto/faker/src/autoload.php");

// ============ START OF DB DEV TOOLS CLASS ===================
class Database {

    // @ class information start

        // faker object
        private $Faker;

        public $errors_array = [];
        public $latest_selection_array = [];
        public $select_table_name = NULL;

        // user pic count, this is important for giving correct photos to users
        public $userPicCount = 9;

        // record details
        protected $numRecords_bookMarks = 34;
        protected $numRecords_categories = 18; 
        protected $numRecords_comments = 509; 
        protected $numRecords_contents = 69; 
        // only add what we have for fake data
        protected $numRecords_mediaContents = 19; 
        protected $numRecords_labels = 22; 
        // only add what we have for fake data
        protected $numRecords_permissions = 6; 
        protected $numRecords_posts = 294; 
        protected $numRecords_tags = 294; 
        protected $numRecords_todos = 116; 
        protected $numRecords_users = 25; 

        // image helper
        protected $imageData = [
            ["imageName"=>"shay_profile_pic.jpg","fileType"=>"JPG"],
            ["imageName"=>"stephanie_profile_pic.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-1.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-2.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-3.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-4.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-5.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-6.jpg","fileType"=>"JPG"],
            ["imageName"=>"abd_profile_pic.jpg","fileType"=>"JPG"],
            ["imageName"=>"2018-03-19_14-56-54.png","fileType"=>"PNG"],
            ["imageName"=>"adilas_e-mail_featured_image-1.jpg","fileType"=>"JPG"],
            ["imageName"=>"adilas_university_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"code_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"Full-5.mp4","fileType"=>"MP4"],
            ["imageName"=>"news_and_update.jpg","fileType"=>"JPG"],
            ["imageName"=>"php_cms_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"that_herb_shop_featured_image.jpg","fileType"=>"JPG"],
            ["imageName"=>"Untitled-Project.mp4","fileType"=>"MP4"],
            ["imageName"=>"wicked_cute_boutique_featured_image.jpg","fileType"=>"JPG"]
        ];

    // @ class information end

    // @ main databases information start
        private $SERVERNAME;
        private $USERNAME;
        private $PASSWORD;
        private $DBNAME;
        
        // only use inside the class
        protected $mysqli;

        // starting up db methods
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

    // @ main databases information end

    // @ main instantiate start
        public function __construct($args) {
            // Set the variables for the connection
            $this->SERVERNAME = $args['servername'] ?? NULL;
            $this->USERNAME = $args['username'] ?? NULL;
            $this->PASSWORD = $args['password'] ?? NULL;
            $this->DBNAME = $args['dbname'] ?? NULL;

            // Connect to the database
            $this->connect_to_database();

            // Create a Faker object for user with populating random data
            // todo: ???????
            // $this->Faker = Factory::create();
            $this->Faker = Faker\Factory::create();
        }
    // @ main instantiate end

    
    // @ queries start
        // # BOOKMARKS
        // create bookmarks table
        public function create_bookmarks_table() {
            $sql = "CREATE TABLE IF NOT EXISTS bookmarks ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "url VARCHAR(255), ";
            $sql .= "name VARCHAR(50), ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'bookmarks');
        }

        // INSERT INTO bookmarks
        public function insert_into_bookmarks() {

            // build sql
            $sql = "INSERT INTO bookmarks ( ";
            $sql .= "userId, url, name ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_bookMarks; $i++) {

                $userId = $this->Faker->numberBetween(1, $this->numRecords_users);
                $url = $this->escape($this->Faker->url());
                $name = $this->escape($this->Faker->domainword());

                $sql .= "( " . $userId . ", ";
                $sql .= "'" . $url . "', ";
                $sql .= "'" . $name . "' )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_bookMarks - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'bookmarks', $this->numRecords_bookMarks);
        }

        // # CATEGORIES
        // create categories table
        public function create_categories_table() {
            $sql = "CREATE TABLE IF NOT EXISTS categories ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "title VARCHAR(50) NOT NULL, ";
            $sql .= "subCatId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "note VARCHAR(255) DEFAULT NULL, ";
            // * collection_type_reference, located at: root/private/reference_information.php
            $sql .= "useCat TINYINT(1) UNSIGNED NOT NULL, ";
            $sql .= "KEY subCatId (subCatId) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'categories');
        }

        // INSERT INTO categories
        public function insert_into_categories() {

            // stat sql
            $sql = "INSERT INTO categories ( ";
            $sql .= "title, subCatId, note, useCat ) ";
            $sql .= "VALUES ";

            $counter = 1;
            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_categories; $i++) {

                // make fake data
                $title = ucfirst($this->escape(str_replace(".","",$this->Faker->sentence(rand(1, 2)))));
                // need more 0 then not, also creating a section of sub classes
                if ($counter == 2 || $counter == 3 || $counter == 4 || $counter == 5) {
                        $subCatId = $counter - 1;
                } else {
                    $subCatId = 0;
                }
                $note = $this->escape($this->Faker->paragraph($nbSentences = 2));
                // need more 1 then not, make sure the first 5 are 1 
                if ($counter > 5) {
                    if ($i % 2) {
                        // * collection_type_reference, located at: root/private/reference_information.php
                        $useCat = $this->Faker->numberBetween(1, 4);
                    } else {
                        $useCat = 1;
                    }
                } else {
                    $useCat = 1;
                }

                // finish sql
                $sql .= "( '" . $title . "', ";
                $sql .= "" . $subCatId . ", ";
                $sql .= "'" . $note . "', ";
                $sql .= "'" . $useCat . "' )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_categories - 1) {
                    $sql .= ", ";
                }

                // increment counter
                $counter++;
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'categories', $this->numRecords_categories);
        }

        // # COMMENTS
        // create comments table
        public function create_comments_table() {
            $sql = "CREATE TABLE IF NOT EXISTS comments ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "title VARCHAR(50) NOT NULL, ";
            $sql .= "createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "comment VARCHAR(255) NOT NULL, ";
            $sql .= "status TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "name VARCHAR(50) NOT NULL, ";
            $sql .= "email VARCHAR(150) NOT NULL, ";
            $sql .= "actionBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
            // on delete cascade, this is meant for post comments
            $sql .= "FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'comments');
        }

        // INSERT INTO comments
        public function insert_into_comments() {

            // start sql
            $sql = "INSERT INTO comments ( ";
            $sql .= "title, createdDate, comment, status, name, email, actionBy, postId ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_comments; $i++) {

                // make fake data
                $title = ucfirst($this->escape(str_replace(".","",$this->Faker->sentence(rand(1, 2)))));
                $createdDate = $this->Faker->dateTime($max = 'now')->format('Y-m-d');
                $comment = $this->escape($this->Faker->paragraph($nbSentences = 2));
                // need some 0 = no action has taken place by a person
                if ($i > 10) {
                    $status = (int) $this->Faker->numberBetween(0, 1);
                } else {
                    $status = 0;
                }
                $name = $this->escape($this->Faker->firstName() . " " . $this->Faker->lastName());
                $email = $this->escape($this->Faker->email());
                // need some 0 = no action has taken place by a person
                if ($i > 10) {
                    $actionBy = $this->Faker->numberBetween(1, $this->numRecords_users);
                } else {
                    $actionBy = 0;
                }
                $postId = $this->Faker->numberBetween(1, $this->numRecords_posts);

                // finish sql
                $sql .= "( '" . $title . "', ";
                $sql .= "'" . $createdDate . "', ";
                $sql .= "'" . $comment . "', ";
                $sql .= $status . ", ";
                $sql .= "'" . $name . "', ";
                $sql .= "'" . $email . "', ";
                $sql .= $actionBy . ", ";
                $sql .= $postId . " )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_comments - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'comments', $this->numRecords_comments);
        }

        // # CONTENT
        // create content table
        public function create_content_table() {
            $sql = "CREATE TABLE IF NOT EXISTS content ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "createdBy INT(10) UNSIGNED NOT NULL, ";
            $sql .= "createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "catIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "tagIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "labelIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "content JSON, ";
            $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'content');
        }

        // INSERT INTO content
        public function insert_into_content() {

            $sql = "INSERT INTO content ( ";
            $sql .= " createdBy, createdDate, content ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_contents; $i++) {

                $createdBy = (int) $this->Faker->numberBetween(1, $this->numRecords_users);
                $createdDate = $this->Faker->dateTime($max = 'now')->format('Y-m-d');
                // creating an array to convert to fake JSON
                    // list helper 
                    $random = rand(1, 5);
                    $list_array = [];
                    $tempList_array = [];
                    for ($j=1; $j <= $random; $j++) {
                        $tempList_array = $this->Faker->words(rand(2, 10));
                        $list_array[] = $tempList_array;
                    }
                    // paired list helper
                    $random = rand(1, 5);
                    $pairedList_array = [];
                    $tempPairedList_array = [];
                    for ($j=1; $j <= $random; $j++) {
                        $tempPairedList_array["firstName"] = str_replace("'","", $this->Faker->firstName());
                        $tempPairedList_array["lastName"] = str_replace("'","", $this->Faker->lastName());
                        $tempPairedList_array["skill"] = (int) $this->Faker->numberBetween(1, 10);
                        $tempPairedList_array["email"] = $this->escape($this->Faker->url());
                        $pairedList_array[] = $tempPairedList_array;
                    }
                    // password helper
                    if ($i % 2 === 0) {
                        $password =  $this->Faker->password();
                        // remove unwanted characters
                        $password = str_replace("'","", $password);
                        $password = str_replace("\"","", $password);
                        $password = $this->escape($password);
                    } else {
                        $password = "";
                    }
                    
                    // make array
                    $content = [
                        "main_content" => [
                            "url" => $this->Faker->url(),
                            "password" => $password,
                            "status" => (int) $this->Faker->numberBetween(0, 1),
                            "title" => $this->escape(ucfirst($this->Faker->sentence(rand(1, 2)))),
                            "description" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                            "contentUrl" => $this->Faker->url()          
                        ],
                        "content_holder" => [
                            "text" => [
                               "content" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                               "description" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                            ],
                            "richText" => [
                                "content" => $this->escape($this->Faker->randomHtml(2,3)),
                                "description" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                            ],
                            "list" => [
                                "content" => $list_array,
                                "description" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                            ],
                            "pairedList" => [
                                "content" => $pairedList_array,
                                "description" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                            ],
                            "json" => [
                                "content" => $this->escape(json_encode($pairedList_array)),
                                "description" => $this->escape($this->Faker->paragraph(rand(2, 4))),
                            ]
                        ]
                    ];
                    // convert to fake JSON
                    $content = json_encode($content);
                    
                $sql .= "( " . $createdBy . ", ";
                $sql .= "'" . $createdDate . "', ";
                $sql .= "'" . $content . "' )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_contents - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'content', $this->numRecords_contents);
        }

        // # MAIN SETTINGS
        // create main settings table
        public function create_main_settings_table() {
            $sql = "CREATE TABLE IF NOT EXISTS main_settings ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "changedDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            // all, post, media content, users, content, tags, labels, categories, none
            $sql .= "apiUse VARCHAR(255) NOT NULL, ";
            $sql .= "apiKey VARCHAR(255), ";
            $sql .= "commentKey VARCHAR(255), ";
            $sql .= "contentKey VARCHAR(255), ";
            $sql .= "mainSettings JSON NOT NULL ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'main_settings');
        }

        // INSERT INTO main_settings
        public function insert_into_main_settings() {
            // start sql
            $sql = "INSERT INTO main_settings ( ";
            $sql .= " changedDate, apiUse, apiKey, commentKey, contentKey, mainSettings ) ";
            $sql .= "VALUES ";

            // make fake data
            $changedDate = date("Y-m-d H:i:s");
            // all, post, media content, users, content, tags, labels, categories, none
            $apiUse = "all";
            $apiKey = "xfun!@gogo55%$#***!soso@";
            $commentKey = "Comm3&109##*&!3@";
            $contentKey = "V$$290!!@%&##*";
            $mainSettings = [
                "homePage" => "index.php",
                "defaltSearch" => "post",
                "userStyleOverride" => "no",
                "userSettingsOverride" => "no",
                "referenceDate" => date("Y-m-d"),
                "logo" => $this->escape("https://mooredigitalsolutions.com/wp-content/uploads/2017/10/logo-03.png"),
                "userDefaultShowOnWeb" => "no",
                // ex "headerLink" => [["headerLinkIcon"=>"fa fas-book", "headerLinkTooltip"=>"Documentation", "headerLinkUrl"=>"http//...", "headerLinkSort"=>"1"]]
                "headerLink" => [],
                // ex "sideMenuLink" => [["sideMenuLinkName"=>"Cool Stuff", "sideMenuLinkUrl"=>"http//...", "sideMenuLinkSort"=>"1"]]
                "sideMenuLink" => []
            ];
            // convert to fake JSON
            $mainSettings = json_encode($mainSettings);

            // end sql
            $sql .= "( '" . $changedDate . "', ";
            $sql .= "'" . $apiUse . "', ";
            $sql .= "'" . $apiKey . "', ";
            $sql .= "'" . $commentKey . "', ";
            $sql .= "'" . $contentKey . "', ";
            $sql .= "'" . $mainSettings . "' ) ";

            // Execute the query
            return  $this->execute_insert_query($sql, 'main_settings', 1);
        }

        // # MEDIA CONTENT
        // create media content table
        public function create_media_content_table() {
            $sql = "CREATE TABLE IF NOT EXISTS media_content ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "name VARCHAR(150) NOT NULL, ";
            // PNG,JPG,MP4
            $sql .= "type VARCHAR(25) NOT NULL, ";
            $sql .= "note VARCHAR(255) DEFAULT NULL, ";
            $sql .= "alt VARCHAR(75) DEFAULT NULL, ";
            $sql .= "createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'media_content');
        }

        // INSERT INTO media_content
        public function insert_into_media_content() {

            // start sql
            $sql = "INSERT INTO media_content ( ";
            $sql .= " name, type, note, alt, createdBy, createdDate) ";
            $sql .= "VALUES ";
            

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_mediaContents; $i++) {

                // make fake data
                $name = $this->imageData[$i]["imageName"];
                $type = $this->imageData[$i]["fileType"];
                $note = $this->escape($this->Faker->sentence());
                $alt = $this->escape($this->Faker->sentence(rand(2, 3)));
                $createdBy = $this->Faker->numberBetween(1, $this->numRecords_users);
                $createdDate = $this->Faker->dateTime($max = 'now')->format("Y-m-d");

                // sql finish
                $sql .= "( '" . $name . "', ";
                $sql .= "'" . $type . "', ";
                $sql .= "'" . $note . "', ";
                $sql .= "'" . $alt . "', ";
                $sql .= $createdBy . ", ";
                $sql .= "'" . $createdDate . "' ) ";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_mediaContents - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'media_content', $this->numRecords_mediaContents);
        }
        
        // # LABELS
        public function create_labels_table() {
            $sql = "CREATE TABLE IF NOT EXISTS labels ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "title VARCHAR(50) NOT NULL, ";
            $sql .= "note VARCHAR(255) DEFAULT NULL, ";
            // * collection_type_reference, located at: root/private/reference_information.php
            $sql .= "useLabel TINYINT(1) UNSIGNED DEFAULT 1) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'labels');
        }

        // INSERT INTO labels
        public function insert_into_labels() {
            // start sql
            $sql = "INSERT INTO labels ( ";
            $sql .= "title, note, useLabel ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_labels; $i++) {

                $title = ucfirst($this->escape(str_replace(".","",$this->Faker->sentence(rand(1, 2)))));
                $note = $this->escape($this->Faker->paragraph($nbSentences = 2));
                // * collection_type_reference, located at: root/private/reference_information.php
                $useLabel = $this->Faker->numberBetween(1, 4);

                // finish sql
                $sql .= "( '" . $title . "', ";
                $sql .= "'" . $note . "', ";
                $sql .= "" . $useLabel . " )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_labels - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'labels', $this->numRecords_labels);
        }

        // # PERMISSIONS
        // create permissions table
        public function create_permissions_table() {
            $sql = "CREATE TABLE IF NOT EXISTS permissions ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "name VARCHAR(50) NOT NULL, ";
            $sql .= "description VARCHAR(255) NOT NULL ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'permissions');
        }

        // INSERT INTO permissions
        public function insert_into_permissions() {

            // start sql
            $sql = "INSERT INTO permissions ( ";
            $sql .= " name, description ) ";
            $sql .= "VALUES ";

            // permission helper
            $permissionData_array = [
                ["name"=>"Full Admin","description"=>"Full permissions, all permissions, all powerful, one permission to rule them all."],
                ["name"=>"Admin","description"=>"Only able to edit/delete profiles lower than admin."],
                ["name"=>"Full Editor","description"=>"All privileges regarding posts, categories, tags and other blogging assets. Only able to edit/delete profiles lower than admin."],
                ["name"=>"Post Editor","description"=>"Able to edit all post. Cannot delete anything."],
                ["name"=>"Full Subscriber","description"=>"Only able to edit and delete personal posts and view others posts/read-only."],
                ["name"=>"Limited Subscriber","description"=>"Only able to edit personal posts and not view others posts"]
            ];

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_permissions; $i++) {

                $name =  $permissionData_array[$i]["name"];
                $description = $permissionData_array[$i]["description"];

                // finish sql
                $sql .= "( '" . $name . "', ";
                $sql .= "'" . $description . "' )";


                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_permissions - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'permissions', $this->numRecords_permissions);
        }

        // # PERSONAL SETTINGS
        // create personal settings table
        public function create_personal_settings_table() {
            $sql = "CREATE TABLE IF NOT EXISTS personal_settings ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "changedDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "personalSettings JSON NOT NULL, ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'personal_settings');
        }

        // INSERT INTO personal_settings
        public function insert_personal_settings() {

            // start sql
            $sql = "INSERT INTO personal_settings ( ";
            $sql .= " changedDate, userId, personalSettings) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_users; $i++) {

                // make fake data
                $changedDate = $this->Faker->dateTime($max = 'now')->format("Y-m-d");
                $userId = $i + 1;
                $personalSettings = [
                    "homePage" => "index.php",
                    "defaltSearch" => "post",
                    "referenceDate" => date("Y-m-d"),
                    // ex "headerLink" => [["headerLinkIcon"=>"fa fas-book", "headerLinkTooltip"=>"Documentation", "headerLinkUrl"=>"http//...", "headerLinkSort"=>"1"]]
                    "headerLink" => [],
                    // ex "sideMenuLink" => [["sideMenuLinkName"=>"Cool Stuff", "sideMenuLinkUrl"=>"http//...", "sideMenuLinkSort"=>"1"]]
                    "sideMenuLink" => [],
                    "useMainSettings" => "yes",
                    "useMainStyles" => "yes"
                ];
                // convert to fake JSON
                $personalSettings = json_encode($personalSettings); 

                // sql finish
                $sql .= "( '" . $changedDate . "', ";
                $sql .= "" . $userId . ", ";
                $sql .= "'" . $personalSettings . "' ) ";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_users - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'personal_settings', $this->numRecords_users);
        }

        // # POSTS
        // create posts table
        public function create_posts_table() {
            $sql = "CREATE TABLE IF NOT EXISTS posts ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "author INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "authorName VARCHAR(50) NOT NULL, ";
            // increased by triggers
            $sql .= "comments INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "content TEXT NOT NULL, ";
            $sql .= "createdBy INT(10) UNSIGNED NOT NULL, ";
            $sql .= "createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "postDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "status TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "title VARCHAR(50) NOT NULL, ";
            $sql .= "catIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "tagIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "labelIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "imageName VARCHAR(150) DEFAULT NULL, ";
            $sql .= "mediaContentIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "FOREIGN KEY (author) REFERENCES users(id), ";
            $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'posts');
        }

        // Insert into posts
        public function insert_into_posts() {

            // start sql
            $sql = "INSERT INTO posts ( ";
            $sql .= "author, authorName, comments, content, createdBy, ";
            $sql .= "createdDate, postDate, status, title, catIds, ";
            $sql .= "tagIds, labelIds, imageName, mediaContentIds ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_posts; $i++) {

                // add comments correctly
                    $subSql = "SELECT COUNT(*) FROM comments WHERE postId = " . ($i + 1);
                    $result = $this->mysqli->query($subSql);
                    // error handling
                    if (!$result) {
                        exit("Query Failed!!!: " . $this->mysqli->error);
                    } 
                    // get row, only one there
                    $row = $result->fetch_array();
                    // return count 
                    $commentCount = array_shift($row);
                // add author
                    $authorId = $this->Faker->numberBetween(1, $this->numRecords_users);
                    // make new sql
                    $subSql = "SELECT firstName, lastName FROM users WHERE id = {$authorId}";
                    // get the sql result
                    $result = $this->mysqli->query($subSql);
                    // error handling
                    if (!$result) {
                        exit("Query Failed!!!: " . $this->mysqli->error);
                    } 
                    // loop through query, should only have one record
                    while ($record = $result->fetch_assoc()) {
                        $authorName = $record["firstName"];    
                        $authorName .= " " . $record["lastName"];    
                    }

                // special add
                $author = $authorId;
                $authorName = $this->escape($authorName);
                // special add
                $comments = $commentCount;
                $content = $this->escape($this->Faker->paragraph(rand(5, 20)));
                $createdBy = $this->Faker->numberBetween(1, $this->numRecords_users);
                $createdDate = $this->Faker->dateTimeThisYear($max = 'now')->format('Y-m-d');
                $postDate = $this->Faker->dateTimeThisYear($max = 'now')->format('Y-m-d');
                $status = $this->Faker->numberBetween(0, 1);
                $title = ucfirst($this->escape(str_replace(".","",$this->Faker->sentence(rand(1, 2)))));
                $catIds = "";
                $tagIds = "";
                $labelIds = "";
                $imageName = "";
                $mediaContentIds = "";

                // end sql
                $sql .= "( " . $author . ", ";
                $sql .= "'" . $authorName . "', " . $comments . ", ";
                $sql .= "'" . $content . "', ";
                $sql .=  $createdBy . ", " . "'" .  $createdDate . "', ";
                $sql .= "'" . $postDate . "', " . $status . ", ";
                $sql .= "'" . $title . "', '" . $catIds . "', ";
                $sql .= "'" . $tagIds . "', '" . $labelIds . "', ";
                $sql .= "'" . $imageName . "', '" . $mediaContentIds . "')";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_posts - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'posts', $this->numRecords_posts);
        }

        // # TAGS
        public function create_tags_table() {
            $sql = "CREATE TABLE IF NOT EXISTS tags ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "title VARCHAR(50) NOT NULL, ";
            $sql .= "note VARCHAR(255) DEFAULT NULL, ";
            // * collection_type_reference, located at: root/private/reference_information.php
            $sql .= "useTag TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'tags');
        }

        // INSERT INTO tags
        public function insert_into_tags() {
            // start sql
            $sql = "INSERT INTO tags ( ";
            $sql .= "title, note, useTag ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_tags; $i++) {

                $title = ucfirst($this->escape(str_replace(".","",$this->Faker->sentence(rand(1, 2)))));
                $note = $this->escape($this->Faker->paragraph(rand(1, 2)));
                // * collection_type_reference, located at: root/private/reference_information.php
                $useTag = $this->Faker->numberBetween(1, 4);

                // end sql
                $sql .= "( '" . $title . "', ";
                $sql .= "'" . $note . "', ";
                $sql .= "" . $useTag . " )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_tags - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'tags', $this->numRecords_tags);
        }

        
        // # TODO
        // create todo table
        public function create_todo_table() {
            $sql = "CREATE TABLE IF NOT EXISTS todos ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "todo VARCHAR(255) DEFAULT NULL, ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'todo');
        }

        // INSERT INTO tags
        public function insert_into_todos() {
            // start sql
            $sql = "INSERT INTO todos ( ";
            $sql .= "userId, todo ) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_todos; $i++) {

                $userId = $this->Faker->numberBetween(1, $this->numRecords_users);
                $todo = $this->escape($this->Faker->paragraph(rand(1, 2)));

                // end sql
                $sql .= "( " . $userId . ", ";
                $sql .= "'" . $todo . "' )";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_todos - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'tags', $this->numRecords_todos);
        }

        // # USERS
        // create users table
        public function create_users_table() {
            $sql = "CREATE TABLE IF NOT EXISTS users ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "username VARCHAR(35) NOT NULL UNIQUE, ";
            $sql .= "password VARCHAR(50) NOT NULL, ";
            $sql .= "firstName VARCHAR(25) NOT NULL, ";
            $sql .= "lastName VARCHAR(25) NOT NULL, ";
            $sql .= "address VARCHAR(150) DEFAULT NULL, ";
            $sql .= "phoneNumber VARCHAR(25) DEFAULT NULL, ";
            $sql .= "emailAddress VARCHAR(150) NOT NULL, ";
            $sql .= "title VARCHAR(45) DEFAULT NULL, ";
            $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "adminNote VARCHAR(255) DEFAULT NULL, ";
            $sql .= "note VARCHAR(255) DEFAULT NULL, ";
            $sql .= "showOnWeb TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, ";
            $sql .= "createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "imageName VARCHAR(150) DEFAULT NULL, ";
            $sql .= "catIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "tagIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "labelIds VARCHAR(255) DEFAULT NULL, ";
            $sql .= "FOREIGN KEY (createdBy) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'users');
        }

        // INSERT INTO users
        public function insert_into_users() {
            // start sql
            $sql = "INSERT INTO users ( ";
            $sql .= " username, password, firstName, lastName, address, phoneNumber, emailAddress, title, mediaContentId, adminNote, note, showOnWeb, createdBy, createdDate, imageName, catIds, tagIds, labelIds ) ";
            $sql .= "VALUES ";

            // helper counter
            $counter = 1;

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_users; $i++) {

                // making fake data
                $username = $this->escape($this->Faker->username());
                $password = $this->escape($this->Faker->password());
                $firstName = $this->escape($this->Faker->firstName());
                $lastName = $this->escape($this->Faker->lastName());
                $address = $this->escape($this->Faker->address());
                $phoneNumber = $this->escape($this->Faker->tollFreePhoneNumber());
                $emailAddress = $this->escape($this->Faker->email());
                $title = $this->escape($this->Faker->jobTitle());
                if ($counter > $this->userPicCount) {
                    $counter = 1;
                }
                $mediaContentId = $counter;
                $adminNote = $this->escape($this->Faker->sentence());
                $note = $this->escape($this->Faker->sentence());
                $showOnWeb = $this->Faker->numberBetween(0, 1);
                $createdBy = $this->Faker->numberBetween(1, $this->numRecords_users);
                $createdDate = $this->Faker->dateTimeThisYear($max = 'now')->format('Y-m-d');
                $imageName = $this->imageData[($counter - 1)]["imageName"];
                $catIds = "";
                $tagIds = "";
                $labelIds = "";

                // end sql
                $sql .= "( '" . $username . "', ";
                $sql .= "'" . $password . "', ";
                $sql .= "'" . $firstName . "', ";
                $sql .= "'" . $lastName . "', ";
                $sql .= "'" . $address . "', ";
                $sql .= "'" . $phoneNumber . "', ";
                $sql .= "'" . $emailAddress . "', ";
                $sql .= "'" . $title . "', ";
                $sql .= $mediaContentId . ", ";
                $sql .= "'" . $adminNote . "', ";
                $sql .= "'" . $note . "', ";
                $sql .= $showOnWeb . ", ";
                $sql .= $createdBy . ", ";
                $sql .= "'" . $createdDate . "', ";
                $sql .= "'" . $imageName . "', ";
                $sql .= "'" . $catIds . "', ";
                $sql .= "'" . $tagIds . "', ";
                $sql .= "'" . $labelIds . "' )";
                

                // increment counter
                $counter++;

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_users - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return $this->execute_insert_query($sql, 'users', $this->numRecords_users);
        }

        // add main test user
        public function add_main_test_user() {
            // start sql
            $sql = "INSERT INTO users ( ";
            $sql .= " username, password, firstName, lastName, address, phoneNumber, emailAddress, title, mediaContentId, adminNote, note, showOnWeb, createdBy, createdDate, imageName ) ";
            $sql .= "VALUES ";

            // making fake data
            $username = "developer.io";
            $password = "test1234";
            $firstName = "developer";
            $lastName = "Tester";
            $address = $this->escape($this->Faker->address());
            $phoneNumber = $this->escape($this->Faker->tollFreePhoneNumber());
            $emailAddress = "developer@developer.io";
            $title = "developer/tester";
            $mediaContentId = 8;
            $adminNote = $this->escape($this->Faker->sentence());
            $note = $this->escape($this->Faker->sentence());
            $showOnWeb = 1;
            $createdBy = $this->Faker->numberBetween(1, $this->numRecords_users);
            $createdDate = $this->Faker->dateTimeThisYear($max = 'now')->format('Y-m-d');
            $imageName = $this->imageData[8]["imageName"];

            // end sql
            $sql .= "( '" . $username . "', ";
            $sql .= "'" . $password . "', ";
            $sql .= "'" . $firstName . "', ";
            $sql .= "'" . $lastName . "', ";
            $sql .= "'" . $address . "', ";
            $sql .= "'" . $phoneNumber . "', ";
            $sql .= "'" . $emailAddress . "', ";
            $sql .= "'" . $title . "', ";
            $sql .= $mediaContentId . ", ";
            $sql .= "'" . $adminNote . "', ";
            $sql .= "'" . $note . "', ";
            $sql .= $showOnWeb . ", ";
            $sql .= $createdBy . ", ";
            $sql .= "'" . $createdDate . "', ";
            $sql .= "'" . $imageName . "' )";

            // get the sql result
            $result = $this->mysqli->query($sql);
            // error handling
            if (!$result) {
                exit("Query Failed!!!: " . $this->mysqli->error);
            } 

            // add the correct permissions
            $userId = $this->mysqli->insert_id;
            $sql2 = "INSERT INTO users_to_permissions (";
            $sql2 .= "userId, permissionId) ";
            $sql2 .= "VALUES ( {$userId}, 1)";

            // get the sql result
            $result = $this->mysqli->query($sql2);
            // error handling
            if (!$result) {
                exit("Query Failed!!!: " . $this->mysqli->error);
            } 

            // Execute the query
            return  "Main test developer added";
        }

        
        // # STYLE SETTINGS
        // create style settings table
        public function create_style_settings_table() {
            $sql = "CREATE TABLE IF NOT EXISTS style_settings ( ";
            $sql .= "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "changedDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', ";
            $sql .= "styleSettings JSON NOT NULL, ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'style_settings');
        }

         // INSERT INTO style_settings
         public function insert_style_settings() {

            // start sql
            $sql = "INSERT INTO style_settings ( ";
            $sql .= " userId, changedDate, styleSettings) ";
            $sql .= "VALUES ";

            // Populate the dynamic data into the query
            for ($i = 0; $i < $this->numRecords_users; $i++) {

                // make fake data
                $userId = $i + 1;
                $changedDate = $this->Faker->dateTime($max = 'now')->format("Y-m-d");
                $styleSettings = [
                    "backgroundImage"=>[
                        "imageName"=>"that_herb_shop_featured_image.jpg",   
                        "option"=>"cover or repeat"   
                    ],
                    "backgroundColor"=>"",
                    "headerColor"=>"",
                    "headerTextColor"=>"",
                    "sidebarColor"=>"",
                    "sidebarTextColor"=>"",
                    "mainColor"=>"",
                    "dashboardBackgroundColor"=>"",
                    "dashboardBackgroundImage"=>[
                        "imageName"=>"that_herb_shop_featured_image.jpg",   
                        "option"=>"cover or repeat"   
                    ],
                    "panelColor"=>"",
                    "basicTextColor"=>"",
                    "titleColor"=>"",
                    "postColor"=>"",
                    "userColor"=>"",
                    "commentColor"=>"",
                    "categoryColor"=>"",
                    "contentColor"=>"",
                    "layout"=>"",
                    "customCss"=>"",
                    "customJs"=>"",
                    "headerBackgroundImage"=>[
                        "imageName"=>"that_herb_shop_featured_image.jpg",   
                        "option"=>"cover or repeat"   
                    ],
                    "sidebarBackgroundImage"=>[
                        "imageName"=>"that_herb_shop_featured_image.jpg",   
                        "option"=>"cover or repeat"   
                    ],
                    "panelRadius"=>"",
                    "dashboardRadius"=>"",
                    "buttonRadius"=>"",
                    "useMainSettings"=>"yes or no"
                ];
                // convert to fake JSON
                $styleSettings = json_encode($styleSettings); 

                // sql finish
                $sql .= "(" . $userId . ", ";
                $sql .= "'" . $changedDate . "', ";
                $sql .= "'" . $styleSettings . "' ) ";

                // If we are not on the last iteration then add a comma for the next statement to be inserted
                if ($i != $this->numRecords_users - 1) {
                    $sql .= ", ";
                }
            }

            // Execute the query
            return  $this->execute_insert_query($sql, 'style_settings', $this->numRecords_users);
        }


        // # ------ Lookup Table Creation ----------

        // # POSTS TO MEDIA CONTENT
        public function create_posts_to_media_content_table() {
            $sql = "CREATE TABLE IF NOT EXISTS posts_to_media_content ( ";
            $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (postId, mediaContentId), ";
            $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
            $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'posts_to_media_content');
        }

        // # POSTS TO TAGS
        public function create_posts_to_tags_table() {
            $sql = "CREATE TABLE IF NOT EXISTS posts_to_tags ( ";
            $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (postId, tagId), ";
            $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
            $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'posts_to_tags');
        }

        // # POSTS TO LABELS
        public function create_posts_to_labels_table() {
            $sql = "CREATE TABLE IF NOT EXISTS posts_to_labels ( ";
            $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (postId, labelId), ";
            $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
            $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'posts_to_labels');
        }

        // # POSTS TO CATEGORIES
        public function create_posts_to_categories_table() {
            $sql = "CREATE TABLE IF NOT EXISTS posts_to_categories ( ";
            $sql .= "postId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (postId, categoryId), ";
            $sql .= "FOREIGN KEY (postId) REFERENCES posts(id), ";
            $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'posts_to_categories');
        }

        // # MEDIA CONTENT TO TAGS
        public function create_media_content_to_tags_table() {
            $sql = "CREATE TABLE IF NOT EXISTS media_content_to_tags ( ";
            $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (mediaContentId, tagId), ";
            $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
            $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'media_content_to_tags');
        }

        // # MEDIA CONTENT TO LABELS
        public function create_media_content_to_labels_table() {
            $sql = "CREATE TABLE IF NOT EXISTS media_content_to_labels ( ";
            $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (mediaContentId, labelId), ";
            $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
            $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'media_content_to_labels');
        }

        // # MEDIA CONTENT TO CATEGORIES
        public function create_media_content_to_categories_table() {
            $sql = "CREATE TABLE IF NOT EXISTS media_content_to_categories ( ";
            $sql .= "mediaContentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (mediaContentId, categoryId), ";
            $sql .= "FOREIGN KEY (mediaContentId) REFERENCES media_content(id), ";
            $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'media_content_to_categories');
        }

        // # CONTENT TO TAGS
        public function create_content_to_tags_table() {
            $sql = "CREATE TABLE IF NOT EXISTS content_to_tags ( ";
            $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (contentId, tagId), ";
            $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
            $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'content_to_tags');
        }

        // # CONTENT TO LABELS
        public function create_content_to_labels_table() {
            $sql = "CREATE TABLE IF NOT EXISTS content_to_labels ( ";
            $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (contentId, labelId), ";
            $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
            $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'content_to_labels');
        }

        // # CONTENT TO CATEGORIES
        public function create_content_to_categories_table() {
            $sql = "CREATE TABLE IF NOT EXISTS content_to_categories ( ";
            $sql .= "contentId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (contentId, categoryId), ";
            $sql .= "FOREIGN KEY (contentId) REFERENCES content(id), ";
            $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'content_to_categories');
        }

        // # USER TO PERMISSIONS
        public function create_users_to_permissions_table() {
            $sql = "CREATE TABLE IF NOT EXISTS users_to_permissions ( ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "permissionId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (userId, permissionId), ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id), ";
            $sql .= "FOREIGN KEY (permissionId) REFERENCES permissions(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'users_to_permissions');
        }

        // # USERS TO TAGS
        public function create_users_to_tags_table() {
            $sql = "CREATE TABLE IF NOT EXISTS users_to_tags ( ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "tagId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (userId, tagId), ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id), ";
            $sql .= "FOREIGN KEY (tagId) REFERENCES tags(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'users_to_tags');
        }

        // # USERS TO LABELS
        public function create_users_to_labels_table() {
            $sql = "CREATE TABLE IF NOT EXISTS users_to_labels ( ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "labelId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (userId, labelId), ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id), ";
            $sql .= "FOREIGN KEY (labelId) REFERENCES labels(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'users_to_labels');
        }

        // # USERS TO CATEGORIES
        public function create_users_to_categories_table() {
            $sql = "CREATE TABLE IF NOT EXISTS users_to_categories ( ";
            $sql .= "userId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "categoryId INT(10) UNSIGNED NOT NULL, ";
            $sql .= "PRIMARY KEY (userId, categoryId), ";
            $sql .= "FOREIGN KEY (userId) REFERENCES users(id), ";
            $sql .= "FOREIGN KEY (categoryId) REFERENCES categories(id) ) ENGINE=InnoDB";

            // Execute the query then return the result
            return $this->execute_create_query($sql, 'users_to_categories');
        }

        // # insert into connecting tables/lookup tables
        // Function to randomize the number of connections in a lookup table
        // Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
        private function create_lookup_table_connections($lookupTable) {
            // Set the initial argument values
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

        // INSERT INTO any lookuptable. Expected args: 'tablename', 'field1', 'field2', 'table1_ids', 'table2_ids', 'connections', 'relationships'
        // Connections are between one table and another. eg: 1 to 2, 1 to 1, 1 to 3, are all one connection because they come from the same table
        // Relationships are between 1 id and another. eg 1 to 2, 1 to 1, 1 to 3 are each a separate relationship. 3 relationships are listed
        // One connection can contain one or more relationships
        public function insert_into_lookup_table($args = []) {
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
            $connections = max($args['table1_ids']);

            // Check to see if we have enough ids to form the number of requested relationships
            $relationships = max($args['table2_ids']);

            // Using the ignore statement to ignore inserting
            $sql = "INSERT INTO " . $args['tablename'] . " ( ";
            $sql .= $args['field1'] . ", " . $args['field2'] . ") ";
            $sql .= "VALUES ";

            for ($i = 0; $i < $connections; $i++) {
                // randomize data
                if ($this->Faker->numberBetween(0, 1) || $i === $connections - 1) {
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
            }

            // Tag on the ON DUPLICATE KEY to the end of our query
            $sql .= " ON DUPLICATE KEY UPDATE " .  $args['field1'] . " = " . $args['field1'];

            // Execute the query
            return  $this->execute_insert_query($sql, $args['tablename'], ($connections * $relationships));
        }

    // @ queries end

    // @ helper methods start
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

        // CREATE ALL TABLES
        public function create_all_tables() {
            $results = [];

            // Base Tables
            $results[] = $this->create_categories_table();
            $results[] = $this->create_comments_table();
            $results[] = $this->create_users_table();
            $results[] = $this->create_posts_table();
            $results[] = $this->create_tags_table();
            $results[] = $this->create_labels_table();
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
            $results[] = $this->create_users_to_permissions_table();
            $results[] = $this->create_users_to_tags_table();
            $results[] = $this->create_users_to_labels_table();
            $results[] = $this->create_users_to_categories_table();

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

        // INSERT INTO ALL TABLES
        public function insert_into_all_tables() {
            $results = [];

            $results[] = $this->insert_into_users();
            $results[] = $this->insert_into_comments();
            $results[] = $this->insert_into_posts();
            $results[] = $this->insert_into_bookmarks();
            $results[] = $this->insert_into_categories();
            $results[] = $this->insert_into_content();
            $results[] = $this->insert_into_tags();
            $results[] = $this->insert_into_labels();
            $results[] = $this->insert_into_media_content();
            $results[] = $this->insert_into_permissions();
            $results[] = $this->insert_into_main_settings();
            $results[] = $this->insert_personal_settings();
            $results[] = $this->insert_style_settings();
            $results[] = $this->insert_into_todos();
            

            // Use the lookupTablesArray to go through and create the connections by inserting into each lookup table
            $lookupTablesArray = ['posts_to_media_content', 'posts_to_tags', 'posts_to_labels', 'posts_to_categories', 'media_content_to_tags', 'media_content_to_labels', 'media_content_to_categories', 'content_to_tags', 'content_to_labels', 'content_to_categories', 'users_to_permissions'];

            // foreach($lookupTablesArray as $table) {
            //     $results[] = $this->create_lookup_table_connections($table);
            // }

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

        // drop all tables
        public function drop_tables() {
            // Remove foreign key checks in preparation to drop the tables
            $this->toggle_foreign_key_checks(false);
            // all tables and drop all tables
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
            // check to see if it was successful
            if ($this->mysqli->query($sql) === true) {
                // Turn on foreign key checks after dropping the tables
                $this->toggle_foreign_key_checks(true);
                return "All Table's Dropped Successfully!";
            } else {
                $this->errors_array[] = $this->mysqli->error;
                return false;
            }
        }

        // select from tables
        public function select_from_table($tablename) {
            // create select statement
            $sql = "SELECT * FROM " . $tablename;
            $result = $this->mysqli->query($sql);
            if ($result) {
                // Loop through the records and store them in our array
                while ($row = $result->fetch_assoc()) {
                    $this->latest_selection_array[] = $row;
                }
                // return message
                return "Selected " . $this->mysqli->affected_rows . " rows from " . $tablename .  " successfully!";
            } else {
                $this->errors_array[] = $tablename . ": " . $this->mysqli->error;
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

        // Function to escape strings
        public function escape($string) {
            return $this->mysqli->escape_string($string);
        }

        
    // @ helper methods start
}
?>




