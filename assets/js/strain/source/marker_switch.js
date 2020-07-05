

var $ = require('jquery');

var ajaxRequestFunction = function() {
    console.log("called");
    
    $.ajax({
        url: "/strain/new/molbiol/markerswitch/" + $(this).val(),
        success: function (html) {
            $("#allele_section").replaceWith($(html).find("#allele_section"));
            
            // if ($(html).find("#form_save").length)
            // {
            //     $("#submission_button").html($(html).find("#form_save"));
            // }
            // else
            // {
                
            //     $("#submission_button").html("");
            // }
        },
        error: function()
        {

        }
    });

};

$(document).ready( function() {
    var select_element = $($('.strain-select')[0]).find("select")[0];
    
    select_element.addEventListener('strains_added',ajaxRequestFunction);
    select_element.addEventListener('change',ajaxRequestFunction);
    

});