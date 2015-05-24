var logWork = function() {
	$.ajax({
		method: 'post',
		url: 'php/DirectPHPApi.php',
		data: {
			'action': 'TASK_LOG_WORK',
			'id': loadContent.itemId(),
			'hours': $('.log-work').children('.hours').val()		
		}
	}).done(function(response) {
		var success = JSON.parse(response).payload;
		if (success) {
			notify.success('work hours recorded');
		} else {
			notify.fail('we had trouble logging your work');
		}	
	}).fail(function() {
		notify.fail('wasnt able to log your work');
	});
};

$(document).ready(function() {
	$('.btn-log-work').on('click', function() {
		logWork();
	});
});