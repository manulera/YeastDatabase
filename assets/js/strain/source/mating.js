// On select change hide all forms except for the on that was just selected
// $('#types').on('change', function () {
//     $('.form').addClass('hidden');
//     $('#' + $(this).val()).removeClass('hidden');
// });



// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

var ajaxRequestFunction = function() {
    $.ajax({
        url: "/strain/new/mating/combinations",
        data: {
            strain1: $("#form_strain1").val(),
            strain2: $("#form_strain2").val()
        },
        success: function (html) {

            $("#form_strain_choice").replaceWith($(html).find("#form_strain_choice"));
            if ($(html).find("#form_save").length)
            {
                $("#submission_button").html($(html).find("#form_save"));
            }
            else
            {
                
                $("#submission_button").html("");
            }
        },
        error: function()
        {

        }
    });

};

$(document).ready( function() {
    
    $('#form_strain1').change(ajaxRequestFunction);
    $('#form_strain2').change(ajaxRequestFunction);

});