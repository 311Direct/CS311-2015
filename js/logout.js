$.fn.logout = function() {
	$(this).disabled = true;
	// Fade out
	$(this).fadeOut('slow', function() {
		// Change icon from "log out" to "tick"
		$(this).children('.glyphicon').removeClass('glyphicon-log-out').addClass('glyphicon-ok');
		// Fade in "tick"
		$(this).fadeIn('slow', function() {
			// Redirect after 3 seconds
			setTimeout(function() {
				window.location.href = 'login.php';
			}, 3000);
		});
	});	
};

$(document).ready(function() {
	$('.btn-logout').on('click', function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'LOGOUT'
			}
		}).done(function(response) {
			var logoutSuccess = JSON.parse(response).payload;
			if (logoutSuccess) {
				$('.btn-logout').logout();
			} else {
				alert('Failed to logout. Please try again.');
			}
		}).fail(function() {
			console.log('Failed to execute action ' + logoutSuccess.action);
		});
	});
});