$(document).ready(function() {
    // on change reset sub selection options
    $('select[name="category[useCat]"]').on('change', function() {
        // get ctr // * collection_type_reference, located at: root/private/reference_information.php
        const ctr = $(this).val();
        // set element for changing options
        const $el = $('select[name="category[subCatId]"]');
        // empty options, remove old options
        $el.empty();
        // get new options object,set by php on the view
        let newOptions_obj = "";
        switch (ctr) {
            case "1": newOptions_obj = postCategories_obj; break;
            case "2": newOptions_obj = mediaContentCategories_obj; break;
            case "3": newOptions_obj = usersCategories_obj; break;
            case "4": newOptions_obj = contentCategories_obj; break;
        }
        // set the option of none
        $el.append($("<option></option>").attr("value", "0").text("None"));
        // create new options
        $.each(newOptions_obj, function(key,value) {
            $el.append($("<option></option>").attr("value", key).text(value));
        });
    });
});