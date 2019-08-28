<?php

namespace Dashboard\Helpers;

use function add_option;
use function apply_filters;
use function get_option;
use function get_transient;
use function is_array;
use function sanitize_title;
use function self_admin_url;
use function set_transient;
use function stripslashes;
use function thfo_retrieve_alert;
use function untrailingslashit;
use function var_dump;
use function wp_remote_get;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;
use const HOUR_IN_SECONDS;
use const MAIN_SITE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Class Helpers
 *
 * @package Dashboard\Helpers
 * @author  sebastienserre
 * @since   1.0.0
 */
class Helpers {

	protected static $options;

	public function __construct() {
		self::$options = self::dbwp_get_options();
	}

	/**
	 * Get WP Direct filesystem object. Also define chmod constants if not done yet.
	 *
	 * @return `$wp_filesystem` object.
	 * @since  1.3 Don't use the global Filesystem anymore, to make sure to use "direct" (some things don't work over
	 *         "ftp").
	 *
	 * @since  1.0
	 */
	public static function thfo_get_filesystem() {
		static $filesystem;

		if ( $filesystem ) {
			return $filesystem;
		}

		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );

		$filesystem = new \WP_Filesystem_Direct( new \StdClass() ); // WPCS: override ok.

		// Set the permission constants if not already set.
		if ( ! defined( 'FS_CHMOD_DIR' ) ) {
			define( 'FS_CHMOD_DIR', ( @fileperms( ABSPATH ) & 0777 | 0755 ) );
		}
		if ( ! defined( 'FS_CHMOD_FILE' ) ) {
			define( 'FS_CHMOD_FILE', ( @fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
		}

		return $filesystem;
	}

	/**
	 * Get remote Term_id
	 *
	 * @param $slug string Website slug
	 *
	 * @return $id int term_id
	 */
	public static function get_term_id( $slug ) {
		$main_url = untrailingslashit( MAIN_SITE );
		$json     = wp_remote_get( "$main_url/wp-json/wp/v2/websites?slug=$slug" );
		if ( 200 === (int) wp_remote_retrieve_response_code( $json ) ) {

			$body         = wp_remote_retrieve_body( $json );
			$decoded_body = json_decode( $body, true );
		}
		if ( 404 === (int) wp_remote_retrieve_response_code( $json ) ) {
			return;
		}
		if ( ! empty( $decoded_body ) ) {
			foreach ( $decoded_body as $decoded ) {
				$id = $decoded['id'];
			}
			if ( ! empty( $id ) ) {
				return $id;
			}
		}

	}

	/**
	 * Return the list of alerts
	 *
	 * @return array List of alerts
	 */
	public static function thfo_retrieve_alert( $site = '' ) {

		$main_url = stripslashes( MAIN_SITE );
		$id       = self::get_term_id( sanitize_title( $site ) );
		if ( empty( $id ) ) {
			$decoded_body = [];

			return $decoded_body;
		}
		$json = wp_remote_get( "$main_url/wp-json/wp/v2/alert?websites=$id&orderby=date&order=desc&lang=fr" );
		if ( 200 === (int) wp_remote_retrieve_response_code( $json ) ) {

			$body         = wp_remote_retrieve_body( $json );
			$decoded_body = json_decode( $body, true );
		}

		return $decoded_body;
	}

	/**
	 * Display alert
	 *
	 * @param string $content Type of content
	 */
	public static function thfo_get_msg( $content = '' ) {
		$current_site = home_url();
		$decoded_body = self::thfo_retrieve_alert( $current_site );
		foreach ( $decoded_body as $alert ) {
			if ( ! empty( $alert ) && 'general' !== $alert['slug'] ) {
				$decoded[ $alert['slug'] ]['content'] = $alert['content']['rendered'];
				$decoded[ $alert['slug'] ]['title']   = $alert['title']['rendered'];
			}
		}
		if ( ! empty( $decoded ) ) {
			foreach ( $decoded as $current_alert ) {
				?>
                <div class="alert-msg">
                    <h3><?php echo $current_alert['title']; ?></h3>
					<?php echo $current_alert['content']; ?>
                </div>
				<?php
			}
		}
	}


	public static function thfo_get_general_msg() {
		$decoded = get_transient( 'dashboard-general-msg' );
		if ( empty( $decoded ) ) {
			$decoded_body = self::thfo_retrieve_alert();
			foreach ( $decoded_body as $alert ) {
				if ( 'general' === $alert['slug'] ) {
					$decoded[ $alert['slug'] ] = $alert['content']['rendered'];
					set_transient( 'dashboard-general-msg', $decoded, HOUR_IN_SECONDS * 12 );
				}
			}
		}
		if ( ! empty( $decoded ) ) {
			foreach ( $decoded as $current_alert ) {
				echo '<div class="general">' . $current_alert . '</div>';
			}
		}
	}

	public static function dbwp_get_options() {
		$json = wp_remote_get( untrailingslashit( MAIN_SITE ) . '/wp-json/acf/v3/options/dashboard-settings' );
		if ( 200 === (int) wp_remote_retrieve_response_code( $json ) || 404 === (int) wp_remote_retrieve_response_code( $json ) ) {
			$body    = wp_remote_retrieve_body( $json );
			$options = json_decode( $body, true );
		}
		if ( 404 === (int) wp_remote_retrieve_response_code( $json ) ){
		    $options = __( $options['message'], 'dashboard-wp' );
		    //$options[] = $options;
        }

		if ( ! is_array( $options ) ){
		    $options = array( $options);
        }
		return $options;
	}

	/**
	 * Get WP Dashboard Options from remote site
	 *
	 * @param string $options Possible Options: 'welcome', 'slogan', 'social', 'posts', 'logo', 'css.
	 *
	 * @return string
	 *
	 * @since  1.2.0
	 * @author sebastienserre
	 */
	public static function get_options( $options ) {
		if ( empty( self::$options['acf'] ) ) {


			    return sprintf( __( 'Please activate Dashboard WordPress on %1$s', 'dashboard-wp' ), MAIN_SITE );
		    }

		switch ( $options ) {
			case 'welcome':
				$data = self::$options['acf']['dbwp_welcome_message']['dbwp_title'];
				break;
			case 'slogan':
				$data = self::$options['acf']['dbwp_welcome_message']['dbwp_slogan'];
				break;
			case 'social':
				$data = self::$options['acf']['dbwp_social'];
				break;
			case 'posts':
				$data = self::$options['acf']['dbwp_posts'];
				break;
			case 'logo':
				$data = self::$options['acf']['dbwp_logo'];
				break;
			case 'css':
				$data = self::$options['acf']['dbwp_css'];
				break;
			default:
				$data = __( 'Information missing in Main Site Settings', 'dashboard-wp' );
				break;
		}

		return $data;
	}

}

new Helpers();
