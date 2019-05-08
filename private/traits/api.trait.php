<?php
// The trait for the API
// TODO: Implement API Authentication, Code for having the API Keys
// TODO: Update error handling for invalid parameters
// Return accepted vs rejected parameters
trait Api {
    // set over arching API keys, use function to get the key
    // you can specify individual class API keys in the databaseObject class for post and get
    static protected $mainApiKey = 'T3$$tK3y!2#4%6&'; // use get_main_api_key()
    static protected $mainGetApiKey = 'T3$$tK3y!2#4%6&'; // use get_main_get_api_key()
    static protected $mainPostApiKey = 'T3$$tK3y!2#4%6&'; // use get_main_post_api_key()

    // @ helper methods start
        // get main api key 
        static public function get_main_api_key() {
            return self::$mainApiKey;
        }

        // get main get api key
        static public function get_main_get_api_key() {
            return self::$mainGetApiKey;
        }

        // get main post api key
        static public function get_main_post_api_key() {
            return self::$mainPostApiKey;
        }
    // @ helper methods end
}
?>