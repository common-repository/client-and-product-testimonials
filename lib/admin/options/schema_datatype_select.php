<?php
/*
*	Schema Data Types
*	- Data types are used to specify more specifically what type of product, or what type of business is being reviewed by these testimonials
*	- This is used to generate rich snippets in google reviews, and help with SEO
*	Custom list built by Evan Herman, compiled from http://schema.org/docs/full.html
*	@since 0.1
*/
$options = Client_and_Product_Testimonials::get_cat_options();
$default = ( isset( $options['_client_and_product_testimonial_taxonomy'] ) && $options['_client_and_product_testimonial_taxonomy'] == 'testimonial_products' ) ? 'Products' : 'LocalBusiness';
?>
<p><?php _e( "Make your reviews really stand out in Google search results using rich snippet schema markup. The pro version automatically generates all the schema markup, for your testimonials and product reviews, to ensure your reviews don't go unnoticed on Google.", 'client-and-product-testimonials' ); ?></p>
<p><strong>Example:</strong></p>
<p><img src="<?php echo Client_Product_Testimonials_URL; ?>lib/images/schema-review-example.png" /></p>
<p><a href="http://captpro.evan-herman.com/" class="button-primary button-hero" title="Upgrade Now" target="_blank">Upgrade Now</a></p>
<p class="cmb2-metabox-description schema-markup-info"><?php printf( __( 'For more information about the "%s" schema data, %s', 'client-and-product-testimonials' ), $default, '<a class="selected_schema_info_link" href="' . esc_url( 'http://schema.org/' ) . $default . '" target="_blank" title="' . __( 'Click Here', 'client-and-product-testimonials' ) . '">' . __( 'Click Here', 'client-and-product-testimonials' ) . '</a>' ); ?></p>
<!-- that's all folks! -->