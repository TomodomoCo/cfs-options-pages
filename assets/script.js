jQuery(document).on('ready', function() {
//	jQuery('#title').prop('type', 'hidden');
	var title = jQuery('#title').val();
	jQuery('.wrap h2:first-of-type').text( 'Edit ' + title );

	jQuery('.add-new-h2').remove();
});
