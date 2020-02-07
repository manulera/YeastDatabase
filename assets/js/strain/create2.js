// On select change hide all forms except for the on that was just selected
// $('#types').on('change', function () {
//     $('.form').addClass('hidden');
//     $('#' + $(this).val()).removeClass('hidden');
// });



// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

$(document).ready( function() {
    
    var $select_element = $('.data-combinations-url');
    var $js_target = $('.js-combination-target');

    $('#combinations_button').click(function(e) {
        $("#mating_outcomes").text("reached");
        $.ajax({
            url: "/strain/create/Mating/combinations",
            data: {
                strain1: $("#mating_strain1").val(),
                strain2: $("#mating_strain2").val()
            },
            success: function (html) {
                
                if (!html) {
                    $js_target.find('combinations').remove();
                    return;
                }

                // Replace the current field and show
                $js_target
                    .html(html)
                    .removeClass('d-none')
            }
        });


    });
});