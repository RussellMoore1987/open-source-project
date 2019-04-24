$(document).ready(function() {
    // # general
        // code here

    // # header
        // header drop-down menu functionality
        $("header .dropdown").on( "click", function() {
            // get menu
            const dropdown_menu = $("header .dropdown_menu");
            // performed information accordingly
            if (dropdown_menu.hasClass("menu_down")) {
                // toggle class name
                dropdown_menu.toggleClass("menu_down")
                // perform necessary CSS
                dropdown_menu.css("opacity", "0").css("top", "90px");
                // remove it from the clickable page
                setTimeout(function() { 
                    dropdown_menu.css("top", "-100vh");
                }, 400);
            } else {
                // toggle class name
                dropdown_menu.toggleClass("menu_down")
                // perform necessary CSS
                // bring it into the correct location quickly
                dropdown_menu.css("top", "90px");
                // take half a second then show the animation
                setTimeout(function() { 
                    dropdown_menu.css("opacity", "1").css("top", "60px");
                }, 400);
                
            }
        });
        // book mark functionality
        $("header .header_bookmark").on( "click", function() {
            // check to see what class is currently there
            if ($(this).hasClass('far')) {
                // set bookmark
                $(this).removeClass('far');
                $(this).addClass('fas');
            } else {
                // remove the bookmark
                $(this).removeClass('fas');
                $(this).addClass('far');
            }
        });
});