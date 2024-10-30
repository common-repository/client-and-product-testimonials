/*
*	Toggle the preloader preview image on the CAPT options page
*	@since 0.1.0
*/
jQuery( document ).ready( function() {
	
	/* On initial page laod, ensure the preloader image matches the selection above */
	var preloader_number = jQuery( '#_client_and_product_testimonial_preloader' ).val();
	var preloader_title = localized_data.preloader_title + ' ' + preloader_number;
	jQuery( '.capt-preloader-preview' ).html( '<img src="' + localized_data.preloader_directory + preloader_number + '.gif" style="max-width:100%;" title="' + preloader_title + '">' );
	
	/* On change of select field - alter the preloader preview */
	jQuery( 'body' ).on( 'change', '#_client_and_product_testimonial_preloader', function() {
		var preloader_number = jQuery( this ).val();
		var preloader_title = localized_data.preloader_title + ' ' + preloader_number;
		jQuery( '.capt-preloader-preview' ).html( '<img src="' + localized_data.preloader_directory + preloader_number + '.gif" style="max-width:100%;" title="' + preloader_title + '">' );
	});
});