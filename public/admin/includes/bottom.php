<!-- jQuery CDN -->
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script src="<?php PUBLIC_PATH . "/admin/js/general.js" ?>"></script>

<?php
    // set page specific js if there 
    if (file_exists(PUBLIC_PATH . "/admin/js/{$Router->pathJs}")) {
        // got it, set path
        echo "<script src='" . PUBLIC_LINK_PATH . "/admin/js/{$Router->pathJs}?{$assetVersion}'></script>";
    }
    
?>
<!-- general form JS -->
<script src="<?php echo PUBLIC_LINK_PATH . "/admin/js/forms.js?{$assetVersion}"; ?>"></script>
</body>
</html>