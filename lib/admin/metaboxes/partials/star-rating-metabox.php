<?php
	$meta = ( isset( $meta ) ) ? $meta : '5';
?>
<section id="cmb2-star-rating-metabox">
	<fieldset>
		<span class="star-cb-group">
			<?php
				$y = 5;
				while( $y > 0 ) {
					?>
						<input type="radio" id="rating-<?php echo $y; ?>" name="<?php echo $field->args['id']; ?>" value="<?php echo $y; ?>" <?php checked( $meta, $y ); ?>/>
						<label for="rating-<?php echo $y; ?>"><?php echo $y; ?></label>
					<?php
					$y--;
				}
			?>
		</span>
	</fieldset>
</section>