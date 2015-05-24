var HEADER_HEIGHT = $('header').css('height');
var HEADER_HEIGHT_NAV = '140px';

$(document).ready(function() {
	$('.mobile-menu-container').on('click', function() {
		var navShown = ($('nav').css('display') == 'block');
		if (navShown) {
			$('header').css('height', HEADER_HEIGHT);
		} else {
			$('header').css('height', HEADER_HEIGHT_NAV);
		}
		$('nav').stop().fadeToggle('slow');
	});
});