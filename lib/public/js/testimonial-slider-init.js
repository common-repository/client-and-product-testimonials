/*
*	Main Flexslider initialization Class
*	Flexslider by WooThemes (http://flexslider.woothemes.com/)
*	Compiled by Evan Herman | https://www.evan-herman.com
*/
jQuery(window).load(function() {

	/* Fade Slider Begin */
		jQuery( ".testimonial-fade-slider-flexslider" ).each( function() {
			/* Store our options */
			var controls = jQuery( this ).attr( 'data-attr-controls' );
			var auto = jQuery( this ).attr( 'data-attr-auto' );
			var speed = parseInt( jQuery( this ).attr( 'data-attr-speed' ) * 1000 );
			var animation = jQuery( this ).attr( 'data-attr-animation' );
			/* Initiailize */
			jQuery( this ).flexslider({
				animation: animation, /* passed in via testimonial-fade-slider.php, */
				controlNav: controls, /* passed in via testimonial-fade-slider.php */
				directionNav: false,
				slideshow: auto,
				slideshowSpeed: speed,
			});
		});

		/* Recursive function to animate the slide height when found */
		function grab_slide_height_and_animate_slider_height() {
			var interval_timer = setInterval( function() {
				jQuery( '.testimonial-fade-slider-flexslider' ).each( function() {	
					var largest_height = 0;
					// set the largest height based on longest testimonial
					jQuery( this ).find( '.slides li' ).each( function() {
						if( jQuery( this ).height() > largest_height ) {
							largest_height =  jQuery( this ).height();
						}
					});
					if( largest_height == 0 ) {
						grab_slide_height_and_animate_slider_height();
					} else {
						jQuery( this ).find( '.capt-preloader' ).fadeOut( 600 );
						jQuery( this ).find( '.flex-control-nav' ).fadeIn( 'slow' );
						jQuery( this ).find( '.slides' ).css({visibility: "visible", position: "relative"});
						jQuery( this ).animate({
							opacity: 1,
							height : largest_height + 'px'
						}, 400, 'swing' );
						// jQuery( this ).css( 'height', largest_height );
						jQuery( this ).find( '.slides' ).animate({
							height : largest_height + 'px'
						}, 650, 'swing' );
						clearInterval(interval_timer);
					}
				});
			}, 1200);
		}
		grab_slide_height_and_animate_slider_height();
		
		/* On window resize, we should re-calculate the largest height */
		jQuery( window ).resize(function() {
			recalculate_largest_slide_height_and_resize_slider();
		});
		
	/* End Fade Slider */
	
});

/*
*	On window resize, we should recalculate the height of the slider and slides
*	to make things fluid
*/
function recalculate_largest_slide_height_and_resize_slider() {
	jQuery( '.testimonial-fade-slider-flexslider' ).each( function() {	
		var largest_height = 0;
		// set the largest height based on longest testimonial
		jQuery( this ).find( '.slides li' ).each( function() {
			if( jQuery( this ).height() > largest_height ) {
				largest_height =  jQuery( this ).height();
			}
		});
		jQuery( this ).css( 'height', largest_height + 'px' ); 
		jQuery( this ).find( 'ul.slides' ).css( 'height', largest_height + 'px' );
	});
}