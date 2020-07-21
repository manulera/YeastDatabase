
var $ = require('jquery');
var d3 = require("d3");

import '../../css/strain/network.css';

$(document).ready(function () {
    // set the dimensions and margins of the graph
    var width = 900,
        height = 900;

    // append the svg object to the body of the page
    var svg = d3.select("#my_dataviz")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g");
    // build the arrow.
    svg.append("svg:defs").selectAll("marker")
    .data(["end"])      // Different link/path types can be defined here
    .enter().append("svg:marker")    // This section adds in the arrows
    .attr("id", String)
    .attr("viewBox", "0 -5 10 10")
    .attr("refX", 15)
    .attr("refY", -1.5)
    .attr("markerWidth", 4)
    .attr("markerHeight", 4)
    .attr("orient", "auto")
    .append("svg:path")
    .attr("d", "M0,-5L10,0L0,5");
    
    
    var data = JSON.parse($('#network_json').text())            
        
    // Initialize the links
    var link = svg
        .selectAll("line")
        .data(data.links)
        .enter()
        .append("line")
        .style("stroke", "#aaa")
        .attr("marker-end", "url(#end)");
        ;

        
    // Initialize the nodes
    var node = svg
        .selectAll(".node")
        .data(data.nodes)
        .enter()
        .append("g")
        .attr("class", "node");
    
    node.append("circle")
        .call(d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended));

    // The div used for the labels
    var div = d3.select(".tooltip");
    var div_main = d3.select(".tooltip .tooltip_main");
    var div_rest = d3.select(".tooltip .tooltip_rest");
    d3.selectAll("circle").on("mouseover", function (d, i) {
        div.style("visibility", "visible")
            .transition()
            .duration(200)
            .style("opacity", .9);
        
        div_main.html(data.info[i]['main']);
        var spl_rest = data.info[i]['rest'].split(' ');
        div_rest.html(spl_rest.join('<br>'));

        div.style("left", (d.x ) + "px")
            .style("top", (d.y) + "px");
            

    }).on("mouseout", function (d, i) {
        div.transition()
            .duration(500)
            .style("opacity", 0)
            .on("end", function () {
                div.style("visibility", "hidden")
            });
    });
    
    // Append the class written in the name
    node.each(function(d)
    {
        var name_components=d.name.split('_');
        
        for (let i = 0; i < name_components.length-1; i++) {
            console.log(name_components[i]);
            this.classList.add(name_components[i]);
        }
    }
    )
    var circle_node = svg.selectAll('circle');
    
    // Let's list the force we wanna apply on the network
    var simulation = d3.forceSimulation(data.nodes)                 // Force algorithm is applied to data.nodes
        .force("link", d3.forceLink()                               // This force provides links between nodes
            .id(function (d) { return d.id; })                     // This provide  the id of a node
            .links(data.links)                                    // and this the list of links
        )
        .force("charge", d3.forceManyBody().strength(-30))         // This adds repulsion between nodes. Play with the -400 for the repulsion strength
        .force("center", d3.forceCenter(width / 2, height / 2))     // This force attracts nodes to the center of the svg area
        ;

    
    simulation.on("tick", function (e) {
        
        circle_node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
        
        link.attr("x1", function (d) { return d.source.x; })
            .attr("y1", function (d) { return d.source.y; })
            .attr("x2", function (d) { return d.target.x; })
            .attr("y2", function (d) { return d.target.y; });
    });
    
    // Fix the position of the dragged node to the pointer

    function dragstarted(d) {
        if (!d3.event.active) simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
      }
      
      function dragged(d) {
        d.fx = d3.event.x;
        d.fy = d3.event.y;
      }
      
      function dragended(d) {
        if (!d3.event.active) simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
      }


});