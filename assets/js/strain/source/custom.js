
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

var ajaxRequestFunction = function() {
    $.ajax({
        url: "/strain/new/mating/combinations",
        data: {
            
        },
        success: function (html) {

            $("#mating_strain_choice").replaceWith($(html).find("#mating_strain_choice"));
            $("#submission_button").replaceWith($(html).find("#mating_save"));
        },
        error: function()
        {

        }
    });

};

$(document).ready( function() {
    
    $('#add_allele').click(ajaxRequestFunction);

});