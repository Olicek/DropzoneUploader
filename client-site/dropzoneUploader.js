
function dropzoneUploader()
{
	if (window.Dropzone === undefined) {
		console.error('Plugin "Dropzone.js"  is missing! Run `bower install dropzone` and load it.');
		return;
	}

	Dropzone.autoDiscover = false;
	$( '.dropzone' ).each(function() {
		var self = $(this);

		var settings = $(this).children('.fileUpload-settings').data('fileupload-settings');

		var myDropzone = new Dropzone('#' + self.attr('id'),
				{
					url: $(this).attr('action'),
					maxFilesize: settings.fileSizeLimit,
					maxFiles: settings.maxFiles
					//maxfilesexceeded: function (){
					//	console.log('too mutch');
					//}
				});

		myDropzone.on("addedfile", function(file) {
			$.ajax({
				url: settings.onUploadStart
			});
		});

		myDropzone.on("queuecomplete", function(file) {
			if (settings.ajax)
			{
				$.nette.ajax({
					type: 'get',
					url: settings.onSuccess
				});
				myDropzone.removeAllFiles();

			} else {
				window.location.href = settings.onSuccess;
			}
		});
	});

}
