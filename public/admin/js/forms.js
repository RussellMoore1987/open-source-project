// ! temp js
$(document).ready(function() {
    // toggle active and not active, add listener to the parent so that new children will have desired functionality
    $(".multiSelect").on("click", "span", function () {
        // go class on click
        $(this).toggleClass("active");
        // create default array
        const idList = [];
        // find all active elements and get their IDs, put them into an array
        $(this).parent().find("span.active").map(function() {idList.push(this.id)})
        // if ID list is blank add in blank string
        if (idList.length < 1) {
            idList.push(" ");
        }
        // . Array into a string and put it in the input for that section
        $(this).parent().find("input[type='hidden']").attr("value", idList.join());
    });
});
// ! temp js