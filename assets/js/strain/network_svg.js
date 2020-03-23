
var $ = require('jquery');
var d3 = require("d3");

import '../../css/strain/network.css';

$(document).ready(function () {
    // set the dimensions and margins of the graph
    var width = 900,
        height = 400;

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
        .attr("class", "node")
        .on("click", click);
    
    node.append("circle")
        .attr("r","5");
    
    function click(d) {
        
        if (!d3.event.defaultPrevented) {
            if (d.children) {
            d._children = d.children;
            d.children = null;
            } else {
            d.children = d._children;
            d._children = null;
            }
            console.log('drag');
            simulation.drag();
        }
        } 

    // The div used for the labels
    var div = d3.select("#my_dataviz").append('div').attr("class", "tooltip")
    .style("opacity", 0);
    
    d3.selectAll(".node").on("mouseover", function (d, i) {
        div.style("visibility", "visible")
            .transition()
            .duration(200)
            .style("opacity", .9);
        var html;
        html = d.name;
        
        div.html(html)
            .style("left", (d.x ) + "px")
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
            this.classList.add(name_components[i]);
        }
    }
    )
    
    
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
    
        link.attr("x1", function (d) { return d.source.x; })
            .attr("y1", function (d) { return d.source.y; })
            .attr("x2", function (d) { return d.target.x; })
            .attr("y2", function (d) { return d.target.y; });
    
            node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
    });

    d3.select(svg)
    .call(d3.drag().subject(dragsubject).on("start", dragstarted).on("drag", dragged).on("end",dragended))


    function dragsubject() {
        var i,
        x = transform.invertX(d3.event.x),
        y = transform.invertY(d3.event.y),
        dx,
        dy;
        for (i = tempData.nodes.length - 1; i >= 0; --i) {
          node = tempData.nodes[i];
          dx = x - node.x;
          dy = y - node.y;
    
          if (dx * dx + dy * dy < radius * radius) {
    
            node.x =  transform.applyX(node.x);
            node.y = transform.applyY(node.y);
    
            return node;
          }
        }
      }
    
    
      function dragstarted() {
        if (!d3.event.active) simulation.alphaTarget(0.3).restart();
        d3.event.subject.fx = transform.invertX(d3.event.x);
        d3.event.subject.fy = transform.invertY(d3.event.y);
      }
    
      function dragged() {
        d3.event.subject.fx = transform.invertX(d3.event.x);
        d3.event.subject.fy = transform.invertY(d3.event.y);
    
      }
    
      function dragended() {
        if (!d3.event.active) simulation.alphaTarget(0);
        d3.event.subject.fx = null;
        d3.event.subject.fy = null;
      }

    // d3.selectAll('.node').on('click', function (d, i) {
    //     d3.event.stopPropagation();
    // });



});