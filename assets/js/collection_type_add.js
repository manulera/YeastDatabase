import '../css/collection_type_add.css';
import { createPopper } from '@popperjs/core';
import mRNA from './classes/mrna.js';
var sequence_utils= require('ve-sequence-utils');

function range(start,stop,step)
{
    var array = new Array();
    for (let i = start; i < stop; i+=step) {
        array[i] = i;
    }
    return array;
}

var entityTypeButton = '<button type="button" aria-describedby="tooltip" class="btn-info add_EntityType_link">Add</button>';

function renderSequence()
{
    thismRNA = new mRNA(mRNA_json);
    var Sequence = require("sequence-viewer");
    var seq_dna = new Sequence(thismRNA.full_sequence);
    // Render the sequence with or without rendering options
    // (Check the interactive documentation)
    seq_dna.render('#dna-sequence-viewer',{'badge': false,'title':'','charsPerLine':100,'search': false,});
    
    
    var my_legend = [
        {name: "UTR", color: '#ffd891'},
        {name: "Exons", color: 'white'},
        {name: "Introns", color: '#7BE0AD'},
        {name: "Selected codons", color: 'red'}

    ]
    
    seq_dna.coverage(thismRNA.coverage);
    seq_dna.addLegend(my_legend);
    
    
    var seq_protein = new Sequence(thismRNA.protein_sequence);
    
    seq_protein.render('#protein-sequence-viewer',{'badge': false,'title':'','charsPerLine':100,'search': false,});
    seq_protein.addLegend([{name: "Selected aminoacids", color: 'red'}]);
}
function getCodon(codon_ind)
{
    var i = codon_ind-1;
    var aa = thismRNA.protein_sequence[i];
    var codon = thismRNA.cds.slice(i*3,(i+1)*3);
    return {'aa': aa, 'codon':codon};
}


function makeAjaxRequest() {

    return $.ajax({
        url: "/sequencejson",
        data: {
            locus_id: document.getElementById('form_allele_locus_locus').value,
        },
        dataType: 'json',
        async: false,
        
        success: function (json_response) {
            return json_response.responseJSON;
        },
        error: function()
        {
            console.log('a');
        }
    });
}


function addEntityTypeForm(collectionHolder, newLinkButt) {
    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');
    // get the new index
    var index = collectionHolder.data('index');

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
    collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a EntityType" link li
    var $newFormLi = $('<li></li>').append(newForm);
    newLinkButt.before($newFormLi);
}
var sequence_displayed=false;
var mRNA_json = null;
var thismRNA = null;

$(document).ready(function () {
    
    var collectionHolder = $('ul.entityType_list');
    var locus_selector = document.getElementById('form_allele_locus_locus');
    // add the "add a EntityType" anchor and li to the EntityTypes ul
    // collectionHolder.append(newLinkButt);

    for (let index = 0; index < collectionHolder.length; index++) {
        
        
        let ch = $(collectionHolder[index]);
        let newLinkButt = $('<div></div>').append(entityTypeButton);
        ch.append(newLinkButt);
        ch.data('index', ch.find(':input').length);
        let button = $(ch.find('.add_EntityType_link'));
        button.on('click', function(e) {
            // add a new EntityType form (see next code block)
            if (document.getElementById('form_allele_locus_locus').value!='')
            {
                addEntityTypeForm(ch, newLinkButt);
                if (!sequence_displayed)
                {
                    mRNA_json= makeAjaxRequest().responseJSON;
                }
                renderSequence();
            }
        });
    }

    $('.add_EntityType_link').on("mouseover", function()
    {
        if (locus_selector.value=='')
        {
            var tooltip = document.getElementById("tooltip");
            
            createPopper(this, tooltip, {
                placement: 'right',
                modifiers: [{name: 'offset', options: {offset:[0, 16]}}]
                });
            tooltip.style.visibility = "visible";
            $(this).on("mouseout", function()
            {
                var tooltip = document.getElementById("tooltip");
                tooltip.style.visibility = "hidden";
            }
            );
        }
    });
    $('.entityType_list').on('DOMNodeInserted',function()
    {
        console.log(this);
        var a = $(this).find("#form_pointMutations_1_sequencePosition")
        a.on("change",function()
        {
            if (this.value>0 && this.value< thismRNA.protein_sequence.length)
            {
                console.log(getCodon(this.value));
            }
        });
        a.on("keyup",function()
        {
            if (this.value>0 && this.value< thismRNA.protein_sequence.length)
            {
                console.log(getCodon(this.value));
            }
        });
        
        
    }
    );
    
    

});