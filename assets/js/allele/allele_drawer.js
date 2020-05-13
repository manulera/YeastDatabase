var $ = require('jquery');
var d3 = require("d3");

exports.read_allele = function(id,n)
{

    var allele =  $(id).data("jsonarray");
    var out = [];
    out.push({ 'name': '', 'type': 'main','id':n})
    console.log(n,'read')
    if (!allele) {return out;}
    
    allele.truncations.forEach(trunc => {
        let name = "Δ" + String(trunc.start) + "-" + String(trunc.finish)
        out.push({ 'name': name, 'type': 'truncation', 'start': trunc.start, 'finish': trunc.finish});
    });

    allele.pointMutations.forEach(pm => {
        let name = pm.originalAminoAcid + String(pm.sequencePosition) + pm.newAminoAcid;
        out.push({ 'name': name, 'type': 'pointMutation', 'position': pm.sequencePosition});
    });

    var n_term_index = -1;
    if (allele.nTag)
    {
        out.push({ 'name': allele.nTag.name, 'type': 'tag', 'ind': n_term_index});
        n_term_index-=1;
    }
    if (allele.promoter)
    {
        out.push({ 'name': allele.promoter.name, 'type': 'promoter', 'ind': n_term_index});
        n_term_index-=1;
    }
    if (allele.nMarker)
    {
        out.push({ 'name': allele.nMarker.name, 'type': 'marker', 'ind': n_term_index});
        n_term_index-=1;
    }
    var c_term_index = 0;
    if (allele.cTag)
    {
        out.push({ 'name': allele.cTag.name, 'type': 'tag', 'ind': c_term_index});
        c_term_index+=1;
    }
    if (allele.cMarker)
    {
        out.push({ 'name': allele.cMarker.name, 'type': 'marker', 'ind': c_term_index});
        c_term_index+=1;
    }
    return out;

}

exports.update_allele = function(data,center,square_size)
{
    data.forEach(d => {
        d.xcenter=center[0];
        d.ycenter=center[1];
        d.xsize=square_size[0];
        d.ysize=square_size[1];
        
    });
}


exports.draw_allele = function(data,svg){
    
    var tag_length = 50;
    var real_size = 1000.;

    function centerx(x,d) {
        return x + d.xcenter - d.xsize / 2;
    }

    function xposition(d) {
        if (d.type == 'main') {
            return centerx(0,d);
        }
        else if (d.type == "truncation") {
            return centerx(d.start / real_size * d.xsize,d);

        }
        else if (d.type == "pointMutation") {
            return centerx(d.position / real_size * d.xsize,d);

        }
        else if (d.type == "tag" || d.type == "promoter"||d.type=="marker") {
            if (d.ind>=0)
                return centerx(d.xsize + d.ind * tag_length,d);

            else
                return centerx(d.ind * tag_length,d);

        }
    }
    function yposition(d) {
        return d.ycenter;
    }
    
    function ypositionText(d) {
        console.log('called');
        var padding_top = -10;
        var padding_bottom = 15;
        if (d.type == 'main') {
            return yposition(d) + padding_top;
        }
        else if (d.type == 'pointMutation') {
            return yposition(d) + d.ysize + (d.ind + 1) * padding_bottom;
        }
        else if (d.type == 'promoter') {
            return yposition(d) + d.ysize + padding_bottom;
        }
        else {
            return yposition(d) + padding_top;
        }
    }

    function xpositionText(d)
    {
        // console.log(d);
        if (d.type=='pointMutation')
        {
            var padding=3;
            return xposition(d)+padding;
        }
        else {return xposition(d);}
    }

    function chunkWidth(d) {
        if (d.type == 'main') { return d.xsize; }

        else if (d.type == "truncation") { return (d.finish - d.start) / real_size * d.xsize; }

        else if (d.type == "pointMutation") { return 1. / d.xsize; }

        else if (d.type == "tag" || d.type == "promoter"||d.type=="marker") {
            return tag_length;
        }
        
    }

    function chunkHeight(d){
        if (d.type=='pointMutation')
        {
            return ypositionText(d)-yposition(d);
        }
        else {return d.ysize;}
    }

    function promoterArrow(d)
    {
        var x0=xposition(d)+tag_length/4.;
        var x1=x0;
        var x2=x0+tag_length/2.;
        var y0=yposition(d);
        var y1=y0-20;
        var y2=y0-20;
        return [[x0,y0],[x1,y1],[x2,y2]]
    }

    // Define the arrow
    svg.append("svg:defs").selectAll("marker")
    .data(["arrow"])      // Different link/path types can be defined here
    .enter().append("svg:marker")    // This section adds in the arrows
    .attr("id", String)
    .attr("viewBox", "0 -5 10 10")
    .attr("refX", 0)
    .attr("refY", 0)
    .attr("markerWidth", 4)
    .attr("markerHeight", 4)
    .attr("orient", "auto")
    .append("svg:path")
    .attr("d", "M0,-5L10,0L0,5");
    
    let g = svg
        .selectAll('g')
        .data(data)
        .enter()
        .append('g');
    console.log(g);
    console.log(data);
    g.append('rect')
        .attr('x', xposition)
        .attr('y', yposition)
        .attr('width', chunkWidth)
        .attr('height', chunkHeight)
        .attr('class', function (d) { return 'allele ' + d.type })
        ;
    g.append('text')
        .attr('x', xpositionText)
        .attr('y', ypositionText)
        .text(function (d) { return d.name; })
        ;

    g.each(function(d){
        
        if (d.type=="promoter")
        {
            var p = d3.line()(promoterArrow(d));
            d3.select(this).append('path')
            .attr("d",p)
            .attr("class", "line")
            .attr("stroke", "black")
            .attr("marker-end","url(#arrow)")
            // .attr("fill",false)
            ;
        }
    });
    
}