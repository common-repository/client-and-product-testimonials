<?php
/*
*	Process any and all testimonial Submissions
*	By: Evan Herman | https://www.evan-herman.com
*	@since 0.1
*/
$user_name = $_POST['testimonial-user-name'];
$taxonomy = $_POST['testimonial-taxonomy'];
$taxonomy_id = (int) $_POST['testimonial-associated-' . $taxonomy  ];
$user_url = $_POST['testimonial-user-url'];
$user_rating = $_POST['testimonial-user-rating'];
$user_testimonial = $_POST['testimonial-user-testimonial'];
$assigned_taxonomy = get_term_by( 'id', $taxonomy_id, 'testimonial-' . $taxonomy, ARRAY_A );

	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;
	$slug = sanitize_title( wp_strip_all_tags( $user_name ) );
	$title = wp_strip_all_tags( $user_name );

	// If the testimonial doesn't already exist, then create it
	if( null == get_page_by_title( $title ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'post_content' => $user_testimonial,
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	$slug,
				'post_title'		=>	$title,
				'post_status'		=>	'pending',
				'post_type'		=>	'testimonial',
			)
		);
		
		// set the taxonomy
		wp_set_object_terms( $post_id, $assigned_taxonomy['name'], 'testimonial-' . $taxonomy, true );
		
		update_post_meta( $post_id, '_testimonial_details_testimonial_rating', $user_rating );
		update_post_meta( $post_id, '_testimonial_details_testimonial_rating', $user_rating );
		update_post_meta( $post_id, '_testimonial_details_url', $user_url );
		
		// if 'client'
		if( $taxonomy == 'clients' ) {
			$user_position = $_POST['testimonial-user-position'];
			update_post_meta( $post_id, '_testimonial_details_client_position', $user_position );
		}
		
		// set up the image
		if( is_user_logged_in() && current_user_can( 'upload_files' ) ) {
			$user_image = esc_url( $_POST['testimonial-upload-user-image'] );
		} else {
			if( isset( $_POST['capt_testimonial_upload_nonce'] ) && wp_verify_nonce( $_POST['capt_testimonial_upload_nonce'], 'capt_testimonial_image_upload' ) ) {
				// These files need to be included as dependencies when on the front end.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				// handle the upload
				$attachment_id = media_handle_upload( 'testimonial-upload-user-image', $post_id );
				if ( is_wp_error( $attachment_id ) ) {
					return;
				} else {
					$attachment_array = wp_get_attachment_image_src( $attachment_id, 'full' );
					$user_image = esc_url( $attachment_array[0] );
				}
			}
		}

	// Otherwise, we'll stop
	} else {
    		// Arbitrarily use -2 to indicate that the page with the title already exists
    		$post_id = -2;
	} // end if
	
	// update the testimonial image
	set_post_thumbnail( $post_id, eh_get_image_idby_url( $user_image ) );