<?php

add_action( 'cmb2_init', 'testimonial_register_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function testimonial_register_metabox() {

	// get our options
	$options = Client_and_Product_Testimonials::get_cat_options();
		
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_testimonial_details_';
	
	/**
	 * 	Sample metabox to demonstrate each field type included
	 *	@since 0.1
	 */
	$testimonial_metabox = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => sprintf( __( 'Linked %s', 'client-and-product-testimonials' ), ucwords( str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ) ),
		'object_types'  => array( 'testimonial', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
	) );
		
	/**
	 * 	Add dynamic 'Star Rating'
	*	@since 0.1
	*/
	if( $options['_client_and_product_testimonial_enable_star_rating'] == '1' ) {
		$testimonial_metabox->add_field( array(
			'name'    => sprintf( __( 'Rating', 'client-and-product-testimonials' ) ),
			'desc'    => sprintf( __( 'Set the rating for this testimonial.', 'client-and-product-testimonials' ), $options['_client_and_product_testimonial_taxonomy'] ),
			'id'      => $prefix . 'testimonial_rating',
			'type'    => 'star_rating',
			'default' => '5',
		) );
	}
	
	/**
	 * 	Add dynamic 'Association' field
	*	@since 0.1 
	*/
	$testimonial_metabox->add_field( array(
		'name'    => sprintf( __( '%s Associaton', 'client-and-product-testimonials' ), ucwords( str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ) ),
		'desc'    => sprintf( __( 'Select which %s to assign this testimonial to.', 'client-and-product-testimonials' ), str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ),
		'id'      => $prefix . 'testimonial_association',
		'type'    => 'taxonomy_select',
		'show_option_none' => sprintf( __( 'No %s', 'client-and-product-testimonials' ), ucwords( str_replace( 'testimonial_', '', rtrim( $options['_client_and_product_testimonial_taxonomy'], 's' ) ) ) ),
		'taxonomy' => str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] ),
	) );
		
	$testimonial_metabox->add_field( array(
		'name'    => __( 'Product/Client Association', 'client-and-product-testimonials' ),
		'desc'    => __( 'This field should be hidden. If you are currently able to see it, we may have an issue.', 'client-and-product-testimonials' ),
		'id'      => $prefix . 'testimonial_association_value',
		'type'    => 'testimonial_association_value',
	) );	
		
	/**
	*	If 'testimonial_clients' is set, we should also display a 'position' input field
	*	@since 0.1
	*/
	if( 'testimonial_clients' == $options['_client_and_product_testimonial_taxonomy'] ) {
		$testimonial_metabox->add_field( array(
			'name'    => __( 'Position at Company', 'client-and-product-testimonials' ),
			'desc'    => __( 'Input this users position at the company assigned above.', 'client-and-product-testimonials' ),
			'id'      => $prefix . 'client_position',
			'type'    => 'text',
			'placeholder' => __( 'Product Manager', 'client-and-product-testimonials' ),
		) );
	}	
		
	$testimonial_metabox->add_field( array(
		'name'    => __( 'Website', 'client-and-product-testimonials' ),
		'desc'    => __( 'Input this users website to link back to.', 'client-and-product-testimonials' ),
		'id'      => $prefix . 'url',
		'type'    => 'text_url',
		'placeholder' => 'http://',
	) );	
	
	/**
	 * 	Video Testimonial Details
	 *	@since 0.1
	 */
	$video_testimonial_metabox = new_cmb2_box( array(
		'id'            => $prefix . 'video_testimonial_metabox',
		'title'         => __( 'Video Testimonial Details', 'client-and-product-testimonials' ) . ' <small>(' . __( 'pro', 'client-and-product-testimonials' ) . ')</small>',
		'object_types'  => array( 'testimonial', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
	) );
	
	/*
	*	Video Testimonial Checkbox
	*/
	$video_testimonial_metabox->add_field( array(
		'name'    => __( 'Enable Video Testimonial', 'client-and-product-testimonials' ),
		'desc'    => sprintf( __( 'This is a video testimonial. %s', 'client-and-product-testimonials' ), '<small>(' . __( 'check this field and save the testimonial to reveal additional fields', 'client-and-product-testimonials' ) . ')</small>' ),
		'id'      => $prefix . 'video_testimonial_placeholder',
		'type'    => '',
		'after' => '<p><a href="http://captpro.evan-herman.com/" class="button-primary button-hero" target="_blank">' . __( 'Upgrade Now to Enable Video Testimonials', 'client-and-product-testimonials' ) . '</a></p>'
	) );
		
	/**
	* Initiate the 'About' metabox
	*	@since 0.1
	*/
	$about_metabox = new_cmb2_box( array(
		'id'            => 'about_the_author',
		'title'         => __( 'About', 'client-and-product-testimonials' ),
		'object_types'  => array( 'testimonial', ), // Post type
		'context'    => 'side',
		'priority'   => 'low',
		'show_names'    => true, // Show field names on the left
	) );
					
	// Render About metabox
	// @since 0.1
	$about_metabox->add_field( array(
		'name' => '',
		'desc' => '',
		'id'   => $prefix . 'about',
		'type' => 'about_metabox',
	) );
		
	/**
	 * 	Upsell Details
	 *	@since 0.1
	 */
	$upgrade_to_pro = new_cmb2_box( array(
		'id'            => $prefix . 'upgrade_to_pro',
		'title'         => '&nbsp',
		'object_types'  => array( 'testimonial', ), // Post type
		'context'    => 'side',
		'priority'   => 'low',
		'show_names'    => true, // Show field names on the left
	) );
	
	/*
	*	Video Testimonial Checkbox
	*/
	$upgrade_to_pro->add_field( array(
		'name'    => '',
		'desc' => '',
		'id'      => $prefix . 'pro_upsell',
		'type' => 'pro_upsell_metabox',
	) );
	
}

/*
*	Display a custom message when the user is using a video that is non-vimeo
* 	since the color picker will have no affect on the player.
*/
function capt_display_non_vimeo_warning( $field_args, $field ) {
	// check the video URL and display a warning if not a vimeo url 
	$vimeo_video_url = get_post_meta( $field->object_id, '_testimonial_details_video_testimonial_url', true );
	$vimeo_video_warning = ( $vimeo_video_url && strpos( $vimeo_video_url, 'vimeo.com' ) !== false ) ? false : __( "It looks like you aren't embedding a Vimeo video. This option will have no affect on the video player.", "client-and-product-testimonials" );
	if( $vimeo_video_warning ) {
		echo '<p style="color:#D52D2D;"><small>' . $vimeo_video_warning . '</small></p>';
	}
}
/*
*	Callback to check if the video testimonial metabox fields should be visible
*	@since 0.1
*/
function video_testimonial_active_check( $args ) {
	$post_id = $args->object_id;
	$video_testimonial_active = get_post_meta( $post_id, '_testimonial_details_video_testimonial', true );
	return ( $video_testimonial_active && $video_testimonial_active == 'on' ) ? 1 : 0;
}

/*
*	Callback to check if the video testimonial will be using a custom video color
*	@since 0.1
*/
function custom_vdieo_player_color_check( $args ) {
	$post_id = $args->object_id;
	$video_player_parameters = get_post_meta( $post_id, '_testimonial_details_video_player_parameters', true );
	return ( $video_player_parameters && ( in_array( 'color', $video_player_parameters ) ) ) ? 1 : 0;
}

/*
* cmb_render_callback_about_metabox()
* render the data contained in our custom about metabox
* since @0.1
*/
function cmb2_render_callback_about_metabox( $field, $meta, $object_id, $object_type, $field_type_object ) {
	require_once plugin_dir_path(__FILE__) . 'partials/about-metabox-template.php';
}
add_action( 'cmb2_render_about_metabox', 'cmb2_render_callback_about_metabox', 10, 5 );

/*
* cmb2_render_callback_pro_upsell()
* render the data contained in our custom upsell metabox
* since @0.1
*/
function cmb2_render_callback_pro_upsell( $field, $meta, $object_id, $object_type, $field_type_object ) {
	require_once plugin_dir_path(__FILE__) . 'partials/pro-upsell-metabox-template.php';
}
add_action( 'cmb2_render_pro_upsell_metabox', 'cmb2_render_callback_pro_upsell', 10, 5 );

/*
* cmb2_render_callback_star_rating()
* render the star rating metabox
* since @0.1
*/
function cmb2_render_callback_star_rating( $field, $meta, $object_id, $object_type, $field_type_object ) {
	wp_enqueue_style( 'star-rating-metabox-css', plugin_dir_url(__FILE__) . 'partials/css/star-rating-metabox.css' );
	require_once plugin_dir_path(__FILE__) . 'partials/star-rating-metabox.php';
}
add_action( 'cmb2_render_star_rating', 'cmb2_render_callback_star_rating', 10, 5 );

/*
* cmb2_render_callback_star_rating()
* render the star rating metabox
* since @0.1
*/
function cmb2_render_callback_testimonial_association_value( $field, $meta, $object_id, $object_type, $field_type_object ) {
	global $post;
	$options = Client_and_Product_Testimonials::get_cat_options();
	$selected_taxonomy = get_the_terms( $post->ID, str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] ) );
	$input_value = ( ( ! is_wp_error( $selected_taxonomy ) && ! empty( $selected_taxonomy ) ) ? $selected_taxonomy[0]->name : '' ); // client name (eg. Yikes inc.)
	?>
		<input type="text" value="<?php echo $input_value; ?>" class="regular-text" name="_testimonial_details_testimonial_association_value" id="_testimonial_details_testimonial_association_value">
	<?php
}
add_action( 'cmb2_render_testimonial_association_value', 'cmb2_render_callback_testimonial_association_value', 10, 5 );

/*
*	Sanitize our data for the field above
*	@since 0.1
*/
function cmb2_sanitize_testimonial_association_value_callback( $override_value, $value ) {
    // not an email?
    return ( $value == '' ) ? 'Not Set' : $value;
}
add_filter( 'cmb2_sanitize_testimonial_association_value', 'cmb2_sanitize_testimonial_association_value_callback', 10, 2 );

function wpfstop_change_default_title( $title ){
    $screen = get_current_screen();
    if ( 'testimonial' == $screen->post_type ){
        $title = __( 'Persons Name', 'client-and-product-testimonials' );
    }
    return $title;
}
add_filter( 'enter_title_here', 'wpfstop_change_default_title' );

/* 
* 	Get Star Rating on the Front end
*	@since 0.1 
*/
function eh_cmb2_get_star_rating_field( $post_id ) {
	$options = Client_and_Product_Testimonials::get_cat_options();
	// check if the stars are enabled, or we're admin side.
	if( $options['_client_and_product_testimonial_enable_star_rating'] == '1' ) {
		// allow users to enqueue custom font families here so they can use custom icons
		apply_filters( 'client_and_product_testimonials_font_family', wp_enqueue_style( 'dashicons' ) );
		$rating = ( get_post_meta( $post_id, '_testimonial_details_testimonial_rating', 1 ) ) ? get_post_meta( $post_id, '_testimonial_details_testimonial_rating', true ) : '5'; // set to 5 if user switches over and data is not found
		$rating_schema_markup = ( ! is_admin() ) ? '<span class="capt_rating_schema_markup"><span>' . $rating . '</span></span>' : '';
		$stars_container = '<section class="client-product-testimonial-rating-container" title="' . sprintf( _n( '%s star', '%s stars', $rating, 'client-and-product-testimonials' ), $rating ) . '" >';
			$x = 1;
			$total = 5;
			while( $x <= $rating ) {
				$stars_container .= '<span class="capt-rating-icon ' . apply_filters( 'client_and_product_testimonials_filled_icon_class' , 'dashicons dashicons-star-filled' ) . '"></span>';
				$x++;
			}
			// don't display empty stars if set in the settings
			if( $options['_client_and_product_testimonial_hide_empty_stars'] == '0' ) {
				if( $rating < $total ) {
					while( $rating < $total ) {
						$stars_container .= '<span class="capt-rating-icon ' . apply_filters( 'client_and_product_testimonials_empty_icon_class' , 'dashicons dashicons-star-empty' ) . '"></span>';
						$rating++;
					}
				}
			}	
		$stars_container .= '</section>';
		return $rating_schema_markup . $stars_container;
	} else {
		return;
	}
}

/* 
* 	Get Average of All Reviews Star Rating on the Front end
*	@since 0.1 
*/
function eh_cmb2_get_average_star_rating_field( $average_of_all_ratings, $total_number_of_reviews ) {
	$options = Client_and_Product_Testimonials::get_cat_options();
	if( $options['_client_and_product_testimonial_enable_star_rating'] == '1' ) {
		$stars_container = '<section class="client-product-testimonial-rating-container" title="' . sprintf( _n( '%s out of 5', '%s out of 5', $average_of_all_ratings, 'client-and-product-testimonials' ), $average_of_all_ratings ) . ', ' . sprintf( _n( '%s review', '%s reviews', $total_number_of_reviews, 'client-and-product-testimonials' ), $total_number_of_reviews ) . '" >';
			$x = 1;
			$total = 5;
			while( $x <= $average_of_all_ratings ) {
				$stars_container .= '<span class="dashicons dashicons-star-filled"></span>';
				$x++;
			}
			// don't display empty stars if set in the settings
			if( $options['_client_and_product_testimonial_hide_empty_stars'] == '0' ) {
				if( $average_of_all_ratings < $total ) {
					while( $average_of_all_ratings < $total ) {
						$stars_container .= '<span class="dashicons dashicons-star-empty"></span>';
						$average_of_all_ratings++;
					}
				}
			}	
		$stars_container .= '</section>';
		wp_enqueue_style( 'dashicons' );
		return $stars_container;
	} else {
		return;
	}
}

/*
*	Get the client/product name + client name / client position
*	@since 0.1
*/
function eh_get_testimonial_details( $post_id ) {
	$options = Client_and_Product_Testimonials::get_cat_options();
	$selected_taxonomy = get_the_terms( get_the_ID(), str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] ) );
	if( $options['_client_and_product_testimonial_taxonomy'] == 'testimonial_clients' ) { 
		$client_name = ( ( ! is_wp_error( $selected_taxonomy ) && ! empty( $selected_taxonomy ) ) ? $selected_taxonomy[0]->name . apply_filters( 'client_and_product_testimonial_position_divider', ' / ' ) : '' ); // client name (eg. Yikes inc.)
		$client_position = ( ( get_post_meta( get_the_ID(), '_testimonial_details_client_position', true ) != '' ) ? get_post_meta( get_the_ID(), '_testimonial_details_client_position', true ) : '' );
		$thing_reviewed_schema_markup = '<span class="capt_item_reviewed_schema_markup"><span>' . get_bloginfo( 'name' ) . '</span></span>';
	} else {
		$client_name = ''; // product name shouldn't be displayed, no point in listing the product below the users name
		$client_position = '';
		$thing_reviewed_schema_markup = '<span class="capt_item_reviewed_schema_markup"><span>' . ( ( ! is_wp_error( $selected_taxonomy ) && ! empty( $selected_taxonomy ) ) ? $selected_taxonomy[0]->name : '' ) . '</span></span>';
	}	
	$client_url = ( get_post_meta( get_the_ID(), '_testimonial_details_url', true ) ) ? esc_url( get_post_meta( get_the_ID(), '_testimonial_details_url', true ) ) : false;
	// wrap the client name in the appropriate link tag
	$client_name = ( $client_url ) ? '<a href="' . $client_url . '" target="_blank" title="' . $client_name . ' website">' . $client_name . '</a>' : $client_name;
	return $client_name . $client_position . $thing_reviewed_schema_markup;
}

/*
*	Get the client name - displayed in custom column
*	@since 0.1
*/
function eh_get_testimonial_client_name( $post_id ) {
	$options = Client_and_Product_Testimonials::get_cat_options();
	$selected_taxonomy = get_the_terms( get_the_ID(), str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] ) );
	$client_name = ( ( ! is_wp_error( $selected_taxonomy ) && ! empty( $selected_taxonomy ) ) ? $selected_taxonomy[0]->name : '<strong>' . __( 'Not Set', 'client-and-product-testimonials' ) . '</strong>' ); // client name (eg. Yikes inc.)
	return $client_name;
}

/*
*	Add custom column to our testimonial CPT
*	@since 0.1
*/
function eh_catp_edit_testimonial_columns( $columns ) {
	$options = Client_and_Product_Testimonials::get_cat_options();
	$taxonomy = rtrim( ucwords( str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ) ), 's' );
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'user_image' => 'User Image',
		'title' => __( 'Name', 'client-and-product-testimonials' ),
		'rating' => __( 'Rating', 'client-and-product-testimonials' ),
		'client' => $taxonomy,
		'date' => __( 'Created', 'client-and-product-testimonials' )
	);
	// check if the stars are enabled, if not, remove the rating column to hide the star containers
	if( $options['_client_and_product_testimonial_enable_star_rating'] == '0' ) {
		unset( $columns['rating'] );
	}
	return $columns;
}
add_filter( 'manage_edit-testimonial_columns', 'eh_catp_edit_testimonial_columns' ) ;

/*
*	Add custom data to the custom columns created above
*	@since 0.1
*/
function eh_capt_manage_testimonial_columns( $column, $post_id ) {
	global $post;
	$options = Client_and_Product_Testimonials::get_cat_options();
	switch( $column ) {

		/* If displaying the 'user image' column. */
		case 'user_image' :
			$user_image = ( get_the_post_thumbnail( $post_id ) ? get_the_post_thumbnail( $post_id, 'thumbnail', array( 'class' => 'testimonial-thumbnail-preview', 'title' => get_the_title( $post_id ) ) ) : wp_get_attachment_image( $options['_client_and_product_testimonial_no_photo_fallback_id'], 'thumbnail', 0, array( 'class' => 'testimonial-thumbnail-preview', 'title' => __( 'No Image Set', 'client-and-product-testimonials' ) ) ) );
			echo $user_image;
			break;

		/* If displaying the 'rating' column. */
		case 'rating' :
			echo eh_cmb2_get_star_rating_field( $post_id );
			break;

		case 'client' :
			echo eh_get_testimonial_client_name( $post_id );
			break;
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
add_action( 'manage_testimonial_posts_custom_column', 'eh_capt_manage_testimonial_columns', 10, 2 );

/*
*	Make Specified Custom Columns Sortable
*	( Rating, Product/Client )
*/
add_filter( 'manage_edit-testimonial_sortable_columns', 'eh_capt_sortable_rating_column' );
function eh_capt_sortable_rating_column( $columns ) {
    $columns['rating'] = 'rating';
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
    return $columns;
}

/*
*	Filter 'pending' to 'Pending Review' on our testimonial edit post list page
*	@since 0.1
*/
add_filter( 'views_edit-testimonial', 'capt_custom_pending_translation', 10, 1);
function capt_custom_pending_translation( $views ) {
	$views['video_testimonials'] = '<a style="color:rgba(140, 140, 140, 0.6);" href="#" onclick="return false;" title="Video testimonials only available in the pro version.">' . __( 'Video Testimonials', 'client-and-product-testimonials' ) . ' <small>(' . __( 'pro', 'client-and-product-testimonials' ) . ')</small></a>';
	// return the views
	return $views;
}

/*
*	Filter the 'pending' post state on our testimonial post type
*	@since 0.1
*/
add_filter( 'display_post_states', 'capt_filter_pending_post_state', 10, 2 );
function capt_filter_pending_post_state( $post_states, $post ) {
	if( $post->post_type == 'testimonial' ) {
		$post_states = str_replace( 'Pending', '<span class="capt-pending-review" title="' . __( "Testimonial Pending Review", "client-and-product-testimonials" ) . '"><span class="dashicons dashicons-star-filled"></span> ' . __( 'Pending Review', 'client-and-product-testimonials' ) . '</span>', $post_states );
	}
	return $post_states;
}

/*
*	Add custom taxonomy filter to the testimonail list page
*	@since 0.1
*/
function capt_add_taxonomy_filters() {
	global $typenow;
	
	$options = Client_and_Product_Testimonials::get_cat_options();
	$selected_taxonomy = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
	
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array( $selected_taxonomy );
 
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'testimonial' ){
 
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>" . sprintf( __( 'Show All %s' , 'client-and-product-testimonials' ), $tax_name ) . "</option>";
				foreach ($terms as $term) { 
					echo '<option value='. $term->slug, ( isset( $_GET[$tax_slug] ) && $_GET[$tax_slug] == $term->slug ) ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
add_action( 'restrict_manage_posts', 'capt_add_taxonomy_filters' );