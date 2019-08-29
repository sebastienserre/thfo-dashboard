<?php


namespace Dashboard\Rest;

use function add_action;
use function delete_transient;
use function get_transient;
use function register_rest_route;
use function set_transient;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.

add_action( 'rest_api_init', __NAMESPACE__ .  '\register_route' );

function register_route() {
    $namespace = 'dashboard-wp/v1';
    $register  = register_rest_route(
        $namespace,
        'dashboard-settings',
        [
            'methods'  => 'POST',
            'callback' => __NAMESPACE__ . '\\get_acf_settings',
        ]
    );
}

function get_acf_settings(  $resquest ) {
	$acf = get_transient( 'remote-settings' );
	if ( empty( $acf ) ) {
		$acf['acf'] = get_fields( 'dashboard-settings' );
		set_transient( 'remote-settings', $acf, 86400 );
	}
    return $acf;
}