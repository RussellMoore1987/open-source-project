
<?php
    // todo
        // do bottom check to see which JavaScript/assets to use
        // pull in custom js
    
    
    // set page specific js if there 
    if (file_exists(PUBLIC_PATH . "/admin/js/{$Router->pathJs}")) {
        // got it, set path
        echo "<script src='" . PUBLIC_LINK_PATH . "/admin/js/{$Router->pathJs}" . "'></script>";
    }
    
?>
<script src="<?php echo PUBLIC_LINK_PATH . "/admin/js/forms.js"; ?>"></script>
</body>
</html>