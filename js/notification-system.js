var notify = {
	bgSuccess: '#4B8B6E',
	bgFail: '#563649',
	prefixSuccess: 'Cheers, ',
	prefixFail: 'Alright mate, ',
	postfixSuccess: '.',
	postfixFail: '.',
	showDur: 7000,	// 7 secs
	btnRefreshHtml: '<span class="col-xs-2 col-md-12 btn-refresh glyphicon glyphicon-refresh"></span>',
	success: function(msg) {
		$('.notify').clearQueue().stop().children('.btn-refresh').show().parent().css('background-color', this.bgSuccess).children('.notify-message').html(this.prefixSuccess + msg + this.postfixSuccess);		
		this.show();
	},
	fail: function(msg) {
		$('.notify').clearQueue().stop().css('background-color', this.bgFail).children('.btn-refresh').hide().parent().children('.notify-message').html(this.prefixFail + msg + this.postfixFail);
		this.show();
	},
	show: function() {
		$('.notify').fadeIn('slow').delay(this.showDur).fadeOut('slow');	
	}
};

var notifications = {
	addMsg: function(ntf) {
		$ntf = $('<div/>').load('templates/notification-message.php', function() {
			$(this).find('.notification').attr('data-id', ntf.id);
			$(this).find('.title').text('Message');
			$(this).find('.datetime').text(ntf.datetime);
			$(this).find('.message').html(ntf.message);
			$(this).appendTo('.notifications');
			
			// After this notification has been loaded, there is no need to sync it again
			$('.notifications').userMsgRead();
		});
	},
	addMsgWatch: function(ntf) {
		$ntf = $('<div/>').load('templates/notification-message-watch-alert.php', function() {
			$(this).find('.notification').attr('data-id', ntf.id);
			$(this).find('.title').text('Watched task');
			$(this).find('.datetime').text(ntf.datetime);
			$(this).find('.message').html(ntf.message);
			$(this).appendTo('.notifications');
			
			// After this notification has been loaded, there is no need to sync it again
			$('.notifications').userMsgRead();
		});
	},
	addApprovalReq: function(ntf) {
		$ntf = $('<div/>').load('templates/notification-approval-request.php', function() {
			$(this).find('.notification').attr('data-id', ntf.id);
			$(this).find('.title').text('Approval request');
			$(this).find('.datetime').text(ntf.datetime);
			$(this).find('.message').html(ntf.message);
			$(this).find('.notification').attr('data-for-type', ntf.forType);
			$(this).find('.notification').attr('data-for-id', ntf.forId);			
			$(this).appendTo('.notifications');
			
			// Allow project managers (or user-defined role equivalents) to attend to this approval request
			$('.btn-approval-request.approve, .btn-approval-request.reject').on('click', function() {
				$(this).closest('.notification').attr('disabled', 'disabled').fadeOut('slow', function() {
					$(this).remove();
				});
				$(this).approvalReqAttended();				
			});			
			
			// After this notification has been loaded, there is no need to sync it again
			$('.notifications').userMsgRead();
		});
	}
};

$.fn.userMsgRead = function() {
	// Get the notifications that are loaded
	var ntfs = [];
	$('.notification').each(function() {
		if ($(this).hasClass('approval-req')) {
			var type = 'approvalRequest';
		} else {
			var type = 'message';
		}
		ntfs.push({
			'type': type,
			'id': $(this).attr('data-id')
		});
	});

	// Mark notifications as read
	if (!ntfs.length) return;	// Dont mark stuff as read, when there is not even anything for the user to read
	$.ajax({
		method: 'POST',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'USER_MSG_READ',
			'ntfs': ntfs
		}
	}).done(function(response) {
		var res = JSON.parse(response).payload;
		if (res != 1) {
			notify.fail('failed to tell server that you read messages');
		}
	}).fail(function() {
		console.log('userMsgRead() failed to send');
	});
};

$.fn.userMsgSend = function() {
	$.ajax({
		method: 'POST',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'USER_MSG_SEND',
			'usernameTo': $('.new-msg').children('input[class="new-msg-to"]').val(),
			'msg': $('.new-msg').children('textarea').val()
		}
	}).done(function(response) {
		var res = JSON.parse(response).payload;
		if (res == 'success') {
			notify.success('sent your message');
		} else {
			notify.fail('failed to send message');
		}
	}).fail(function() {
		console.log('userMsgSend() failed to send');
	});		
};

$.fn.msgPoll = function() {
	$.ajax({
		method: 'POST',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'MSG_POLL'
		}
	}).done(function(response) {			
		var ntfs = JSON.parse(response).payload;
		for (var nIndex = 0; nIndex < ntfs.length; nIndex++) {
			// Add unread notifications
			var ntf = ntfs[nIndex];			
			if (ntf.type == 'msg') {
				if (ntf.watchAlert == 1) notifications.addMsgWatch(ntf);
				else notifications.addMsg(ntf);
			} else if (ntf.type == 'approvalReq') {
				notifications.addApprovalReq(ntf);
			} else {
				console.log('msgPoll(): Unknown notification type encountered');
			}	
		}
	
		// Tell user if they have new notifications
		var ntfsViewedAlready = ($('.notifications').attr('data-has-been-viewed') == 1);

		if (ntfsViewedAlready && ntfs.length > 0) {
			notify.success('you have ' + ntfs.length + ' new notifications');
		}
	}).fail(function() {
		notify.fail("you are on a slow network. Notifications re-optimized.");
	});
};

$.fn.editItem = function(itemType) {
	var url = document.location.href;
	var pgName = url.slice(url.lastIndexOf('/') + 1, url.indexOf('.php'));
	var itemType = pgName.slice(0, pgName.indexOf('-'));
	var itemId = url.substr(url.lastIndexOf('=') + 1);
	$.ajax({
		method: 'POST',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'EDIT_ITEM',
			'itemType': itemType,
			'itemId': itemId
		}
	}).done(function(response) {
		var res = JSON.parse(response).payload;
		if (res == 'success') {
			notify.success('edit success');
		} else {
			notify.fail("we can't seem to update the item you changed");
		}
	}).fail(function() {
		console.log('editItem() failed to send');
	});
};

$.fn.approvalReqAttended = function() {
	var id = $(this).closest('.notification').attr('data-id');
	var accepted = $(this).hasClass('approve');

	$.ajax({
		method: 'POST',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'APPROVAL_REQ_ATTENDED',
			'id': id,
			'accepted': accepted
		}
	}).done(function(response) {
		var res = JSON.parse(response).payload;
		if (res == 1) {
			notify.success('your decision has been processed');
		} else {
			notify.fail("we can't seem to tell the person of your decision");
		}
	}).fail(function() {
		console.log('approvalReqAttended() failed to send');
	});
};

$.fn.watchTask = function() {
	var url = document.location.href;
	var id = url.substr(url.lastIndexOf('=') + 1);
	$.ajax({
		method: 'post',
		url: 'php/notificationSystemAPI.php',
		data: {
			'action': 'WATCH_TASK',
			'id': id
		}
	}).done(function(response) {
		var res = JSON.parse(response).payload;
		var success = res.success;
		var nowWatching = res.nowWatching;
		if (success == 1) {
			if (nowWatching) {
				$('.btn-watch').addClass('watching');			
				notify.success('you are now watching this task');
			} else {
				$('.btn-watch').removeClass('watching');							
				notify.success('you are no longer watching this task');
			}
		} else {
			notify.fail("we can't seem to get you to watch/unwatch this task");
		}
	}).fail(function() {
		console.log('watchTask() failed to send');
	});
};

$(document).ready(function() {
	$('.btn-refresh').on('click', function() {
		location.reload();
	});
});
