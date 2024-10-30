<!-- Generate the taxonomy selection checkboxes -->
<?php echo generate_testimonial_select2( 'full-width-fields' ); ?>
<p class="description"><?php _e( 'Assigning multiple testimonials (or assigning none) will select and display one at random on each page load.', 'client-and-product-testimonials' ); ?></p>

<section class="shortcode-generator-options testimonial-full-width-fields">
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Image Border Radius', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Select the border type for each testimnoial image.', 'client-and-product-testimonials' ); ?></p>
		<label class="image-radius-radio"><input type="radio" name="images-style" value="square" checked><?php _e( 'Square', 'client-and-product-testimonials' ); ?></label>
		<label class="image-radius-radio"><input type="radio" name="images-style" value="rounded-small"><?php _e( 'Rounded Small', 'client-and-product-testimonials' ); ?></label>
		<label class="image-radius-radio"><input type="radio" name="images-style" value="rounded-medium"><?php _e( 'Rounded Medium', 'client-and-product-testimonials' ); ?></label>
		<label class="image-radius-radio"><input type="radio" name="images-style" value="circle"><?php _e( 'Circle', 'client-and-product-testimonials' ); ?></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Grayscale Images', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Grayscale images will load in grayscale and when hovered on will fade to full color.', 'client-and-product-testimonials' ); ?></p>
		<label><input type="radio" name="grayscale" value="" checked><?php _e( 'No', 'client-and-product-testimonials' ); ?></label>
		<label><input type="radio" name="grayscale" value="grayscale"><?php _e( 'Yes', 'client-and-product-testimonials' ); ?></label>
	</section>
	
</section>