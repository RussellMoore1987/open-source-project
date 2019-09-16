$(document).ready(function() {
    // set focus
    var titleText = $('#title').val();
    $('#title').val(' ' + titleText).focus();

    // on change reset sub selection options
    $('select[name="category[useCat]"]').on('change', function() {
        // get ctr // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
        const ctr = $(this).val();
        // set element for changing options
        const $el = $('select[name="category[subCatId]"]');
        // empty options, remove old options
        $el.empty();
        // get new options object,set by php on the view
        let newOptions_obj = {};
        switch (ctr) {
            case "1": newOptions_obj = postCategories_obj; break;
            case "2": newOptions_obj = mediaContentCategories_obj; break;
            case "3": newOptions_obj = usersCategories_obj; break;
            case "4": newOptions_obj = contentCategories_obj; break;
        }
        // set the option of none
        $el.append($("<option></option>").attr("value", "0").text("None"));
        // turn object into an array of objects expand details
            // create objects for collecting information
            let grouped_obj = {};
            // create new array of objects and
            const newOptions_array = [];
            // look over newOptions_obj
            $.each(newOptions_obj, function(key,value) {
                // reset object for collecting so not to continue caring more information than needed
                grouped_obj = {};
                // set values
                grouped_obj.id = key;
                grouped_obj.title = value;
                // pushed to new array
                newOptions_array.push(grouped_obj);
            });        
        // sort array of objects case insensitive, sort by title 
        newOptions_array.sort(function(a, b) {
            var titleA = a.title.toUpperCase(); // ignore upper and lowercase
            var titleB = b.title.toUpperCase(); // ignore upper and lowercase
            if (titleA < titleB) {
                return -1;
            }
            if (titleA > titleB) {
                return 1;
            }
        
            // names must be equal
            return 0;
        });
        // output new options
        $.each(newOptions_array, function(key,value) {
            $el.append($("<option></option>").attr("value", value.id).text(value.title));
        });
    });
});


