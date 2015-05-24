$.fn.publish = function() {
	$popup = $(this).closest('.field-edit');
	var tagLsVal = $popup.children('select[data-items-field]').length;
	var lsVal = $popup.children('select:not([data-items-field])').length;
	if (tagLsVal) {
		var tagSel = $popup.children('select').attr('data-items-field');
		var val = create.flagsFor(tagSel);
	} else if (lsVal) {
		var val = $popup.children('select').val();
	} else {
		var val = $popup.children('input').val();
		// Don't attempt to edit, if no string value has been put in
		if (val.trim() == '') {
			notify.fail('specify a value');
			return;
		}
	}

	$.ajax({
		method: 'post',
		url: 'php/DirectPHPApi.php',
		data: {
			'action': 'EDIT',
			'for-type': $(this).attr('data-for-type'),
			'for-id': loadContent.itemId(),
			'for-column': $(this).attr('data-for-column'),
			'value': val
		}
	}).done(function(response) {
		var editInfo = response.payload;
		var column = editInfo.column;
		var success = editInfo.success;
		var isProjMgr = editInfo.isProjMgr;
		
		if (success) {
			if (isProjMgr){
  			 notify.success('Item Updated!');
  			 reloadData();
			 }
			else notify.success('edit request sent to project manager');
		} else {
			notify.fail('attempted to publish edits, but failed');
		}
	}).fail(function() {
		notify.fail("wasn't able to publish edits");
	});
};

$(document).ready(function() {
	$('.btn-edit').on('click', function() {
		$('.popup-edit').slideToggle('fast');
	});

	$('.btn-edit-publish').on('click', function() {
		$('.popup-edit').fadeOut('fast');
		$(this).publish();
	});

	$('.btn-edit-close').on('click', function() {
		$(this).closest('.popup-edit').slideUp('fast');
	});
});