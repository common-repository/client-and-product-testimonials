<?php 

/*
*	Build up our testimonial grid layout to display on the front page
*	uses jQuery masonry*
*/
function generate_testimonial_list( $atts ) {
		
	// get our options
	$options = Client_and_Product_Testimonials::get_cat_options();
	
	// extract attributes
	$a = shortcode_atts( array(
        str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) => '', // either 'product' or 'client' (defualts to all taxonomies)
		'style' => '1', // 1-3
		'columns' => '1', // 1-4
		'limit' => '16',
		'image-style' => 'square',
		'greyscale' => '', // greyscale/grayscale images (add greyscale/grayscale parameter to set images to grey/gray)
		'grayscale' => '', // greyscale/grayscale images (add greyscale/grayscale parameter to set images to grey/gray)
		'order' => '',
		'orderby' => '',
		'exclude' => '',
    ), $atts );
	
	$taxonomy_name = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
	
	$use_fallback_image = ( isset( $options['_client_and_product_testimonial_use_fallback_image'] ) ) ? $options['_client_and_product_testimonial_use_fallback_image'] : '1';
	
	// set the list style
	$style = (int) ! empty( $atts['style'] ) ? $atts['style'] : '1';
	// set the columns
	$columns = (int) ! empty( $atts['columns'] ) ? $atts['columns'] : '1';
	// set the limit
	$limit = (int) ! empty( $atts['limit'] ) ? $atts['limit'] : '16';
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
	
	if( ! empty( $atts[str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] )] ) && $atts[str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] )] != '-1' ) {
		$tax_ids = explode( ',', $atts[ str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ] );
		if( $tax_ids && is_array( $tax_ids ) ) {
			$taxonomy_ids = $tax_ids;
		} else {
			$taxonomy_ids = array( $atts[str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy']) ] );
		}
		$args = array(
			'post_type' => 'testimonial',
			'posts_per_page' => $limit ,
			'status' => 'publish',
			'orderby' => ( ( ! empty( $atts['orderby'] ) ) ? $atts['orderby'] : 'date' ),
			'order' => ( ( ! empty( $atts['order'] ) ) ? $atts['order'] : 'DESC' ),
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy_name,
					'field'    => 'term_id',
					'terms'    => apply_filters( 'capt-wooc-single', apply_filters( 'capt-edd-single', $taxonomy_ids, $taxonomy_name ), $taxonomy_name ),
				),
			),
		);
	} else { // query all
		
		$args = array(
			'post_type' => 'testimonial',
			'posts_per_page' => $limit ,
			'status' => 'publish',
			'orderby' => ( ( ! empty( $atts['orderby'] ) ) ? $atts['orderby'] : 'date' ),
			'order' => ( ( ! empty( $atts['order'] ) ) ? $atts['order'] : 'DESC' ),
		);

		// pass the taxonomy terms through our custom filters
		$taxonomy_terms = apply_filters( 'capt-wooc-single', apply_filters( 'capt-edd-single', array(), $taxonomy_name ), $taxonomy_name );
		
		/*
		*	If content is passed back via the filter,
		*	push in the tax_query data
		*/
		if( ! empty( $taxonomy_terms ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy_name,
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
		
	$testmonial_query = new WP_Query( apply_filters( 'client_and_product_testimonials_list_query_args', $args ) );
	
	// start output buffering to catch the list
	ob_start();
		
		/* Generate the HTML debugging comment (inside capt-helpers.php) */
		echo generate_capt_html_comments( 'Testimonial List', $atts );
		
		if( $testmonial_query->have_posts() ) {

			// un-minifed styles for testing
				// enqueue the list styles
				// wp_enqueue_style( 'testmonial-list-styles', Client_Product_Testimonials_URL . 'lib/public/css/testimonial-list-styles.css' );
			
			// Minified Styles - Global for all testimonial shortcodes
			wp_enqueue_style( 'testmonial-list-styles', Client_Product_Testimonials_URL . 'lib/public/css/min/client-and-product-testimonials.min.css', array( capt_get_themes_last_enqueued_style() ) );
			
			?><div id="testimonial-list" class="capt-list-style-<?php esc_attr_e( $style ); ?> capt-list-columns-<?php esc_attr_e( $columns ); ?>"><?php
				?><div class="list"><?php
				$i = 1;
				$x = 1;
				$total_posts = $testmonial_query->found_posts;
				
				while( $testmonial_query->have_posts() ) {
					$testmonial_query->the_post();
					
					/* Setup the containers */
					if( $columns > 1 && ( $x == 1 ) ) {
						?>
						<section class="capt-row">
						<?php
					}
					
					?>
						
						<div class="list-item list-item-<?php echo $i; ?>">
							<?php 								
								// Setup the fallback image
								if ( has_post_thumbnail( get_the_ID() ) ) {
									$attachment_alt_text = get_post_meta( get_post_thumbnail_id( get_the_ID() ), '_wp_attachment_image_alt', true );
									echo get_the_post_thumbnail( get_the_ID(), 'testimonial-image', array( 'class' => 'testimonial-image capt-image-' . $image_style . ' ' . $images_greyscale, 'alt' => $attachment_alt_text, 'title' => get_the_title() ) ); 
									$testimonial_full_width = '';
								} else {
									if( $use_fallback_image == '1' ) {
										$fallback_image_id = (int) $options['_client_and_product_testimonial_no_photo_fallback_id'];
										echo wp_get_attachment_image( $fallback_image_id, 'testimonial-image', false, array( 'class' => 'testimonial-image capt-image-' . $image_style . ' ' . $images_greyscale . ' wp-post-image', 'alt' => __( 'No Image Provided', 'client-and-product-testimonials' ), 'title' => __( 'No Image Provided', 'client-and-product-testimonials' ) ) );
										$testimonial_full_width = '';
									} else {
										$testimonial_full_width = 'testimonial-content-full-width';
									}
								}
							
								?>
								<div class="testimonial-content <?php echo $testimonial_full_width; ?>">
									<section class="testimonial-content-text">
										<cite>	
											<?php echo apply_filters( 'capt_content', get_the_content() ); ?>
										</cite>
									</section>
									<span class="testimonial-author">
										<section><?php echo get_the_title( get_the_ID() ); ?></section>
									</span>
									<small class="testimonial-details"><?php echo eh_get_testimonial_details( get_the_ID() ); ?></small>
									<?php echo eh_cmb2_get_star_rating_field( get_the_ID() ); ?>
								</div>
						</div>

						<?php if( $i != $testmonial_query->found_posts && $columns == '1' ) { ?>
							<hr />
						<?php } ?>
						
					<?php
					/* Setup the containers */
					if( $columns > 1 && ( $x == $columns ) ) {
						?>
						</section>
						<?php
						$x = 0;
					}
					
					$i++;
					$x++;
				}
				
				?></div><?php
			?></div><?php
		} else {
			?>
				<section><?php _e( 'No testimonials found.' , 'client-and-product-testimonials' ); ?></section>
			<?php
		}
	wp_reset_query();
	// clean up output buffering
	$testimonial_list = ob_get_clean();
	// return the list
    return $testimonial_list;
}
add_shortcode( 'testimonial-list', 'generate_testimonial_list' );