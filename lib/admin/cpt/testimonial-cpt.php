<?php

class Register_Testimonial_Post_Type extends Client_and_Product_Testimonials {

	public function __construct() {
		// Initialize CMB2
		if ( file_exists(  plugin_dir_path(__FILE__) . '../CMB2/init.php' ) ) {
			// include the bootstrap file
			require_once  plugin_dir_path(__FILE__) . '../CMB2/init.php';
		}
		
		// get our options
		$options = $this->get_cat_options();
		
		/*
		*	Select which taxonomy to register
		*	@since 0.1
		*/
		add_action( 'init', array( $this, 'register_testimonial_' . $options['_client_and_product_testimonial_taxonomy'] . '_taxonomy' ), 0 );

		
		/*
		*	Hook into the 'init' action, register our testimonial post type
		*	@since 0.1
		*/
		add_action( 'init', array( $this, 'register_testimonial_post_type' ), 0 );
		
		/*
		*	Include the necessary metaboxes
		*	@since 0.1
		*/
		include_once( plugin_dir_path(__FILE__) . '../metaboxes/testimonial-metaboxes.php' );
		
		/*
		*	Enqueue Admin CSS
		*	@since 0.1
		*/
		add_action( 'admin_enqueue_scripts', array( $this, 'client_and_product_testimonials_admin_styles' ) );
						
		/*
		*	Custom featured image box
		*	@since 0.1
		*/
		add_action( 'do_meta_boxes', array( $this, 'change_image_box' ) );
		
		add_action( 'admin_head-post-new.php', array( $this, 'alter_testimonial_featured_image_text' ) );
		add_action( 'admin_head-post.php', array( $this, 'alter_testimonial_featured_image_text' ) );
		
		/*
		*	Custom notices for our testimonial cpt
		*/
		add_filter( 'post_updated_messages', array( $this, 'custom_testimonial_updated_messages' ) );
		
		/*
		*	Add custom row action buttons
		*	@since 0.1
		*/
		add_filter( 'post_row_actions', array( $this, 'capt_testimonial_quick_toggle_testimonial_status' ), 10, 2 );
		
		/*
		*	Redirect after status is toggled
		*	@since 0.1
		*/
		add_action( 'init', array( $this, 'redirect_after_testimonial_status_toggle' ) );
		
	}
	
	/*
	*	Alter testimonial featured image text
	*	@since 0.1
	*/
	public function alter_testimonial_featured_image_text() {
		if ( 'testimonial' == $GLOBALS['post_type'] ) {
			add_filter( 'admin_post_thumbnail_html', array( $this, 'capt_custom_admin_post_thumbnail_html' ), 9999 );
		}
	}

	/* Testimonial Custom Post Type */
	// Register Custom Post Type
	public function register_testimonial_post_type() { 

		$labels = array(
			'name'                => _x( 'Testimonials', 'Post Type General Name', 'client-and-product-testimonials' ),
			'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'client-and-product-testimonials' ),
			'menu_name'           => __( 'Testimonials', 'client-and-product-testimonials' ),
			'name_admin_bar'      => __( 'Testimonials', 'client-and-product-testimonials' ),
			'parent_item_colon'   => __( 'Parent Testimonial:', 'client-and-product-testimonials' ),
			'all_items'           => __( 'All Testimonials', 'client-and-product-testimonials' ),
			'add_new_item'        => __( 'Add New Testimonial', 'client-and-product-testimonials' ),
			'add_new'             => __( 'New Testimonial', 'client-and-product-testimonials' ),
			'new_item'            => __( 'New Testimonial', 'client-and-product-testimonials' ),
			'edit_item'           => __( 'Edit Testimonial', 'client-and-product-testimonials' ),
			'update_item'         => __( 'Update Testimonial', 'client-and-product-testimonials' ),
			'view_item'           => __( 'View Testimonial', 'client-and-product-testimonials' ),
			'search_items'        => __( 'Search Testimonials', 'client-and-product-testimonials' ),
			'not_found'           => __( 'No Testimonials Found', 'client-and-product-testimonials' ) . '<br /><a href="' . esc_url( admin_url( 'post-new.php?post_type=testimonial' ) ) . '" class="button button-secondary"><span class="dashicons dashicons-plus"></span>' . __( 'Create New Testimonial', 'client-and-product-testimonials' ) . '</a>',
			'not_found_in_trash'  => __( 'Not found in Trash', 'client-and-product-testimonials' ),
		);
		$args = array(
			'label'               => __( 'testimonial', 'client-and-product-testimonials' ),
			'description'         => __( 'Testimonials for all of our plugins', 'client-and-product-testimonials' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => apply_filters( 'capt_post_type_public', false ),
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-editor-quote',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,		
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'testimonial', $args );
	}
	
	/*
	*	Register Product Taxonomy
	*	@since 0.1
	*/
	public function register_testimonial_testimonial_products_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Products', 'Taxonomy General Name', 'client-and-product-testimonials' ),
			'singular_name'              => _x( 'Product', 'Taxonomy Singular Name', 'client-and-product-testimonials' ),
			'menu_name'                  => __( 'Products', 'client-and-product-testimonials' ),
			'all_items'                  => __( 'All Products', 'client-and-product-testimonials' ),
			'parent_item'                => __( 'Parent Product', 'client-and-product-testimonials' ),
			'parent_item_colon'          => __( 'Parent Products:', 'client-and-product-testimonials' ),
			'new_item_name'              => __( 'New Product', 'client-and-product-testimonials' ),
			'add_new_item'               => __( 'Add New Product', 'client-and-product-testimonials' ),
			'edit_item'                  => __( 'Edit Product', 'client-and-product-testimonials' ),
			'update_item'                => __( 'Update Product', 'client-and-product-testimonials' ),
			'view_item'                  => __( 'View Product', 'client-and-product-testimonials' ),
			'separate_items_with_commas' => __( 'Separate products with commas', 'client-and-product-testimonials' ),
			'add_or_remove_items'        => __( 'Add or remove products', 'client-and-product-testimonials' ),
			'choose_from_most_used'      => '',
			'popular_items'              => __( 'Popular Products', 'client-and-product-testimonials' ),
			'search_items'               => __( 'Search Products', 'client-and-product-testimonials' ),
			'not_found'                  => __( 'Product Not Found', 'client-and-product-testimonials' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		);
		register_taxonomy( 'testimonial-products', array( 'testimonial' ), $args );
	}

	/*
	*	Register Client Taxonomy
	*	@since 0.1
	*/
	public function register_testimonial_testimonial_clients_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Clients', 'Taxonomy General Name', 'client-and-product-testimonials' ),
			'singular_name'              => _x( 'Client', 'Taxonomy Singular Name', 'client-and-product-testimonials' ),
			'menu_name'                  => __( 'Clients', 'client-and-product-testimonials' ),
			'all_items'                  => __( 'All Clients', 'client-and-product-testimonials' ),
			'parent_item'                => __( 'Parent Client', 'client-and-product-testimonials' ),
			'parent_item_colon'          => __( 'Parent Clients:', 'client-and-product-testimonials' ),
			'new_item_name'              => __( 'New Client', 'client-and-product-testimonials' ),
			'add_new_item'               => __( 'Add New Client', 'client-and-product-testimonials' ),
			'edit_item'                  => __( 'Edit Client', 'client-and-product-testimonials' ),
			'update_item'                => __( 'Update Client', 'client-and-product-testimonials' ),
			'view_item'                  => __( 'View Client', 'client-and-product-testimonials' ),
			'separate_items_with_commas' => __( 'Separate clients with commas', 'client-and-product-testimonials' ),
			'add_or_remove_items'        => __( 'Add or remove clients', 'client-and-product-testimonials' ),
			'choose_from_most_used'      => '',
			'popular_items'              => __( 'Popular Clients', 'client-and-product-testimonials' ),
			'search_items'               => __( 'Search Clients', 'client-and-product-testimonials' ),
			'not_found'                  => __( 'Client Not Found', 'client-and-product-testimonials' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		);
		register_taxonomy( 'testimonial-clients', array( 'testimonial' ), $args );
	}
	
	/*
	*	Setting custom 'Tesitmonial' messages
	*	@since 0.1
	*/
	public function custom_testimonial_updated_messages( $messages ) {
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['testimonial'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Testimonial Successfully Updated.', 'client-and-product-testimonials' ),
			2  => __( 'Custom Field Successfully Updated.', 'client-and-product-testimonials' ),
			3  => __( 'Custom Field Successfully Deleted.', 'client-and-product-testimonials' ),
			4  => __( 'Testimonial Successfully Updated.', 'client-and-product-testimonials' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Testimonial restored to revision from %s', 'client-and-product-testimonials' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Testimonial Successfully Published.', 'client-and-product-testimonials' ),
			7  => __( 'Testimonial Successfully Saved.', 'client-and-product-testimonials' ),
			8  => __( 'Testimonial Successfully Submitted.', 'client-and-product-testimonials' ),
			9  => sprintf(
				__( 'Testimonial Scheduled For: <strong>%1$s</strong>.', 'client-and-product-testimonials' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', 'client-and-product-testimonials' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Testimonial Draft Updated.', 'client-and-product-testimonials' )
		);

		return $messages;
	}
	
	/*
	*	Enqueue the admin styles and scripts
	*	@since 0.1
	*/
	public function client_and_product_testimonials_admin_styles( $hook ) {
		global $post;
		// enqueue scripts on post pages w/ post_type => testimonial || post.php/post-new.php to style our modal & add testimonial button
		if( isset( $post ) && $post->post_type == 'testimonial' || ( $hook == 'post-new.php' || $hook == 'edit.php' || $hook == 'post.php' ) ) {
			wp_enqueue_script( 'select2.min.js', Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/js/select2.min.js', array( 'jquery' ), 'all', true );
			wp_enqueue_script( 'client-and-product-testimonial-shortcode-generator-scripts', Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/js/capt-shortcode-generator.js', array( 'select2.min.js' ), 'all', true );
			wp_enqueue_style( 'select2.min.css', Client_Product_Testimonials_URL . 'lib/admin/shortcode-generator/css/select2.min.css' );
			wp_enqueue_style( 'client-and-product-testimonial-admin-styles', Client_Product_Testimonials_URL . 'lib/admin/css/cat-styles.css', array( 'select2.min.css' ) );
		}
	}
	
	/*
	*	Filter 'Set featured image'/'Remove featured image'
	*	@since 0.1
	*/
	public function capt_custom_admin_post_thumbnail_html( $content ) {
	
		$content = str_replace( __( 'Set featured image' ), __( 'Upload User Image' ), $content );
		$content = str_replace( __( 'Remove featured image' ), __( 'Remove User Image' ), $content );
		return $content;
	}
	
	/*
	*	Add custom uploadi mage box to the 'Testimonial' cpt
	*	@since 0.1
	*/
	public function change_image_box() {
		remove_meta_box( 'postimagediv', 'testimonial', 'side' );
		remove_meta_box( 'tagsdiv-testimonial-clients', 'testimonial', 'side' ); // remove the side tags metabox
		remove_meta_box( 'tagsdiv-testimonial-products', 'testimonial', 'side' ); // remove the side tags metabox
		add_meta_box( 'postimagediv', __( 'User Image', 'client-and-product-testimonials' ), 'post_thumbnail_meta_box', 'testimonial', 'side', 'low' );	
	}
	
	/*
	*	Add custom row action to allow users to approve/set to review
	*	@since 0.1
	*/
	public function capt_testimonial_quick_toggle_testimonial_status( $actions, $post ){
		// nonce it too
		if( get_post_type() === 'testimonial' ) {
			if( get_post_status( $post->ID ) == 'pending' ) {
				$publish_url = add_query_arg(
					array(
					  'post_id' => $post->ID,
					  'capt_action' => 'toggle_testimonial_status',
					  'testimonial_status' => 'publish',
					  'nonce' => wp_create_nonce( 'toggle_testimonial_status-' . $post->ID )
					)
				);
				$actions['approve_testimonial'] = '<a href="' . esc_url_raw( $publish_url ) . '" title="' . __( 'Approve this testimonial for display on your site.', 'client-and-product-testimonials' ) . '">' . __( "Approve Testimonial", "client-and-product-testimonials" ) . '</a>';
			} else {
				$set_to_pending_url = add_query_arg(
					array(
					  'post_id' => $post->ID,
					  'capt_action' => 'toggle_testimonial_status',
					  'testimonial_status' => 'pending',
					  'nonce' => wp_create_nonce( 'toggle_testimonial_status-' . $post->ID )
					)
				);
				$actions['pending_testimonial'] = '<a href="' . esc_url_raw( $set_to_pending_url ) . '" title="' . __( 'Mark this testimonial as needing to be reviewed.', 'client-and-product-testimonials' ) . '">' . __( "Needs Review", "client-and-product-testimonials" ) . '</a>';
			}
		}
		return $actions;
	}
	
	/*
	*	Redirect after user toggles status
	*	@since 0.1
	*/
	public function redirect_after_testimonial_status_toggle() {
		if( isset( $_GET['capt_action'] ) && $_GET['capt_action'] == 'toggle_testimonial_status' ) {
			wp_verify_nonce( $_GET['nonce'], 'toggle_testimonial_status-' . $_GET['post_id'] );
			global $wpdb;
			$testimonial_id = (int) $_GET['post_id'];
			$testimonial_object = get_post( $testimonial_id );
			if ( ! $testimonial_object ) return;
			$new_testimonial_status = $_GET['testimonial_status'];		
			$wpdb->update( $wpdb->posts, array( 'post_status' => $new_testimonial_status ), array( 'ID' => $testimonial_id ) );
			clean_post_cache( $testimonial_id );
			$old_testimonial_status = $testimonial_object->post_status;
			wp_transition_post_status( $new_testimonial_status, $old_testimonial_status, $testimonial_object );
			$redirection_url = esc_url_raw( add_query_arg( array( 'post_status' => $new_testimonial_status ), admin_url( 'edit.php?post_type=testimonial' ) ) );
			wp_redirect( $redirection_url, 302 );
			exit;
		}
	}

}
new Register_Testimonial_Post_Type;