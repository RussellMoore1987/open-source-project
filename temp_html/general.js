$(document).ready(function() {
    // # general
        // code here

    // # header
        // header drop-down menu functionality
        $(".header_dropdown").on( "click", function() {
            // get menu
            const dropdown_menu = $(".header_dropdown_menu");
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
        $(".header_bookmark").on( "click", function() {
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
    // # side bar
        // menu open and close
        $(".dst i").on( "click", function() {
            $(this).toggleClass('dst_i_active');
            $('.ds').toggleClass('ds_open');
        });
        // expand interface/dashboard
        $(".ds_btn_expand").on( "click", function(e) {
            // prevent default behavior
            e.preventDefault();
            // reset icon on button
            const icon = $(this).find("i");
            // check to see what icon to set
            if (icon.hasClass("fa-expand-arrows-alt")) {
                icon.removeClass("fa-expand-arrows-alt")  
                icon.addClass("fa-compress-arrows-alt")  
            } else {
                icon.removeClass("fa-compress-arrows-alt")
                icon.addClass("fa-expand-arrows-alt")  
            }
            $(".d_container").toggleClass('d_box');
        });
    // # right side bar
        // open right side bar
        $(".header_default_icons .fa-check-circle").on( "click", function() {
            $('.dso').toggleClass('dso_open');
            $('.dmf').toggleClass('dmf_open');
            $('.dso_modal').toggleClass('dso_modal_open');
        });
        // open ability to close right side bar 
        $(".dso_modal").on( "click", function() {
            $('.dso').toggleClass('dso_open');
            $('.dmf').toggleClass('dmf_open');
            $('.dso_modal').toggleClass('dso_modal_open');
        });
    // # media content
        // open media content modal // ! not all it needs to be
        $(".header_default_icons .fa-search").on( "click", function() {
            $('.dm').toggleClass('dm_open');
            $('.dmc_modal').toggleClass('dmc_modal_open');
        });
        $(".dmc_modal").on( "click", function() {
            $('.dm').toggleClass('dm_open');
            $('.dmc_modal').toggleClass('dmc_modal_open');
        });
    
});