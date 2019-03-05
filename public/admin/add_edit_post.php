<?php
    // @ page template start
        // include main logic for all pages
        require_once('../../private/initialize.php');

        // set page title
        $pageTitle = "Add/Edit Posts";

        // include top
        require_once(PUBLIC_PATH . '/admin/includes/top.php');

        // include header
        require_once(PUBLIC_PATH . '/admin/includes/header.php');

        // include sidebar
        require_once(PUBLIC_PATH . '/admin/includes/sidebar.php');

        // include body/page
        require_once(PUBLIC_PATH . '/admin/pages/add_edit_post.php');

        // include bottom
        require_once(PUBLIC_PATH . '/admin/includes/bottom.php');
    // @ page template end
?>