<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
if ( ! function_exists( 'sites' ) ) {

// Register Custom Taxonomy
	function sites() {

		$labels = array(
			'name'                       => _x( 'Websites', 'Taxonomy General Name', 'thivinfo-dashboard' ),
			'singular_name'              => _x( 'Website', 'Taxonomy Singular Name', 'thivinfo-dashboard' ),
			'menu_name'                  => __( 'Websites', 'thivinfo-dashboard' ),
			'all_items'                  => __( 'Websites', 'thivinfo-dashboard' ),
			'parent_item'                => __( 'Parent website', 'thivinfo-dashboard' ),
			'parent_item_colon'          => __( 'Parent website:', 'thivinfo-dashboard' ),
			'new_item_name'              => __( 'New website Name', 'thivinfo-dashboard' ),
			'add_new_item'               => __( 'Add New website', 'thivinfo-dashboard' ),
			'edit_item'                  => __( 'Edit website', 'thivinfo-dashboard' ),
			'update_item'                => __( 'Update website', 'thivinfo-dashboard' ),
			'view_item'                  => __( 'View website', 'thivinfo-dashboard' ),
			'separate_items_with_commas' => __( 'Separate website with commas', 'thivinfo-dashboard' ),
			'add_or_remove_items'        => __( 'Add or remove website', 'thivinfo-dashboard' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'thivinfo-dashboard' ),
			'popular_items'              => __( 'Popular website', 'thivinfo-dashboard' ),
			'search_items'               => __( 'Search website', 'thivinfo-dashboard' ),
			'not_found'                  => __( 'Not Found', 'thivinfo-dashboard' ),
			'no_terms'                   => __( 'No website', 'thivinfo-dashboard' ),
			'items_list'                 => __( 'website list', 'thivinfo-dashboard' ),
			'items_list_navigation'      => __( 'website list navigation', 'thivinfo-dashboard' ),
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