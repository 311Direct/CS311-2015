var create = {
	data: {
		'action': ''
	},
	type: '',
	flagsFor: function(selFlagContainer) {
		// Convert multi-values into a single string
		return $('.' + selFlagContainer + ':visible .flag').map(function(){
			return $(this).text();
		}).get().join(',');	
	},
	project: function() {
		// Get data for new project
		$popup = $('.popup-new-project:visible');
		this.data['title'] = $popup.children('.title').val();
		this.data['dateStart'] = $popup.children('.date-start').val();
		this.data['dateExpectedFinish'] = $popup.children('.date-expected-finish').val();
		this.data['dateFinished'] = $popup.children('.date-finished').val();
		this.data['projectManagerUsernames'] = this.flagsFor('project-managers');
		this.data['allocatedBudget'] = $popup.children('.allocated-budget').val();
		this.data['allocatedTime'] = $popup.children('.allocated-time').val();
		this.data['description'] = $popup.children('.description').val();
		this.data['status'] = $popup.children('.status').val();
		this.data['projectMilestones'] = this.flagsFor('project-milestones');
		this.do();
	},
	milestone: function() {
		// Get data for new milestone
		$popup = $('.popup-new-milestone:visible');
		this.data['title'] = $popup.children('.title').val();
		this.data['projectId'] = $popup.children('.project-id').val();
		this.data['allocatedBudget'] = $popup.children('.allocated-budget').val();
		this.data['allocatedTime'] = $popup.children('.allocated-time').val();
		this.data['description'] = $popup.children('.description').val();
		this.data['startDate'] = $popup.children('.start-date').val();
		this.data['dueDate'] = $popup.children('.due-date').val();
		this.data['endDate'] = $popup.children('.end-date').val();
		this.data['status'] = $popup.children('.status').val();
		this.data['milestoneTasks'] = this.flagsFor('milestone-tasks');
		this.do();
	},
	task: function() {
		// Get data for new task
		$popup = $('.popup-new-task:visible');
		this.data['title'] = $popup.children('.title').val();
		this.data['projectId'] = $popup.children('.project-id').val();
		this.data['assigneeUserIds'] = this.flagsFor('assignees');
		this.data['priority'] = $popup.children('.priority').val();
		this.data['allocatedBudget'] = $popup.children('.allocated-budget').val();
		this.data['allocatedTime'] = $popup.children('.allocated-time').val();
		this.data['startDate'] = $popup.children('.date-start').val();
		this.data['dueDate'] = $popup.children('.date-expected-finish').val();
		this.data['endDate'] = $popup.children('.date-end').val();
		this.data['flags'] = $popup.children('.flags').val();
		this.data['description'] = $popup.children('.description').val();
		this.data['subTaskIds'] = this.flagsFor('subtask-ids');
		this.data['dependeeIds'] = this.flagsFor('dependee-ids');
		this.data['dependantIds'] = this.flagsFor('dependant-ids');
		this.data['status'] = $popup.children('.status').val();
		this.data['taskMilestones'] = this.flagsFor('task-milestones');
		this.do();
	},
	user: function() {
		// Get data for new user
		$popup = $('.popup-new-user:visible');	
		this.data['displayName'] = $popup.children('.display-name').val();
		this.data['username'] = $popup.children('.username').val();
		this.data['password'] = $popup.children('.password').val();
		this.data['expertise'] = $popup.children('.expertise').val();
		this.data['role'] = $popup.children('.roles').val();
		this.data['permissions'] = $popup.children('.permissions').val();
		this.data['userProjects'] = this.flagsFor('user-projects');
		this.do();
	},
	do: function() {
		// The API requires a type & API action, to create the correct item
		if (!this.type.length || !this.data.action.length) {
			console.log('Failed to execute action ' + create.action);
			return;
		}
		
		// Create item
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: create.data
		}).done(function(response) {
			var result = JSON.parse(response).payload;
			if (result.forId === undefined || result.forType === undefined) {
				console.log('Failed to create system item. PLease try again');
			} else {
				var id = result.forId;
				var targetPage = result.forType + '-details.php?id=' + id;				

				var createdItemTitle = $('.popup:visible').children('.title').val();
				if (result.forType == 'user') createdItemTitle = $('.popup:visible').children('.username').val();

				notify.success('"<a href="' + targetPage + '">' + createdItemTitle + '</a>" created');
				$('.popup:visible').attr('disabled', 'disabled').fadeOut('slow', function() {
				    $(this).removeAttr('disabled');
				});				
			}
		}).fail(function() {
			console.log('Failed to execute action ' + create.action);
		});
	}
};

$(document).ready(function() {
	// On "create item" button click
	$('.btn-create-project, .btn-create-milestone, .btn-create-task, .btn-create-user').on('click', function() {
		// Get selector for creation popup
		var onDesktopWebsite = $('.secondary-panel').is(':visible');
		var selPrefix = '';
		if (onDesktopWebsite) selPrefix = '.secondary-panel ';
		var selType = '.popup-new-' + $(this).attr('data-item-type');
		var sel = selPrefix + selType;
		
		// Show creation panel
		$(sel).clearQueue().stop().fadeIn('slow');
	});
	
	$('.cancel').on('click', function() {
		$(this).parent().clearQueue().stop().fadeOut(1000, 'easeOutQuad');
	});
	$('.create').on('click', function() {
		var type = $(this).parent().attr('data-create-item-type');
		create.type = type;
		create.data.action = type.toUpperCase() + '_CREATE';
		if (type == 'project') {
			create.project();			
		} else if (type == 'milestone') {
			create.milestone();
		} else if (type == 'task') {
			create.task();
		} else if (type == 'user') {
			create.user();
		} else {
			console.log('Failed to determine the type of system item to create');
		}
	});
});