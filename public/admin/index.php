<?php
    // @ page template start
        // include main logic for all pages
        require_once('../../private/initialize.php');

        // set page title
        $pageTitle = "";

        // include top
        require_once(PUBLIC_PATH . 'includes/top.php');

        // include header
        require_once(PUBLIC_PATH . 'includes/header.php');

        // include sidebar
        require_once(PUBLIC_PATH . 'includes/sidebar.php');

        // include body/page
        require_once(PUBLIC_PATH . 'pages/dashboard.php');

        // include bottom
        require_once(PUBLIC_PATH . 'includes/bottom.php');
    // @ page template end
?>