var tasksGantt = {
    data:[],
    links:[
    	// Disabled. If enough time at end of project, recommended
    	// to show links to identify subtasks
        /*{id:1, source:1, target:2, type:"1"},
        {id:2, source:1, target:3, type:"1"},
        {id:3, source:3, target:4, type:"1"},
        {id:4, source:4, target:5, type:"0"},
        {id:5, source:5, target:6, type:"0"}
        */
    ]
};

var ganttEngine = {
	apiUrl: 'php/DirectPHPApi.php',
	millToDays: (1000 * 60 * 60 * 24),
	do: function() {
		// Load tasks
		$.ajax({
			'url': this.apiUrl,
			'method': 'post',
			'data': {
				'action': 'GANTT',
				'id': loadContent.itemId()
			}			
		}).done(function(response) {
			var milestones = JSON.parse(response).payload;
			// Put tasks into gantt chart
			ganttEngine.show(milestones);
		}).fail(function() {
			console.log('Failed to execute action: ');
		});
	},
	show: function(milestones) {
		// Add all milestones first
		for (var mIndex = 0; mIndex < milestones.length; mIndex++) {
			var milestone = milestones[mIndex];
			this.addMilestone(milestone);		
		}
	
		// For each milestone
		var addedTasks = [];
		for (var mIndex = 0; mIndex < milestones.length; mIndex++) {
			// For each task in milestone
			var milestone = milestones[mIndex];
			var tasks = milestone.tasks;
			for (var tIndex = 0; tIndex < tasks.length; tIndex++) {
				// If no added yet...
				var task = tasks[tIndex];
				if (addedTasks.indexOf(task.id) != -1) continue;
				
				// ...add it
				this.addTask(task, milestone.id);
				// Don't add it again for this milestone (for 
				// example, if T-1 & T-2 belong to M-1, then
				// T-1 & T-2 should only be added once under M-1
				// on the Gantt chart.
				addedTasks.push(task.id);
			// Endfor
			}
			
			// About to move on to the next milestone, so reset the tasks marked as added to this milestone
			addedTasks.length = 0;
		// Endfor		
		}

		gantt.init("gantt");
		gantt.parse(tasksGantt);
	},
	addMilestone: function(milestone) {
		tasksGantt.data.push({
			'id': tasksGantt.data.length + 1,
			'text': 'M-' + milestone.id + ': ' + milestone.title,
			'start_date': milestone.startDate,
			'duration': milestone.duration,
			'progress': 0,
			'open': true			
		});
	},
	addTask: function(task, mId) {	
		tasksGantt.data.push({
			'id': tasksGantt.data.length + 1,
			'text': 'T-' + task.id + ': ' + task.title,
			'start_date': task.startDate,
			'duration': task.duration,
			'progress': (task.status == 'Closed' ? 1 : 0),
			'open': true,
			'parent': mId
		});
	}
};

$(document).ready(function() {
	ganttEngine.do();
});