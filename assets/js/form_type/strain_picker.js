var $ = require('jquery');

function makeAjaxRequest(i) {

    var filter_strain_id = document.getElementsByClassName("strain-id")[i].getElementsByTagName("input")[0].value;
    var filter_genotype = document.getElementsByClassName("strain-genotype")[i].getElementsByTagName("input")[0].value;
    var select_element = document.getElementsByClassName("strain-select")[i].getElementsByTagName("select")[0];
    // console.log(select_element);
    // Remove old options
    while (select_element.options.length>0){ select_element.remove(0);}
    if (filter_strain_id.length>0||filter_genotype.length>1)
    {
        $.ajax({
            url: "/entitypicker/strain",
            data: {
                filter_strain_id: filter_strain_id,
                filter_genotype: filter_genotype
            },
            success: function (html_response) {
                // We create a temporary div that is not rendered 
                // to store the ajax response html and look for something in it.
                var temp_div = document.createElement('div');
                temp_div.innerHTML=html_response;
                var options=temp_div.querySelectorAll("option");
                options.forEach(option => {
                    select_element.add(option);
                });
                // Here we select the first element of the list. This is important when filtering with the id.
                // For example, when querying id 31, you want the id=31 to show up before id=331
                select_element.selectedIndex = 0;
            },
            error: function()
            {
    
            }
        });
    }
}

$(document).ready(function () {
    
    let all_filters = document.getElementsByClassName("picker-form");
    console.log(all_filters[0]);
    for (let i = 0; i < all_filters.length; i++) {
        let inputs_i = all_filters[i].getElementsByTagName("input");
        inputs_i.forEach(input => {
            input.addEventListener("input", function(){makeAjaxRequest(i);});
        });
        
    }


    
});

