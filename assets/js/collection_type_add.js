
import '../css/collection_type_add.css';


var entityTypeButton = '<button type="button" class="btn-info add_EntityType_link">Add</button>';


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

    var $collectionHolder = $('ul.entityType_list');

    // add the "add a EntityType" anchor and li to the EntityTypes ul
    // $collectionHolder.append($newLinkButt);

    for (let index = 0; index < $collectionHolder.length; index++) {
        
        
        let $ch = $($collectionHolder[index]);
        let $newLinkButt = $('<div></div>').append(entityTypeButton);

        $ch.append($newLinkButt);
        $ch.data('index', $ch.find(':input').length);
        $newLinkButt.on('click', function(e) {
            // add a new EntityType form (see next code block)
            
            addEntityTypeForm($ch, $newLinkButt);
        });
        
    }

    
    // $addEntityTypeButton.on('click', function(e) {
    //     // add a new EntityType form (see next code block)
    //     addEntityTypeForm($collectionHolder, $newLinkButt);
    // });


});