<!-- Generate the taxonomy selection checkboxes -->
<?php echo generate_taxonomy_select2( 'list-fields' ); ?>
<p class="description"><?php _e( 'Select testimonials to display in this list. Leave blank to display select from all testimonials.', 'client-and-product-testimonials' ); ?></p>

<section class="shortcode-generator-options testimonial-list-fields">
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Columns', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Set the number of columns of this list.', 'client-and-product-testimonials' ); ?></p>
		<label><input type="number" value="1" name="columns" min="1" max="4"><em>&nbsp;<?php _e( 'column(s)', 'client-and-product-testimonials' ); ?></em></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Limit', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Set the number of testimonials to display in this list. (set to -1 for no limit)', 'client-and-product-testimonials' ); ?></p>
		<label><input type="number" value="10" name="limit" min="-1" max="99"><em>&nbsp;<?php _e( 'testimonial(s)', 'client-and-product-testimonials' ); ?></em></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Image Border Radius', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Select the border type for each testimnoial image.', 'client-and-product-testimonials' ); ?></p>
		<label class="image-radius-radio"><input type="radio" name="image-style" value="square" checked><?php _e( 'Square', 'client-and-product-testimonials' ); ?></label>
		<label class="image-radius-radio"><input type="radio" name="image-style" value="rounded-small"><?php _e( 'Rounded Small', 'client-and-product-testimonials' ); ?></label>
		<label class="image-radius-radio"><input type="radio" name="image-style" value="rounded-medium"><?php _e( 'Rounded Medium', 'client-and-product-testimonials' ); ?></label>
		<label class="image-radius-radio"><input type="radio" name="image-style" value="circle"><?php _e( 'Circle', 'client-and-product-testimonials' ); ?></label>
	</section>
	
	<section class="shortcode-generator-section">
		<strong><?php _e( 'Grayscale Images', 'client-and-product-testimonials' ); ?></strong>
		<p class="description"><?php _e( 'Grayscale images will load in grayscale and when hovered on will fade to full color.', 'client-and-product-testimonials' ); ?></p>
		<label><input type="radio" name="grayscale-images" value="" checked><?php _e( 'No', 'client-and-product-testimonials' ); ?></label>
		<label><input type="radio" name="grayscale-images" value="grayscale"><?php _e( 'Yes', 'client-and-product-testimonials' ); ?></label>
	</section>
	
</section>