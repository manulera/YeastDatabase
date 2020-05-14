var $ = require('jquery');




$(document).ready(function () {
    $('.filter-locus :input').on('input',function()
    {
        var filter_locus_name = $('.filter-locus.locus_name  :input').val();
        var filter_pombase_id = $('.filter-locus.pombase_id  :input').val();
        console.log(filter_locus_name,filter_pombase_id);
        
        if (filter_pombase_id.length>4||filter_locus_name.length>1)
        {
            $.ajax({
                url: "/strain/new/molbiol/custom_allele/ajax",
                data: {
                    filter_pombase_id: filter_pombase_id,
                    filter_locus_name: filter_locus_name
                },
                success: function (html) {
                    var new_options=$(html).find("#allele_locus option");
                    var select_element = $("#form_allele_locus");
                    // Remove old options
                    select_element.empty();
                    $("#form_allele_locus").append(new_options);
                    
                },
                error: function()
                {
        
                }
            });
        }
        
        
        
    }
    );

    
});

