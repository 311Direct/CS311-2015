  function init(pertInfo) {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for more concise visual tree definitions

    myDiagram =
      $(go.Diagram, "myDiagram",
        {
          initialAutoScale: go.Diagram.Uniform,
          initialContentAlignment: go.Spot.Center,
          layout: $(go.LayeredDigraphLayout)
        });

    // The node template shows the activity name in the middle as well as
    // various statistics about the activity, all surrounded by a border.
    // The border's color is determined by the node data's ".critical" property.
    // Some information is not available as properties on the node data,
    // but must be computed -- we use converter functions for that.
    myDiagram.nodeTemplate =
      $(go.Node, "Auto",
        $(go.Shape, "Rectangle",  // the border
          { fill: "white" },
          new go.Binding("stroke", "critical",
                         function (b) { return (b ? "red" : "blue"); })),
        $(go.Panel, "Table",
          { padding: 0.5 },
          $(go.RowColumnDefinition, { column: 1, separatorStroke: "black" }),
          $(go.RowColumnDefinition, { column: 2, separatorStroke: "black" }),
          $(go.RowColumnDefinition, { row: 1, separatorStroke: "black", background: "white", coversSeparators: true }),
          $(go.RowColumnDefinition, { row: 2, separatorStroke: "black" }),
          $(go.TextBlock,
            new go.Binding("text", "earlyStart"),
            { row: 0, column: 0, margin: 5, textAlign: "center" }),
          $(go.TextBlock,
            new go.Binding("text", "length"),
            { row: 0, column: 1, margin: 5, textAlign: "center" }),
          $(go.TextBlock,  // earlyFinish
            new go.Binding("text", "",
                           function(d) { return (d.earlyStart + d.length).toFixed(2); }),
            { row: 0, column: 2, margin: 5, textAlign: "center" }),

          $(go.TextBlock,
            new go.Binding("text", "text"),
            { row: 1, column: 0, columnSpan: 3, margin: 5,
              textAlign: "center", font: "bold 14px sans-serif" }),

          $(go.TextBlock,  // lateStart
            new go.Binding("text", "",
                           function(d) { return (d.lateFinish - d.length).toFixed(2); }),
            { row: 2, column: 0, margin: 5, textAlign: "center" }),
          $(go.TextBlock,  // slack
            new go.Binding("text", "",
                           function(d) { return (d.lateFinish - (d.earlyStart + d.length)).toFixed(2); }),
            { row: 2, column: 1, margin: 5, textAlign: "center" }),
          $(go.TextBlock,
            new go.Binding("text", "lateFinish"),
            { row: 2, column: 2, margin: 5, textAlign: "center" })
        )  // end Table Panel
      );  // end Node

    // The link data object does not have direct access to both nodes
    // (although it does have references to their keys: .from and .to).
    // This conversion function gets the GraphObject that was data-bound as the second argument.
    // From that we can get the containing Link, and then the Link.fromNode or .toNode,
    // and then its node data, which has the ".critical" property we need.
    //
    // But note that if we were to dynamically change the ".critical" property on a node data,
    // calling myDiagram.model.updateTargetBindings(nodedata) would only update the color
    // of the nodes.  It would be insufficient to change the appearance of any Links.
    function linkColorConverter(linkdata, elt) {
      var link = elt.part;
      if (!link) return "blue";
      var f = link.fromNode;
      if (!f || !f.data || !f.data.critical) return "blue";
      var t = link.toNode;
      if (!t || !t.data || !t.data.critical) return "blue";
      return "red";  // when both Link.fromNode.data.critical and Link.toNode.data.critical
    }

    // The color of a link (including its arrowhead) is red only when both
    // connected nodes have data that is ".critical"; otherwise it is blue.
    // This is computed by the binding converter function.
    myDiagram.linkTemplate =
      $(go.Link,
        $(go.Shape,
          { isPanelMain: true },
          new go.Binding("stroke", "", linkColorConverter)),
        $(go.Shape,  // arrowhead
          { toArrow: "Standard", stroke: null },
          new go.Binding("fill", "", linkColorConverter))
      );

	console.log(pertInfo); //set nodeList = nodes
	var length = pertInfo.tasks.length;
	var unDoneList = [];
	for(var i=0;i<length;i++){
		unDoneList.push(i);
	}
	// set startNodes = (nodes in nodeList without any left)
	// for each in startNodes
  	// set ES & EF
  	// remove from nodeList
	// endfor
	for(var i=0;i<unDoneList.length;i++){
		var node = pertInfo.tasks[i];
		if(node.left.length == 0) {
			node.es = 0;
			node.ef = Number(node.duration);
			var index = unDoneList.indexOf(i);
			unDoneList.splice(index, 1);
		}
	}
	
	console.log(unDoneList);
	
	// while there are nodes in nodeList
  		// for each remaining node in nodeList
	    		// if left node done
	      			// set (this).ES
	      			// set (this).EF
	      			// remove (this) from nodeList
	    		// endif
  		// endfor
	// endwhile
	while(unDoneList.length != 0) {
		for(var i=0;i<unDoneList.length;i++){
			var node = pertInfo.tasks[unDoneList[i]];
			if(node.id == 4){
				console.log(node.left.length);
			}
			for(var j=0;j<node.left.length;j++) {
				var parentId = node.left[j].id;
				for(var k=0;k<length;k++){
					var parentTask = pertInfo.tasks[k];
					if(parentTask.id == parentId) {
						if(node.id == 4){
							console.log(k);
						}
						if(parentTask.ef == null) {
							console.log("parentTask id ");
							//continue;
						} else {
							if(node.id == 4){
								console.log(node.es);
								console.log(parentTask.ef);
							}
							node.es = node.es > parentTask.ef ? node.es : parentTask.ef;
						}
					}
				}
			}
			if(node.es != null){
				node.ef = node.es + Number(node.duration);
				unDoneList.splice(i, 1);
			}
		}
	}
	console.log(unDoneList);
	//console.log(pertInfo);
	
    // here's the data defining the graph
    var nodeDataArray = [
      { key: 1, text: "Start", length: 0, earlyStart: 0, lateFinish: 0, critical: true },
      { key: 2, text: "a", length: 4, earlyStart: 0, lateFinish: 4, critical: true },
      { key: 3, text: "b", length: 5.33, earlyStart: 0, lateFinish: 9.17, critical: false },
      { key: 4, text: "c", length: 5.17, earlyStart: 4, lateFinish: 9.17, critical: true },
      { key: 5, text: "d", length: 6.33, earlyStart: 4, lateFinish: 15.01, critical: false },
      { key: 6, text: "e", length: 5.17, earlyStart: 9.17, lateFinish: 14.34, critical: true },
      { key: 7, text: "f", length: 4.5, earlyStart: 10.33, lateFinish: 19.51, critical: false },
      { key: 8, text: "g", length: 5.17, earlyStart: 14.34, lateFinish: 19.51, critical: true },
      { key: 9, text: "Finish", length: 0, earlyStart: 19.51, lateFinish: 19.51, critical: true }
    ];
    var linkDataArray = [
      { from: 1, to: 2 },
      { from: 1, to: 3 },
      { from: 2, to: 4 },
      { from: 2, to: 5 },
      { from: 3, to: 6 },
      { from: 4, to: 6 },
      { from: 5, to: 7 },
      { from: 6, to: 8 },
      { from: 7, to: 9 },
      { from: 8, to: 9 }
    ];
    myDiagram.model = new go.GraphLinksModel(nodeDataArray, linkDataArray);

    // create an unbound Part that acts as a "legend" for the diagram
    myDiagram.add(
      $(go.Node, "Auto",
        $(go.Shape, "Rectangle",  // the border
          { fill: "lightblue" } ),
        $(go.Panel, "Table",
          $(go.RowColumnDefinition, { column: 1, separatorStroke: "black" }),
          $(go.RowColumnDefinition, { column: 2, separatorStroke: "black" }),
          $(go.RowColumnDefinition, { row: 1, separatorStroke: "black", background: "lightblue", coversSeparators: true }),
          $(go.RowColumnDefinition, { row: 2, separatorStroke: "black" }),
          $(go.TextBlock, "Early Start",
            { row: 0, column: 0, margin: 5, textAlign: "center" }),
          $(go.TextBlock, "Length",
            { row: 0, column: 1, margin: 5, textAlign: "center" }),
          $(go.TextBlock, "Early Finish",
            { row: 0, column: 2, margin: 5, textAlign: "center" }),

          $(go.TextBlock, "Activity Name",
            { row: 1, column: 0, columnSpan: 3, margin: 5,
              textAlign: "center", font: "bold 14px sans-serif" }),

          $(go.TextBlock, "Late Start",
            { row: 2, column: 0, margin: 5, textAlign: "center" }),
          $(go.TextBlock, "Slack",
            { row: 2, column: 1, margin: 5, textAlign: "center" }),
          $(go.TextBlock, "Late Finish",
            { row: 2, column: 2, margin: 5, textAlign: "center" })
        )  // end Table Panel
      ));
  }

$.ajax({
	url: 'php/DirectPHPApi.php',
	method: 'post',
	data: {
		'action': 'PERT_GET',
		'id': loadContent.itemId()
	}
}).done(function(response) {
	pertInfo = JSON.parse(response).payload;
	init(pertInfo);	
}).fail(function() {
	console.log('Failed to execute action: PERT_GET');
});  