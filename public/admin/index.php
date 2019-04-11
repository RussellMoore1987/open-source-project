<?php
    // todo: which one
    // open_source_project/public/admin/add_edit_post?postId=299
    // open_source_project/public/admin/add_edit_post.php?postId=299
    // open_source_project/public/admin/add_edit_post/postId=299&user=55 ***** but more work

    // echo $_SERVER['QUERY_STRING'];
    // var_dump($_GET);

    // @ main layout start
        // include main logic for all pages
        require_once('../../private/initialize.php');

        // set up the router 
        $Router = new Router($_SERVER['QUERY_STRING']);

        // check to see if file exists, if not run 404 page 
        if (!(file_exists(PUBLIC_PATH . "/admin/views/{$Router->path}"))) {
            // set $Router->path to 404 page
            $Router->path = "404.php";
        }

        // view specific logic, page title set here
        require_once(PUBLIC_PATH . "/admin/view_logic/{$Router->path}");

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