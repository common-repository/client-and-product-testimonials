<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Client_and_Testimonial_Options {
	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'cat_options'; // "Client and Testimonial Options'
	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'cat_option_metabox';
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = 'Client and Product Testimonial Options';
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Settings', 'client-and-product-testimonials' );
	}
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
		add_action( 'print_updated_settings_notice', array( $this, 'render_updated_settings_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_capt_options_styles' ) );
		/*
		*	Add / Sanitize our custom schema_datatype/post_type_selection field
		*/
		add_action( 'cmb2_render_schema_datatype_select', array( $this, 'cmb2_render_callback_for_schema_datatype_select' ), 10, 5 );
	}
	/**
	*	Enqueue select2/options page styles/scripts
	*	@sine 0.1
	*/
	public function enqueue_capt_options_styles( $hook ) {
		if( $hook == 'testimonial_page_cat_options' ) {
			wp_enqueue_script( 'select2.min.js', Client_Product_Testimonials_URL . 'lib/admin/options/js/select2.min.js', array( 'jquery' ), 'all', true );
			wp_enqueue_script( 'select2-init.js', Client_Product_Testimonials_URL . 'lib/admin/options/js/select2-init.js', array( 'select2.min.js' ), 'all', true );
			wp_enqueue_style( 'select2.min.css', Client_Product_Testimonials_URL . 'lib/admin/options/css/select2.css' );
		}
	}
	public function render_updated_settings_notice() {	
		if( isset( $_POST['nonce_CMB2phpcat_option_metabox'] ) && ( isset( $_POST['submit-cmb'] ) && $_POST['submit-cmb'] == 'Save' ) ) {
			?>
				<div class="updated">
					<p><?php _e( 'Client and Product Testimonials settings successfully updated.', 'client-and-product-testimonials' ); ?></p>
				</div>
			<?php
		}
	}
	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}
	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page( 'edit.php?post_type=testimonial', $this->title, $this->title, apply_filters( 'capt_admin_menu_capabalities', 'manage_options' ), $this->key, array( $this, 'render_cat_options_page' ) );		// Include CMB CSS in the head to avoid FOUT
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function render_cat_options_page() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<?php do_action( 'print_updated_settings_notice' ); ?>
			<h2><?php _e( 'Client and Product Testimonials Lite Settings', 'client-and-product-testimonials' ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key, array( 'cmb_styles' => false ) ); ?>
		</div>
		<?php
	}
	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {
		
		// print_r( get_option( 'cat_options' ) );
		
		$prefix = '_client_and_product_testimonial_';
		
		$regenerate_plugin_images_notice = $this->get_regen_image_notification();
		
		$options_page_links = '<hr /><a href="https://www.evan-herman.com" target="_blank"><img src="https://www.evan-herman.com/wp-content/plugins/timeline-express-pro/images/evan_herman_logo.png" alt="EH Dev Shop" title="EH Dev Shop Logo" style="margin-right:1em;width:50px;margin-top:10px;"></a><a href="http://captpro.evan-herman.com/documentation/" target="_blank" class="button-secondary" style="margin:.5em 0;" title="' . __( 'Documentation', 'client-and-product-testimonial-pro' ) . '">' . __( 'Documentation', 'client-and-product-testimonials' ) . '</a><hr />';
	
		$cmb = new_cmb2_box( array(
			'id'      => $this->metabox_id,
			'hookup'  => false,
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );
		// Set our CMB2 fields
			// client or product taxonomy
			$cmb->add_field( array(
				'name'    => __( 'Clients or Products', 'client-and-product-testimonials' ),
				'desc'    => __( 'Select which type of testimonial you want to display.', 'client-and-product-testimonials' ),
				'id'      => $prefix . 'taxonomy',
				'type'    => 'select',
				'options' => array(
					'testimonial_clients' => 'Clients',
					'testimonial_products' => 'Products'
				),
				'before_row' => $options_page_links,
			) );
			// Star Ratings
			$cmb->add_field( array(
				'name'    => __( 'Star Ratings', 'client-and-product-testimonials' ),
				'desc'    => __( 'When clients submit new testimonials, should they be allowed to leave a star rating as well?', 'client-and-product-testimonials' ),
				'id'      => $prefix . 'enable_star_rating',
				'type'    => 'select',
				'options' => array(
					'0' => 'Disabled',
					'1' => 'Enabled'
				),
			) );
			// Hide Empty Stars
			$cmb->add_field( array(
				'name'    => __( 'Empty Star Visibility', 'client-and-product-testimonials' ),
				'desc'    => __( 'Toggle the visibility of empty stars on the front end of the site. (eg: a 3 star rating will show 3 filled stars instead of 3 filled stars and 2 empty stars)', 'client-and-product-testimonials' ),
				'id'      => $prefix . 'hide_empty_stars',
				'type'    => 'select',
				'options' => array(
					'0' => __( 'Visible', 'client-and-product-testimonials' ),
					'1' => __( 'Hidden', 'client-and-product-testimonials' ),
				),
			) );
			// Hide Empty Stars
			$cmb->add_field( array(
				'name'    => __( 'Fallback Image', 'client-and-product-testimonials' ),
				'desc'    => __( "If a testimonial doesn't have a profile photo assigned to it, should the fallback image be used?", 'client-and-product-testimonials' ),
				'id'      => $prefix . 'use_fallback_image',
				'type'    => 'select',
				'options' => array(
					'1' => __( 'Use Fallback Image', 'client-and-product-testimonials' ),
					'0' => __( 'Exclude Profile Photo', 'client-and-product-testimonials' ),
				),
			) );
			// No Profile Fallback Image
			$cmb->add_field( array(
				'name'    => __( 'No Profile Photo', 'client-and-product-testimonials' ),
				'desc'    => __( 'Select a fallback image to display when the user has not set a profile photo.', 'client-and-product-testimonials' ),
				'id'      => $prefix . 'no_photo_fallback',
				'type'    => 'file',
				'default' => '',
			) );
			
			
			/**************
			Preloader
			*************/
			// Create an empty array to house our preloaders in $key => $value fashion
			$preloader_selection = array();
			$preloaders = glob( Client_Product_Testimonials_Path . 'lib/images/preloaders/*.gif' );
			$preloader_count = count( $preloaders );
			$x = 1;
			// $preloader_count == number of preloaders found in the preloaders directory
			// ~/plugins/client-and-product-tesetimonials/lib/images/preloaders/*.gif
			while( $x <= $preloader_count ) {
				$preloader_selection[$x] = 'Style ' . $x;
				$x++;
			}
			
			// get our options
			$options = Client_and_Product_Testimonials::get_cat_options();
			$preloader_number = ( isset( $options['_client_and_product_testimonial_preloader'] ) ) ? $options['_client_and_product_testimonial_preloader'] : '1';
			
			$cmb->add_field( array(
				'name'    => __( 'Preloader', 'client-and-product-testimonials' ),
				'desc'    => sprintf( __( 'Select the pre-loader to use on the testimonial sliders. (tip: to use a custom preloader, see the following %s)', 'client-and-product-testimonials' ), '<a href="#">' . __( 'knowledge base article', 'client-and-product-testimonials' ) . '</a>' ),
				'id'      => $prefix . 'preloader',
				'type'    => 'select',
				'default' => '1',
				'options' => $preloader_selection,
				'after_row' => '<div class="cmb-row">
										<div class="cmb-th">
											<label for="_client_and_product_testimonial_preloader">' . __( 'Preloader Preview', 'client-and-product-testimonials' ) . '</label>
										</div>
										<div class="cmb-td">
											<div class="capt-preloader-preview" style="width:50px;">
												<img src="' . Client_Product_Testimonials_URL . 'lib/images/preloaders/Preloader_' . $preloader_number . '.gif" title="Preloader ' . $preloader_number . '" />
											</div>
										</div>
									</div>',											
			) );		
			

			// Schema Markup Option
			$cmb->add_field( array(
				'name'    => __( 'Schema Datatype', 'client-and-product-testimonials' ),
				'desc'    => sprintf( __( 'For more info, see: %s and %s.', 'client-and-product-testimonials' ), '<a href="http://schema.org/" target="_blank">schema.org</a>', '<a href="http://schema.org/docs/full.html" target="_blank">' . __( 'Schema Datatypes', 'client-and-product-testimonials' ) . '</a>' ),
				'id'      => $prefix . 'schma_datatype',
				'type'    => 'schema_datatype_select',
				'before_row' => '<hr /><h3>' . sprintf( __( 'Rich Snippet Schema Markup %s', 'client-and-product-testimonials' ), '<small>(' . __( 'pro', 'client-and-product-testimonials' ) . ')</small>' ) . '</h3>',
			) );
			
			
	}
	
	/*
	*	Custom Schema Data Type select field
	*	@since 0.1
	*/
	public function cmb2_render_callback_for_schema_datatype_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		return include_once( Client_Product_Testimonials_Path . 'lib/admin/options/schema_datatype_select.php' );
	}
	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
	
	/*
	*	Check if the recommended plugin is installed, if so return a button to regenerate the thumbnails
	*	else, let's display the recommended plugin notice
	*/
	public function get_regen_image_notification() {
		if( capt_is_regen_thumbnails_active() ) {
			$notice = '<a class="button-secondary" style="margin-top:.5em;" href="' . esc_url( admin_url( 'tools.php?page=regenerate-thumbnails' ) ) . '" title="' . __( 'Regenerate Thumbnails', 'client-and-product-testimonials' ) . '">' . __( 'Regenerate Thumbnails', 'client-and-product-testimonials' ) . '</a>';
		} else {
			$notice = sprintf( __( 'Note: Everytime you change this option you should regenerate the testimonial thumbnails. Recommended Plugin: %s', 'client-and-product-testimonials' ), '<a href="' . esc_url( admin_url() . 'plugin-install.php?tab=search&type=term&s=Regenerate+Thumbnails+Viper007Bond+Allows+you+to' ) . '" title="' . __( 'Regenerate Thumbnails by Viper007Bond', 'client-and-product-testimonials' ) . '">Regenerate Thumbnails</a>' );
		}
		return $notice;
	}
	
}

/*
*	Add custom text_number field for image_size option
*	@since 0.1
*/
function cmb2_render_callback_for_text_number( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    echo $field_type_object->input( 
		array( 
			'type' => 'number',
			'min' => 100,
			'max' => 999,
			'class' => 'cmb2-text-small',
		) 
	);
}
add_action( 'cmb2_render_text_number', 'cmb2_render_callback_for_text_number', 10, 5 );

function cmb2_sanitize_text_number_callback( $override_value, $value ) {
    // not a number?
    if ( ! is_numeric( $value ) ) {
        // Empty the value
        $value = '100';
    }
    return $value;
}
add_filter( 'cmb2_sanitize_text_number', 'cmb2_sanitize_text_number_callback', 10, 2 );

/**
 * Helper function to get/return the cat_Admin object
 * @since  0.1.0
 * @return cat_Admin object
 */
function cat_admin() {
	static $object = null;
	if ( is_null( $object ) ) {
		$object = new Client_and_Testimonial_Options();
		$object->hooks();
	}
	return $object;
}

/*
*	Print inline script to toggle the preloader image preview
*	@since 0.1.0
*/
function toggle_preloader_preview_image_script() {
	$screen = get_current_screen();
	if( $screen->base == 'testimonial_page_cat_options' ) {
		// enqueue the preloader preview scripts
		wp_enqueue_script( 'toggle-preloader-preview.js', Client_Product_Testimonials_URL . 'lib/admin/options/js/toggle-preloader-preview.js', array( 'jquery' ), 'all', true );
		wp_localize_script( 'toggle-preloader-preview.js', 'localized_data', array(
			'preloader_directory' => Client_Product_Testimonials_URL . 'lib/images/preloaders/Preloader_', // we'll append the number + .gif to complete the image
			'preloader_title' => __( 'Preloader', 'client-and-product-testimonials' ),
		) );
		// enqueue capt options styles - for CMB2 overrides
		wp_enqueue_style( 'client-and-product-testimonial-admin-styles', Client_Product_Testimonials_URL . 'lib/admin/css/cat-styles.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'toggle_preloader_preview_image_script' );

// Get it started
cat_admin();