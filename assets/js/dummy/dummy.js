import '../../css/dummy/dummy.css';
import mRNA from '../classes/mrna.js';
var $ = require('jquery');
var sequence_utils= require('ve-sequence-utils');


// function range(start,stop,step)
// {
//     var array = new Array();
//     for (let i = start; i < stop; i+=step) {
//         array[i] = i;
//     }
//     return array;
// }

function makeAjaxRequest() {

    
    return $.ajax({
        url: "/sequencejson",
        data: {
            locus_id: 120,
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

$(document).ready(function () {
    var mRNA_json = makeAjaxRequest().responseJSON;
    var thismRNA = new mRNA(mRNA_json);
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
 
    seq_protein.onMouseSelection(function(elem){
        // Switch to zero indexing
        var i = elem.detail.start-1;
        var len = (elem.detail.end-i);
        
        var codon_edges = thismRNA.findAminoacidInSequence(i*3,(i+len)*3);
        
        var codon_coverage=[];
        
        for (let i = 0; i < codon_edges.length; i+=2) {
            codon_coverage.push(
                {
                        start: codon_edges[i],
                        end:codon_edges[i+1],
                        bgcolor: 'red'
                }
            );
            
        }
        seq_dna.coverage([...codon_coverage, ...thismRNA.coverage]);

        seq_protein.coverage([{
            start: i,
            end: i+len,
            bgcolor: 'red'

        }])
        $('.btn-sequence-viewer').hide();
        if (len==1)
        {
            $('#btn-mutate-aa').show();
        }
        else if (len>1)
        {
            $('#btn-truncate-protein').show();
        }

    }
    
    );

});


