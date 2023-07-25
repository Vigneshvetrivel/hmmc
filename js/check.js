$(function(){
    $('#addupload').click(function(){
        var addControl = '<label>Upload Text File:</label>';
        addControl += '<input type="file"
        name="file[]" class="imageupload">';
        $('#display').before(addControl);
    });
});
