<?php 
/*
*	Build up our testimonial fade slider layout to display on the front page
*	uses wooslider (Flexslider2)
* 	- Setup a static variable, assign the static variable to the slider, pass the variable into the init so we can initialize multiple sliders on a single page
*/
function generate_fade_slider( $atts ) {
	
	// start output buffering to catch the grid
	ob_start();
	
	// get our options
	$options = Client_and_Product_Testimonials::get_cat_options();
	
	// extract attributes
	$a = shortcode_atts( array(
        str_replace( 'testimonial_', '', rtrim( $options['_client_and_product_testimonial_taxonomy'], 's' ) ) => '', // taxonomy ID(s) - seperated by , if more than 1 (defualts to all taxonomies)
		'style' => '1', // 1-3
		'controls' => '1', // slideshow controls
		'automatic' => '1', // auto slideshow?
		'duration' => '5', // duration between slides if auto is set to true (ms, default: 5000ms/5s)
		'animation' => 'fade', // slide or fade
		'order' => '',
		'orderby' => '',
		'exclude' => '',
    ), $atts );
	
	$taxonomy = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
	$taxonomy_name = str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] );
	
	$preloader_selection = $options['_client_and_product_testimonial_preloader'];
	
	/* Should the fallback image be used? */
	$use_fallback_image = ( isset( $options['_client_and_product_testimonial_use_fallback_image'] ) ) ? $options['_client_and_product_testimonial_use_fallback_image'] : '1';
	
	// set the image style
	$image_style = ! empty( $atts['image-style'] ) ? $atts['image-style'] : 'square';
	
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
	
	if( ! empty( $atts[$taxonomy_name] ) && $atts[$taxonomy_name] != '-1' ) {	
		$taxonomy_terms = explode( ',' , $atts[$taxonomy_name] );
		// pass the taxonomy terms through our custom filters
		$taxonomy_terms = apply_filters( 'capt-wooc-single', apply_filters( 'capt-edd-single', $taxonomy_terms, $taxonomy ), $taxonomy );
		$args = array(
			'post_type' => 'testimonial',
			'posts_per_page' => 99,
			'status' => 'publish',
			'orderby' => ( ( ! empty( $atts['orderby'] ) ) ? $atts['orderby'] : 'date' ),
			'order' => ( ( ! empty( $atts['order'] ) ) ? $atts['order'] : 'DESC' ),
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    =>  $taxonomy_terms
				),
			),
		);
	} else {	
		$args = array(
			'post_type' => 'testimonial',
			'posts_per_page' => 99,
			'status' => 'publish',
			'orderby' => ( ( ! empty( $atts['orderby'] ) ) ? $atts['orderby'] : 'date' ),
			'order' => ( ( ! empty( $atts['order'] ) ) ? $atts['order'] : 'DESC' ),
		);
		// pass the taxonomy terms through our custom filters
		$taxonomy_terms = apply_filters( 'capt-wooc-single', apply_filters( 'capt-edd-single', array(), $taxonomy ), $taxonomy );
		/*
		*	If content is passed back via the filter,
		*	push in the tax_query data
		*/
		if( ! empty( $taxonomy_terms ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $taxonomy_terms,
				),
			);
		}
	}
	
	/* 
	*	Append the excluded post IDs 
	*	passed in via exclude="1,2,3"
	*/
	if( ! empty( $excluded_testimonial_ids ) ) {
		$args['post__not_in'] = $excluded_testimonial_ids;
	}	
	
	$testmonial_query = new WP_Query( apply_filters( 'client_and_product_testimonials_slider_query_args', $args ) );
	wp_reset_query();
	
	/* Generate the HTML debugging comment (inside capt-helpers.php) */
	echo generate_capt_html_comments( 'Testimonial Fade Slider', $atts );
	
	if( $testmonial_query->have_posts() ) {
		
		// Flex Slider Options
		$flex_slider_controls = ( isset( $atts['controls'] ) && $atts['controls'] == '1' ? true : false );
		$flex_slider_auto = ( isset( $atts['automatic'] ) && $atts['automatic'] == '1' ? true : false );
		$flex_slider_duration = ( isset( $atts['duration'] ) ? $atts['duration'] : '5' );
		$flex_slider_animation = ( isset( $atts['animation'] ) && ( $atts['animation'] == 'fade' || $atts['animation'] == 'slide' ) ? $atts['animation'] : 'fade' );
					
		// Un-minified - for testing
			// Scripts
			// enqueue flexslider
			// wp_enqueue_script( 'testmonial-slider-init-js', Client_Product_Testimonials_URL . 'lib/public/js/testimonial-slider-init.js', array( 'jquery', 'flexslider.js' ), '', FALSE );
			
			// Styles
			// enqueue the slider styles
				// wp_enqueue_style( 'flexslider.css', Client_Product_Testimonials_URL . 'lib/public/css/flexslider.css' );
				// wp_enqueue_style( 'testimonial-fade-slider-css', Client_Product_Testimonials_URL . 'lib/public/css/testimonial-fade-slider.css', array( 'flexslider.css', capt_get_themes_last_enqueued_style() ) );
			
		// Minified - production
			// Scripts
			wp_enqueue_script( 'flexslider.js', Client_Product_Testimonials_URL . 'lib/public/js/min/jquery.flexslider-min.js', array( 'jquery' ), '', FALSE );
			wp_enqueue_script( 'testmonial-slider-init-js', Client_Product_Testimonials_URL . 'lib/public/js/min/testimonial-slider-init.min.js', array( 'flexslider.js' ), '', FALSE );
			// Styles - Global for all testimonial shortcodes
			wp_enqueue_style( 'capt-styles', Client_Product_Testimonials_URL . 'lib/public/css/min/client-and-product-testimonials.min.css', array( capt_get_themes_last_enqueued_style() ) );
			
		/* 
		*	Note: Flexslider options are stored as data-attr parameters on the div element
		*	This is so that we can initialize multiple sliders on a page, with separate options
		*	@since 0.1
		*/
		?><div id="testimonial-fade-slider" class="flexslider testimonial-fade-slider-flexslider" data-attr-controls="<?php echo $flex_slider_controls; ?>" data-attr-auto="<?php echo $flex_slider_auto; ?>" data-attr-speed="<?php echo $flex_slider_duration; ?>" data-attr-animation="<?php echo $flex_slider_animation; ?>"><?php
			echo '<image src="' . apply_filters( 'client_and_product_testimonials_preloader_url', Client_Product_Testimonials_URL . 'lib/images/preloaders/Preloader_' . apply_filters( 'client_and_product_testimonials_preloader_number', $preloader_selection ) . '.gif' ) . '" class="capt-preloader">';
			?><ul class="slides"><?php
			$i = 0;
			while( $testmonial_query->have_posts() ) {	
				$testmonial_query->the_post();	
				/* Standard Testimonial */
				?>
				<li>
				  <div class="slide-text">
						<?php
							if ( has_post_thumbnail( get_the_ID() ) ) {
								$attachment_alt_text = get_post_meta( get_post_thumbnail_id( get_the_ID() ), '_wp_attachment_image_alt', true );
								echo get_the_post_thumbnail( get_the_ID(), 'testimonial-image', array( 'class' => 'testimonial-image', 'alt' => $attachment_alt_text, 'title' => get_the_title() ) ); 
								$full_width_class = '';
							} else {
								if( $use_fallback_image == '1' ) {
									$fallback_image_id = (int) $options['_client_and_product_testimonial_no_photo_fallback_id'];
									echo wp_get_attachment_image( $fallback_image_id, 'testimonial-image', false, array( 'class' => 'testimonial-image capt-image-' . $image_style . ' ' . $images_greyscale . ' wp-post-image', 'alt' => __( 'No Image Provided', 'client-and-product-testimonials' ), 'title' => __( 'No Image Provided', 'client-and-product-testimonials' ) ) );
									$full_width_class = '';
								} else {
									$full_width_class = 'full-width-content';
								}
							}
						?>
						<div class="testimonial-content <?php echo $full_width_class; ?>">
							<section class="testimonial-content-text">
								<cite>
									<?php echo apply_filters( 'capt_content', get_the_content() ); ?>
								</cite>
							</section>
							<?php echo eh_cmb2_get_star_rating_field( get_the_ID() ); ?>
							<span class="testimonial-author">
								<section><?php the_title(); ?></section>
							</span>
							<?php echo eh_get_testimonial_details( get_the_ID() ); ?>
						</div>
				  </div>
				</li>
				<?php
			}
			wp_reset_postdata();
			?></ul><?php
		?></div><?php
	} else {
		?>
			<section class="capt-no-testimonials-found-error"><?php _e( 'No testimonials found.', 'client-and-product-testimonials' ); ?></section>
		<?php
	}
	
	// clean up output buffering
	$testimonial_grid = ob_get_clean();
	// return the grid
    return $testimonial_grid;
}
add_shortcode( 'testimonial-fade-slider', 'generate_fade_slider' );