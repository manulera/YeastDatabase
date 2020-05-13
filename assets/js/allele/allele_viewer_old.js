
var $ = require('jquery');
var d3 = require("d3");

import '../../css/allele/allele_viewer.css';

$(document).ready(function () {
    
    // set the dimensions and margins of the graph
    var width = 900,
        height = 400;

    var center = [width / 2, height / 2];
    var square_size = [300, 40];
    var top_height = 20;
    var bottom_height = 120;
    var tag_length = 50;

    // Define the called functions
    function centerx(x) {
        return x + center[0] - square_size[0] / 2;
    }

    function xposition(d) {
        if (d.type == 'main') {
            return centerx(0);
        }
        else if (d.type == "truncation") {
            return centerx(d.start / real_size * square_size[0]);
        }
        else if (d.type == "pointMutation") {
            return centerx(d.position / real_size * square_size[0]);
        }
        else if (d.type == "tag" || d.type == "promoter"||d.type=="marker") {
            if (d.ind>=0)
                return centerx(square_size[0] + d.ind * tag_length);
            else
                return centerx(d.ind * tag_length);

        }

    }
    function yposition(d) {
        return d.y_position == 'top' ? top_height : bottom_height;
    }

    function ypositionText(d) {
        var padding_top = -10;
        var padding_bottom = 15;
        if (d.type == 'main') {
            return yposition(d) + padding_top;
        }
        else if (d.type == 'pointMutation') {
            return yposition(d) + square_size[1] + (d.ind + 1) * padding_bottom;
        }
        else if (d.type == 'promoter') {
            return yposition(d) + square_size[1] + padding_bottom;
        }
        else {
            return yposition(d) + padding_top;
        }
    }

    function xpositionText(d)
    {
        if (d.type=='pointMutation')
        {
            var padding=3;
            return xposition(d)+padding;
        }
        else {return xposition(d);}
    }

    function chunkWidth(d) {
        if (d.type == 'main') { return square_size[0]; }
        else if (d.type == "truncation") { return (d.finish - d.start) / real_size * square_size[0]; }
        else if (d.type == "pointMutation") { return 1. / square_size[0]; }
        else if (d.type == "tag" || d.type == "promoter"||d.type=="marker") {
            return tag_length;
        }
        
    }
    
    function chunkHeight(d){
        if (d.type=='pointMutation')
        {
            return ypositionText(d)-yposition(d);
        }
        else {return square_size[1];}
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

    // Define the data processing function
    function processData(id,pos)
    {
        var allele =  $(id).data("jsonarray");
        var out = [];
        out.push({ 'name': '', 'type': 'main','y_position':pos })
        if (!allele) {return out;}
        console.log(allele);
        
        allele.truncations.forEach(trunc => {
            let name = "Δ" + String(trunc.start) + "-" + String(trunc.finish)
            out.push({ 'name': name, 'type': 'truncation', 'start': trunc.start, 'finish': trunc.finish ,'y_position':pos});
        });

        allele.pointMutations.forEach(pm => {
            let name = pm.originalAminoAcid + String(pm.sequencePosition) + pm.newAminoAcid;
            out.push({ 'name': name, 'type': 'pointMutation', 'position': pm.sequencePosition ,'y_position':pos});
        });

        var n_term_index = -1;
        if (allele.nTag)
        {
            out.push({ 'name': allele.nTag.name, 'type': 'tag', 'ind': n_term_index ,'y_position':pos});
            n_term_index-=1;
        }
        if (allele.promoter)
        {
            out.push({ 'name': allele.promoter.name, 'type': 'promoter', 'ind': n_term_index ,'y_position':pos});
            n_term_index-=1;
        }
        if (allele.nMarker)
        {
            out.push({ 'name': allele.nMarker.name, 'type': 'marker', 'ind': n_term_index ,'y_position':pos});
            n_term_index-=1;
        }
        var c_term_index = 0;
        if (allele.cTag)
        {
            out.push({ 'name': allele.cTag.name, 'type': 'tag', 'ind': c_term_index ,'y_position':pos});
            c_term_index+=1;
        }
        if (allele.cMarker)
        {
            out.push({ 'name': allele.cMarker.name, 'type': 'marker', 'ind': c_term_index ,'y_position':pos});
            c_term_index+=1;
        }
        return out;
    }
    var data = processData('#allele_jsonTop','top');
    data = data.concat(processData('#allele_jsonBottom','bottom'));
    console.log(data);

    // append the svg object to the body of the page
    var svg = d3.select("#my_dataviz")
        .append("svg")
        .attr("width", width)
        .attr("height", height);

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
    

    // var data = {
    //     'alleles': [
    //         { 'name': '', 'type': 'main','y_position':'top' },
    //         { 'name': '', 'type': 'main','y_position':'bottom'},
    //         { 'name': 'Δ100-200', 'type': 'truncation', 'start': 100, 'finish': 200 ,'y_position':'bottom'},
    //         { 'name': 'Δ400-600', 'type': 'truncation', 'start': 400, 'finish': 600 ,'y_position':'bottom'},
    //         { 'name': 'L202A', 'type': 'pointMutation', 'position': 202, 'ind': 0 ,'y_position':'bottom'},
    //         { 'name': 'L210A', 'type': 'pointMutation', 'position': 210, 'ind': 1 ,'y_position':'bottom'},
    //         { 'name': 'L630A', 'type': 'pointMutation', 'position': 630, 'ind': 2 ,'y_position':'bottom'},
    //         { 'name': 'GFP', 'type': 'tag', 'ind': 0 ,'y_position':'bottom'},
    //         { 'name': 'KanMX', 'type': 'marker', 'ind': 1 ,'y_position':'bottom'},
    //         { 'name': 'pnmt1', 'type': 'promoter', 'ind': -2 ,'y_position':'bottom'},
    //         { 'name': 'mCherry', 'type': 'tag', 'ind': -1 ,'y_position':'bottom'},
    //         { 'name': 'KanMX', 'type': 'marker', 'ind': -3 ,'y_position':'bottom'}
    //     ]
    // };

    var g = svg
        .selectAll('g')
        .data(data)
        .enter()
        .append('g');
    

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

});