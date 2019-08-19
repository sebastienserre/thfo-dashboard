<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
/**
 * Get WP Direct filesystem object. Also define chmod constants if not done yet.
 *
 * @since 1.0
 * @since 1.3 Don't use the global Filesystem anymore, to make sure to use "direct" (some things don't work over "ftp").
 *
 * @return `$wp_filesystem` object.
 */
function thfo_get_filesystem() {
	static $filesystem;

	if ( $filesystem ) {
		return $filesystem;
	}

	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );

	$filesystem = new WP_Filesystem_Direct( new StdClass() ); // WPCS: override ok.

	// Set the permission constants if not already set.
	if ( ! defined( 'FS_CHMOD_DIR' ) ) {
		define( 'FS_CHMOD_DIR', ( @fileperms( ABSPATH ) & 0777 | 0755 ) );
	}
	if ( ! defined( 'FS_CHMOD_FILE' ) ) {
		define( 'FS_CHMOD_FILE', ( @fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
	}

	return $filesystem;
}
