$(document).ready(function() {
	$('.type').on('change', function() {
		var sel = '.criteria-' + $(this).val();
		$('.criteria').hide();
		$(sel).show();
	});
	
	$('.criteria').on('change', function() {
		if ($(this).val() == 'estimatedWorkRemaining') {
			$('.value-text').hide();
			$('.value-numeric').show();
		} else {
			$('.value-text').show();
			$('.value-numeric').hide();
		}
	});

	$('.btn-search-do').on('click', function() {
		var apiAction = 'SEARCH_' + $('.type').val().toUpperCase();
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': apiAction,
				'criteria': $('.criteria:visible').val().toLowerCase(),
				'value': $('.value:visible').val()
			}
		}).done(function(response) {		
		
			console.log(response);	//dm
		
			var targetPage = $('.type').val().slice(0, -1) + '-details.php';	// Slice(...) to get rid of plural	
			var results = JSON.parse(response).payload;
			$('.result-count').text(results.length);

			// For the results, convert all hyperlink-able fields to hyperlinks
			var type = $('.type').val();
			if (type == 'projects') {
				for (var pIndex = 0; pIndex < results.length; pIndex++) {
					results[pIndex] = loadContent.cvtToHyperlink(results[pIndex], 'projectManagerUserNames', 'projectManagerDisplayNames', 'user-details.php');
				}
			} else if (type == 'tasks') {
				for (var tIndex = 0; tIndex < results.length; tIndex++) {
					results[tIndex] = loadContent.cvtToHyperlink(results[tIndex], 'assigneeIds', 'assigneeDisplayNames', 'user-details.php');
				}			
			} else if (type == 'users') {
				for (var uIndex = 0; uIndex < results.length; uIndex++) {
					results[uIndex] = loadContent.cvtToHyperlink(results[uIndex], 'username', 'displayName', targetPage);
				}			
			}
			
			// Determine fields to display, based on the search type
			if (type == 'projects') {
				var fields = ['id', 'title', 'projectManagerDisplayNames', 'status'];
			} else if (type == 'milestones') {
				var fields = ['id', 'title', 'status'];
			} else if (type == 'tasks') {
				var fields = ['id', 'title', 'status', 'assigneeDisplayNames'];
			} else if (type == 'users') {
				var fields = ['displayName', 'manhours'];
			} else {
				// Fail gracefully
				console.log('btn-search-do(): attempted to determine fields to display, but encountered an unknown search type.');
			}  
			
			// Show results
			$searchRows = $('.search-row');
			$searchRows.slideUp('slow');
			$('.results-' + type).show();
			$('.search-again-row, .results-row').slideDown('slow');
			loadContent.displayIn('.results-' + $('.type').val(),
				results,
				targetPage,
				fields
			);
		}).fail(function() {
			console.log('Failed to execute action: ' + apiAction);
		});
	});
	
	$('.btn-new-search').on('click', function() {

		
		window.location.reload();
	});
});