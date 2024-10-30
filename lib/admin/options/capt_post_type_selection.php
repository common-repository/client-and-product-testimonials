<?php
/*
*	Custom field to select which post types are allowed to be reviewed
*	@compiled by Evan Herman	
*	@since 0.1
*/

/*
*	Get post types
*	@since 0.1
*/
$post_types = get_post_types( '', 'names' );

/* Remove known, not useful post types */
$excluded_post_types = array(
	'testimonial', /* testimonials */
	'attachment',
	'revision',
	'nav_menu_item',
	'product_variation', /* woocommerce */
	'shop_order', /* edd */
	'shop_order_refund', /* edd */
	'shop_coupon', /* edd */
	'shop_webhook', /* edd */
);
/* Allow users to exclude post types here as well */
$excluded_post_types = apply_filters( 'capt_expluded_review_post_types', $excluded_post_types );
/* Loop over and unset what we don't want */
foreach( $excluded_post_types as $post_type ) {
	unset( $post_types[ $post_type ] );
}
/* Get the previously stored option */
$options = Client_and_Product_Testimonials::get_cat_options();
$previously_saved_post_types = ( isset( $options['_client_and_product_testimonial_post_page_reviews_post_types'] ) ) ? $options['_client_and_product_testimonial_post_page_reviews_post_types'] : array();

/* Build the dropdown */
?>
<div class="cmb-td">
	<select id="<?php echo $field->args['_id']; ?>" name="<?php echo $field->args['_id']; ?>[]" multiple>
		<?php
			foreach ( $post_types as $post_type ) {
				$post_type_labels = get_post_type_object( $post_type );
				$post_type_name = ( isset( $post_type_labels->labels->singular_name ) ) ? $post_type_labels->labels->singular_name : $post_type;
				$selected = ( in_array( $post_type, $previously_saved_post_types ) ) ? 'selected="selected"' : '';
				echo '<option value="' . $post_type . '" ' . $selected . '>' . ucwords( $post_type_name ) . '</option>';
			}
		?>
	</select>
	<p class="cmb2-metabox-description"><?php echo $field->args['desc']; ?></p>
	<p class="cmb2-metabox-description"><?php printf( __( 'Enabling post/page reviews will allow %s to rate posts using a star rating field on a 5 point scale. Logged in users may only review posts or pages 1 time.', 'client-and-product-testimonials' ), '<strong>' . __( 'logged in users', 'client-and-product-testimonials' ) . '</strong>' ); ?></p>
</div>