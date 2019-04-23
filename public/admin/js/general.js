$(document).ready(function() {
    // # general
    // code here
    // # header
    // header drop-down menu functionality
    $("header .dropdown").on( "click", function() {
        // get menu
        const dropdown_menu = $("header .dropdown_menu");
        // performed information accordingly
        console.log(dropdown_menu);
        dropdown_menu.css("display", "block");
    });
});