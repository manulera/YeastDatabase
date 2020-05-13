
var $ = require('jquery');
var d3 = require("d3");

import '../../css/allele/allele_viewer.css';

var MyMethods = require('./allele_drawer.js');
var read_allele = MyMethods.read_allele;
var draw_allele = MyMethods.draw_allele;
var update_allele = MyMethods.update_allele;

$(document).ready(function () {
    
    // set the dimensions and margins of the graph
    var width = 900,
        height = 400;

    var center = [width / 2, height / 2];
    var square_size = [300, 40];
    var top_height = 20;
    var bottom_height = 120;
    
    var svg = d3.select("#my_dataviz")
    .append("svg")
    .attr("width", width)
    .attr("height", height);

    var allele1 = read_allele('#allele_jsonTop',1);
    var allele2 = read_allele('#allele_jsonBottom',2);
    update_allele(allele1,[width / 2,20],square_size);
    update_allele(allele2,[width / 2,120],square_size);
    draw_allele(allele1.concat(allele1,allele2),svg);
    
});