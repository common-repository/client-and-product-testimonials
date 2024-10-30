<?php
/*
*	Helper functions to perform specific repetitive tasks throughout the plugin
*	- Helps prevent bloated code base, and repetitive tasks
*	@since 0.1
*	@Author Evan Herman
*/

/*
*	Generate an HTML comment to identify the plugin & the versio
*	@since 0.1
*/
function generate_capt_html_comments( $shortcode, $parameters ) {
	ob_start();
	$shortcode_parameters = generate_capt_shortcode_attributes_array( $parameters );
	?>
<!--
   Plugin:	Client and Product Testimonials Lite,
   Shortcode:	<?php echo $shortcode; ?>,
   Parameters:	<?php echo implode( ', ', $shortcode_parameters ); ?>,
   Version:	<?php echo Client_Product_Testimonials_Version; ?>,
   Author:	Evan Herman | http://www.captpro.evan-herman.com
-->
	<?php
	return ob_get_clean();
}

/*
*	Build an array of shortcode attributes (used to display in our html comment)
*	@since 0.1
*/
function generate_capt_shortcode_attributes_array( $attributes ) {
	/* Build an array to display in our comments - for debugging help */
	$shortcode_atts_array = array();
	if( ! empty( $attributes ) ) {
		foreach( $attributes as $key => $value ) {
			$shortcode_atts_array[] = $key . ':' . $value;
		}
	}
	return ( ! empty( $shortcode_atts_array ) ) ? $shortcode_atts_array : array( __( 'Parameters', 'client-and-product-testimonials' ) => __( 'None', 'client-and-product-testimonials' ) );
}

/*
*	Get the stored options for use 
*	@since 0.1
*/
function get_capt_options() {
	$options = Client_and_Product_Testimonials::get_cat_options();
	return $options;
}

/*
*	Create a checkbox array of appropriate taxonomies
*	for use in the shortcode generator
*	@since 0.1
*/
function generate_capt_taxonomy_selection() {
	$options = get_capt_options();
	$taxonomy = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
	$terms = get_terms( $taxonomy, array(
		'hide_empty' => true
	) );
	?>
	<input type="hidden" name="capt-taxonomy-name" value="<?php echo str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ); ?>">
	<strong><?php printf( __( '%s', 'client-and-product-testimonials' ), ucwords( str_replace( 'testimonial-', '', $taxonomy ) ) ); ?></strong>
	<p class="description"><?php printf( __( 'Select which %s to display in this slider.', 'client-and-product-testimonials' ), str_replace( 'testimonial-', '', $taxonomy ) ); ?></p>
	<?php
	if( $terms ) {
		?>
		<section class="capt-taxonomy-selection-container">
			<label class="capt-tax-checkbox-label">
				<input type="checkbox" name="capt-taxonomy[]" class="capt-taxonomy-checkbox capt-all-taxonomies-checkbox" value="-1">
				<?php printf( __( 'All %s', 'client-and-product-testimonials' ), ucwords( str_replace( 'testimonial-', '', $taxonomy ) ) ); ?>
			</label>
			<?php
				foreach( $terms as $term ) {
					?>
						<label class="capt-tax-checkbox-label">
							<input type="checkbox" name="capt-taxonomy[]" class="capt-taxonomy-checkbox" value="<?php echo $term->term_taxonomy_id; ?>">
							<?php echo $term->name; ?>
						</label>
					<?php
				}
			?>
		</section>
		<?php
	} else {
		?>
			<p class="description"><?php printf( __( "It looks like you haven't setup any %s yet, or you haven't assigned any testimonials to them.", "client-and-product-testimonials" ), str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ); ?></p>
		<?php
	}
}

/*
*	Create a dropdown of possible testimonials to choose from
*	for use in the shortcode generator
*	@since 0.1
*/
function generate_testimonial_select2( $class_name ) {
	$class_name = $class_name . '-select2';
	$tax_name = ( $class_name == 'full-width-fields-select2' ) ? 'testimonial' : str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] );
	$options = get_capt_options();
	$testimonial_post_args = array(
		'post_per_page' => 99,
		'post_type' => 'testimonial',
		'status' => 'publish'
	);
	$testimonial_query = new WP_Query( $testimonial_post_args );
	wp_reset_query();
	/* Get post data as an array, as to not screw with the global post data by using the_post(); */
	$testimonials = $testimonial_query->get_posts();
	if( ! empty( $testimonials ) ) {
		?>	
			<strong><?php _e( 'Select Testimonial(s) to Display', 'client-and-product-testimonials' ); ?></strong>
			<input type="hidden" name="capt-taxonomy-name" value="<?php echo $tax_name; ?>">
			<select id="capt-testimonial-select2" class="<?php echo $class_name; ?>"multiple style="width:100%;">
				<?php
					foreach( $testimonials as $testimonial ) {
						?>
							<option value="<?php echo $testimonial->ID; ?>"><?php echo ( $testimonial->post_title != '' ) ? $testimonial->post_title : __( 'Title Not Set', 'client-and-product-testimonials' ); ?></option>
						<?php
					}
				?>
			</select>
		<?php
	}
}

/*
*	Create a dropdown of possible testimonials to choose from
*	for use in the shortcode generator
*	@since 0.1
*/
function generate_taxonomy_select2( $class_name ) {
	$class_name = $class_name . '-select2';
	$options = get_capt_options();
	$taxonomy = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
	$terms = get_terms( $taxonomy, array(
		'hide_empty' => true
	) );
	wp_reset_query();
	if( $terms ) {
		?>
			<strong><?php printf( __( 'Select %s to Display', 'client-and-product-testimonials' ), ucwords( str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ) ); ?></strong>
			<input type="hidden" name="capt-taxonomy-name" value="<?php echo str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ); ?>">
			<select id="capt-testimonial-select2" class="<?php echo $class_name; ?>"multiple style="width:100%;">
				<?php
					foreach( $terms as $term ) { 
						?>
							<option value="<?php echo $term->term_taxonomy_id; ?>"><?php echo $term->name; ?></option>
						<?php
					}
				?>
			</select>
		<?php
	}
}

/*
*  Generate a drop down of possible caroufredsel slider transition effects
*	for use in the shortcode generator
*	@since 0.1
*/
function generate_caroufredsel_transition_effects() {
	$transition_effects = array(
		"crossfade", "fade", "cover", "cover-fade", "uncover", "uncover-fade"
	);
	if( $transition_effects ) {
		?>
			<p class="description"><?php printf( __( 'Select the type of effect to use when transitioning between testimonials.', 'client-and-product-testimonials' ) ); ?></p>
			<select name="effect" id="caroufredsel-slider-transition-effects" class="caroufredsel-slider-transition-effects" style="width:100%;">
				<?php
					foreach( $transition_effects as $effect ) { 
						?>
							<option value="<?php echo $effect; ?>"><?php echo $effect; ?></option>
						<?php
					}
				?>
			</select>
		<?php
	}
}

/*
*	Generate a color picker
*	- used inside of the thumbnail slider shortcode generator
*	@since 0.1
*/
function generate_capt_colorpicker( $shortcode_section_name, $prameter_name, $default_color = "#bada55" ) {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'capt-color-picker.js', Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/js/capt-color-picker.js', array( 'wp-color-picker' ), false, true );
	?>
	<input type="text" value="<?php echo $default_color; ?>" class="capt-color-picker <?php echo $shortcode_section_name; ?>" name="<?php echo $prameter_name; ?>" />
	<?php
}


/*
*	Convert Hex value to RGBA
*	- used inside of the thumbnail slider, for our color overlay
*	@since 0.1
*/
function capt_eherman_hex2rgba($color, $opacity = '.3') {
  
 $default = 'rgb(0,0,0)';
 
 //Return default if no color provided
 if(empty($color))
    return $default; 
 
 //Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
			$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
			 if(abs($opacity) > 1)
			 $opacity = 1.0;
			 $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
			$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}


/* 
*	If EDD is active, add a filter so any testimonial 
*	displayed on a single edd page querys the proper product
*	Can be overridden with shortcode parameter (or widget option)
*	@since 0.1
*/			
add_filter( 'capt-edd-single', 'filter_capt_on_edd_single', 10, 2 );
function filter_capt_on_edd_single( $query_terms, $taxonomy ) {
	global $post;
	if( is_single() && $post->post_type == 'download' ) {
		$tax_slug = sanitize_title( $post->post_title );
		$associated_term = get_term_by( 'slug', $tax_slug, $taxonomy );
		/* If a term is found to be set in capt */
		if( $associated_term ) {
			$query_terms = array( $associated_term->term_id );
		} else {
			$query_terms = array();
		}
	}
	return $query_terms;
}

/* 
*	If Woocommerce is active, add a filter so any testimonial 
*	displayed on a single Woocommerce product page querys the current product
*	Can be overridden with shortcode parameter (or widget option)
*	@since 0.1
*/			
add_filter( 'capt-wooc-single', 'filter_capt_on_wooc_single', 10, 2 );
function filter_capt_on_wooc_single( $query_terms, $taxonomy ) {
	/* Get and store our options */
	$options = Client_and_Product_Testimonials::get_cat_options();
	/* If the user has set the taxonomy to 'Clients' there will be no products to associate to */
	if( $options['_client_and_product_testimonial_taxonomy'] != 'testimonial_products' ) {
		return $query_terms;
	}
	global $post;
	if( is_single() && $post->post_type == 'product' ) {
		$tax_slug = sanitize_title( $post->post_title );
		$associated_term = get_term_by( 'slug', $tax_slug, $taxonomy );
		/* If a term is found to be set in capt */
		if( $associated_term ) {
			$query_terms = array( $associated_term->term_id );
		} else {
			$query_terms = array();
		}
	}
	return $query_terms;
}

/*
*	Check if EDD is active
*	@return true if active/ false if not
*	@since 0.1
*/
function capt_is_edd_active() {
	/* Get and store our options */
	$options = Client_and_Product_Testimonials::get_cat_options();
	if( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
		/* confirm that the user has 'products' set, else this is not useful */
		if( $options['_client_and_product_testimonial_taxonomy'] != 'testimonial_products' ) {
			return false;
		}
		return true;
	}
	return false;
}

/*
*	Check if WooCommerce is active
*	@return true if active/ false if not
*	@since 0.1
*/
function capt_is_woocommerce_active() {
	/* Get and store our options */
	$options = Client_and_Product_Testimonials::get_cat_options();
	if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		/* confirm that the user has 'products' set, else this is not useful */
		if( $options['_client_and_product_testimonial_taxonomy'] != 'testimonial_products' ) {
			return false;
		}
		return true;
	}
	return false;
}

/*
*	Check if Thumbnail Regenerate is active
*	@return true if active/ false if not
*	@since 0.1
*/
function capt_is_regen_thumbnails_active() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // required, else an error is thrown (since this is hooked early)
	/* Get and store our options */
	if( is_plugin_active( 'regenerate-thumbnails/regenerate-thumbnails.php' ) ) {
		return true;
	}
	return false;
}

/*
*	Get a video testimonial embed, and pass in video parameters
*	@return video player with custom parameters
*	@since 0.1
*/
function capt_get_video_testimonial( $video_testimonial_url, $video_testimonial_id ) {
	$video_parameters = get_post_meta( $video_testimonial_id, '_testimonial_details_video_player_parameters', true );
	$new_parameter_array = array();
	if( $video_parameters ) {
		foreach( $video_parameters as $parameter ) {
			/* Vimeo */
			// title
			if( 'title' == $parameter ) {
				$new_parameter_array['title'] = '0';
			}
			// byline
			if( 'byline' == $parameter ) {
				$new_parameter_array['byline'] = '0';
			}
			// image
			if( 'portrait' == $parameter ) {
				$new_parameter_array['portrait'] = '0';
			}
			// color
			if( 'color' == $parameter ) {
				$custom_color = str_replace( '#', '', get_post_meta( $video_testimonial_id, '_testimonial_details_vimeo_video_color', true ) ); // strip the pound from the hex color code
				$new_parameter_array['color'] = ( $custom_color ) ? $custom_color : '00adef';
			}
			// autoplay
			if( 'autoplay' == $parameter ) {
				$new_parameter_array['autoplay'] = '1';
			}
		}
	}
	echo '<div class="capt-videoWrapper">' . wp_oembed_get( $video_testimonial_url, apply_filters( 'client_and_product_testimonial_iframe_args', $new_parameter_array, $video_testimonial_id ) ) . '</div>';
}


/**
*	Check if the fallback image already exists
*	Reference: https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
*	- This is used to prevent duplicate 'Fallback' images being uploaded upon plugin activation
*	@since 0.1
*	@return	true/false
*/
function capt_does_fallback_image_already_exist() {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title='%s';", 'Client and Product Testimonial - No Image' )); 
	if( ! empty( $attachment ) ) {	
		// if found, return true
		return true;
	}
	// if not found, return false
	return false;
}

/**
*	Get the last enqueued style and return it
*	- Used to load our stylesheet after all others to ensure this plugins styles are not overridden
*	@since 0.1
*	@return last enqueued style handle
*/
function capt_get_themes_last_enqueued_style() {
	// grab the last enqueued style, so we can use it as a dependency of our styles (for override)
	global $wp_styles;
	end( $wp_styles->groups );
	$last_key = key( $wp_styles->groups );
	return $last_key;
}
			
?>