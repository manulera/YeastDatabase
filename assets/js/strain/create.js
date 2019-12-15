// On select change hide all forms except for the on that was just selected
// $('#types').on('change', function () {
//     $('.form').addClass('hidden');
//     $('#' + $(this).val()).removeClass('hidden');
// });



// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');



$(document).ready( function() {
    $('#types').change(function() {
        $('.form').hide();
        $('#' + $(this).val()).show();
    });
});