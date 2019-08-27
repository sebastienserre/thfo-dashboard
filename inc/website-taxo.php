<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
if ( ! function_exists( 'sites' ) ) {

// Register Custom Taxonomy
	function sites() {

		$labels = array(
			'name'                       => _x( 'Websites', 'Taxonomy General Name', 'wp-dashboard' ),
			'singular_name'              => _x( 'Website', 'Taxonomy Singular Name', 'wp-dashboard' ),
			'menu_name'                  => __( 'Websites', 'wp-dashboard' ),
			'all_items'                  => __( 'Websites', 'wp-dashboard' ),
			'parent_item'                => __( 'Parent website', 'wp-dashboard' ),
			'parent_item_colon'          => __( 'Parent website:', 'wp-dashboard' ),
			'new_item_name'              => __( 'New website Name', 'wp-dashboard' ),
			'add_new_item'               => __( 'Add New website', 'wp-dashboard' ),
			'edit_item'                  => __( 'Edit website', 'wp-dashboard' ),
			'update_item'                => __( 'Update website', 'wp-dashboard' ),
			'view_item'                  => __( 'View website', 'wp-dashboard' ),
			'separate_items_with_commas' => __( 'Separate website with commas', 'wp-dashboard' ),
			'add_or_remove_items'        => __( 'Add or remove website', 'wp-dashboard' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wp-dashboard' ),
			'popular_items'              => __( 'Popular website', 'wp-dashboard' ),
			'search_items'               => __( 'Search website', 'wp-dashboard' ),
			'not_found'                  => __( 'Not Found', 'wp-dashboard' ),
			'no_terms'                   => __( 'No website', 'wp-dashboard' ),
			'items_list'                 => __( 'website list', 'wp-dashboard' ),
			'items_list_navigation'      => __( 'website list navigation', 'wp-dashboard' ),
		);
		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'query_var'         => true
		);
		register_taxonomy( 'websites', array( 'alert' ), $args );

	}
	add_action( 'init', 'sites', 0 );

}