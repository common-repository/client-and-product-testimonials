<?php
/*
#_________________________________________________ PLUGIN
Plugin Name: Client and Product Testimonials Lite
Plugin URI: https://www.evan-herman.com
Description: Easily create powerful client and product testimonials for your site.
Version: 1.0.1
Author: Evan Herman, EH Dev Shop
Author URI: https://www.evan-herman.com
License: GPL3
Text Domain: client-and-product-testimonials

#_________________________________________________ LICENSE
Copyright 2015 Evan Herman (email : evan.m.herman@gmail.com)
 *
 * 	Client and Product Testimonials Lite is free software: you can redistribute it and/or modify
 * 	it under the terms of the GNU General Public License as published by
 * 	the Free Software Foundation, either version 2 of the License, or
 * 	any later version.
 *
 * 	Client and Product Testimonials Lite is distributed in the hope that it will be useful,
 * 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * 	GNU General Public License for more details.
 *
 * 	You should have received a copy of the GNU General Public License
 *	along with Easy Forms for MailChimp. If not, see <http://www.gnu.org/licenses/>.
 *
 *	We at Daemon Lab Plugin embrace the open source philosophy on a daily basis. We donate company time back to the WordPress project,
 *	and constantly strive to improve the WordPress project and community as a whole. We eat, sleep and breath WordPress.
 *
 *	"'Free software' is a matter of liberty, not price. To understand the concept, you should think of 'free' as in 'free speech,' not as in 'free beer'."
 *	- Richard Stallman
*/

class Client_and_Product_Testimonials {
	
	public function __construct() {
		/* Define Global Constants */
		if( ! defined( 'Client_Product_Testimonials_Version') )						
			define( 'Client_Product_Testimonials_Version', '1.0' );
		if( ! defined( 'Client_Product_Testimonials_Path') )						
			define( 'Client_Product_Testimonials_Path', trailingslashit( plugin_dir_path(__FILE__) ) );
		if( ! defined( 'Client_Product_Testimonials_URL') )						
			define( 'Client_Product_Testimonials_URL', trailingslashit( plugin_dir_url(__FILE__) ) );
		/* Define Support Constants */
		if( ! defined( 'EH_DEV_SHOP_URL' ) ) 
			define( 'EH_DEV_SHOP_URL', 'https://www.evan-herman.com' );
		if( ! defined( 'Client_Product_Testimonials_Product_Name' ) ) 
			define( 'Client_Product_Testimonials_Product_Name', 'client-and-product-testimonials-pro' ); 	
		
		
		// store our options
		$options = $this->get_cat_options();
		
		/* 
		*	Include testimonial class
		*	Generates our testimonial post type and metaboxes
		*	@since 0.1
		*/
		include_once( Client_Product_Testimonials_Path .  'lib/admin/cpt/testimonial-cpt.php' );
		/*
		*	Include the Client and Product Testimonials options page
		*	@since 0.1
		*/
		include_once( Client_Product_Testimonials_Path . 'lib/admin/options/options-page.php' );
		/*
		*	Include the Client and Product Testimonials helper functions
		*	@since 0.1
		*/
		require_once( Client_Product_Testimonials_Path . 'lib/admin/capt-helpers.php' );
		
		// Register a custom image size
		add_image_size( 'testimonial-image', $options['_client_and_product_testimonial_image_size'], $options['_client_and_product_testimonial_image_size'], array( 'center', 'top' ) );
		
		// Register shortcodes
		add_action( 'init', array( $this, 'register_client_and_product_testimonial_shortcodes' ) );
		
		// Register widgets
		include_once( Client_Product_Testimonials_Path . 'lib/public/widgets/testimonial-fade-slider-widget.php' ); // testimonial fade slider widget
		include_once( Client_Product_Testimonials_Path . 'lib/public/widgets/testimonial-list-widget.php' ); // testimonial list widget
		
		// Include our custom TinyMCE button
		add_action('media_buttons_context', array( $this, 'add_capt_add_testimonial_button' ) );
		
		// Add custom plugin action links 
		add_filter( 'plugin_action_links_client-and-product-testimonials/client-and-product-testimonials.php', array( $this, 'capt_plugin_action_links' ) );
		
		// Activation script
		register_activation_hook( __FILE__, array( $this, 'capt_plugin_activate' ) );
				
		// Add menu page for 'Support'
		add_action( 'admin_menu', array( $this, 'register_capt_support_page' ) );
		
		// Custom 'Pending Review' admin notice
		add_action('admin_notices', array( $this, 'display_pending_review_admin_notice' ) );
		
		// Render an 'Import Products' button on the 'product' taxonomy page
		add_action( 'admin_init', array( $this, 'create_import_products_button_on_product_taxonony_page' ) );
		
		// Load this plugins text domain
		add_action( 'plugins_loaded', array( $this, 'capt_load_textdomain' ) );
		
		/*
		*	Custom content filter so we can parse content 
		*	without worrying about other plugins hooking in
		*/
		add_filter( 'capt_content', 'wptexturize' );
		add_filter( 'capt_content', 'convert_smilies' );
		add_filter( 'capt_content', 'convert_chars' );
		add_filter( 'capt_content', 'wpautop' );
		add_filter( 'capt_content', 'shortcode_unautop' );
		add_filter( 'capt_content', 'do_shortcode' );
		
		/** Activation Hooks (inside of capt-helpers.php) **/
		// the redirection
		add_action( 'admin_init' , array( $this, 'client_and_product_testimonials_activation_redirect' ) );
						
		/** Add a disclaimer to ensure that we let people know we are not endorsed/backed by MailChimp at all **/
		add_filter( 'admin_footer_text', array( $this, 'yikes_easy_forms_admin_disclaimer' ) );
		
		add_action( 'admin_head', array( $this, 'style_capt_footer_stars_on_tax_edit_page' ) );
	}
		
	/*
	*	Create custom 'Import' buttons on the taxonomy page
	*	@since 0.1
	*/
	public function create_import_products_button_on_product_taxonony_page() {
		if( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
			include_once( Client_Product_Testimonials_Path . 'lib/admin/import-product-classes/class-easy-digital-downloads.php' );
		}
		if( is_plugin_active( 'woocommerce/woocommerce.php' ) )  {
			include_once( Client_Product_Testimonials_Path . 'lib/admin/import-product-classes/class-woocommerce.php' );
		}
	}
	
	/*
	*	Register a custom 'support' page 
	*	@since 0.1
	*/
	public function register_capt_support_page() {
		// register the support page
		add_submenu_page( 
			'edit.php?post_type=testimonial',   //or 'options.php' 
			__( 'Upgrade to Pro', 'client-and-product-testimonials' ),
			__( 'Upgrade to Pro', 'client-and-product-testimonials' ) . ' <span class="dashicons dashicons-upload" style="font-size:16px;height:16px;width:16px;margin-left:1px;line-height:.8;"></span>',
			apply_filters( 'capt_admin_menu_capabalities', 'manage_options' ),
			'client-and-product-testimonial-upgrade',
			array( $this, 'client_and_product_testimonial_upgrade_to_pro' )
		);
	}
	
	/*
	*	Include the license and support page
	*	@since 1.0
	*/
	public function client_and_product_testimonial_upgrade_to_pro() {
		require_once Client_Product_Testimonials_Path . '/lib/admin/upgrade/upgrade-to-pro-page.php'; 
	}
	
	/*====================
	Activation Hooks
	@since		1.0
	====================*/	
			
		/*
		*	Upload a temporary placeholder to the media library,
		*	and assign it to the 'no profile photo' option & 'no profile photo id' option
		*	@since 0.1
		*/
		public static function capt_plugin_activate() {
			global $wpdb;
			$options = get_option( 'cat_options', self::get_cat_options() );
			// if the option has been previously set, let's abort
			if( $options['_client_and_product_testimonial_no_photo_fallback'] != '' ) {
				$url = esc_url( Client_Product_Testimonials_URL . '/lib/images/no-profile.jpg' );
				// only re-add the image if it doesn't already exist
				// defined inside capt-helpers.php (returns true/false)
				if( ! capt_does_fallback_image_already_exist() ) {
					$no_image_placeholder_description = __( "Client and Product Testimonial - No Image", "client-and-product-testimonials" );
					// side load the image from our images directory
					$image_src = media_sideload_image( $url, '', $no_image_placeholder_description, 'src' );
					// grab the image id from the database
					$image_id = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_src )); 
					// update the two options
					$options['_client_and_product_testimonial_no_photo_fallback'] = $image_src;
					$options['_client_and_product_testimonial_no_photo_fallback_id'] = $image_id[0];
				}
			}
			// check for the activation date
			if( ! isset( $options['_client_and_product_testimonial_activation_date'] ) || $options['_client_and_product_testimonial_activation_date'] == '' ) {
				$options['_client_and_product_testimonial_activation_date'] = strtotime( 'now' ); // store the current date in strtotime format
				$options['_client_and_product_testimonial_2_week_notice'] = 0; // set to 0, so we know it hasn't been dismissed yet
			}
			update_option( 'cat_options' , $options );
			// add our option so we know to redirect
			add_option( 'client_and_product_testimonials_activation_redirect', true );
		}
		
		/*
		*	Redirect the user to the settings page on initial activation
		*	@since 0.1
		*/
		public function client_and_product_testimonials_activation_redirect() {
			// Redirect the user to the license page, if there was no previous license key stored
			if ( trim( get_option( 'client_and_product_testimonials_license_key', '' ) ) == '' && get_option( 'client_and_product_testimonials_activation_redirect' , false ) ) {
				delete_option( 'client_and_product_testimonials_activation_redirect' );
				// redirect to settings page
				wp_redirect( esc_url_raw( admin_url( 'edit.php?post_type=testimonial&page=cat_options' ) ) );
				exit;
			}
		}
		
	/*=======================
		End activation hooks
	=======================*/

	/*
	*	Register our public shortcodes
	*	@since 0.1
	*/
	public function register_client_and_product_testimonial_shortcodes() {		
		// List
		include_once( Client_Product_Testimonials_Path . 'lib/public/shortcodes/testimonial-list-shortcode.php' );
		// Sliders
			// Fade Slider
			include_once( Client_Product_Testimonials_Path . 'lib/public/shortcodes/testimonial-fade-slider.php' );
		// Full Width Testimonial Section
		include_once( Client_Product_Testimonials_Path . 'lib/public/shortcodes/testimonial-full-width.php' );
	}
	
	/*
	*	Retrieve Client and Product Testimonial Options
	*	@since 0.1
	*/
	public static function get_cat_options() {
		$options = get_option( 'cat_options', array(
			'_client_and_product_testimonial_image_size' => '220',
			'_client_and_product_testimonial_excerpt_length' => '125',
			'_client_and_product_testimonial_taxonomy' => 'testimonial_clients',
			'_client_and_product_testimonial_enable_star_rating' => '1',
			'_client_and_product_testimonial_hide_empty_stars' => '1',
			'_client_and_product_testimonial_use_fallback_image' => '1',
			'_client_and_product_testimonial_no_photo_fallback' => '',
			'_client_and_product_testimonial_no_photo_fallback_id' => '',
			'_client_and_product_testimonial_schma_datatype' => '',
			'_client_and_product_testimonial_activation_date' => '',
			'_client_and_product_testimonial_2_week_notice' => '0',
			'_client_and_product_testimonial_preloader' => '1',
		) );
		return $options;
	}
	
	/*	
	*	Custom Page/Post Buttons
	*	@since 0.1
	*/
	public static function add_capt_add_testimonial_button( $context ) {
		global $post;
		if( isset( $post ) && $post->post_type != 'testimonial' ) {
			// Add our Custom Button
			$context .= '<a href="#TB_inline?width=1500&amp;inlineId=capt-shortcode-generator&amp;width=753&amp;height=808" onclick="getVisibleContainerHeight();return false;" id="insert-media-button" class="add-testimonial-button thickbox" title="' . __( 'Testimonial Shortcode Generator', 'client-and-product-testimonials' ) . '"><button type="button" id="" class="button capt-shortcode-generator-button"><span class="dashicons dashicons-format-quote"></span> ' . __( 'Add Testimonial', 'client-and-product-testimonials' ) . '</button></a>';
			// Thickbox Shortcode Generator
			include_once( Client_Product_Testimonials_Path . 'lib/admin/shortcode-generator/testimonial-shortcode-generator.php' );
		}
		return $context;
	}
	
	/*
	*	Additional Plugin Action Links
	*	@since 0.1
	*/
	public static function capt_plugin_action_links( $links ) {
	   $links[] = '<a href="'. esc_url( get_admin_url( null, 'edit.php?post_type=testimonial&page=cat_options' ) ) .'">' . __( 'Settings', 'client-and-product-testimonials' ) . '</a>';
	   $links[] = '<a href="' . esc_url( 'https://www.evan-herman.com/wordpress/plugins/' ) . '" target="_blank">' . __( 'More Plugins by EH Dev Shop' , 'client-and-product-testimonials' ) . '</a>';
	   return $links;
	}
	
	/*
	*	Post is in 'Draft' mode (ie: pending review)
	*	let's display a custom admin message
	*	@since 0.1
	*/
	public function display_pending_review_admin_notice() {
		global $post;
		
		$options = self::get_cat_options();
		
		// Check if no_bug is set and update the necessary options
		if( isset( $_GET['capt_no_bug'] ) && $_GET['capt_no_bug'] == 1 ) {
			$options['_client_and_product_testimonial_2_week_notice'] = 1;
			update_option( 'cat_options' , $options );
		}	
		
		if( $this->is_capt_edit_page() ) { // defined below
			if( isset( $post ) && $post->post_type == 'testimonial' && $post->post_status == 'pending' ) {
				echo '<div class="notice notice-warning">
				   <p><span class="dashicons dashicons-warning"></span> ' . __( 'This testimonial is currently pending a review. It will not appear on your site until you publish it.', 'client-and-product-testimonials' ) . '</p>
				</div>';
			}
		}
		/* If on the pending review table listing page */
		if( ! $this->is_capt_edit_page() ) {
			if( ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'testimonial' ) && ( isset( $_GET['post_status'] ) && $_GET['post_status'] == 'pending' ) ) {
				echo '<div class="notice notice-warning">
				   <p>' . __( 'The following testimonials are pending a review. They will not appear on your site until they are reviewed and published.', 'client-and-product-testimonials' ) . '</p>
				   <p>' . sprintf( __( 'With the %s, your customers can submit testimonials for your products or business through a front end form, which you can then manage below.', 'client-and-product-testimonials' ), '<a href="http://captpro.evan-herman.com/" target="_blank" title="' . __( 'Client and Product Testimonials Pro', 'client-and-product-testimonials' ) . '">pro version</a>' ) . '</p>
				   <p><a href="http://captpro.evan-herman.com/" class="button-secondary" target="_blank" title="' . __( 'Upgrade Now', 'client-and-product-testimonials' ) . '">' . __( 'Upgrade Now', 'client-and-product-testimonials' ) . '</a>&nbsp;<a href="http://captpro.evan-herman.com/examples/testimonial-submission-form/" class="button-secondary" target="_blank" title="' . __( 'Example', 'client-and-product-testimonials' ) . '">' . __( 'Example', 'client-and-product-testimonials' ) . '</a></p>
				</div>';
			}
		}
		/* Display our 2 week usage notice */
		if( ! isset( $options['_client_and_product_testimonial_2_week_notice'] ) || $options['_client_and_product_testimonial_2_week_notice'] == 0 ) {
			if( current_user_can( 'manage_options' ) ) {	
				if( isset( $options['_client_and_product_testimonial_activation_date'] ) && $options['_client_and_product_testimonial_activation_date'] ) {
					$activation_date = $options['_client_and_product_testimonial_activation_date'];
					if( $activation_date > strtotime('+14 days') ) {
						?>
							<div class="notice notice-warning">
								<img src="http://captpro.evan-herman.com/wp-content/uploads/2015/09/cropped-capt-star-site-logo.png" width="40" style="float:left;margin:5px 5px 0 0;">
								<p>
									<?php _e( "Hey! It looks like you've been enjoying <strong>Client and Product Testimonials Lite</strong> for 2 weeks now. We would love it if you could leave us a review to let us know how were doing. Reviews provide us with feedback to grow and improve the plugin. If you're really loving the plugin, please consider sharing your experience witht others!", "client-and-product-testimonial-pro" ); ?>
								</p>
								<section class="button-container"> 
									<a href="https://wordpress.org/" target="_blank" class="button-secondary" style="margin-right:5px;">
										<span class="dashicons dashicons-star-filled" style="color:goldenrod;font-size:15px;line-height:1.7;"></span><?php _e( 'Leave A Review', 'client-and-product-testimonial-pro' ); ?>
									</a> 
									<?php
										$message = urlencode( __( "I love Client and Product Testimonials Lite! So simple, yet so powerful. Give it a try - http://j.mp/capt-pro #WordPress @EvanMHerman", "client-and-product-testimonial-pro" ) );
										$twitter_url = esc_url(
											"https://twitter.com/intent/tweet?text=" . $message
										);
									?>
									<a class="button-secondary" style="margin-right:5px;" href="<?php echo $twitter_url; ?>" title="<?php _e( 'Tweet it!' , 'yikes-inc-easy-mailchimp-extender' ); ?>" target="_blank">
										<span class="dashicons dashicons-twitter" style="color:#55acee;font-size:15px;line-height:1.7;"></span> <?php _e( 'Tweet About It', 'client-and-product-testimonial-pro' ); ?>
									</a>
									<a href="<?php echo esc_url( 'https://www.evan-herman.com/wordpress/plugins/' ); ?>" target="_blank" class="button-secondary" style="margin-right:5px;">
										<span class="dashicons dashicons-upload" style="color:#59B300;font-size:15px;line-height:1.7;"></span><?php _e( 'View Other Plugins', 'client-and-product-testimonial-pro' ); ?>
									</a>
									<a href="<?php echo esc_url_raw( add_query_arg( array( 'capt_no_bug' => 1 ) ) ); ?>" class="button-secondary" style="margin-right:5px;">
										<span class="dashicons dashicons-no-alt" style="color:rgba(202, 78, 78, 0.78);font-size:15px;line-height:1.7;"></span><?php _e( 'Dismiss', 'client-and-product-testimonial-pro' ); ?>
									</a>
								</section>
								<br />
							</div>
						<?php
					}
				}
			}
		}

	}
	
	/*
	*	Custom function to check if were on an edit/new page
	*	@since 0.1
	*/
	public function is_capt_edit_page($new_edit = null){
		global $pagenow;
		//make sure we are on the backend
		if (!is_admin()) 
			return false;

		if($new_edit == "edit")
			return in_array( $pagenow, array( 'post.php',  ) );
		elseif($new_edit == "new") //check for new post page
			return in_array( $pagenow, array( 'post-new.php' ) );
		else //check for either new or edit
			return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}

	/*
	 * Load plugin 'Client and Product Testimonial' text domain/translation files.
	 * @since 0.1
	*/
	public function capt_load_textdomain() {
		load_plugin_textdomain( 'client-and-product-testimonials', false, Client_Product_Testimonials_Path . 'languages' ); 
	}
			
	/*
	* timeline_express_sanitize_license()
	* save license key function
	* since @v1.0
	*/
	public function client_and_product_testimonial_sanitize_license( $new ) {
		$old = get_option( 'client_and_product_testimonials_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'client_and_product_testimonials_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}	
	
	/**
	 *	Add a disclaimer to the admin footer for all YIKES pages to ensure that users understand there is no coorelation between this plugin and MailChimp. 
	 *	
	 * @since        1.0
	 * @param       string   $footer_text The existing footer text
	 * @return      string
	 */
	public function yikes_easy_forms_admin_disclaimer( $footer_text ) {
		$screen = get_current_screen();
		if( isset( $screen ) && isset( $screen->post_type ) ) {
			if ( $screen->post_type == 'testimonial' ) {
				$capt_footer_text = sprintf( '<em>' . __( 'This is a %s plugin created and maintained by %s. If you are enjoying it, please consider %s for additional features. A huge thank you from the dev team!', 'client-and-product-testimonials' ) . '</em>', '<strong>free</strong>', '<a href="https://www.Evan-Herman.com" target="_blank" title="EH Dev Shop">EH Dev Shop</a>', '<a href="http://captpro.evan-herman.com" target="_blank" title="Client and Product Testimonial Pro">upgrading to the pro version</a>', ' <span class="dashicons dashicons-star-filled capt-footer-text-star"></span><span class="dashicons dashicons-star-filled capt-footer-text-star"></span><span class="dashicons dashicons-star-filled capt-footer-text-star"></span><span class="dashicons dashicons-star-filled capt-footer-text-star"></span><span class="dashicons dashicons-star-filled capt-footer-text-star"></span> ' );
				return $capt_footer_text;
			} else {
				return $footer_text;
			}
		}
	}
	
	/*
	*	Simply style the stars in the footer text, on the edit taxonomy page
	*	- this is needed since cat-style.css doesn't load on the edit tax page
	*	@since 1.0
	*/
	function style_capt_footer_stars_on_tax_edit_page() {
		$screen = get_current_screen();
		if( isset( $screen ) && isset( $screen->id ) ) {
			if( $screen->id == 'edit-testimonial-clients' || $screen->id == 'edit-testimonial-products' ) {
				?>
					<style>
						/* CAPT footer stars */
						.capt-footer-text-star {
							font-size: 14px;
							height: 14px;
							width: 14px;
							color: goldenrod;
							margin-top: 3px;
						}
						/* Fix EDD button icon alignment */
						.edd-logo-in-button {
							float: left;
							width: 18px;
							padding-right: 5px;
							margin-top: 3px;
						}
						/* Fix WooCommerce button icon alignment */
						.woocommerce-logo-in-button {
							float: left;
							width: 18px;
							padding-right: 5px;
							margin-top: 8px;
						}
						/* Adjust the update nag */
						.capt-product-import-notice {
							width: 95%;
							margin: 5px 15px 2px;
							padding: 1px 12px;
							box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
							-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
						}
					</style>
				<?php
			}
		}	
	}
		
}
new Client_and_Product_Testimonials;

/*
* Shortcodes Available:
*
*	[testimonial-grid]
* 	[testimonial-list]
*	[testimonial-submission-form]
*	[testimonial-full-width]
*	[testimonial-aggregate-reviews]
*	Sliders -
* 		[testimonial-fade-slider] - Flexslider
*		[testimonial-thumbnail-slider] - Caroufredsel
*/