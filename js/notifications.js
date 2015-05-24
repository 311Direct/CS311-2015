$(document).ready(function() {
	// Show notifications when the user clicks the "notification" header button
	$('.btn-notifications').on('click', function() {
		var showingNtfs = ($('.notifications').is(':visible') == false);
		if (showingNtfs) $('.notifications').userMsgRead();
		$('.notifications').attr('data-has-been-viewed', '1').slideToggle('slow');
	});
	
	// Start polling for notifications
	var url = document.location.href;
	var pageWithExt = url.substr(url.lastIndexOf('/') + 1);
	var page = pageWithExt.slice(0, pageWithExt.lastIndexOf('.'));
	if (page != 'login') {
		// Poll for new notifications every now & then
		var pollInterval = 5000;
		$('body').msgPoll();	// Poll immediately on page load
		setInterval(function() {
			$('body').msgPoll();
		}, pollInterval);
	}
	
	// Load user list, so that the user can send messages to other users
	$.ajax({
		method: 'POST',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'USER_LIST_GET'
		}
	}).done(function(response) {
  	if(response.payload == undefined || response.payload == null)
  	  return;
  	
		// In this code segment, "user" refers to "username"
		var users = response.payload;
		for (var uIndex = 0; uIndex < users.length; uIndex++) {
			var user = users[uIndex];
			$('<option/>', {
				text: user,
				value: user
			}).appendTo('.msg-send-user-list');
		}
	}).fail(function() {
		notify.fail("we can't seem to load the list of users to message");
	});	
	
	// When the user wants to send a message, show the text boxes
	$('.btn-new-msg').on('click', function() {
		$('.send-msg-container').clearQueue().stop().slideDown('slow');
	});
	
	// Send message
	$('.send-msg-container > .btn-msg-send').on('click', function() {	
		$.ajax({
			method: 'POST',
			url: 'php/notificationSystemAPI.php',
			data: {
				'action': 'USER_MSG_SEND',
				'usernameTo': $('.msg-send-user-list').val(),
				'msg': $('.send-msg-container').find('textarea').val()
			}
		}).done(function(response) {
			var success = response.payload;
			if (success) {
				$('.msg-send-user-list').val('');
				$('.send-msg-container').find('textarea').val('');
				$('.send-msg-container').clearQueue().stop().slideUp('slow');
				notify.success('message sent');			
			} else {
				notify.fail('we were unable to send your message');
			}
		}).fail(function() {
			notify.fail("we weren't able to send your message");
		});
	});
	
	// Don't send a message
	$('.send-msg-container > .btn-msg-cancel').on('click', function() {	
		$('.msg-send-user-list').val('');
		$('.send-msg-container').find('textarea').val('');	
		$('.send-msg-container').clearQueue().stop().slideUp('slow');
	});

	$('.btn-watch').on('click', function() {
		$(this).watchTask();
	});

	// N.B.: the event handler for attending approval requests is bound upon notifications sync
});