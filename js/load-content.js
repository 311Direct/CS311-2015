var loadContent = {
	URL_USER_DETAILS: 'user-details.php',
	itemId: function() {
		var url = window.location.href;
		var id = url.slice(url.lastIndexOf('=') + 1);
		return id;
	},
	pageType: function() {
		var url = window.location.href;
		var type = url.slice(url.lastIndexOf('/') + 1, url.lastIndexOf('-'));
		return type;
	},
	taskList: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'TASK_LIST'
			}
		}).done(function(response) {
			var tasks = response.payload;
			
			loadContent.displayIn('.tasks-assigned-to-user',
				tasks.assignedToUser,
				'task-details.php',
				['id', 'priority', 'taskTitle', 'projectTitle', 'status']
			);
			
			loadContent.displayIn('.all-tasks',
				tasks.all,
				'task-details.php',
				['id', 'priority', 'taskTitle', 'projectTitle', 'status']
			);
		}).fail(function() {
			console.log('Failed to execute action: TASK_LIST');
		});
	},
	projectList: function() {
		// Projects the user is managing
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'PROJECT_LIST_I_AM_MANAGING'
			}
		}).done(function(response) {		
			var projects = response.payload;

			// Display project managers as hyperlinks
			for (var pIndex = 0; pIndex < projects.length; pIndex++) {
				var proj = projects[pIndex];
				proj = loadContent.cvtToHyperlink(proj, 'managerUsernames', 'managerDisplayNames', 'user-details.php');
			}
			
			loadContent.displayIn('.projects-managing',
				projects,
				'project-details.php',
				['id', 'managerDisplayNames', 'title', 'progress']
			);
		}).fail(function() {
			console.log('Failed to execute action: PROJECT_LIST_I_AM_MANAGING');
		});
		// All projects in system
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'PROJECT_LIST_ALL'
			}
		}).done(function(response) {
			var projects = response.payload;
			
			// Display project managers as hyperlinks
			for (var pIndex = 0; pIndex < projects.length; pIndex++) {
				var proj = projects[pIndex];
				proj = loadContent.cvtToHyperlink(proj, 'managerUsernames', 'managerDisplayNames', 'user-details.php');
			}			
			
			loadContent.displayIn('.all-projects',
				projects,
				'project-details.php',
				['id', 'managerDisplayNames', 'title', 'progress']
			);
		}).fail(function() {
			console.log('Failed to execute action: PROJECT_LIST_ALL');
		});		
	},
	projectDetails: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'PROJECT_GET',
				'id': loadContent.itemId()
			}
		}).done(function(response) {		
			var project = response.payload;			

			project = loadContent.cvtToHyperlink(project, 'projectManagerUserIds', 'projectManagerDisplayNames', 'user-details.php');
			
			$('.project-title').text(project.title);
			var summary = 'Created by <a href="' + loadContent.URL_USER_DETAILS + '?id=' + project.creatorUserId + '">' + project.creatorDisplayName + '</a> on ' + project.createdDate + '<br>' +
				'Start date: ' + project.dateStart + '<br>' +
				'Expected finish date: ' + project.dateExpectedFinish + '<br>' +
				'Finished on ' + project.dateFinished + '<br>' +
				'Managers: ' + project.projectManagerDisplayNames + '<br>' +
				project.status;
			$('.summary').html(summary);

			loadContent.progress('.progress-budget', project.usedBudget, project.allocatedBudget);
			loadContent.progress('.progress-time', project.usedTime, project.allocatedTime);
			$('.description').html(project.description);
			loadContent.attachments(project.attachments);

			loadContent.displayIn('.milestones',
				project.milestones,
				'milestone-details.php',
				['id', 'title', 'status']
			);
			loadContent.displayIn('.tasks',
				project.tasks,
				'task-details.php',
				['id', 'priority', 'title', 'status', 'assignees']
			);
			loadContent.displayIn('.users',
				project.users,
				'user-details.php',
				['username', 'displayName', 'manhours']
			);

			for (var cIndex = 0; cIndex < project.comments.length; cIndex++) {
				var com = project.comments[cIndex];
				var comHtml = '<article>' +
						'<div class="comment-header"><a href="user-details.php?id=' + com.username + '">' + com.displayName + '</a> @ ' + com.datetime + '</div>' +
						'<p>' + com.comment + '</p>' +
					'</article>';
				$('.comments').append(comHtml);
			}
		}).fail(function() {
			console.log('Failed to execute action: PROJECT_GET');		
		});
	},
	milestoneDetails: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'MILESTONE_GET',
				'id': loadContent.itemId()
			}
		}).done(function(response) {
			var milestone = response.payload;
			$('.milestone-title').text(milestone.title);
			milestone = loadContent.cvtToHyperlink(milestone, 'managerUserNames', 'managerDisplayNames', 'user-details.php');
			var summary = 'Created by <a href="user-details.php?id=' + milestone.creatorUsername + '">' + milestone.displayName + '</a> on ' + milestone.createdDate + '<br>' +
				'Start date: ' + milestone.startDate + '<br>' +
				'Due on ' + milestone.dueDate + '<br>' +
				'Finished on ' + milestone.endDate + '<br>' +
				'Belongs to <a href="project-details.php?id=' + milestone.projectId + '">P-' + milestone.projectId + '</a><br>' +
				'Managed by ' + milestone.managerDisplayNames + '<br>' +
				'Status: ' + milestone.status;
			$('.summary').html(summary);
			
			loadContent.progress('.progress-budget', milestone.usedBudget, milestone.allocatedBudget);
			loadContent.progress('.progress-time', milestone.usedTime, milestone.allocatedTime);

			$('.description').html(milestone.description);

			loadContent.displayIn('.tasks',
				milestone.tasks,
				'task-details.php',
				['id', 'priority', 'title', 'projectId', 'status']
			);
			loadContent.displayIn('.users',
				milestone.users,
				'user-details.php',
				['username', 'displayName', 'manhours']
			);
		}).fail(function() {
			console.log('Failed to execute action: MILESTONE_GET');
		});
	},
	taskDetails: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'TASK_GET',
				'id': loadContent.itemId()
			}
		}).done(function(response) {
			var task = response.payload;
			$('.task-title').text(task.title);
			
			var summary = 'Belongs in project: <a href="project-details.php?id=' + task.projectId + '">' + task.projectTitle + '</a>';
			
			// Set milestones that this task belongs in
			if (task.milestones.length) summary += '<br>Belongs in milestones: ';
			for (var mIndex = 0; mIndex < task.milestones.length; mIndex++) {
				var ms = task.milestones[mIndex];
				task.milestones[mIndex] = '<a href="milestone-details.php?id=' + ms.id + '">' + ms.title + '</a>';
			}
			summary += task.milestones.join(', ') + '<br>';
			
			task = loadContent.cvtToHyperlink(task, 'assigneeUsernames', 'assigneeDisplayNames', 'user-details.php');
			summary += 'Assignees: ' + task.assigneeDisplayNames;
			summary += '<br>' + task.priority + ' priority<br>status: ' +
				task.status;
			$('.summary').html(summary);

			// Show work log
			var logs = task.workLog;
			for (var lIndex = 0; lIndex < logs.length; lIndex++) {
				var entry = logs[lIndex];
				$dt = $('<td/>', {
					text: entry.datetime
				});
				$hrs = $('<td/>', {
					text: entry.hours
				});

				$tr = $('<tr/>');
				$tr.append($dt);
				$tr.append($hrs)
				$tr.appendTo('.work-log-history');
			}

			loadContent.progress('.progress-budget', task.usedBudget, task.allocatedBudget);
			loadContent.progress('.progress-time', task.usedTime, task.allocatedTime);
			$('.start-date').text(task.startDate);
			$('.due-date').text(task.dueDate);
			$('.end-date').text(task.endDate);
			
			for (var fIndex = 0; fIndex < task.flags.length; fIndex++) {
				var flag = '<span class="flag">' + task.flags[fIndex].flag + '</span';
				$('.flags').append(flag);
			}
			
			$('.description').html(task.description);
			loadContent.attachments(task.attachments);
			
			for (var cIndex = 0; cIndex < task.comments.length; cIndex++) {
				var commentInfo = task.comments[cIndex];
				var commentHtml = '<article>' +
					'<div class="comment-header"><a href="user-details.php?id=' + commentInfo.username + '">' + commentInfo.displayName + '</a> @ ' + commentInfo.datetime + '</div>' +
					'<p>' + commentInfo.comment + '</p></article>';
				$('.comments').append(commentHtml);
			}

			// Convert the assignees for subtasks into a string, so it can be displayed properly in a table
			for (var sIndex = 0; sIndex < task.subtasks.length; sIndex++) {
				var subtask = task.subtasks[sIndex];
				task.subtasks[sIndex] = loadContent.cvtToHyperlink(subtask, 'assigneeUsernames', 'assigneeDisplayNames', 'user-details.php');
				// Convert subtasks IDs into hyperlinks	
				var tId = subtask.taskId;
				task.subtasks[sIndex]['taskId'] = '<a href="task-details.php?id=' + tId + '">' + tId + '</a>';
			}

			loadContent.displayIn('.sub-tasks',
				task.subtasks,
				'task-details.php',
				['taskId', 'priority', 'title', 'assigneeDisplayNames', 'status']
			);
				
			dependencyChain(task.dependeeIds, task.id, task.dependantIds);
		}).fail(function() {
			console.log('Failed to execute action: TASK_GET');
		});
	},
	userDetails: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'USER_GET',
				'username': loadContent.itemId()
			}
		}).done(function(response) {
			var user = response.payload;
			$('.display-name').text(user.displayName);
			
			// Display summary
			var summary = 'Username: ' + user.username + '<br>' +
				'Expertise: ' + user.expertise;
			// Show what projects this user belongs to
			var projs = user.belongsToProjects;
			if (projs.length) {
				summary += '<br>Belongs to projects ';
				for (var pIndex = 0; pIndex < projs.length; pIndex++) {
					var pId = projs[pIndex].projectId;
					var anchor = '<a href="project-details.php?id=' + pId + '">P-' + pId + '</a>';
					projs[pIndex] = anchor;
				}
				summary += projs.join(', ') + '<br>';
			}
			$('.summary').html(summary);

			$('.role').text(user.role);
			
			// Modify DOM in 1 go, instead of modifying it each time for every
			// permission. Done to maximize performance on mobile devices
			var perms = '<ul>';
			for (var pIndex = 0; pIndex < user.permissions.length; pIndex++) {			
				perms += '<li>' + user.permissions[pIndex] + '</li>'
			}
			perms += '</ul>';
			$('.permissions').html(perms);

			for (var pIndex = 0; pIndex < user.pastProjects.length; pIndex++) {
				// Convert the project managers for each project into a string, so it can be displayed properly in a table			
				var project = user.pastProjects[pIndex];
				user.pastProjects[pIndex] = loadContent.cvtToHyperlink(project, 'projectManagerUserName', 'projectManagerDisplayName', 'user-details.php');
				// For each past project, convert each role served (array) to a list (html ul li)				
				user.pastProjects[pIndex] = loadContent.arrToList(project, 'rolesServed');
			}

			loadContent.displayIn('.past-projects',
				user.pastProjects,
				'project-details.php',
				['id', 'title', 'projectManagerDisplayName', 'rolesServed']
			);
		}).fail(function() {
			console.log('Failed to execute action: USER_GET');
		});
	},
	search: function() {
		
	},
	projectVisualization: function() {
	},
	projectEffortEstimation: function() {
	
	},
	displayIn: function(sel, items, targetPage, fields) {
		// Gracefully fail
		if (fields === undefined) {
			console.log('displayIn(): attempted to run, but it needs the fields you want it to display');
			return false;		
		}

		for (var iIndex = 0; iIndex < items.length; iIndex++) {
			var item = items[iIndex];
			$tr = $('<tr/>');
			
			// For each item
			for (var kIndex = 0; kIndex < fields.length; kIndex++) {
				// Determine if it's field has a value
				var field = fields[kIndex];
				var val = item[field];			
				// Gracefully fail
				if (val === undefined) console.log('displayIn(): tried to display the field "' + field + '", but it was empty.');

				// Display field
				var curFieldIsLinkable = (field == 'id' || field == 'ID' || field == 'username' || field == 'assignees' || field == 'projectManagerUserNames' || field == 'assigneeIds');
				if (curFieldIsLinkable) {
					$val = $('<a/>', {
						href: targetPage + '?id=' + val
					}).append(val);
				} else {
					$val = val;
				}
				$td = $('<td/>').append($val).appendTo($tr);					
			}
			$tr.appendTo(sel);
		}	
	},
	attachments: function(attachments) {
		for (var aIndex = 0; aIndex < attachments.length; aIndex++) {
			var attachment = attachments[aIndex];
			var attachmentHtml = '<a href="' + attachment.url + '"><div class="col-xs-12 attachment">' + attachment.title + ' (' + attachment.type + ')</div></a>';
			$('.attachments').append(attachmentHtml);
		}
	},	
	progress: function(sel, used, allocated) {
		// Calculate percentage used & the progress bar type
		var percent = (parseFloat(used) / parseFloat(allocated) * 100).toFixed(1) + '%';
		if (sel.indexOf('budget') != -1) {
			var unit = '$';
		} else if (sel.indexOf('time') != -1) {
			var unit = 'hrs';			
		} else {
			var unit = '';
		}
		
		// Display progress, with the format dependent upon the type of progress bar needed
		$(sel).css('width', percent);
		if (unit == '$') {
			$(sel).text(unit + used + ' / ' + unit + allocated);
		} else if (unit == 'hrs') {
			$(sel).text(used + ' ' + unit + ' / ' + allocated + ' ' + unit);
		} else {
			$(sel).text(used + ' / ' + allocated);
		}
		$(sel).text($(sel).text() + ' (' + percent + ')');
	},
	/*
	 * Convert the assignees for subtasks into a string, so it can be displayed properly in a table
	 */
	cvtToHyperlink: function(obj, fieldId, fieldTitle, targetPage) {
		var html = '';
		var idArr = obj[fieldId];
		var titleArr = obj[fieldTitle];
		// For each item to convert
		for (var iIndex = 0; iIndex < obj[fieldId].length; iIndex++) {
			// Convert item to hyperlink
			var curId = idArr[iIndex];
			var curTitle = titleArr[iIndex];
			html += '<a href="' + targetPage + '?id=' + curId + '">' + curTitle + '</a>, ';
		}
		// Remove trailing comma & whitespace
		html = html.slice(0, -2);
		// Delete the key-value pair, that was used for generating hyperlinks
		delete obj[fieldId];
		// Update the assignees for this subtask, so that the assignees display as hyperlinks in a table
		obj[fieldTitle] = html;
		return obj;
	},
	arrToList: function(obj, arrField) {
		// Convert array to list
		var html = '<li>';
		html += obj[arrField].join('</li><li>');
		html += '</li>';
		// Update object
		obj[arrField] = html;
		return obj;
	}
};

function reloadData()
{
	if (window.location.href.indexOf('task-list') != -1) loadContent.taskList();
	if (window.location.href.indexOf('project-list') != -1) loadContent.projectList();
	if (window.location.href.indexOf('project-details') != -1) loadContent.projectDetails();
	if (window.location.href.indexOf('milestone-details') != -1) loadContent.milestoneDetails();
	if (window.location.href.indexOf('task-details') != -1) loadContent.taskDetails();
	if (window.location.href.indexOf('user-details') != -1) loadContent.userDetails();
	if (window.location.href.indexOf('search') != -1) loadContent.search();
	//if (window.location.href.indexOf('project-visualization') != -1) loadContent.projectVisualization();		// Visualization method called manually from "pert.js"
	if (window.location.href.indexOf('project-effort-estimation') != -1) loadContent.projectEffortEstimation();
}

$(document).ready(function() {
	reloadData();
});