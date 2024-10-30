<?php
/*
*	Main uninstall execution file
*	Deletes all of our plugins options so we leave no trace :)
*	@since 0.1
*/

// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Delete the plugin options 
delete_option( 'cat_options' );

// Remove the 'Fallback' image
global $wpdb;
$attachments = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%s';", $wpdb->esc_like( 'Client and Product Testimonial - No Image' ) ) ); 
if( ! empty( $attachments ) ) {
	foreach( $attachments as $attachment_id ) {
		// remove the attachment, and remove it from the trash
		wp_delete_post( $attachment_id, true );
	}
}	

// Remove the testimonials
global $wpdb;
$attachments = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='%s';", 'testimonial' ) ); 
if( ! empty( $attachments ) ) {
	foreach( $attachments as $attachment_id ) {
		// remove the attachment, and remove it from the trash
		wp_delete_post( $attachment_id, true );
	}
}	