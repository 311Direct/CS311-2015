$('.btn-create-note').on('click', function() {
	if (!$('.new-note > textarea').val()) return;
	
	// Determine attach point
	if ($('.note').length) {
		$attachPt = $('.note').last();
	} else {
		$attachPt = $('.btn-create-note');
	}

	// Attach note
	$('<div/>', {
		class: 'col-md-12 note'
	}).append($('<p/>', {
		text: $('.new-note > textarea').val()
	})).css('display', 'none').insertAfter($attachPt).slideDown('slow');


	// Clear 
	$('.new-note > textarea').val('');
});