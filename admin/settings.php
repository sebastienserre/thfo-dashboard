<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

function register_acf_options_pages() {

	// Check function exists.
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	// register options page.
	$option_page = acf_add_options_sub_page(
		[
			'page_title' => __( 'Settings' ),
			'menu_title' => __( 'Settings' ),
			'menu_slug'  => 'dashboard-settings',
			'parent'     => 'edit.php?post_type=alert',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'post_id'    => 'dashboard-settings'
		]
	);
}

// Hook into acf initialization.
add_action('acf/init', 'register_acf_options_pages');

// register the endpoint
//add_action( 'rest_api_init', 'dashboard_create_settings_route' );
function dashboard_create_settings_route() {
	register_rest_route(
		'dashboard-settings/v3',
		'/settings/',
		[
			'methods'  => 'GET',
			'callback' => 'dashboard_acf_settings',
		]
	);
}

function dashboard_acf_settings( $request_data ){
	$fields = get_fields( 'options');
	var_dump( $fields);
}