<?php
    // check to see if page title was sent
    $pageTitle = $pageTitle ?? "Default Page";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- set page title -->
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo PUBLIC_LINK_PATH . "/admin/css/style.css?{$assetVersion}"; ?>">
    <?php
        // todo list
            // set favicon
            // pull in custom CSS
    ?>
</head>
<body>
    
