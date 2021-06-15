import '../css/collection_type_add.css';
import { createPopper } from '@popperjs/core';
import mRNA from './classes/mrna.js';


var sequence_utils = require('ve-sequence-utils');

const codon_dict = {
    "A": ["GCA", "GCC", "GCG", "GCT"],
    "C": ["TGC", "TGT"],
    "D": ["GAC", "GAT"],
    "E": ["GAA", "GAG"],
    "F": ["TTC", "TTT"],
    "G": ["GGA", "GGC", "GGG", "GGT"],
    "H": ["CAC", "CAT"],
    "I": ["ATA", "ATC", "ATT"],
    "K": ["AAA", "AAG"],
    "L": ["CTA", "CTC", "CTG", "CTT", "TTA", "TTG"],
    "M": ["ATG"],
    "N": ["AAC", "AAT"],
    "P": ["CCA", "CCC", "CCG", "CCT"],
    "Q": ["CAA", "CAG"],
    "R": ["AGA", "AGG", "CGA", "CGC", "CGG", "CGT"],
    "S": ["AGC", "AGT", "TCA", "TCC", "TCG", "TCT"],
    "T": ["ACA", "ACC", "ACG", "ACT"],
    "V": ["GTA", "GTC", "GTG", "GTT"],
    "W": ["TGG"],
    "Y": ["TAC", "TAT"],
}

var addEntityTypeButton = '<button type="button" class="btn-info add_EntityType_link">Add</button>';
var remEntityTypeButton = '<button type="button" class="btn-danger remove_EntityType_link">Delete</button>';

function renderSequence() {
    thismRNA = new mRNA(mRNA_json);
    var Sequence = require("sequence-viewer");
    var seq_dna = new Sequence(thismRNA.full_sequence);
    // Render the sequence with or without rendering options
    // (Check the interactive documentation)
    seq_dna.render('#dna-sequence-viewer', { 'badge': false, 'title': '', 'charsPerLine': 100, 'search': false, });


    var my_legend = [
        { name: "UTR", color: '#ffd891' },
        { name: "Exons", color: 'white' },
        { name: "Introns", color: '#7BE0AD' },
        { name: "Selected codons", color: 'red' }

    ]    
    var extra_coverage = [];
    var extra_coverage_dna = [];
    $("table.pointmutation-table").each(function () {
        // Zero indexing
        var position = $(this).find(':input')[0].value;
        if (position) {
            var seq_position = parseInt(position) - 1;
            extra_coverage.push({
                start: seq_position,
                end: seq_position + 1,
                bgcolor: "red"
            });
            var codon_edges = thismRNA.findAminoacidInSequence(seq_position*3,(seq_position+1)*3);
            for (let i = 0; i < codon_edges.length; i+=2) {
                extra_coverage_dna.push({
                    start: codon_edges[i],
                    end: codon_edges[i+1],
                    bgcolor: "red"
                });    
            }
            

        }
        
        
    });
    $("table.truncation-table").each(function () {
        // Zero indexing
        var seq_start = parseInt($(this).find(':input')[0].value) - 1;
        var seq_end = parseInt($(this).find(':input')[1].value) - 1;
        if (seq_start && seq_end) {
            if (seq_start > 0 && seq_end < thismRNA.protein_sequence.length && seq_start < seq_end) {
                extra_coverage.push({
                    start: seq_start,
                    end: seq_end + 1,
                    bgcolor: "red"
                });
                $(this).find('.truncation-warning').css('visibility', 'hidden');
                var codon_edges = thismRNA.findAminoacidInSequence(seq_start*3,(seq_end+1)*3);
                for (let i = 0; i < codon_edges.length; i+=2) {
                    extra_coverage_dna.push({
                        start: codon_edges[i],
                        end: codon_edges[i+1],
                        bgcolor: "red"
                    });    
                }
            }
            else{
                console.log($(this).find('.truncation-warning'));
                $(this).find('.truncation-warning').css('visibility', 'visible');
            }
        }

    });

    seq_dna.coverage([...thismRNA.coverage, ...extra_coverage_dna]);
    seq_dna.addLegend(my_legend);


    var seq_protein = new Sequence(thismRNA.protein_sequence);

    seq_protein.render('#protein-sequence-viewer', { 'badge': false, 'title': '', 'charsPerLine': 100, 'search': false, });
    
    if (extra_coverage.length) { seq_protein.coverage(extra_coverage); }
    seq_protein.addLegend([{ name: "Selected aminoacids", color: 'red' }]);
}
function getCodon(codon_ind) {
    var i = codon_ind - 1;
    var aa = thismRNA.protein_sequence[i];
    var codon = thismRNA.cds.slice(i * 3, (i + 1) * 3);
    return { 'aa': aa, 'codon': codon };
}


function makeAjaxRequest() {

    return $.ajax({
        url: "/sequencejson",
        data: {
            locus_id: document.getElementById('mol_biol_allele_chunky_allelesOut_0_locus_locus').value,
        },
        dataType: 'json',
        async: false,

        success: function (json_response) {
            return json_response.responseJSON;
        },
        error: function () {
            
        }
    });
}

function updatePointMutation() {
    var table = $(this).closest('table');
    var value = $(this).val();
    var select_original_aa = $(table).find('input')[1];
    var select_original_codon = $(table).find('input')[2];
    if (value > 0 && value < thismRNA.protein_sequence.length) {
        var codon = getCodon(value);
        $(select_original_aa).val(codon.aa);
        $(select_original_codon).val(codon.codon);
    }
    else {
        $(select_original_aa).val(null);
        $(select_original_codon).val(null);
    }
    renderSequence();
}

function updateNewCodon() {
    var table = $(this).closest('table');
    var select_new_aa = $(table).find('select')[0];
    var select_new_codon = $(table).find('select')[1];

    // Remove all the options from the select element of new codon
    $(select_new_codon).empty();
    var new_aa = $(select_new_aa).val();
    var codons = codon_dict[new_aa];

    $(select_new_codon).append($("<option></option>").attr("value", "unknown").text("unknown"))
    $.each(codons, function (i, codon) {
        $(select_new_codon).append($("<option></option>").attr("value", codon).text(codon));
    });

}

function addEntityTypeForm(collection_holder, newLinkButt) {
    // Get the data-prototype explained earlier
    var prototype = collection_holder.data('prototype');
    // get the new index
    var index = collection_holder.data('index');

    var new_form = prototype;
    // You need this only if you didn't set 'label' => false in your EntityTypes field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // new_form = new_form.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    new_form = new_form.replace(/__name__/g, index);
    new_form = $(new_form);
    // increase the index with one for the next item
    collection_holder.data('index', index + 1);
    // Display the form in the page in an li, before the "Add a EntityType" link li
    var new_formLi = $('<li></li>').append(new_form);
    newLinkButt.before(new_formLi);

    var this_button = $(remEntityTypeButton);
    $(new_formLi).append(this_button);
    this_button.on('click', function (e) {
        // remove the li for the tag form
        new_formLi.remove();
    });

    return new_form;
}
var sequence_displayed = false;
var mRNA_json = null;
var thismRNA = null;

$(document).ready(function () {
    var collection_holder = $('ul.entityType_list');
    var locus_selector = document.getElementById('mol_biol_allele_chunky_allelesOut_0_locus_locus');
    // add the "add a EntityType" anchor and li to the EntityTypes ul
    // collection_holder.append(newLinkButt);

    for (let index = 0; index < collection_holder.length; index++) {


        let ch = $(collection_holder[index]);
        let newLinkButt = $('<div></div>').append(addEntityTypeButton);
        ch.append(newLinkButt);
        ch.data('index', ch.find(':input').length);
        let button = $(ch.find('.add_EntityType_link'));
        button.on('click', function (e) {
            // add a new EntityType form (see next code block)
            if (document.getElementById('mol_biol_allele_chunky_allelesOut_0_locus_locus').value != '') {
                let new_form = addEntityTypeForm(ch, newLinkButt);
                let input_ind = $(new_form).find(':input')[0];
                if ($(input_ind).closest('table').hasClass("pointmutation-table")) {
                    let new_aa_select = $(new_form).find('select')[0];
                    $(input_ind).on("keyup input change", updatePointMutation);
                    $(new_aa_select).each(updateNewCodon);
                    $(new_aa_select).on("keyup input change", updateNewCodon);
                }
                else if ($(input_ind).closest('table').hasClass("truncation-table")) {
                    $(new_form).find('input').on("keyup input change", renderSequence);
                }


                if (!sequence_displayed) {
                    mRNA_json = makeAjaxRequest().responseJSON;
                }
                renderSequence();
            }
        });
    }

    $('.add_EntityType_link').on("mouseover", function () {
        if (locus_selector.value == '') {
            var tooltip = document.getElementById("tooltip");

            createPopper(this, tooltip, {
                placement: 'right',
                modifiers: [{ name: 'offset', options: { offset: [0, 16] } }]
            });
            tooltip.style.visibility = "visible";
            $(this).on("mouseout", function () {
                var tooltip = document.getElementById("tooltip");
                tooltip.style.visibility = "hidden";
            }
            );
        }
    });
});