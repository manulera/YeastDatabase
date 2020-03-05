var $ = require('jquery');

function getCheckedBoxes()
{
    checkboxes = $('.form-check-input').get();
    out = [];
    for (i=0;i<checkboxes.length;i++)
    {
        if (checkboxes[i].checked)
        {
            out.push(checkboxes[i].value);   
        }
    }
    return out;
}

function updateCheckBoxes()
{
    delete_checkbox = $('input:checkbox[value="Deletion"]').get();
    if (delete_checkbox[0].checked)
    {
        // Uncheck the others
        $('input:checkbox[value!="Deletion"]').prop("checked", false);
    }
}

var ajaxRequestFunction = function() {
    
    updateCheckBoxes();

    $.ajax({
        url: "/strain/new/molbiol/possibilities",
        data: {
            choice: getCheckedBoxes(),
            strain: $("#mol_biol_inputStrain").val(),
            locus: $("#mol_biol_targetLocus").val()
        },
        success: function (html) {
            $("#changing_fields").replaceWith($(html).find("#changing_fields"));
        },
        error: function()
        {

        }
    });

};

$(document).ready( function() {
    // TODOCUMENT: This is an interesting example of event delegation. Check the 
    // website in bookmarks
    $("#mol_biol_choice").on("click",'input[type="checkbox"]',ajaxRequestFunction);
    $("#changing_fields").on("change",ajaxRequestFunction);
});