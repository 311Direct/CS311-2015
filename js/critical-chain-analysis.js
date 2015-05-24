var critChainAnalysis = {
	critPathNodes: function() {
		// Get nodes (tasks)
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'PERT_GET',
				'id': loadContent.itemId()
			}
		}).done(function(response) {
			var tasks = JSON.parse(response).payload.tasks;
			var critTasks = [];
			
			// For ea tasks
			for (var tIndex = 0; tIndex < tasks.length; tIndex++) {
				// If float = 0, record as a crit path task
				var task = tasks[tIndex];
				if (task.float == 0) critTasks.push(task);
			// Endfor
			}
			// Return recorded nodes
			return critTasks;
		}).fail(function() {
			console.log('Failed to execute action: PERT_GET');
		}); 
	},
	
	sumComZone: function(task) {
		// Cur = pred
		var cur = preds[pIndex];
		var sumComZone = 0;
		// SumComfortZone += comfort zone
		sumComZone += cur.comfortZone;
		// Do
		do {
			// Cur = first pred
			cur = cur.left[0];
			// SumComfortZone += comfort zone
			sumComZone += cur.comfortZone;
		// While has preds
		} while (cur.left.length);
		return sumComZone;
	},
	
	addFeedBufs: function() {
		// Get crit tasks
		var critTasks = critChainAnalysis.critPathNodes();
		
		// For ea task in crit path
		for (var tIndex = 0; tIndex < critTasks.length; tIndex++) {
			// For ea pred
			var task = critTasks[tIndex];
			var preds = task.left;
			for (var pIndex = 0; pIndex < preds.length; pIndex++) {
				// Get feeding buffer after initial pred = sumComfortZone / 2
				var pred = preds[pIndex];
				var feedBuf = critChainAnalysis.sumComZone(pred) / 2;
				
				// Display feeding buffer info
				var info = 'T-' + pred.id + ' has a feeding buffer of ' + feedBuf + ' days after it';
				$('<li/>', {
					text: info
				}).appendTo('.buffer-info-container');
			// Endfor
			}
		// Endfor
		}
	}
};

/*
 * TODO: call this function after Yang's PERT adds the floats to the tasks in the graph
 */
//critChainAnalysis.addFeedBufs();