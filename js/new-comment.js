$(document).ready(function() {
	/*
	 * "Add comment" refers to opening the dialogue box, for the user to enter in their comment
	 * "Publish comment" refers to inserting the comment into the database
	 */
	 
	// Allow user to comment when clicking the "+" button next
	// to the "Comments" heading. Then hide it if they press the
	// "X" button in the comment dialogue
	$('.btn-add-comment, .new-comment-publish, .new-comment-cancel').on('click', function() {
		$('.new-comment').css({
			'top': ($(this).height() * -1) + 'px',
			'transition': 'top 0s'
		});
		$('.new-comment-container').fadeToggle('slow');
		$('.new-comment').css({
			'top': 0,
			'transition': 'top 2s'
		});
	});
	
	// Publish a comment
	$('.new-comment-publish').on('click', function() {
		var comment = $('.new-comment').children('textarea').val();
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'COMMENT_PUBLISH',
				'forType': loadContent.pageType(),
				'forId': loadContent.itemId(),
				'comment': comment
			}
		}).done(function(response) {
			var success = response.payload;
			if (success) {
				notify.success('your comment has been published');			
			} else {
				notify.fail("wasn't able to publish your comment");
			}
		}).fail(function() {
			notify.fail("failed to publish your comment");
		});
	});
});