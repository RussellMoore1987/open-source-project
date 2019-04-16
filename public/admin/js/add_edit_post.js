$(document).ready(function() {
    // set focus
    var titleText = $('#title').val();
    $('#title').val(' ' + titleText).focus();
    
    // on change reset author's name in hidden input field
    $('select[name="post[author]"]').on('change', function() {
        var name = $(this).find('option:selected').text();
        var input = $('input[name="post[authorName]"]');
        input.val(name);
    });
    
});