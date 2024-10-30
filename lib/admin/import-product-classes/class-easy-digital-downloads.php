<?php
/*
*	Class to handle the importing of Easy Digital Download Products
*	- If the Testimonial taxonomy is set to products, we create a button to import Easy Digital Download products to the 'product' taxonomy
*	@since 0.1
*	Compiled by: Evan Herman
*/
class Import_Easy_Digital_Download_Taxonomies {
	
	function __construct() {
		// Add term page
		add_action( 'testimonial-products_add_form_fields', array( $this, 'add_import_edd_product_button' ), 10, 2 );
		// Import the products, and re-direct the user
		add_action( 'admin_init', array( $this, 'import_edd_and_redirect_user' ), 11 );
		// Add custom admin notice after an import
		add_action( 'admin_notices', array( $this, 'display_custom_edd_import_notice' ) );
	}
	
	/*
	*	Display a custom success/error message at the top of our taxonomy page
	*	after an import attempt
	*	@since 0.1
	*/
	public function display_custom_edd_import_notice() {
		$screen = get_current_screen();
		if( isset( $screen->base ) && $screen->base == 'edit-tags' ) {
			if( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'testimonial-products' ) {
				
				/* Error Message */
				if( isset( $_GET['capt_edd_import'] ) && isset( $_GET['error'] ) ) {
					// no items were impoted, let the user know
					if( $_GET['error'] == 'imported_none' ) {
						?>
						<div class="update-nag capt-product-import-notice">
							<p><?php _e( 'It looks like all of your Easy Digital Downloads products already exist. No products were imported.', 'client-and-product-testimonials' ); ?></p>
						</div>
						<?php
					} else {
						// retreive the error from the URL and decode it
						$error = urldecode( $_GET['error'] );
						?>
						<div class="error">
							<p><?php $error; ?></p>
						</div>
						<?php
					}
				}
				
				/* Success Message */
				if( isset( $_GET['capt_edd_import'] ) && $_GET['capt_edd_import'] == '1' ) {
					$imported_count = (int) $_GET['imported_count'];
					?>
					<div class="updated">
						<p><?php printf( _n( '%s Easy Digital Download product successfully imported.', '%s Easy Digital Download products successfully imported.', $imported_count ), $imported_count ); ?></p>
					</div>
					<?php
				}
				
			}
		}
	}
	
	/*
	*	Add custom 'Import Easy Digital Download Products' button to the 'product' taxonomy page
	*	@since 0.1
	*/
	public function add_import_edd_product_button() {
		/*
		*	Check if EDD is active, if not abort
		*	@since 0.1
		*/
		if( ! is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
			return;
		}
		$total_downloads =  wp_count_posts( 'download' );
		if( $total_downloads && isset( $total_downloads->publish ) ) {
			$total_downloads = $total_downloads->publish;
			// this will add the custom meta field to the add new term page
			$disabled_button = ( $total_downloads == 0 ) ? 'disabled="disabled"' : '';
			$button_title = ( $total_downloads == 0 ) ?  'title="' . __( 'Please create some downloads.', 'client-and-product-testimonials' ) . '"' : '';
			$notice = ( $total_downloads == 0 ) ?  '<p>' . __( 'You need to create some Easy Digital Download products before you can import anything here.', 'client-and-product-testimonials' ) . '</p>' : '';
			?>
			<div class="form-field">
				<form id="import-edd">
					<?php
						/* Security Nonce! */
						wp_nonce_field( 'import_edd', 'import_edd_products' );
						$import_edd_url = esc_url(
							add_query_arg(
								array(
									'capt_action' => 'import_edd_products',
									'nonce' => wp_create_nonce( 'import_edd_products' )
								)
							)
						);
					?>
					<!-- Import EDD Downloads Button -->
					<a href="<?php echo $import_edd_url; ?>" class="import-edd-products-to-taxonomy button-secondary" <?php echo $button_title; ?> <?php echo $disabled_button; ?>>
						<object type="image/svg+xml" data="<?php echo Client_Product_Testimonials_URL . 'lib/images/edd-logo.svg'; ?>" class="edd-logo-in-button"></object> 
						<?php _e( 'Import Easy Digital Downloads Products', 'client-and-product-testimonials' ); ?>
					</a>
				</form>
				<?php echo $notice; ?>
			</div>
		<?php
		}
	}
	
	/*
	*	Import and redirect the user
	*
	*/
	public function import_edd_and_redirect_user() {
		if( isset( $_GET['capt_action'] ) && $_GET['capt_action'] == 'import_edd_products' ) {
			/* If the nonce is invalid */
			if( ! wp_verify_nonce( $_GET['nonce'], 'import_edd_products' ) ) {
				wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_edd_import=0&error=nonce' ) );
				exit;
			}
			/*
			*	Import the EDD products
			*	@since 0.1
			*/
			$edd_args = array(
				'post_type' => 'download',
				'post_status' => 'publish',
			);
			$edd_query = new WP_Query( apply_filters( 'capt_filter_edd_import_query_args', $edd_args ) );
			if( $edd_query->have_posts() ) {
				// initially set to 0
				$edd_imported = 0;
				while( $edd_query->have_posts() ) {
					$edd_query->the_post();
					// check that the term doesn't already exist
					if( ! get_term_by( 'slug', sanitize_title( get_the_title() ), 'testimonial-products' ) ) {	
						// Insert our term
						$new_product_term = wp_insert_term(
							get_the_title(),
							'testimonial-products',
							array( 
								'slug' => sanitize_title( get_the_title() )
							)
						);
						if( ! is_wp_error( $new_product_term ) ) {	
							// increase the impoted count
							$edd_imported++;
						} else {
							wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_edd_import=0&error=' . urlencode( $new_product_term->get_error_message() ) . '' ) );
							exit;
						}
					}
				}
			}
			if( $edd_imported == 0 ) {
				wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_edd_import=0&error=imported_none' ) );
				exit;
			}
			wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_edd_import=1&imported_count='. (int) $edd_imported ) );
			exit;
		}
	}
	
}
new Import_Easy_Digital_Download_Taxonomies;
/* End Import_Easy_Digital_Download_Taxonomies Class */

?>