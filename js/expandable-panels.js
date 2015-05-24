$(document).ready(function() {
	// Show the content, when the user taps the "expand" button
	$('.button-expand').on('click', function() {
		$(this).prevAll('.panel-content').stop().toggle();
		
		var nowShowing = $(this).children('.glyphicon').hasClass('glyphicon-chevron-down');
		if (nowShowing) {
			$(this).children('.glyphicon').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		} else {
			$(this).children('.glyphicon').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');		
		}
	});
});