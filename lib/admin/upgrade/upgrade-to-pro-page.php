<?php
/*
*	Client and Product Testimonials - 'Upgrade to Pro' page template
*	@since	1.0
*/

// enqueue our license/support styles
wp_enqueue_style( 'capt-go-pro', Client_Product_Testimonials_URL . 'lib/admin/css/capt-go-pro-styles.css' );
// enqueue tipso.css/js
wp_enqueue_style( 'tipso-css', Client_Product_Testimonials_URL . 'lib/admin/css/tipso.min.css' );
wp_enqueue_script( 'tipso-js', Client_Product_Testimonials_URL . 'lib/admin/js/tipso.min.js' );
?>

<div id="eh-dev-shop-support-page-wrap wrap">

	<div class="header" style="max-width: 95%;">
		<h1><?php _e( 'Upgrade to Pro', 'client-and-product-testimonials' ); ?></h1>
		<p class="description">
			<?php printf( __( 'If your enjoying the free version of %s, please consider upgrading to the pro version for some enhanced features! Check out some of our included features below. This list will continue to grow as features become available, so check back often!', 'client-and-product-testimonials' ), '<strong>Client and Product Testimonials</strong>' ); ?>
		</p>
		<hr />
	</div>
	
	<section id="eh-dev-shop-support-page-header" style="margin:1em 0 1.5em 0;">
		<a href="http://captpro.evan-herman.com" title="<?php _e( 'Client and Product Testimonials Lite', 'client-and-product-testimonials' ); ?>" target="_blank" style="margin-left:15%; ">
			<img src="<?php echo Client_Product_Testimonials_URL . '/lib/images/capt-logo-256.png'; ?>" title="Client and Product Testimonials Pro" class="capt-logo" >
		</a>
	</section>
	
	<table id="capt-features-table">
		<tbody>
			<tr>
				<td class="feature-text-label">
					<h2>
						<img src="http://captpro.evan-herman.com/wp-content/uploads/2015/09/down-arrow-sketched.png" class="feature-arrow animated infinite bounce">
						<?php _e( 'Features', 'client-and-product-testimonials' ); ?>
					</h2>
				</td>
				<td class="plugin-level-label plugin-level-free"><?php _e( 'FREE', 'client-and-product-testimonials' ); ?></td>
				<td class="plugin-level-label plugin-level-pro"><strong><?php _e( 'PRO', 'client-and-product-testimonials' ); ?></strong></td>
			</tr>
			<tr>
				<td class="feature-label radius-top-left"><?php _e( 'Shortcode Generator', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Included Shortcodes', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container">
					3
					<a href="#" onclick="jQuery(this).next().slideToggle();return false;" class="toggle-shortcode-link"><?php _e( 'view', 'client-and-product-testimonials' ); ?></a>
					<div style="display:none;">
						<hr style="margin: 5px 0;" />
						<ul class="feature-list-interior">
							<li><?php _e( 'Testimonial List', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'Basic Testimonial Slider', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'Full Width Shortcode', 'client-and-product-testimonials' ); ?></li>
						</ul>
					</div>
				</td>
				<td class="check-container">
					6
					<a href="#" onclick="jQuery(this).next().slideToggle();return false;" class="toggle-shortcode-link"><?php _e( 'view', 'client-and-product-testimonials' ); ?></a>
					<div style="display:none;">
						<hr style="margin: 5px 0;" />
						<ul class="feature-list-interior">
							<li><?php _e( 'Testimonial List', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'Basic Testimonial Slider', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'Full Width Shortcode', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'Testimonial Grid', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'Thumbnail Slider', 'client-and-product-testimonials' ); ?></li>
							<li><?php _e( 'User Submission Form', 'client-and-product-testimonials' ); ?></li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Included Widgets', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><?php _e( 'Testimonail List Widget', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><?php _e( 'Testimonail List Widget', 'client-and-product-testimonials' ); ?><p style="margin-bottom:0;"><?php _e( 'Testimonail Slider Widget', 'client-and-product-testimonials' ); ?></p></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Included Preloaders', 'client-and-product-testimonials' ); ?> <i class="dashicons dashicons-editor-help feature-tooltip" data-tipso="<?php _e( 'Preloaders display for certain testimonial sliders before they get initialized and displayed on the page.', 'client-and-product-testimonials' ); ?>"></i></td>
				<td class="check-container">10</td>
				<td class="check-container">36</td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Mobile Responsive Testimonials', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Import &amp; Export', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Frontend Submission Form', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><i class="dashicons dashicons-dismiss" title="Not available in free version"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Video Testimonials', 'client-and-product-testimonials' ); ?> <i class="dashicons dashicons-editor-help feature-tooltip" data-tipso="<?php _e( 'With the pro version you have to ability to convert a standard testimonial into a video tesitmonial, displaying YouTube or Vimeo videos - beautifully.', 'client-and-product-testimonials' ); ?>"></i></td>
				<td class="check-container"><i class="dashicons dashicons-dismiss" title="Not available in free version"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Rich Snippet Schema Markup', 'client-and-product-testimonials' ); ?> <i class="dashicons dashicons-editor-help feature-tooltip" data-tipso="<?php _e( "With the pro version your testimonials schema.org structured data markup is generated for each testimonial. <a href='https://developers.google.com/structured-data/' target='blank'>Structured data markup</a> allows for your testimonials to be presented more prominently in Google search results.", "client-and-product-testimonials" ); ?>"></i></td>
				<td class="check-container"><i class="dashicons dashicons-dismiss" title="Not available in free version"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Multiple Styles', 'client-and-product-testimonials' ); ?> <i class="dashicons dashicons-editor-help feature-tooltip" data-tipso="<?php _e( 'Each testimonial shortcode will soon have a select set of styles for you to switch between.', 'client-and-product-testimonials' ); ?>"></i></td>
				<td class="check-container"><i class="dashicons dashicons-dismiss" title="Not available in free version"></i></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i><small><?php _e( 'Coming Soon!', 'client-and-product-testimonials' ); ?></small></td>
			</tr>
			<tr>
				<td class="feature-label"><?php _e( 'Support &amp; Updates', 'client-and-product-testimonials' ); ?></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i><small><?php _e( 'Free support and updates on WP.org', 'client-and-product-testimonials' ); ?></small></td>
				<td class="check-container"><i class="dashicons dashicons-yes"></i><small><?php _e( '1 year priority support &amp; updates', 'client-and-product-testimonials' ); ?></small></td>
			</tr>
			<tr>
				<td class="empty-row"></td>
				<td class="action-button-td download">
					<strong><i class="dashicons dashicons-yes" style="line-height:2.1;"></i><small> <?php printf( __( 'version %s installed', 'client-and-product-testimonials' ), Client_Product_Testimonials_Version ); ?></strong>
				</td>
				<td class="action-button-td purchase">
					<a title="Buy Now" href="https://www.evan-herman.com/wordpress-plugin/client-product-testimonials/" target="_blank" class="cool-button green"><i class="dashicons dashicons-upload"></i> Upgrade</a>
				</td>
			</tr>
		</tbody>
	</table>
	
	<section id="eh-logos">
		<a href="http://www.evan-herman.com" target="_blank" title="Evan Herman Professional WordPress Development">
			<img src="<?php echo Client_Product_Testimonials_URL; ?>/lib/images/evan_herman_logo.png" class="eh-dev-shop-logo" alt="Evan Herman Logo"><br />
			<img src="<?php echo Client_Product_Testimonials_URL; ?>/lib/images/evan-herman-mascot.png" class="eh-dev-shop-mascot" alt="Evan Herman Mascot">
		</a>
	</section>
	
</div>


<!-- Initiailize Tipso tooltips -->
<script type="text/javascript">
jQuery( document ).ready( function() {
	jQuery( '.feature-tooltip' ).each( function() {
		jQuery( this ).tipso({		
			background: '#F5AB35',
			color: '#ffffff',
			titleContent: jQuery( this ).parents( 'td' ).text(),
			size: 'small',
			animationIn: 'fadeInDown',
			tooltipHover: true,
		});
	});
});
</script>