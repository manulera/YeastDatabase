var sequence_utils= require('ve-sequence-utils');
export default class mRNA {
    constructor(input_json)
    {
        this.color_dictionary = {
            "five_prime_UTR":'#ffd891',
            "CDS":'',
            "intron":'#7BE0AD',
            "three_prime_UTR":'#ffd891',
        };

        this.full_sequence = "";
        this.cds = "";
        this.coverage = [];
        var seq_count = 0;
        var cds_count = 0;
        this.codon_edges_mRNA = [];
        this.codon_edges_CDS = [];
        input_json.mRNA_sequence.forEach(chunk => {
            var chunk_start = seq_count;
            var chunk_end = seq_count+chunk.sequence.length;
            
            this.full_sequence+=chunk.sequence;
            if (chunk.type=='CDS')
            {
                var cds_start = cds_count;
                var cds_end = cds_count+chunk.sequence.length;
                this.cds+=chunk.sequence;
                this.codon_edges_mRNA=this.codon_edges_mRNA.concat([chunk_start, chunk_end]);
                this.codon_edges_CDS = this.codon_edges_CDS.concat([cds_start,cds_end]);
                cds_count=cds_end;

            }
            else{
                this.coverage.push({
                    start: chunk_start,
                    end:chunk_end,
                    bgcolor: this.color_dictionary[chunk.type]
                });
            }
            seq_count=chunk_end;
        });
        this.protein_sequence="";
        this.aa_array=[];
        this.translate();
    }

    translate()
    {
        this.aa_array= sequence_utils.getAminoAcidDataForEachBaseOfDna(this.cds,true,null,false);
        console.log(this.aa_array);
        this.protein_sequence="";
        for (let i = 0; i < this.aa_array.length; i+=3) {
            this.protein_sequence+=this.aa_array[i].aminoAcid.value;
        }
    }

    findAminoacidInSequence(start_cds,stop_cds)
    {
        var codon_edges_list=[];
        var start_mRNA = null;
        var stop_mRNA = null;
        var added_edges = [];
        for (let i = 0; i < this.codon_edges_CDS.length; i+=2) {
            let min_edge = this.codon_edges_CDS[i];
            let max_edge = this.codon_edges_CDS[i+1];
            
            if (start_mRNA===null)
            {
                if (min_edge<=start_cds && max_edge>start_cds)
                {
                    start_mRNA=start_cds-min_edge+this.codon_edges_mRNA[i];
                }
            }
            // The start is already found, but not the end, we keep this start of exon
            else
            {
                added_edges.push(this.codon_edges_mRNA[i]);
            }

            if (start_mRNA!==null)
            {
                // The end is in the same exon
                if (min_edge<=stop_cds && max_edge>stop_cds)
                {
                    stop_mRNA=stop_cds-min_edge+this.codon_edges_mRNA[i];
                    break;
                }
                // the end is in another exon, we keep this end of exon
                else
                {
                    added_edges.push(this.codon_edges_mRNA[i+1]);
                }

            }
        }
        return [start_mRNA, ...added_edges, stop_mRNA];
    }
    
    
}