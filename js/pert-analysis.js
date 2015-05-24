var pertAnalysis = { 
	taskUrl: 'task-details.php?id=',
	safetyBuffMultiplier: 0.25,
	
	do: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'PERT_GET',
				'id': loadContent.itemId()
			}
		}).done(function(response) {
			var tasks = response.payload.tasks;
			pertAnalysis.showZ(tasks);
		}).fail(function() {
			console.log('Failed to execute action: PERT_GET');
		});  
	},
	
	safetyBuff: function (Te) {
		return Math.ceil(Te * pertAnalysis.safetyBuffMultiplier);
	},
	
	showTaskPertDetails: function(tId, title, preds, A, M, B, Te) {
		$tr = $('<tr/>');
		
		// Task ID
		var taskLabel = '<a href="' + pertAnalysis.taskUrl + tId + '">T-' + tId + '</a>: ' + title;
		$('<td/>', {
			html: taskLabel
		}).appendTo($tr);
		// Predecessors
		for (var pIndex = 0; pIndex < preds.length; pIndex++) {
			var pId = preds[pIndex].id;
			preds[pIndex] = '<a href="' + pertAnalysis.taskUrl + pId + '">T-' + pId + '</a>';
		}
		$('<td/>', {
			html: preds.join(', ')
		}).appendTo($tr);
		// Optimistic time
		$('<td/>', {
			text: A.toFixed(2)
		}).appendTo($tr);
		// Likely time
		$('<td/>', {
			text: M.toFixed(2)
		}).appendTo($tr);
		// Pessimistic time
		$('<td/>', {
			text: B.toFixed(2)
		}).appendTo($tr);			
		// Expected time
		$('<td/>', {
			text: Te.toFixed(2)
		}).appendTo($tr);
		// Safety buffer
		$('<td/>', {
			text: pertAnalysis.safetyBuff(Te) + ' day(s)'
		}).appendTo($tr);
		
		$('.tasks-pert-info').append($tr);	
	},
	
	showProjBuff: function(tasks, projBuff) {
		// Show project buffer duration
		var projBuff = Math.ceil(projBuff / 2);
		$('.project-buffer').text(projBuff);
		
		// Get finish tasks
		var finTasks = [];
		for (var tIndex = 0; tIndex < tasks.length; tIndex++) {
			var task = tasks[tIndex];
			if (!task.right.length) finTasks.push(task);
		}
		
		// List finish tasks as the ones that have the project buffer after them
		for (var fIndex = 0; fIndex < finTasks.length; fIndex++) {
			var fTask = finTasks[fIndex];
			var fId = fTask.id;
			var title = fTask.title;
			var anchor = '<a href="' + pertAnalysis.taskUrl + fId + '">T-' + fId + ': ' + title + '</a>';
			
			$('<li/>', {
				html: anchor
			}).appendTo('.finish-tasks');
		}
	},
	
	showZ: function(tasks) {
		// Set sumTe = 0
		var sumTe = 0;
		// Set sumS = 0
		var sumS = 0;
		
		// For ea task
		var projBuff = 0;
		for (var tIndex = 0; tIndex < tasks.length; tIndex++) {
			var task = tasks[tIndex];
			var M = parseInt(task.duration);
			// set A = M * 0.8
			var A = M * 0.8;
			// set B = M * 1.2
			var B = M * 1.2;
			// set Te = (A + 4 * M + B) / 6
			var Te = (A + 4 * M + B) / 6;
			// sumTe += Te
			sumTe += Te;
			// set S = (B - A) / 6
			var S = (B - A) / 6;
			// sumS += S^2
			sumS += Math.pow(S, 2);
			
			// Show this task's PERT data
			pertAnalysis.showTaskPertDetails(task.id, task.title, task.left, A, M, B, Te);
			
			if (task.float == 0) projBuff += pertAnalysis.safetyBuff(Te);
		// Endfor
		}

		pertAnalysis.showProjBuff(tasks, projBuff);

		// Set stdDev = sqrt(sumS)
		var stdDev = Math.sqrt(sumS);
		
		// Set T = 80
		var T = 80;
		
		// Set z = (T - sumTe) / stdDev
		var z = (T - sumTe) / stdDev;
		
		// Show chance of meeting project deadline
		$('.chance-to-meet-project-deadline').text(pertMeetDeadline.chance(z));
		$('.pert-z').text(z.toFixed(2));
	}
};

/*
 * TODO: run this after Yang's algor, so we can get float for ea task.
 * Have Yang's function return a new tasks array, where each task contains float value
 */
$(document).ready(function() {
	pertAnalysis.do();
});