// On select change hide all forms except for the on that was just selected
// $('#types').on('change', function () {
//     $('.form').addClass('hidden');
//     $('#' + $(this).val()).removeClass('hidden');
// });



// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

$(document).ready( function() {
    // $("#mating_outcomes").text("AAA");

    $('p').text('JS is working!');
    $('#my_button').click(function() {
        $('p').text('button clicked!');
        $.ajax("/dummy_ajax", {
            data : {
                type: 'POST',
                data: {name : 'a_name'}
            },
            async: true,
            success: function(data){
                $('p').text('success!');
                $('div#data_passed').text(data.data_passed);
            },
            error: function(){
                $('p').text('error!');
            }
        })
    });
});