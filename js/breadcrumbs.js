var bCrumbs = {
	show: function() {
		$.ajax({
			url: 'php/DirectPHPApi.php',
			method: 'post',
			data: {
				'action': 'BREADCRUMBS',
				'pageType': loadContent.pageType(),
				'id': loadContent.itemId()
			}
		}).done(function(response) {
			var bCrumbs = response.payload;
			$('.breadcrumbs').html(bCrumbs);
		}).fail(function() {
			console.log('Failed to execute action: BREADCRUMBS');
		});
	}
};

$(document).ready(function() {
	bCrumbs.show();
});