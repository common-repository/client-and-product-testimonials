<div id="capt-shortcode-generator" style="display:none;width:100%;">
	<style>
		#TB_ajaxContent {
			width: auto !important;
			height: 94% !important;
			overflow-y: scroll;
		}
	</style>
	<div class="capt-shortcode-generator-wrap">
			
			<!-- Step 1 -->
			<strong class="step"><?php echo __( 'Step 1', 'client-and-product-testimonials' ) . ':'; ?></strong>
			<p class="description"><?php echo __( 'Select which testimonial to generate.', 'client-and-product-testimonials' ); ?></p>
			
			<section class="shortcode-selection">
				<label class="hidden-radio-button">
					<input type="radio" name="capt-shortcode" value="fade-slider" data-attr-shortcode="fade-slider" checked />
					<img src="<?php echo Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/images/fade-slider-image.jpg'; ?>" alt="<?php _e( 'Testimonial Fade Slider', 'client-and-product-testimonials' ); ?>" title="<?php _e( 'Testimonial Fade Slider', 'client-and-product-testimonials' ); ?>">
					<p class="description sub-text"><?php _e( 'Basic Slider', 'client-and-product-testimonials' ); ?></p>
				</label>
			  
				<label class="hidden-radio-button">
					<input type="radio" name="capt-shortcode" value="full-width" data-attr-shortcode="full-width" />
					<img src="<?php echo Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/images/full-width-image.jpg'; ?>" alt="<?php _e( 'Testimonial Full Width', 'client-and-product-testimonials' ); ?>" title="<?php _e( 'Testimonial Full Width', 'client-and-product-testimonials' ); ?>">
					<p class="description sub-text"><?php _e( 'Full Width Section', 'client-and-product-testimonials' ); ?></p>
				</label>
			  			  
				<label class="hidden-radio-button">
					<input id="fb4" type="radio" name="capt-shortcode" value="list" data-attr-shortcode="list" />
					<img src="<?php echo Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/images/list-image.jpg'; ?>" alt="<?php _e( 'Testimonial List', 'client-and-product-testimonials' ); ?>" title="<?php _e( 'Testimonial List', 'client-and-product-testimonials' ); ?>">
					<p class="description sub-text"><?php _e( 'List', 'client-and-product-testimonials' ); ?></p>
				</label>
				
				<label class="hidden-radio-button">
					<input id="fb3" type="radio" name="capt-shortcode" value="grid" data-attr-shortcode="grid" />
					<img src="<?php echo Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/images/grid-image.jpg'; ?>" alt="<?php _e( 'Testimonial Grid (pro)', 'client-and-product-testimonials' ); ?>" title="<?php _e( 'Testimonial Grid', 'client-and-product-testimonials' ); ?>" style="opacity:.5;">
					<p class="description sub-text"><?php printf( __( 'Grid %s', 'client-and-product-testimonials' ), '<strong><small>(' . __( 'pro', 'client-and-product-testimonials' ) . ')</small></strong>' ); ?></p>
				</label>
				
				<label class="hidden-radio-button">
					<input id="fb4" type="radio" name="capt-shortcode" value="thumbnail-slider" data-attr-shortcode="thumbnail-slider" />
					<img src="<?php echo Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/images/thumbnail-slider-image.jpg'; ?>" alt="<?php _e( 'Testimonial Thumbnail Slider (pro)', 'client-and-product-testimonials' ); ?>" title="<?php _e( 'Testimonial Thumbnail Slider', 'client-and-product-testimonials' ); ?>" style="opacity:.5;">
					<p class="description sub-text"><?php printf( __( 'Thumbnail Slider %s', 'client-and-product-testimonials' ), '<strong><small>(' . __( 'pro', 'client-and-product-testimonials' ) . ')</small></strong>' ); ?></p>
				</label>
				
				<label class="hidden-radio-button">
					<input id="fb4" type="radio" name="capt-shortcode" value="submission-form" data-attr-shortcode="submission-form" />
					<img src="<?php echo Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/images/submission-form-image.jpg'; ?>" alt="<?php _e( 'Testimonial Submission Form (pro)', 'client-and-product-testimonials' ); ?>" title="<?php _e( 'Testimonial Submission Form', 'client-and-product-testimonials' ); ?>" style="opacity:.5;">
					<p class="description sub-text"><?php printf( __( 'Submission Form %s', 'client-and-product-testimonials' ), '<strong><small>(' . __( 'pro', 'client-and-product-testimonials' ) . ')</small></strong>' ); ?></p>
				</label>
				
			</section>
			
			<!-- step 2 -->
			<strong class="step"><?php echo __( 'Step 2', 'client-and-product-testimonials' ) . ':'; ?></strong>
			<p class="description"><?php echo __( 'Set the parameters for the chosen shortcode.', 'client-and-product-testimonials' ); ?>
			
			<section class="shortcode-options-section">
				
				<div class="hidden-section fade-slider hidden-section-first">
					<?php include_once( Client_Product_Testimonials_Path . 'lib/admin/shortcode-generator/sections/fade-slider-fields.php' ); ?>
				</div>
				
				<div class="hidden-section full-width">
					<?php include_once( Client_Product_Testimonials_Path . 'lib/admin/shortcode-generator/sections/full-width-fields.php' ); ?>
				</div>
								
				<div class="hidden-section list">
					<?php include_once( Client_Product_Testimonials_Path . 'lib/admin/shortcode-generator/sections/list-fields.php' ); ?>
				</div>
				
				<div class="hidden-section grid">
					<h3>Testimonial Grid</h3>
					<p class="description"><?php printf( __( 'The %s is only available in the pro version.', 'client-and-product-testimonials' ), '<a href="http://captpro.evan-herman.com/testimonial-grid/" target="_blank">testimonial grid</a>' ); ?></p>
				</div>
				
				<div class="hidden-section thumbnail-slider">
					<h3>Testimonial Thumbnail Slider</h3>
					<p class="description"><?php printf( __( 'The %s is only available in the pro version.', 'client-and-product-testimonials' ), '<a href="http://captpro.evan-herman.com/examples/testimonial-thumbnail-slider/" target="_blank">testimonial thumbnail slider</a>' ); ?></p>
				</div>
				
				<div class="hidden-section submission-form">
					<h3>Front End Submission Form</h3>
					<p class="description"><?php printf( __( 'The %s is only available in the pro version.', 'client-and-product-testimonials' ), '<a href="http://captpro.evan-herman.com/examples/testimonial-submission-form/" target="_blank">front end submission form</a>' ); ?></p>
				</div>
				
			</section>
			
			<!-- step 3 -->
			<strong class="step"><?php echo __( 'Step 3', 'client-and-product-testimonials' ) . ':'; ?></strong>
			<p class="description"><?php echo __( 'Click "Insert Shortcode" to place the shortcode in your page, or copy the shortcode to use it elsewhere on your site.', 'client-and-product-testimonials' ); ?></p>
			<input type="text" class="capt-shortcode-input" value="" readonly onclick="jQuery(this).select();">
			<p>&nbsp;</p>
			
	</div>
	
	<a href="#" class="button-primary insert-capt-shortcode-button" onclick="send_to_editor(jQuery('.capt-shortcode-input').val());return false;"><?php _e( 'Insert Shortcode', 'client-and-product-testimonials' ); ?></a>
</div>