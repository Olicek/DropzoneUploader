
function uploader()
{
	
	Dropzone.autoDiscover = false;
	$( '.dropzone' ).each(function() {
		var self = $(this);
		
		var settings = $(this).children('.fileUpload-settings').data('fileupload-settings');
		
		var myDropzone = new Dropzone('#' + self.attr('id'),
		{
			url: $(this).attr('action'),
			maxFilesize: settings.fileSizeLimit
		});

		myDropzone.on("addedfile", function(file) {
			$.ajax({
					url: settings.onUploadStart
			});
		});
	});
	
}
