<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
if ( ! function_exists( 'sites' ) ) {

// Register Custom Taxonomy
	function sites() {

		$labels = array(
			'name'                       => _x( 'Websites', 'Taxonomy General Name', 'dashboard-wp' ),
			'singular_name'              => _x( 'Website', 'Taxonomy Singular Name', 'dashboard-wp' ),
			'menu_name'                  => __( 'Websites', 'dashboard-wp' ),
			'all_items'                  => __( 'Websites', 'dashboard-wp' ),
			'parent_item'                => __( 'Parent website', 'dashboard-wp' ),
			'parent_item_colon'          => __( 'Parent website:', 'dashboard-wp' ),
			'new_item_name'              => __( 'New website Name', 'dashboard-wp' ),
			'add_new_item'               => __( 'Add New website', 'dashboard-wp' ),
			'edit_item'                  => __( 'Edit website', 'dashboard-wp' ),
			'update_item'                => __( 'Update website', 'dashboard-wp' ),
			'view_item'                  => __( 'View website', 'dashboard-wp' ),
			'separate_items_with_commas' => __( 'Separate website with commas', 'dashboard-wp' ),
			'add_or_remove_items'        => __( 'Add or remove website', 'dashboard-wp' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'dashboard-wp' ),
			'popular_items'              => __( 'Popular website', 'dashboard-wp' ),
			'search_items'               => __( 'Search website', 'dashboard-wp' ),
			'not_found'                  => __( 'Not Found', 'dashboard-wp' ),
			'no_terms'                   => __( 'No website', 'dashboard-wp' ),
			'items_list'                 => __( 'website list', 'dashboard-wp' ),
			'items_list_navigation'      => __( 'website list navigation', 'dashboard-wp' ),
		);
		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'show_in_rest'      => true,
			'query_var'         => true
		);
		register_taxonomy( 'websites', array( 'alert' ), $args );

	}
	add_action( 'init', 'sites', 0 );

}

function add_book_place_columns( $columns ) {
	$columns['tma'] = 'TMA';

	return $columns;
}

add_filter( 'manage_edit-websites_columns', 'add_book_place_columns' );

function add_column_due( $content, $column_name, $term_id ) {
	switch ( $column_name ) {
		case 'tma':
			//do your stuff here with $term or $term_id
			if ( function_exists( 'get_field' ) ) {
				$content = get_field( 'tma_due', 'websites_' . $term_id );
			}
			break;
		default:
			break;
	}

	return $content;
}

add_filter( 'manage_websites_custom_column', 'add_column_due', 10, 3 );
