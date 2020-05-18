var $ = require('jquery');

$(document).ready(function () {
    
    $('.filter-strain :input').on('input',function()
    {
        console.log('hello');    
        var filter_strain_id = document.getElementsByClassName("strain-id")[0].getElementsByTagName("input")[0].value;
        var filter_genotype = document.getElementsByClassName("strain-genotype")[0].getElementsByTagName("input")[0].value;
        var select_element = document.getElementsByClassName("strain-select")[0].getElementsByTagName("select")[0];
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
                    console.log(html_response);
                    options.forEach(option => {
                        select_element.add(option);
                    });
                    
                },
                error: function()
                {
        
                }
            });
        }
    }
    );

    
});

