var $ = require('jquery');

$(document).ready(function () {
    
    $('.filter-locus :input').on('input',function()
    {
        
        var filter_locus_name = document.querySelector("input[name*='[filterByLocusName]']").value;
        var filter_pombase_id = document.querySelector("input[name*='[filterByPombaseId]']").value;
        var select_element = document.querySelector("select[name*='[locus]']");
        // console.log(select_element);
        // Remove old options
        while (select_element.options.length>0){ select_element.remove(0);}
        if (filter_pombase_id.length>4||filter_locus_name.length>1)
        {
            $.ajax({
                url: "/entitypicker/locus",
                data: {
                    filter_pombase_id: filter_pombase_id,
                    filter_locus_name: filter_locus_name
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
                    select_element.selectedIndex = 0;
                    
                },
                error: function()
                {
        
                }
            });
        }
    }
    );

    
});

