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

$(document).ready(function() {
	var n = new Node('Perform QC on new spiros', 45);
	new Node('Deliver the network', 123);
	var n2 = new Node('Finalize BSD program spec', 6541);
	var n3 = new Node('Deploy to staging', 241);
	new Node('Conduct training', 7);
	n.connectTo([123, 6541]);
	n2.connectTo([241]);
	n3.connectTo([7]);
	
	
	// Node physics
	var force = d3.layout.force().size([w, h]).nodes(nodes).links(links).gravity(1).linkDistance(150).charge(-3000).linkStrength(function(x) {
		return x.weight * 10
	});
	force.start();
	var force2 = d3.layout.force().nodes(labelAnchors).links(labelAnchorLinks).gravity(0).linkDistance(0).linkStrength(8).charge(-100).size([w, h]);
	force2.start();
	
	// Node appearance
	var link = vis.selectAll("line.link").data(links).enter().append("svg:line").attr("marker-end", "url(#markerArrow)").attr("class", "link").style("stroke", "rgb(44, 44, 44)");
	var node = vis.selectAll("g.node").data(force.nodes()).enter().append("svg:g").attr("class", "node");
	node.append("svg:circle").attr("r", 15).style("fill", "rgba(255, 255, 255, 0.3)").style("stroke", "#FFF").style("stroke-width", 0);
	
	// Node interaction behaviour
	node.call(force.drag);
	
	// Draw nodes & connections on canvas
	var anchorLink = vis.selectAll("line.anchorLink").data(labelAnchorLinks);	
	var anchorNode = vis.selectAll("g.anchorNode").data(force2.nodes()).enter().append("svg:g").attr("class", "anchorNode");
	anchorNode.append("svg:circle")
		.attr("r", 0)
		.style("fill", "#FFF");
		
	anchorNode.append("foreignObject")
		.attr("width", function(d, i) {
			return (d.node.taskTitle.length * 6)
		}).attr("height", 60)	
		.html(function(d, i) {
			return i % 2 == 0 ? "" : ("<a href='" + URL_TASK_DETAILS + "?id=" + d.node.taskId + "'><span class='node-task-title'>" + d.node.taskTitle + '</span><br/><span class="node-task-id">T-' + d.node.taskId + "</span></a>")
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
});