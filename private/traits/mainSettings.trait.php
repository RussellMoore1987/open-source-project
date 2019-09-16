<?php
    trait MainSettings {
        // @ set up section start
            // * api_documentation located at: root/private/rules_docs/reference_information.php
            // set over arching API keys, use function to get the key
            static protected $mainApiKey = ''; // use get_main_api_key()
            // you can specify individual class API keys in the databaseObject class for post and get
            static protected $mainGetApiKey = ''; // use get_main_get_api_key()
            static protected $mainPostApiKey = ''; // use get_main_post_api_key()
            // class list, specify routes
            static protected $classList = [
                "Category" => ['categories', 'categories/dev'],
                // TODO: this class dose not exist right now
                // "Content" => ['content', 'content/dev'],
                "Label" => ['labels', 'labels/dev'],
                "MediaContent" => ['mediaContent', 'mediaContent/dev'],
                "Post" => ['posts', 'posts/dev'],
                "Tag" => ['tags', 'tags/dev'],
                "User" => ['users', 'users/dev']
            ]; // use get_class_list()
        // @ set up section end        
    }  
?>