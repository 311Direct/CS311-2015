$(document).ready(function() {
	$.ajax({
		url: 'php/DirectPHPApi.php',
		method: 'post',
		data: {
			'action': 'ROLES_GET'
		}
	}).done(function(response) {
		var roles = response.payload;
		for (var rIndex = 0; rIndex < roles.length; rIndex++) {
			var role = roles[rIndex];
			$('<option/>', {
				text: role,
				value: role.toLowerCase()
			}).appendTo('.roles');
		}
	}).fail(function() {
		console.log('Error: failed to retrieve roles');
	});
});