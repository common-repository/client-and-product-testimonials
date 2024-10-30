<!-- Generate the taxonomy selection checkboxes -->
<?php echo generate_capt_taxonomy_selection(); ?>

<section class="shortcode-generator-options testimonial-fade-slider-fields">
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Automatic', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Should this slideshow slide to the next testimonial automatically?', 'client-and-product-testimonials' ); ?></p>
		<label><input type="radio" name="automatic" value="1" checked><?php _e( 'Yes', 'client-and-product-testimonials' ); ?></label>
		<label><input type="radio" name="automatic" value="0"><?php _e( 'No', 'client-and-product-testimonials' ); ?></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Duration', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Time to display each testimonial before transitioning to the next one. (in seconds)', 'client-and-product-testimonials' ); ?></p>
		<label><input type="number" value="5" name="duration" class="duration-input" min="1" max="100" step="1"> <em><?php _e( 'seconds', 'client-and-product-testimonials' ); ?></em></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Transition', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Select the type of transition to use between slides.', 'client-and-product-testimonials' ); ?></p>
		<label><input type="radio" name="animation" value="fade" checked><?php _e( 'Fade', 'client-and-product-testimonials' ); ?></label>
		<label><input type="radio" name="animation" value="slide"><?php _e( 'Slide', 'client-and-product-testimonials' ); ?></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Display Controls', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Should the previous and next navigation arrows be displayed for this form?', 'client-and-product-testimonials' ); ?></p>
		<label><input type="radio" name="controls" value="1" checked><?php _e( 'Yes', 'client-and-product-testimonials' ); ?></label>
		<label><input type="radio" name="controls" value="0"><?php _e( 'No', 'client-and-product-testimonials' ); ?></label>
	</section>
	
</section>