<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! function_exists( 'alert' ) ) {

// Register Custom Post Type
	function alert() {

		$labels = array(
			'name'                  => _x( 'Alerts', 'Post Type General Name', 'thivinfo_dashboard' ),
			'singular_name'         => _x( 'Alert', 'Post Type Singular Name', 'thivinfo_dashboard' ),
			'menu_name'             => __( 'Alerts', 'thivinfo_dashboard' ),
			'name_admin_bar'        => __( 'Alert', 'thivinfo_dashboard' ),
			'archives'              => __( 'Alert Archives', 'thivinfo_dashboard' ),
			'attributes'            => __( 'Alert Attributes', 'thivinfo_dashboard' ),
			'parent_item_colon'     => __( 'Parent Alert:', 'thivinfo_dashboard' ),
			'all_items'             => __( 'All Alerts', 'thivinfo_dashboard' ),
			'add_new_item'          => __( 'Add New Alert', 'thivinfo_dashboard' ),
			'add_new'               => __( 'Add New', 'thivinfo_dashboard' ),
			'new_item'              => __( 'New Alert', 'thivinfo_dashboard' ),
			'edit_item'             => __( 'Edit Alert', 'thivinfo_dashboard' ),
			'update_item'           => __( 'Update Alert', 'thivinfo_dashboard' ),
			'view_item'             => __( 'View Alert', 'thivinfo_dashboard' ),
			'view_items'            => __( 'View Alerts', 'thivinfo_dashboard' ),
			'search_items'          => __( 'Search Alert', 'thivinfo_dashboard' ),
			'not_found'             => __( 'Not found', 'thivinfo_dashboard' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'thivinfo_dashboard' ),
			'featured_image'        => __( 'Featured Image', 'thivinfo_dashboard' ),
			'set_featured_image'    => __( 'Set featured image', 'thivinfo_dashboard' ),
			'remove_featured_image' => __( 'Remove featured image', 'thivinfo_dashboard' ),
			'use_featured_image'    => __( 'Use as featured image', 'thivinfo_dashboard' ),
			'insert_into_item'      => __( 'Insert into item', 'thivinfo_dashboard' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'thivinfo_dashboard' ),
			'items_list'            => __( 'Alerts list', 'thivinfo_dashboard' ),
			'items_list_navigation' => __( 'Alerts list navigation', 'thivinfo_dashboard' ),
			'filter_items_list'     => __( 'Filter items list', 'thivinfo_dashboard' ),
		);
		$args   = array(
			'label'               => __( 'Alert', 'thivinfo_dashboard' ),
			'description'         => __( 'Alert', 'thivinfo_dashboard' ),
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