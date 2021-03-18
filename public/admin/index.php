<?php
    // @ main layout start
        // include main logic for all pages
        require_once('../../private/initialize.php');

        // * custom_code_spots located at: root/private/rules_docs/reference_information.php
        // before session check (custom code spot)
        require_once(PUBLIC_PATH . '/admin/all_pages/before_session_check.php');

        // check to make sure they're logged in
        // Session::check_login();

        // * custom_code_spots located at: root/private/rules_docs/reference_information.php
        // after session check (custom code spot)
        require_once(PUBLIC_PATH . '/admin/all_pages/after_session_check.php');

        // set up the router 
        $Router = new Router($_SERVER['QUERY_STRING']);

        // check to see if file exists, if not run 404 page 
        if (!(file_exists(PUBLIC_PATH . "/admin/views/{$Router->path}"))) {
            // set $Router->path to 404 page
            $Router->path = "404.php";
        }

        // view specific logic, page title set here
        if (file_exists(PUBLIC_PATH . "/admin/view_logic/{$Router->path}")) {
            require_once(PUBLIC_PATH . "/admin/view_logic/{$Router->path}");
        }

        // include top
        require_once(PUBLIC_PATH . '/admin/includes/top.php');

        // include header
        require_once(PUBLIC_PATH . '/admin/includes/header.php');

        // include sidebar
        require_once(PUBLIC_PATH . '/admin/includes/sidebar.php');

        // include view/body/page
        require_once(PUBLIC_PATH . "/admin/views/{$Router->path}");

        // include bottom
        require_once(PUBLIC_PATH . '/admin/includes/bottom.php');
    // @ main layout end
?>