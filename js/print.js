$(document).ready(function() {
	$('.btn-print').on('click', function() {
		window.print();
	});
	
	// Do not support printing from mobile
	if (document.documentElement.clientWidth < 1024) {
		$('.btn-print').remove();
	}
});
