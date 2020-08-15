// On select change hide all forms except for the on that was just selected
// $('#types').on('change', function () {
//     $('.form').addClass('hidden');
//     $('#' + $(this).val()).removeClass('hidden');
// });



// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

var ajaxRequestFunction = function() {
    console.log("called");
    $.ajax({
        url: "/strain/new/mating/combinations",
        data: {
            strain1: $("#form_strain1_strain").val(),
            strain2: $("#form_strain2_strain").val()
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
    
    $('#form_strain1_strain').change(ajaxRequestFunction);
    $('#form_strain1_strain').on('strains_added',ajaxRequestFunction);
    $('#form_strain2_strain').change(ajaxRequestFunction);
    $('#form_strain2_strain').on('strains_added',ajaxRequestFunction);

});