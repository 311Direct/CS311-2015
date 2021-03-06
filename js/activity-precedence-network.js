var URL_TASK_DETAILS = 'task-details.php';
var w = 960, h = 500;
var labelDistance = 0;
var vis = d3.select("#activity-precedence-network").append("svg:svg").attr("width", w).attr("height", h)
vis.append("defs").append("marker").attr("id", "markerArrow").attr("refX", 6 + 3) /*must be smarter way to calculate shift*/
    .attr("refY", 2)
    .attr("markerWidth", 6)
    .attr("markerHeight", 4)
    .attr("orient", "auto")
    .append("path")
    .attr("d", "M 0,0 V 4 L6,2 Z"); 	// This is actual shape for arrowhead
var nodes = [];
var labelAnchors = [];
var labelAnchorLinks = [];
var links = [];

var Node = function(taskTitle, taskId) {
	this.id = nodes.length;
	this.taskTitle = taskTitle;
	this.taskId = taskId;	
	nodes.push(this);

	// Build 2 labels
	labelAnchors.push({
		node: this
	});
	labelAnchors.push({
		node: this
	});
	
	// Link both labels to node
	labelAnchorLinks.push({
		source : this.id * 2,
		target : this.id * 2 + 1,
		weight : 1
	});
	return this;
};

Node.prototype.getId = function() {
	return this.taskId;	// In d3.js, taskId is the ID
};

Node.prototype.connectTo = function(taskIds) {
	for (var tIndex = 0; tIndex < taskIds.length; tIndex++) {
		var taskId = taskIds[tIndex];
		// Get the actual node to connect to
		for (var nIndex = 0; nIndex < nodes.length; nIndex++) {
			var node = nodes[nIndex];
			if (node.taskId == taskId) {
				var targetNode = node;
				break;
			}
		}
		// Add link(connection)
		links.push({
			source : this.id,
			target : targetNode.id,
			weight : Math.random()
		});		
	}
	
	return this;
};

function drawActPrecNetwork() {
	// Node physics
	var force = d3.layout.force().size([w, h]).nodes(nodes).links(links).gravity(1).linkDistance(150).charge(-9000).linkStrength(function(x) {
		return x.weight * 30
	});
	force.start();
	var force2 = d3.layout.force().nodes(labelAnchors).links(labelAnchorLinks).gravity(0).linkDistance(0).linkStrength(8).charge(-100).size([w, h]);
	force2.start();
	
	// Node appearance
	var link = vis.selectAll("line.link").data(links).enter().append("svg:line").attr("marker-end", "url(#markerArrow)").attr("class", "link").style("stroke", "rgb(44, 44, 44)");
	/*var node = vis.selectAll("g.node").data(force.nodes()).enter().append("svg:g").attr("class", "node").html(function(d, i) {
		return '<div>yay</div>';
	});
	*/
	
	var node = vis.selectAll('g.node').data(force.nodes()).enter().append('foreignObject').attr('width', 400).attr('height', 200).html(function(n, i) {
		if (n.taskId == -2) {
			return '<div class="pert-circle pert-start">start</div>';
		} else if (n.taskId == -1) {
			return '<div class="pert-circle pert-finish">finish</div>';
		} else {
			return '<table class="pert-node" data-task-id="' + n.taskId + '">' +
				'<tbody>' +
					'<tr>' +
						'<td>' + n.earliestStart + '</td>' +
						'<td>' + n.duration + '</td>' +
						'<td>' + n.earliestFinish + '</td>' +
					'</tr>' +
					'<tr>' +
						'<td colspan="3">' + n.taskId + ': ' + n.taskTitle + '</td>' +
					'</tr>' +
					'<tr>' +
						'<td>' + n.latestStart + '</td>' +
						'<td>' + n.float + '</td>' +
						'<td>' + n.latestFinish + '</td>' +
					'</tr>' +
				'</tbody>' +
			'</table>';		
		}
	});
	
	//node.append("svg:circle").attr("r", 15).style("fill", "rgba(255, 255, 255, 0.3)").style("stroke", "#FFF").style("stroke-width", 0);
	node.append("foreignObject").append("svg:circle").attr("r", 15).style("fill", function(n) {
		var notStartOrFinish = (parseInt(n.taskId) >= 0);
		if (notStartOrFinish) return "rgba(87, 0, 255, 0.5)";
		return "rgba(255, 0, 171, 0.5)";
	}).style("stroke", "#FFF").style("stroke-width", 0);

	// Node interaction behaviour
	//node.call(force.drag);	// There is a bug, where you can drag something & have the graph fly off the screen. Un-comment after fixing.
	
	// Draw nodes & connections on canvas
	var anchorLink = vis.selectAll("line.anchorLink").data(labelAnchorLinks);	
	var anchorNode = vis.selectAll("g.anchorNode").data(force2.nodes()).enter().append("svg:g").attr("class", "anchorNode");
	anchorNode.append("svg:circle")
		.attr("r", 0)
		.style("fill", "#FFF");

	anchorNode.append("foreignObject")
		.attr("width", function(d, i) {
			return 400;
		}).attr("height", 200)	
		.html(function(d, i) {
			return '';
		}).style("fill", "#555").style("font-family", "Arial").style("font-size", 12);
	var updateLink = function() {
		this.attr("x1", function(d) {
			return d.source.x;
		}).attr("y1", function(d) {
			return d.source.y;
		}).attr("x2", function(d) {
			return d.target.x;
		}).attr("y2", function(d) {
			return d.target.y;
		});
	
	}
	
	// Node physics (continued)
	var updateNode = function() {
		this.attr("transform", function(d) {
			return "translate(" + d.x + "," + d.y + ")";
		});
	}
	force.on("tick", function() {
		force2.start();
		node.call(updateNode);
		anchorNode.each(function(d, i) {
			if(i % 2 == 0) {
				d.x = d.node.x;
				d.y = d.node.y;
			} else {
				var b = this.childNodes[1].getBBox();
	
				var diffX = d.x - d.node.x;
				var diffY = d.y - d.node.y;
	
				var dist = Math.sqrt(diffX * diffX + diffY * diffY);
	
				var shiftX = b.width * (diffX - dist) / (dist * 2);
				shiftX = Math.max(-b.width, Math.min(0, shiftX));
				var shiftY = 5;
				this.childNodes[1].setAttribute("transform", "translate(" + shiftX + "," + shiftY + ")");
			}
		});
		anchorNode.call(updateNode);
		link.call(updateLink);
		anchorLink.call(updateLink);
	});
}