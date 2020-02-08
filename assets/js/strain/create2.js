// On select change hide all forms except for the on that was just selected
// $('#types').on('change', function () {
//     $('.form').addClass('hidden');
//     $('#' + $(this).val()).removeClass('hidden');
// });



// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

$(document).ready( function() {
    
    $('#combinations_button').click(function(e) {
        $.ajax({
            url: "/strain/create/Mating/combinations",
            data: {
                strain1: $("#mating_strain1").val(),
                strain2: $("#mating_strain2").val()
            },
            success: function (html) {

                // Replace the current field and show
                $("#ajax_outcome").text("Success in ajax request");
                $("#mating_strain_choice").replaceWith($(html).find("#mating_strain_choice"));
                $("#submission_button").replaceWith($(html).find("#mating_save"));
            },
            error: function()
            {
                $("#ajax_outcome").text("Error in ajax request");
            }
        });


    });
});