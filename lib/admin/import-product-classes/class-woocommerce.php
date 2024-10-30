<?php
/*
*	Class to handle the importing of Easy Digital Download Products
*	- If the Testimonial taxonomy is set to products, we create a button to import Easy Digital Download products to the 'product' taxonomy
*	@since 0.1
*	Compiled by: Evan Herman
*/
class Import_WooCommerce_Taxonomies {
	
	function __construct() {
		// Add term page
		add_action( 'testimonial-products_add_form_fields', array( $this, 'add_import_woocommerce_product_button' ), 10, 2 );
		// Import the products, and re-direct the user
		add_action( 'admin_init', array( $this, 'import_wooc_and_redirect_user' ), 11 );
		// Add custom admin notice after an import
		add_action( 'admin_notices', array( $this, 'display_custom_woocommerce_import_notice' ) );
	}
	
	/*
	*	Display a custom success/error message at the top of our taxonomy page
	*	after an import attempt
	*	@since 0.1
	*/
	public function display_custom_woocommerce_import_notice() {
		$screen = get_current_screen();
		if( isset( $screen->base ) && $screen->base == 'edit-tags' ) {
			if( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'testimonial-products' ) {
				
				/* Error Message */
				if( isset( $_GET['capt_wooc_import'] ) && isset( $_GET['error'] ) ) {
					// no items were impoted, let the user know
					if( $_GET['error'] == 'imported_none' ) {
						?>
						<div class="update-nag capt-product-import-notice">
							<p><?php _e( 'It looks like all of your WooCommerce products already exist. No products were imported.', 'client-and-product-testimonials' ); ?></p>
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
				if( isset( $_GET['capt_wooc_import'] ) && $_GET['capt_wooc_import'] == '1' ) {
					$imported_count = (int) $_GET['imported_count'];
					?>
					<div class="updated">
						<p><?php printf( _n( '%s WooCommerce product successfully imported.', '%s WooCommerce products successfully imported.', $imported_count ), $imported_count ); ?></p>
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
	public function add_import_woocommerce_product_button() {
		/*
		*	Check if EDD is active, if not abort
		*	@since 0.1
		*/
		if( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}	
		$total_wooc_products =  wp_count_posts( 'product' );
		if( $total_wooc_products && isset( $total_wooc_products->publish ) ) {
			$total_wooc_products = $total_wooc_products->publish;
			// this will add the custom meta field to the add new term page
			$disabled_button = ( $total_wooc_products == 0 ) ? 'disabled="disabled"' : '';
			$button_title = ( $total_wooc_products == 0 ) ?  'title="' . __( 'Please create some downloads.', 'client-and-product-testimonials' ) . '"' : '';
			$notice = ( $total_wooc_products == 0 ) ?  '<p>' . __( 'You need to create some Easy Digital Download products before you can import anything here.', 'client-and-product-testimonials' ) . '</p>' : '';
			?>
			<div class="form-field">
				<form id="import-wooc">
					<?php
						/* Security Nonce! */
						wp_nonce_field( 'import_wooc', 'import_wooc_products' );
						$import_wooc_url = esc_url(
							add_query_arg(
								array(
									'action' => 'import_wooc_products',
									'nonce' => wp_create_nonce( 'import_wooc_products' )
								)
							)
						);
					?>
					<a href="<?php echo $import_wooc_url; ?>" class="import-wooc-products-to-taxonomy button-secondary" <?php echo $button_title; ?> <?php echo $disabled_button; ?>>
						<object type="image/svg+xml" data="<?php echo Client_Product_Testimonials_URL . 'lib/images/woocommerce-logo.svg'; ?>" class="woocommerce-logo-in-button"></object> 
						<?php _e( 'Import WooCommerce Products', 'client-and-product-testimonials' ); ?>
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
	public function import_wooc_and_redirect_user() {
		if( isset( $_GET['action'] ) && $_GET['action'] == 'import_wooc_products' ) {
			/* If the nonce is invalid */
			if( ! wp_verify_nonce( $_GET['nonce'], 'import_wooc_products' ) ) {
				wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_wooc_import=0&error=nonce' ) );
				exit;
			}
			/*
			*	Import the EDD products
			*	@since 0.1
			*/
			$wooc_args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
			);
			$wooc_query = new WP_Query( apply_filters( 'capt_filter_wooc_import_query_args', $wooc_args ) );
			if( $wooc_query->have_posts() ) {
				// initially set to 0
				$wooc_imported = 0;
				while( $wooc_query->have_posts() ) {
					$wooc_query->the_post();
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
							$wooc_imported++;
						} else {
							wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_edd_import=0&error=' . urlencode( $new_product_term->get_error_message() ) . '' ) );
							exit;
						}
					}
				}
			}
			if( $wooc_imported == 0 ) {
				wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_wooc_import=0&error=imported_none' ) );
				exit;
			}
			wp_redirect( esc_url_raw( admin_url() . 'edit-tags.php?taxonomy=testimonial-products&post_type=testimonial&capt_wooc_import=1&imported_count='. (int) $wooc_imported ) );
			exit;
		}
	}
	
}
new Import_WooCommerce_Taxonomies;
/* End Import_WooCommerce_Taxonomies Class */

?>