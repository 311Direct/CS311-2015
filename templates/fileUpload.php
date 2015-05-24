<form action="php/fileUpload.php" method="post" enctype="multipart/form-data" id="fileUploadForm">
	<input type="file" name="fileToUpload" id="fileToUpload" class="btn-attach-deliverable">
	<input type="hidden" name="itemId" id="itemId" value="" />
	<input type="hidden" name="itemNum" id="itemNum" value="" />
	<input type="hidden" name="itemType" id="itemType" value="" />
	<input type="hidden" name="originalUrl" id="originalUrl" value="" />
	<input type="submit" value="Upload Image" id="btn-submit">
	<script>
		// Put upload in a folder
		var url = window.location.href;
		var page = url.slice(url.lastIndexOf('/') + 1, url.lastIndexOf('-'));
		var itemType = page[0].toUpperCase();
		var idNum = url.substr(url.lastIndexOf('=') + 1);
		var id = itemType + '-' + idNum;
		document.getElementById('originalUrl').value = url;
		document.getElementById('itemId').value = id;		// e.g.: T-123
		document.getElementById('itemNum').value = idNum;	// e.g.: 123
		document.getElementById('itemType').value = page;	// e.g.: task
		
		// Automatically start upload after selecting file
		document.getElementById('fileToUpload').onchange = function() {
			document.getElementById('fileUploadForm').submit();
			
			$('.upload-mask').css({
				'display': 'block',
				'opacity': '0',
				'transition': 'opacity 2s ease-in',
				'-webkit-transition': 'opacity 2s ease-in'
			}).css('opacity', 1);
		};
	</script>
</form>