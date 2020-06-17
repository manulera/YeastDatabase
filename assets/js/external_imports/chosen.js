var $ = require('jquery');
require('../../../vendor/harvesthq/chosen');
import '../../../vendor/harvesthq/chosen/chosen.css';
$('select').on('chosen:ready', function () {
    const height = $(this).next('.chosen-container').height();
    const width = $(this).next('.chosen-container').width();
    $(this).css({
            'position': 'absolute',
            'height': height,
            'width': width,
            'opacity': 0
        })
        .show();
});
$(".chosen-select").chosen({ allow_single_deselect:true, enable_split_word_search: true});