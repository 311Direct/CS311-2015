$(document).ready(function() {
	var types = ['project', 'milestone', 'task', 'user'];
	for (var type = 0; type < types.length; type++) {
		if (window.location.href.indexOf(types[type]) != -1) {
			// Page-specific title
			$('.page-type').text(types[type]);
			break;
		}
		if (type == types.length - 1) {
			// Default title
			$('.page-type-container').remove();
		}		
	}
});