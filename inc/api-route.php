<?php


namespace Dashboard\Rest;

use function add_action;
use function delete_transient;
use function get_attachment_fields_to_edit;
use function get_field;
use function get_option;
use function get_post_meta;
use function get_transient;
use function register_rest_field;
use function register_rest_route;
use function set_transient;
use function strpos;
use function var_dump;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

add_action( 'rest_api_init', __NAMESPACE__ . '\register_route' );

function register_route() {
	$namespace = 'dashboard-wp/v1';
	$register  = register_rest_route(
		$namespace,
		'dashboard-settings',
		[
			'methods'  => 'POST',
			'callback' => __NAMESPACE__ . '\\get_settings',
		]
	);
}

function get_settings( $resquest ) {
	$remote = get_transient( 'remote-settings' );
	if ( empty( $remote ) ) {
		$remote = get_option( 'dbwp_options' );
		set_transient( 'remote-settings', $remote, 86400 );
	}

	return $remote;
}

add_action( 'acf/save_post', __NAMESPACE__ . '\\delete_transients_on_saving', 15 );
/**
 * Delete Transients on settings saving
 *
 * @param $post_id
 *
 * @author Sébastien Serre
 * @since  1.2.0
 */
function delete_transients_on_saving( $post_id ) {
	$screen = get_current_screen();
	if ( strpos( $screen->id, 'alert_page_dashboard-settings' ) >= 0 ) {
		delete_transient( 'remote-settings' );
	}
}

add_action( 'rest_api_init', __NAMESPACE__ . '\\register_api_field' );
function register_api_field() {
	/**
	 * Add post meta to Rest API
	 *
	 * @since 1.2.3
	 */
	register_rest_field(
		'alert',
		'metadata',
		[
			'get_callback' => function ( $data ) {
				return get_post_meta( $data['id'], '', '' );
			},
		]
	);

	/**
	 * Add term meta to Rest API
	 *
	 * @since 1.2.4
	 */
	register_rest_field(
		'websites',
		'tma_date',
		[
			'get_callback' => function ( $data ) {
				return get_field( 'tma_date', 'term_' . $data['id'] );
			},
		]
	);

	register_rest_field(
		'websites',
		'tma_due',
		[
			'get_callback' => function ( $data ) {
				return get_field( 'tma_due', 'term_' . $data['id'] );
			},
		]
	);
}