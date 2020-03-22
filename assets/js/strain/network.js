
var $ = require('jquery');
var d3 = require("d3");

import '../../css/strain/network.css';

function convertCoords(x,y) {

    var offset = svgDoc.getBoundingClientRect();
  
    var matrix = elem.getScreenCTM();
  
    return {
      x: (matrix.a * x) + (matrix.c * y) + matrix.e - offset.left,
      y: (matrix.b * x) + (matrix.d * y) + matrix.f - offset.top
    };
  }

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
    var data = JSON.parse($('#network_json').text())            

    // console.log(nodes_strains);

        
        // Initialize the links
        var link = svg
            .selectAll("line")
            .data(data.links)
            .enter()
            .append("line")
            .style("stroke", "#aaa");

        // Initialize the nodes
        var node = svg
            .selectAll(".node")
            .data(data.nodes)
            .enter()
            .append("g")
            .attr("class", "node")
            .on("mouseover",mouseOver)
            .on("mouseout", mouseOut)
            ;
        
        node.append("circle")
            .attr("r","5");
        
        
        function mouseOut(){
            d3.selectAll(".mylabel").remove();
        }
        function mouseOver(d) {
            d3.select(this.parentNode).append("text")
            .attr("x", function() {
              return d.x;
            })
            .attr("y", function() {
                return d.y;
              })
            .attr("dx", "6") // margin
            .attr("dy", ".35em") // vertical-align
            .attr("class", "mylabel")//adding a label class
            .text(function() {
              return d.name;
            });
        }

        
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
            .on("end", ticked);

        // This function is run at each iteration of the force algorithm, updating the nodes position.
        function ticked() {
            link
                .attr("x1", function (d) { return d.source.x; })
                .attr("y1", function (d) { return d.source.y; })
                .attr("x2", function (d) { return d.target.x; })
                .attr("y2", function (d) { return d.target.y; });

            node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
        }

    
});