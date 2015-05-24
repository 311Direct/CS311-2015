function login() {	
	// Login
	$.ajax({
		url: 'php/DirectPHPApi.php',
		method: 'post',
		data: {
			'action': 'LOGIN',
			'username': $('#username').val(),
			'password': $('#password').val()
		}
	}).done(function(response) {
		var loginInfo = response.payload;
		if (loginInfo.success) {
			notify.success(' mate');
			if (loginInfo.roleCode == 'projectManager' || loginInfo.roleCode == 'developmentManager') {
				var targetPage = 'project-list.php';
			} else {
				var targetPage = 'task-list.php';
			}
			
			setTimeout(function() {
				window.location.href = targetPage;
			}, 1000);
		} else {
			notify.fail('i dont accept fake IDs');
			// Aesthetics
			setTimeout(function() {
				$('.login-progress').clearQueue().stop().slideUp('slow', function() {
					$('.login-container').show();
				});
			}, 3000);
		}
	}).fail(function() {
		console.log('Failed to execute action ' + loginSuccess.action);
	});
}

function beginLogin() {
	// Aesthetics
	$('.login-container').hide();
	$('.login-progress').clearQueue().stop().slideDown('slow');	
	// Login
	setTimeout(login, 2000);
}

$(document).ready(function() {
	// Aesthetics
	$('.title-login').css('text-shadow','rgb(135, 255, 211) 0px 0px 5px, rgb(135, 255, 211) 0px 0px 5px');
	setTimeout(function() {
		$('.title-login').css({
			'text-shadow': 'none',
			'transition': 'text-shadow 4s ease-out'
		});
	}, 1000);

	// On login attempt
	$('.btn-login').on('click', function() {
		beginLogin();
	});
	$('html').keypress(function (e) {
		var key = e.which;
		if (key == 13) beginLogin();	// Login when user presses "enter key"
	});		
});