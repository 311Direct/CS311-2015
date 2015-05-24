var sysItemLs = {
	sysItemLsSel: '.create-system-item-list',
	projMgrsLsSel: '.create-system-item-list.list-project-managers',
	milestonesLsSel: '.create-system-item-list.list-milestones',
	tasksLsSel: '.create-system-item-list.list-tasks',
	usersLsSel: '.create-system-item-list.list-users',
	projsLsSel: '.create-system-item-list.list-projects',

	showLs: function(sel, itemType) {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'ITEM_CREATION_LIST',
				'itemType': itemType
			}
		}).done(function(response) {
			var opts = response.payload;
			sysItemLs.fill(sel, opts);
		}).fail(function() {
			console.log('sysItemLs.get(): failed to execute action "CREATE_SYS_ITEM_LS"');
		});
	},
	
	fill: function(sel, opts) {
		for (var opIndex = 0; opIndex < opts.length; opIndex++) {
			var opt = opts[opIndex];
			$optDom = $('<option/>', {
				text: opt.label,
				value: opt.val
			});
			$(sel).append($optDom);
		}
	}
};

$(document).ready(function() {
	var lists = [
		{		
			'sel': sysItemLs.projMgrsLsSel,
			'itemType': 'projectManager'
		},
		{
			'sel': sysItemLs.milestonesLsSel,
			'itemType': 'milestone'
		},
		{		
			'sel': sysItemLs.tasksLsSel,
			'itemType': 'task'
		},
		{		
			'sel': sysItemLs.usersLsSel,
			'itemType': 'user'
		},
		{		
			'sel': sysItemLs.projsLsSel,
			'itemType': 'project'
		}
	];
	
	for (var lsIndex = 0; lsIndex < lists.length; lsIndex++) {
		var ls = lists[lsIndex];
		sysItemLs.showLs(ls.sel, ls.itemType);
	}
	
	$('.create-system-item-list').on('change', function() {
		var itemsField = '.' + $(this).attr('data-items-field');

		// Build HTML to show selected item
		// Create container to show selected item
		$selItem = $('<span/>', {
			class: 'flag',
			text: $(this).val()
		});
		// Allow user to remove selected item
		$btnRmItem = $('<span/>', {
			class: 'glyphicon glyphicon-remove-sign'
		}).on('click', function() {
			$(this).parent().remove();
		});
		// Attach remove button
		$btnRmItem.appendTo($selItem);
		
		// Show selected item
		$(itemsField).append($selItem);
	});
});