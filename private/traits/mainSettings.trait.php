<?php
    trait MainSettings {
        // @ set up section start
            // # Class List, REST API, Context API, DevTool
                // * class_list located at: root/private/rules_docs/reference_information.php
                static protected $classList = ["User", "Category", "Label", "MediaContent", "Post", "Tag"]; // public access use get_class_list()
            // # REST API
                // * api_documentation located at: root/private/rules_docs/reference_information.php
                // set over arching API keys, use function to get the key
                static protected $mainApiKey = ''; // public access use get_main_api_key()
                // you can specify individual class API keys in the databaseObject class for post and get
                static protected $mainGetApiKey = ''; // public access use get_main_get_api_key()
                static protected $mainPostApiKey = ''; // public access use get_main_post_api_key()
            // # Context API
                // main context settings
                static protected $mainContextInfo = [ 
                    // documentation password, has to be at least eight characters long and have one capital letter, one lowercase letter, one number, and one special symbol, otherwise it doesn't work
                    'documentationPassword' => "",
                    // TODO: allow Cross-Origin Resource Sharing (CORS), if you are only using the context api internally you should have this as false, if you are using it to populate outside products or pages make sure it is set to true
                    'cors' => false,
                    'devTool' => [
                        // devTool password, has to be at least eight characters long and have one capital letter, one lowercase letter, one number, and one special symbol, otherwise it doesn't work
                        'username' => "test",
                        'password' => "Test@the9"
                    ]
                ];
            // # SQL connection and other tables, and SQL creation 
                // * class_list located at: root/private/rules_docs/reference_information.php
                static protected $otherTablesClassList = []; // public access use get_other_tables_class_list()
                // TODO: documentation * sql_creation_commands located at: root/private/rules_docs/devTool_docs.php
                // TODO: creation command, deletion command, and individual table commands drop, create, insert
                // static protected $sqlCreationCommands = ["customSql" => "creation"]; // public access use get_other_tables_class_list()
                static protected $sqlInsertCommands = ["customSql" => "insert"]; // public access use get_sql_insert_commands()
            // # Authentication
                // * authentication located at: root/private/rules_docs/reference_information.php
                // default static protected $authentication = ['User', 'username', 'hashedPassword', 'id'];
                static protected $authenticationSettings = ['User', 'username', 'hashedPassword', 'id']; // public access use get_authentication_info()
            // # Default Homepage
                // when an individual logs in this is the systems default homepage, assuming they are going into the secure environment always put '/admin/...pageName'
                // ex: static protected $defaultHomepage = '/admin/dashboard';
                static protected $defaultHomepage = '/admin/dashboard';
            // # authentication token 
                // TODO: use authentication token add
            // # custom code spots
                // * custom_code_spots located at: root/private/rules_docs/reference_information.php
        // @ set up section end
    }  
?>