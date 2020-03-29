
import '../css/collection_type_add.css';

var $collectionHolder;

var $addEntityTypeButton = $('<button type="button" class="btn-info add_EntityType_link">Add</button>');
var $newLinkButt = $('<div></div>').append($addEntityTypeButton);

function addEntityTypeForm($collectionHolder, $newLinkButt) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your EntityTypes field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);
    newForm = $(newForm).css('display', 'inline-block');
    newForm = $(newForm).css('padding-right', 20);
    newForm = $(newForm).find('div').css('display', 'inline-block');
    newForm = $(newForm).css('padding-right', 20);
    
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a EntityType" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkButt.before($newFormLi);
}


$(document).ready(function () {

    $collectionHolder = $('ul.entityType_list');

    // add the "add a EntityType" anchor and li to the EntityTypes ul
    $collectionHolder.append($newLinkButt);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addEntityTypeButton.on('click', function(e) {
        // add a new EntityType form (see next code block)
        addEntityTypeForm($collectionHolder, $newLinkButt);
    });


});