<?php
    trait MainSettings {
        // @ set up section start
            // # Class List, REST API, Context API, DevTool 
            // * registering classes is important whether or not you are using an API, the DevTool also uses this list to build the SQL for classes
            // class list, specify routes, rest API, and is used for registering for the context/internal API
            static protected $classList = [
                "Category" => ['categories', 'categories/dev'],
                // TODO: this class dose not exist right now
                // "Content" => ['content', 'content/dev'],
                "Label" => ['labels', 'labels/dev'],
                "MediaContent" => ['mediaContent', 'mediaContent/dev'],
                "Post" => ['posts', 'posts/dev'],
                "Tag" => ['tags', 'tags/dev'],
                "User" => ['users', 'users/dev']
            ]; // TODO: need to pull that in as well as the other above use get_class_list()
            // quick reference note
                // "Post" => ['posts', 'posts/dev'], post class with reference to routes for the rest API, 
                // "Post" => [], post class
            // # REST API
            // * api_documentation located at: root/private/rules_docs/reference_information.php
            // set over arching API keys, use function to get the key
            static protected $mainApiKey = ''; // use get_main_api_key()
            // you can specify individual class API keys in the databaseObject class for post and get
            static protected $mainGetApiKey = ''; // use get_main_get_api_key()
            static protected $mainPostApiKey = ''; // use get_main_post_api_key()
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
                    'password' => "Test@the9",
                ]
            ];
            // TODO: use authentication token add
        // @ set up section end        
    }  
?>