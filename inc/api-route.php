<?php


namespace Dashboard\Rest;

use function add_action;
use function register_rest_route;

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
    $acf = get_fields( 'dashboard-settings');
    return array( 'acf' => $acf );
}