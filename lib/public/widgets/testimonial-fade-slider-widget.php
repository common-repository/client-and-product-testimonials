<?php

// Creating the widget 
class capt_fade_slider_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'capt_fade_slider_widget', 

			// Widget name will appear in UI
			__( 'Testimonial Fade Slider', 'client-and-product-testimonials'), 

			// Widget description
			array( 'description' => __( 'Place a fade slider in the sidebar of your site.', 'client-and-product-testimonials' ), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
	
		// empty array to populate shortcode attributes
		$shortcode_attributes = array();
		// get our options
		$options = Client_and_Product_Testimonials::get_cat_options();
		// taxonomy
		$taxonomy = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
		// title
		$title = apply_filters( 'widget_title', $instance['title'] );
		// selected taxonomy
		$shortcode_attributes[] = ( isset( $instance[ 'capt_taxonomy' ] ) ? 'capt_taxonomy="' . $instance['capt_taxonomy'] . '"' : 'capt_taxonomy="-1"' );
		// split the taxonomy
		$split_taxonomy = explode( '-', $taxonomy );
		// display controls
		$shortcode_attributes[] = ( ( isset( $instance['hide_controls'] ) && $instance['hide_controls'] == 1 ) ? 'controls="1"' : 'controls="0"' );
		// auto start
		$shortcode_attributes[] = ( ( isset( $instance['automatic'] ) && $instance['automatic'] == 1 ) ? 'auto="1"' : 'auto="0"' );
		// limit
		$shortcode_attributes[] = ( isset( $instance['limit'] ) ? 'limit="' . $instance['limit'] . '"' : 'limit="5"' );
		// speed
		$shortcode_attributes[] = ( isset( $instance['duration'] ) ? 'speed="' . intval( $instance['duration'] * 1000 ). '"' : 'speed="5000"' );
		// style
		$shortcode_attributes[] = ( isset( $instance['style'] ) ? 'style="' . $instance['style'] . '"' : 'style="style-1"' );
		// check if EDD is active
		if( capt_is_edd_active() ) {
			// EDD override
			$shortcode_attributes[] = ( isset( $instance['edd_override'] ) ? 'edd_override="' . $instance['edd_override'] . '"' : 'edd_override="0"' );
		}
		// check if WooCommerce is active
		if( capt_is_woocommerce_active() ) {
			// EDD override
			$shortcode_attributes[] = ( isset( $instance['wooc_override'] ) ? 'wooc_override="' . $instance['wooc_override'] . '"' : 'wooc_override="0"' );
		}
		/*
		*	Process the shortcode and display the slider
		*	@since 0.1
		*/		
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			
			// This is where you run the code and display the output
			echo do_shortcode( '[testimonial-fade-slider-widget ' . implode( ' ' , $shortcode_attributes ) . ']' );
			
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		// get our options
		$options = Client_and_Product_Testimonials::get_cat_options();
		// title 
		$title = ( isset( $instance['title'] ) ? $instance['title'] : __( 'Testimonials', 'client-and-product-testimonials' ) );
		// selected taxonomy
		$selected_taxonomy = ( isset( $instance['capt_taxonomy'] ) ? $instance['capt_taxonomy'] : null );
		// taxonomy
		$taxonomy = str_replace( '_', '-', $options['_client_and_product_testimonial_taxonomy'] );
		// split the taxonomy
		$split_taxonomy = explode( '-', $taxonomy );
		// Animation (fade/slide)
		$animation = ( isset( $instance['animation'] ) ? $instance['animation'] : 'fade' );
		// display controls
		$hide_controls = ( isset( $instance['hide_controls'] ) ? $instance['hide_controls'] : 0 );
		// auto start
		$automatic = ( isset( $instance['automatic'] ) ? $instance['automatic'] : 0 );
		// limit
		$limit = ( isset( $instance['limit'] ) ? $instance['limit'] : 5 );
		// duration
		$duration = ( isset( $instance['duration'] ) ? $instance['duration'] : 5 );
		// style
		$style = ( isset( $instance['style'] ) ? $instance['style'] : 'style-1' );
		// is EDD active
		if( capt_is_edd_active() ) {
			// EDD Override
			$edd_override = ( isset( $instance['edd_override'] ) ? $instance['edd_override'] : 0 );
		}
		// is WooCommerce active
		if( capt_is_woocommerce_active() ) {
			// EDD Override
			$wooc_override = ( isset( $instance['wooc_override'] ) ? $instance['wooc_override'] : 0 );
		}
		// Widget admin form
		?>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</label> 
		<p class="description"><?php _e( 'Enter a title for the testimonial widget.', 'client-and-product-testimonials' ); ?></p>
		
		<label for="<?php echo $this->get_field_id( 'capt_taxonomy' ); ?>"><?php echo ucwords( rtrim( str_replace( 'testimonial_', '', $options['_client_and_product_testimonial_taxonomy'] ), 's' ) ); ?>
			<?php wp_dropdown_categories( array(
				'taxonomy' => $taxonomy,
				'show_option_none' => sprintf( __( 'All %s', 'client-and-product-testimonials' ), ucwords( $split_taxonomy[1] ) ),
				'option_none_value' => '-1',
				'class' => 'widefat',
				'hide_empty' => false,
				'selected' => $selected_taxonomy,
				'value_field' => 'term_id',
				'name' => $this->get_field_name( 'capt_taxonomy' ),
			) ); ?>
		</label> 
		<p class="description"><?php printf( __( 'Select one of your %s above.', 'client-and-product-testimonials' ), $split_taxonomy[1] ); ?></p>
		
		<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of Testimonials to Display:' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" min="1" value="<?php echo esc_attr( $limit ); ?>" />
			<p class="description"><?php _e( 'Limit the number of testimonials to display.', 'client-and-product-testimonials' ); ?></p>
		</label>
				
		<label for="<?php echo $this->get_field_id( 'animation' ); ?>"><?php _e( 'Animation Type:' ); ?>
			<select class="widefat" name="<?php echo $this->get_field_name( 'animation' ); ?>" id="<?php echo $this->get_field_id( 'animation' ); ?>">
				<option value="fade" <?php selected( $animation, "fade" ); ?>><?php _e( 'Fade', 'client-and-product-testimonials' ); ?></option>
				<option value="slide" <?php selected( $animation, "slide" ); ?>><?php _e( 'Slide', 'client-and-product-testimonials' ); ?></option>
			</select>
			<p class="description"><?php _e( 'Select the animation for this slider (fade/slide).', 'client-and-product-testimonials' ); ?></p>
		</label>
		
		<label for="<?php echo $this->get_field_id( 'hide_controls' ); ?>"><?php _e( 'Hide Controls:' ); ?>
			&nbsp;<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_controls' ); ?>" name="<?php echo $this->get_field_name( 'hide_controls' ); ?>" value="1" <?php checked( $hide_controls, 1 ); ?>>
			<p class="description"><?php _e( 'Should the arrow controls be displayed below the slider?', 'client-and-product-testimonials' ); ?></p>
		</label>
		
		<label for="<?php echo $this->get_field_id( 'automatic' ); ?>"><?php _e( 'Automatic:' ); ?>
			&nbsp;<input type="checkbox" id="<?php echo $this->get_field_id( 'automatic' ); ?>" name="<?php echo $this->get_field_name( 'automatic' ); ?>" value="1" <?php checked( $automatic, 1 ); ?> onchange="jQuery('.testimonial-duration-label').fadeToggle();return false;">
			<p class="description"><?php _e( 'Should the slider start and transition automatically?', 'client-and-product-testimonials' ); ?></p>
		</label>
		
		<label class="testimonial-duration-label" for="<?php echo $this->get_field_id( 'duration' ); ?>" style="<?php if( $automatic != 1 ) { echo 'display:none;'; } ?> float:left;"><?php _e( 'Tesitmonial Duration:' ); ?>
			<input style="width:60px;" class="" id="<?php echo $this->get_field_id( 'duration' ); ?>" name="<?php echo $this->get_field_name( 'duration' ); ?>" type="number" min="1.5" step="0.25" value="<?php echo esc_attr( $duration ); ?>" />&nbsp;<?php _e( 'seconds', 'client-and-product-testimonials' ); ?>
			<p class="description"><?php _e( 'Duration to display a single testimonial before displaying the next one. (in seconds)', 'client-and-product-testimonials' ); ?></p>
		</label>	
		
		<!-- 
			** To Do at a later point.. **
		<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Display Controls:' ); ?>
			<select class="widefat" name="<?php echo $this->get_field_name( 'style' ); ?>" id="<?php echo $this->get_field_id( 'style' ); ?>">
				<option value="style-1" <?php selected( $style, 'style-1' ); ?>><?php _e( 'Style 1', 'client-and-product-testimonials' ); ?></option>
				<option value="style-2" <?php selected( $style, 'style-2' ); ?>><?php _e( 'Style 2', 'client-and-product-testimonials' ); ?></option>
				<option value="style-3" <?php selected( $style, 'style-3' ); ?>><?php _e( 'Style 3', 'client-and-product-testimonials' ); ?></option>
				<option value="style-4" <?php selected( $style, 'style-4' ); ?>><?php _e( 'Style 4', 'client-and-product-testimonials' ); ?></option>
				<option value="style-5" <?php selected( $style, 'style-5' ); ?>><?php _e( 'Style 5', 'client-and-product-testimonials' ); ?></option>
			</select>
			<p class="description"><?php _e( 'Select the style for this slider.', 'client-and-product-testimonials' ); ?></p>
		</label>
		-->
		
		<?php 
			// Check if EDD is active, if so display custom options
			if( capt_is_edd_active() ) {
				?>
					<label for="<?php echo $this->get_field_id( 'edd_override' ); ?>"><?php _e( 'Override Easy Digital Downloads:' ); ?>
						<select class="widefat" name="<?php echo $this->get_field_name( 'edd_override' ); ?>" id="<?php echo $this->get_field_id( 'edd_override' ); ?>">
							<option value="0" <?php selected( $edd_override, "0" ); ?>><?php _e( 'False', 'client-and-product-testimonials' ); ?></option>
							<option value="1" <?php selected( $edd_override, "1" ); ?>><?php _e( 'True', 'client-and-product-testimonials' ); ?></option>
						</select>
						<p class="description"><?php printf( __( "On any Easy Digital Downloads product page, override the testimonial set above and only display testimonials associated with that product. Set to false to disable the override and display your specific testimonials. %s", "client-and-product-testimonials" ), "<br />(<code>" . htmlspecialchars("is_single() && $" . "post->post_type == 'download'") . "</code>)" ); ?></p>
					</label>
				<?php
			}
			// Check if WooCommerce is active, if so display custom options
			if( capt_is_woocommerce_active() ) {
				?>
					<label for="<?php echo $this->get_field_id( 'wooc_override' ); ?>"><?php _e( 'Override WooCommerce:' ); ?>
						<select class="widefat" name="<?php echo $this->get_field_name( 'wooc_override' ); ?>" id="<?php echo $this->get_field_id( 'wooc_override' ); ?>">
							<option value="0" <?php selected( $wooc_override, "0" ); ?>><?php _e( 'False', 'client-and-product-testimonials' ); ?></option>
							<option value="1" <?php selected( $wooc_override, "1" ); ?>><?php _e( 'True', 'client-and-product-testimonials' ); ?></option>
						</select>
						<p class="description"><?php printf( __( "On any WooCommerce product page, override the testimonial set above and only display testimonials associated with that product. Set to false to disable the override and display your specific testimonials. %s", "client-and-product-testimonials" ), "<br />(<code>" . htmlspecialchars("is_product()") . "</code>)" ); ?></p>
					</label>
				<?php
			}
}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = $new_instance['title'];
		$instance['capt_taxonomy'] = ( isset( $new_instance['capt_taxonomy'] ) ) ? $new_instance['capt_taxonomy'] : '-1';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? $new_instance['limit'] : '5';
		$instance['hide_controls'] = ( isset( $new_instance['hide_controls'] ) ) ? 1 : 0;
		$instance['animation'] = $new_instance['animation'];
		$instance['automatic'] = ( isset( $new_instance['automatic'] ) ) ? 1 : 0;
		$instance['style'] = $new_instance['style'];
		$instance['duration'] = ( isset( $new_instance['duration'] ) ) ? $new_instance['duration'] : 5;
		if( capt_is_edd_active() ) {
			$instance['edd_override'] = $new_instance['edd_override'];
		}
		return $instance;
	}
} // Class capt_fade_slider_widget ends here

function hide_widget_preloader() {
	?>
		<style>
			.testimonial-fade-widget .testimonial-loader {
				display: none;
			}
		</style>
	<?php
}
add_action( 'wp_print_scripts', 'hide_widget_preloader' );

// Register and load the widget
function capt_fade_slider_load_widget() {
	register_widget( 'capt_fade_slider_widget' );
}
add_action( 'widgets_init', 'capt_fade_slider_load_widget' );

?>