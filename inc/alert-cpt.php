<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! function_exists( 'alert' ) ) {

// Register Custom Post Type
	function alert() {

		$labels = array(
			'name'                  => _x( 'Alerts', 'Post Type General Name', 'wp-dashboard' ),
			'singular_name'         => _x( 'Alert', 'Post Type Singular Name', 'wp-dashboard' ),
			'menu_name'             => __( 'Alerts', 'wp-dashboard' ),
			'name_admin_bar'        => __( 'Alert', 'wp-dashboard' ),
			'archives'              => __( 'Alert Archives', 'wp-dashboard' ),
			'attributes'            => __( 'Alert Attributes', 'wp-dashboard' ),
			'parent_item_colon'     => __( 'Parent Alert:', 'wp-dashboard' ),
			'all_items'             => __( 'All Alerts', 'wp-dashboard' ),
			'add_new_item'          => __( 'Add New Alert', 'wp-dashboard' ),
			'add_new'               => __( 'Add New', 'wp-dashboard' ),
			'new_item'              => __( 'New Alert', 'wp-dashboard' ),
			'edit_item'             => __( 'Edit Alert', 'wp-dashboard' ),
			'update_item'           => __( 'Update Alert', 'wp-dashboard' ),
			'view_item'             => __( 'View Alert', 'wp-dashboard' ),
			'view_items'            => __( 'View Alerts', 'wp-dashboard' ),
			'search_items'          => __( 'Search Alert', 'wp-dashboard' ),
			'not_found'             => __( 'Not found', 'wp-dashboard' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp-dashboard' ),
			'featured_image'        => __( 'Featured Image', 'wp-dashboard' ),
			'set_featured_image'    => __( 'Set featured image', 'wp-dashboard' ),
			'remove_featured_image' => __( 'Remove featured image', 'wp-dashboard' ),
			'use_featured_image'    => __( 'Use as featured image', 'wp-dashboard' ),
			'insert_into_item'      => __( 'Insert into item', 'wp-dashboard' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'wp-dashboard' ),
			'items_list'            => __( 'Alerts list', 'wp-dashboard' ),
			'items_list_navigation' => __( 'Alerts list navigation', 'wp-dashboard' ),
			'filter_items_list'     => __( 'Filter items list', 'wp-dashboard' ),
		);
		$args   = array(
			'label'               => __( 'Alert', 'wp-dashboard' ),
			'description'         => __( 'Alert', 'wp-dashboard' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon' =>  'dashicons-warning',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		);
		register_post_type( 'alert', $args );

	}

	add_action( 'init', 'alert', 0 );

}