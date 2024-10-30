jQuery( document ).ready( function() {

	/* Toggling between shortcode paraemter sections */
	jQuery( 'body' ).on( 'click', '.shortcode-selection input[type="radio"]', function() {
		regenerate_slider_shortcodes();
		var clicked_element = jQuery( this ).attr( 'data-attr-shortcode' );
		/* Hide visible container, and show the clicked container */
		jQuery( '.hidden-section' ).filter(":not(:animated)").fadeOut( 'fast', function() {
			/* Calculate the new height of the container for smooth animations */
			calculate_and_append_new_height( clicked_element );
			setTimeout( function() {
				jQuery( '.'+clicked_element ).fadeIn( 'slow' );
			}, 200 );
		});
	});
	
	/* On any change of a parameter, we should re-generate the shortcode - Input fields */
	jQuery( 'body' ).on( 'change', 'input', function() {
		regenerate_slider_shortcodes();
	});
	
	/* On any change of a parameter, we should re-generate the shortcode - Select2 fields*/
	jQuery( 'body' ).on( 'change', 'select[multiple]', function() {
		regenerate_slider_shortcodes();
	});
	
	/* On any change of a parameter, we should re-generate the shortcode - Select fields */
	jQuery( 'body' ).on( 'change', 'select', function() {
		regenerate_slider_shortcodes();
	});
	
	/* Initialize our select 2 fields */
	jQuery(".full-width-fields-select2").select2();
	jQuery(".grid-fields-select2").select2();
	jQuery(".list-fields-select2").select2();
	jQuery(".thumbnail-slider-fields-select2").select2();
	
});

/**
*	Add new height to smooth transition between sections
*	@since 0.1
*/
function calculate_and_append_new_height( clicked_element ) {
	var new_height = jQuery( '.'+clicked_element ).css( 'height' );
	var container = jQuery( '#TB_ajaxContent .capt-shortcode-generator-wrap .shortcode-options-section' );
	if( typeof new_height === 'undefined' ) { // when the 'submission form' is clicked, the height is undefined :: set it to 10
		new_height = 10;
	}
	container.css( 'height', new_height );
}

/**
*	Regenerate our shortcode for the active container
*	@since 0.1
*/
function regenerate_slider_shortcodes() {
	/* store the shortcode to generate */
	var shortcode_selection = 'testimonial-'+jQuery( 'input[name="capt-shortcode"]:checked' ).attr( 'data-attr-shortcode' );
	
	if( 'testimonial-grid' == shortcode_selection || 'testimonial-thumbnail-slider' == shortcode_selection || 'testimonial-submission-form' == shortcode_selection ) {
		jQuery( '.insert-capt-shortcode-button' ).removeAttr( 'onclick' ).attr( 'disabled', 'disabled' );
		jQuery( '.insert-capt-shortcode-button' ).text( 'Pro Version Only' );
	} else {
		jQuery( '.insert-capt-shortcode-button' ).attr( 'onclick', "send_to_editor(jQuery('.capt-shortcode-input').val());return false;" ).removeAttr( 'disabled' );
		jQuery( '.insert-capt-shortcode-button' ).text( 'Insert Shortcode' );
	}
	
	/* get the visible container to loop over fields */
	var visible_containers = jQuery( '.'+shortcode_selection+'-fields' ).find( 'input' );
	var visible_select = jQuery( '.'+shortcode_selection+'-fields' ).find( 'select' );
	/* star t o build the shortcode */
	var shortcode_array = [];
	/* Empty array to populate with selected taxonomies */
	var taxonomy_array = [];
	var current_taxonomy = jQuery( 'input[name="capt-taxonomy-name"]' ).val();
	/* push the shortcode type in to the array */
	shortcode_array.push( shortcode_selection );
	/* Loop over all selected taxonomies */
	if( shortcode_selection == 'testimonial-fade-slider' ) {
		if( jQuery( '.capt-taxonomy-selection-container' ).find( jQuery( '.capt-all-taxonomies-checkbox' ) ).is( ':checked' ) ) {
			jQuery( '.capt-taxonomy-checkbox' ).not( jQuery( '.capt-all-taxonomies-checkbox' ) ).attr( 'checked', false ).attr( 'disabled', 'disabled' );
		} else {
			jQuery( '.capt-taxonomy-checkbox' ).removeAttr( 'disabled' );
		}
		jQuery( '.capt-taxonomy-selection-container' ).find( 'input[type="checkbox"]:checked' ).each( function() {
			taxonomy_array.push( jQuery( this ).val() );
		});
		if( taxonomy_array.length != 0 ) {
			shortcode_array.push( current_taxonomy+'="'+taxonomy_array.join( ',' )+'"' );
		}
	}
	/* If its a full width shortcode */
	if( shortcode_selection == 'testimonial-full-width' || shortcode_selection == 'testimonial-grid' || shortcode_selection == 'testimonial-list' || shortcode_selection == 'testimonial-thumbnail-slider' ) {
		/* Target Select2 Containers */
		var value = jQuery( '.'+shortcode_selection.replace( 'testimonial-', '' )+'-fields-select2' ).val();
		var parameter_name = jQuery(  '.'+shortcode_selection.replace( 'testimonial-', '' )+'-fields-select2' ).prev().val(); 
		if( value ) {
			shortcode_array.push( parameter_name+'="'+value+'"' );
		}
	}
	/* Loop over select fields */
	visible_select.each( function() {
		var value = jQuery( this ).val();
		var parameter_name = jQuery( this ).attr( 'name' );
		shortcode_array.push( parameter_name+'="'+value+'"' );
	});
	/* Loop over each possible input field and build our shortcode array */
	visible_containers.each( function() {
		if( jQuery( this ).attr( 'type' ) == 'text' ) {
			var value = jQuery( this ).val();
			var parameter_name = jQuery( this ).attr( 'name' );
			if( shortcode_selection == 'testimonial-thumbnail-slider' ) {
				if( jQuery( '.color-overlay' ).find( 'input[type="radio"]:checked' ).val() == 'grayscale' ) {
					return;
				}
				shortcode_array.push( parameter_name+'="'+value+'"' );
			} else {
				shortcode_array.push( parameter_name+'="'+value+'"' );
			}
		}
		if( jQuery( this ).attr( 'type' ) == 'number' ) {
			var value = jQuery( this ).val();
			var parameter_name = jQuery( this ).attr( 'name' );
			shortcode_array.push( parameter_name+'="'+value+'"' );;
		}
		if( jQuery( this ).attr( 'type' ) == 'radio' ) {
			if( jQuery( this ).is( ':checked' ) ) {
				if( jQuery( this ).val() == '' || jQuery( this ).val() == 'color-overlay' ) {
					return;
				}
				var value = jQuery( this ).val();
				var parameter_name = jQuery( this ).attr( 'name' ).replace( '-images', '' );
				shortcode_array.push( parameter_name+'="'+value+'"' );
			}
		}
	});
	/* Populate the shortcode input field */
	jQuery( '.capt-shortcode-input' ).val( '[' + shortcode_array.join( ' ' ) + ']' );
}

/**
*	Get the initial visible container height, to allow for smooth transitions from the get go!
*	@since 1.0
*/
function getVisibleContainerHeight() {
	setTimeout( function() {
		if( jQuery( '.shortcode-selection' ).find( 'input[type="radio"]:checked' ).attr( 'data-attr-shortcode' ) == 'fade-slider' ) {
			calculate_and_append_new_height( 'fade-slider' );
			regenerate_slider_shortcodes();
		}
	}, 50);
}