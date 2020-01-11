<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! function_exists( 'alert' ) ) {

// Register Custom Post Type
	function alert() {

		$labels = array(
			'name'                  => _x( 'Alerts', 'Post Type General Name', 'dashboard-wp' ),
			'singular_name'         => _x( 'Alert', 'Post Type Singular Name', 'dashboard-wp' ),
			'menu_name'             => __( 'Alerts', 'dashboard-wp' ),
			'name_admin_bar'        => __( 'Alert', 'dashboard-wp' ),
			'archives'              => __( 'Alert Archives', 'dashboard-wp' ),
			'attributes'            => __( 'Alert Attributes', 'dashboard-wp' ),
			'parent_item_colon'     => __( 'Parent Alert:', 'dashboard-wp' ),
			'all_items'             => __( 'All Alerts', 'dashboard-wp' ),
			'add_new_item'          => __( 'Add New Alert', 'dashboard-wp' ),
			'add_new'               => __( 'Add New', 'dashboard-wp' ),
			'new_item'              => __( 'New Alert', 'dashboard-wp' ),
			'edit_item'             => __( 'Edit Alert', 'dashboard-wp' ),
			'update_item'           => __( 'Update Alert', 'dashboard-wp' ),
			'view_item'             => __( 'View Alert', 'dashboard-wp' ),
			'view_items'            => __( 'View Alerts', 'dashboard-wp' ),
			'search_items'          => __( 'Search Alert', 'dashboard-wp' ),
			'not_found'             => __( 'Not found', 'dashboard-wp' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'dashboard-wp' ),
			'featured_image'        => __( 'Featured Image', 'dashboard-wp' ),
			'set_featured_image'    => __( 'Set featured image', 'dashboard-wp' ),
			'remove_featured_image' => __( 'Remove featured image', 'dashboard-wp' ),
			'use_featured_image'    => __( 'Use as featured image', 'dashboard-wp' ),
			'insert_into_item'      => __( 'Insert into item', 'dashboard-wp' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'dashboard-wp' ),
			'items_list'            => __( 'Alerts list', 'dashboard-wp' ),
			'items_list_navigation' => __( 'Alerts list navigation', 'dashboard-wp' ),
			'filter_items_list'     => __( 'Filter items list', 'dashboard-wp' ),
		);
		$args   = array(
			'label'               => __( 'Alert', 'dashboard-wp' ),
			'description'         => __( 'Alert', 'dashboard-wp' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           =>  'dashicons-warning',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		);

		register_post_type( 'alert', $args );

	}

	add_action( 'init', 'alert', 0 );

}
