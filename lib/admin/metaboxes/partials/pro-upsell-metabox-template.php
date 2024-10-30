<?php
	$upsell_banners = array(
		// limited time discount code banner
		array(
			'banner' => Client_Product_Testimonials_URL . 'lib/images/upsell-banner-templates/limited-time-code.jpg',
			'url' => esc_url( 'https://www.evan-herman.com/wordpress-plugin/client-product-testimonials/?utm_source=capt_plugin&utm_medium=banner&utm_campaign=upsell_sidebar' ),
			'alt' => __( 'Upgrade Now!', 'client-and-product-testimonials' )
		),
		// front end submission form banner
		array(
			'banner' => Client_Product_Testimonials_URL . 'lib/images/upsell-banner-templates/frontend-submission-form.jpg',
			'url' => esc_url( 'http://captpro.evan-herman.com/examples/testimonial-submission-form/?utm_source=capt_plugin&utm_medium=banner&utm_campaign=upsell_sidebar' ),
			'alt' => __( 'Upgrade Now!', 'client-and-product-testimonials' )
		),
		// try it now banner
		array(
			'banner' => Client_Product_Testimonials_URL . 'lib/images/upsell-banner-templates/try-it-now-banner.jpg',
			'url' => esc_url( 'http://captpro.evan-herman.com/?utm_source=capt_plugin&utm_medium=banner&utm_campaign=upsell_sidebar' ),
			'alt' => __( 'Try It Now!', 'client-and-product-testimonials' ),
		),
	);
	
	/* Setup a transient to prevent excessive databse queries */
	if ( false === ( $banner_data = get_transient( 'capt_admin_upsell_banner' ) ) ) {
		// if the current date is beyond Jan. 31st, 2016 - 
		// don't display the discount code since it will no longer be valid
		if( strtotime( 'now' ) >= strtotime( '01/31/2016' ) ) { 
			unset( $upsell_banners[0] );
			$upsell_banners = array_values( $upsell_banners );
		}
		$feature_length = (int) ( count( $upsell_banners ) - 1 );
		$random_number = rand( 0, $feature_length );
		// Banner data doesn't exist, so regenerate the data and save the transient
		$banner_data['banner'] = $upsell_banners[$random_number]['banner'];
		$banner_data['banner_url'] = $upsell_banners[$random_number]['url'];
		$banner_data['banner_alt'] = $upsell_banners[$random_number]['alt'];
		// setup the transient and cache it for an hour
		set_transient( 'capt_admin_upsell_banner', $banner_data, 1 * HOUR_IN_SECONDS );
	}
?>

<section class="capt-pro-upsell-container">
	<a href="<?php echo $banner_data['banner_url']; ?>" target="_blank" title="<?php echo $banner_data['banner_alt']; ?>">
		<img src="<?php echo esc_url( $banner_data['banner'] ); ?>" alt="<?php echo $banner_data['banner_alt']; ?>" />
	</a>
</section>