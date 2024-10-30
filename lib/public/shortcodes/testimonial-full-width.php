<?php 

/*
*	Build up our testimonial grid layout to display on the front page
*	uses jQuery masonry*
*/
function generate_testimonial_section( $atts ) {
		
	// get our options
	$options = Client_and_Product_Testimonials::get_cat_options();
	
	// extract attributes
	$a = shortcode_atts( array(
		'testimonial' => '', // post ID or post title of the testimonial to display
		'images' => 'square',
		'greyscale' => '', // greyscale/grayscale images (add greyscale/grayscale parameter to set images to grey/gray)
		'grayscale' => '', // greyscale/grayscale images (add greyscale/grayscale parameter to set images to grey/gray)
		'order' => '',
		'orderby' => '',
		'exclude' => '',
    ), $atts );
	
	// set images to greyscale
	if( isset( $atts['greyscale'] ) ) {
		$images_greyscale = 'capt-image-greyscale';
	}
	if( isset( $atts['grayscale'] ) ) {
		$images_greyscale = 'capt-image-grayscale';
	}
	if( ! isset( $atts['greyscale'] ) && ! isset( $atts['grayscale'] ) ) {
		$images_greyscale = '';
	}
	
	// set the image style
	$image_style = ! empty( $atts['images'] ) ? $atts['images'] : 'square';
	
	/* Should the fallback image be used? */
	$use_fallback_image = ( isset( $options['_client_and_product_testimonial_use_fallback_image'] ) ) ? $options['_client_and_product_testimonial_use_fallback_image'] : '1';
	
	// setup the testimonial ID to retreive
	if( isset( $atts['testimonial'] ) ) {
		$testimonial_array = explode( ',', $atts['testimonial'] );
		if( $testimonial_array && is_array( $testimonial_array ) && count( $testimonial_array ) > 1 ) { /* Array of testimonial IDs */
			$random_testimonial_id = array_rand( $testimonial_array, 1 );
			$testimonial_id = $testimonial_array[$random_testimonial_id];
		} else if( is_numeric( $atts['testimonial'] ) ) { /* Single testimonial by ID */
			$testimonial_id = (int) $atts['testimonial'];	
		} else { /* Pass in testimonial by title */
			$post_array = get_page_by_title( $atts['testimonial'], OBJECT, 'testimonial' );
			if( ! is_wp_error( $post_array ) ) {
				$testimonial_id = $post_array->ID;
			}
		}
	}
	
	// Exclude testimonials
	$excluded_testimonials = ( ! empty( $atts['exclude'] ) ) ? explode( ',', $atts['exclude'] ) : array();
	$excluded_testimonial_ids = array();
	/* Check for excluded testimonials */
	if( ! empty( $excluded_testimonials ) ) {
		foreach( $excluded_testimonials as $exclude_testimonial ) {
			if( is_numeric( $exclude_testimonial ) ) {
				$excluded_testimonial_ids[] = $exclude_testimonial;
			} else {
				$testimonial = get_page_by_title( $exclude_testimonial, OBJECT, 'testimonial' );
				if( $testimonial ) {
					$excluded_testimonial_ids[] = $testimonial->ID;
				}
			}
		}
	}
	
	// Random post returned		
	if( ! isset( $testimonial_id ) ) {	
		$args = array(
			'post_type' => 'testimonial',
			'posts_per_page' => 1, // limit to a single post
			'status' => 'publish',
			'orderby' => ( ( ! empty( $atts['orderby'] ) ) ? $atts['orderby'] : 'rand' ), // note this is the only shortcode to default to random
			'order' => ( ( ! empty( $atts['order'] ) ) ? $atts['order'] : 'DESC' ),
		);
	} else { // specific post is returned
		$args = array(
			'post_type' => 'testimonial',
			'posts_per_page' => 1, // limit to a single post
			'status' => 'publish',
			'post__in' => array( $testimonial_id ),
		);
	}
		
	/* 
	*	Append the excluded post IDs 
	*	passed in via exclude="1,2,3"
	*/
	if( ! empty( $excluded_testimonial_ids ) ) {
		$args['post__not_in'] = $excluded_testimonial_ids;
	}	
		
	$testmonial_query = new WP_Query( apply_filters( 'client_and_product_testimonials_full_width_query_args', $args ) );
	
	// start output buffering to catch the list
	ob_start();
		
		/* Generate the HTML debugging comment (inside capt-helpers.php) */
		echo generate_capt_html_comments( 'Testimonial Full Width', $atts );
		
		if( $testmonial_query->have_posts() ) {
			$testmonial_query->the_post();
			
			// Un-minified for testing - enqueue the list styles
				// wp_enqueue_style( 'testmonial-full-width-styles', Client_Product_Testimonials_URL . 'lib/public/css/testimonial-full-width-styles.css', array( capt_get_themes_last_enqueued_style() ) );
			
			// Minified Styles - Global for all testimonial shortcodes
				wp_enqueue_style( 'capt-styles', Client_Product_Testimonials_URL . 'lib/public/css/min/client-and-product-testimonials.min.css', array( capt_get_themes_last_enqueued_style() ) );
			
				?>
				<section class="capt-testimonial-full-width-section capt-testimonial-<?php echo get_the_ID(); ?>">
					<section class="interior-container">
						<section class="testimonial-content-text">
							<cite><?php echo apply_filters( 'capt_content', get_the_content() ); ?></cite>
						</section>
						<?php 
							if ( has_post_thumbnail( get_the_ID() ) ) {
								$attachment_alt_text = get_post_meta( get_post_thumbnail_id( get_the_ID() ), '_wp_attachment_image_alt', true );
								echo get_the_post_thumbnail( get_the_ID(), 'testimonial-image', array( 'class' => 'testimonial-image capt-image-' . $image_style . ' ' . $images_greyscale, 'alt' => $attachment_alt_text, 'title' => get_the_title() ) ); 
							} else {
								if( $use_fallback_image == '1' ) {
									$fallback_image_id = (int) $options['_client_and_product_testimonial_no_photo_fallback_id'];
									echo wp_get_attachment_image( $fallback_image_id, 'testimonial-image', false, array( 'class' => 'testimonial-image capt-image-' . $image_style . ' ' . $images_greyscale . ' wp-post-image', 'alt' => __( 'No Image Provided', 'client-and-product-testimonials' ), 'title' => __( 'No Image Provided', 'client-and-product-testimonials' ) ) );
								}
							}
						?>
						<span class="testimonial-author">
							<section><?php echo get_the_title( get_the_ID() ); ?></section>
						</span>
						<small class="testimonial-details"><?php echo eh_get_testimonial_details( get_the_ID() ); ?></small>
						<?php echo eh_cmb2_get_star_rating_field( get_the_ID() ); ?>
					</section>
				</section>
			<?php
		} else {
			?>
				<section><?php _e( 'Testimonial not found.' , 'client-and-product-testimonials' ); ?></section>
			<?php
		}
	wp_reset_query();
	// clean up output buffering
	$testimonial_section = ob_get_clean();
	// return the list
    return $testimonial_section;
}
add_shortcode( 'testimonial-full-width', 'generate_testimonial_section' );