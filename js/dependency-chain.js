function dependencyChain(dependees, curTaskId, dependants) {
	if (!$('#dependency-chain').length) return false;
	var graph = new Springy.Graph();

	// Create parent node
	var curTaskNode = graph.newNode({
		label: 'T-' + curTaskId
	});
	
	// Connections from dependees to current task
	for (var tIndex = 0; tIndex < dependees.length; tIndex++) {
		var dep = dependees[tIndex];
		var depNode = graph.newNode({
			label: 'T-' + parseInt(dep.id)
		});
		graph.newEdge(depNode, curTaskNode);		
	}
	
	// Connections from current tasks to dependants
	for (var tIndex = 0; tIndex < dependants.length; tIndex++) {
		var dep = dependants[tIndex];
		var depNode = graph.newNode({
			label: 'T-' + parseInt(dep.id)
		});
		graph.newEdge(curTaskNode, depNode);		
	}	
	
	$('#dependency-chain').springy({
		'graph': graph
	});	
}