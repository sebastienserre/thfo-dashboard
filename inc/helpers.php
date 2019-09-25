<?php

namespace Dashboard\Helpers;

use function apply_filters;
use function esc_attr;
use function get_current_screen;
use function get_field;
use function get_transient;
use function is_array;
use function is_wp_error;
use function json_decode;
use function sanitize_title;
use function set_transient;
use function stripslashes;
use function untrailingslashit;
use function wp_enqueue_style;
use function wp_remote_get;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;
use const HOUR_IN_SECONDS;
use const MAIN_SITE;
use const THFO_DASHBOARD_PLUGIN_URL;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Class Helpers
 *
 * @package dashboard-wp
 * @author  sebastienserre
 * @since   1.0.0
 */
class Helpers {

	protected static $options;

	public function __construct() {
		self::$options = self::dbwp_get_options();

		add_action( 'admin_enqueue_scripts', [ 'Dashboard\Helpers\Helpers', 'load_admin_css' ] );
	}

	/**
	 * Get WP Direct filesystem object. Also define chmod constants if not done yet.
	 *
	 * @return `$wp_filesystem` object.
	 * @since  1.2.0
	 * @author Sébastien Serre
     * @package dashboard-wp
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
	 * @since  1.2.0
	 * @author Sébastien Serre
	 * @package dashboard-wp
	 */
	public static function get_term_id( $slug ) {
		if ( empty( $slug ) ) {
			return;
		}
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
	 * @since  1.2.0
	 * @author Sébastien Serre
	 * @package dashboard-wp
     *
	 */
	public static function thfo_retrieve_alert( $site = '' ) {

		$main_url = stripslashes( MAIN_SITE );
		$id       = self::get_term_id( sanitize_title( $site ) );
		if ( empty( $id ) ) {
			$decoded_body = [];

			return $decoded_body;
		}
		$json = wp_remote_get( "$main_url/wp-json/wp/v2/alert?websites=$id&orderby=date&order=desc" );
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
     * @since  1.2.0
	 * @author Sébastien Serre
	 * @package dashboard-wp
	 */
	public static function thfo_get_msg( $content = '' ) {
		$current_site = home_url();
		$decoded_body = self::thfo_retrieve_alert( $current_site );
		foreach ( $decoded_body as $alert ) {
			if ( ! empty( $alert ) ) {
				$decoded[ $alert['slug'] ]['id']      = $alert['id'];
				$decoded[ $alert['slug'] ]['content'] = $alert['content']['rendered'];
				$decoded[ $alert['slug'] ]['title']   = $alert['title']['rendered'];
			}
		}
		if ( ! empty( $decoded ) ) {
			foreach ( $decoded as $current_alert ) {
				$important = get_field( 'wp_dashboard_important' );
				if ( ! empty( 'yes' === $important ) ) {
					$class = 'important-notice';
				} else {
					$class = '';
				}
				?>
                <div class="alert-msg <?php echo $class; ?>">
                    <h3><?php echo $current_alert['title']; ?></h3>
					<?php echo $current_alert['content']; ?>
                </div>
				<?php
			}
		}
	}

	/**
     * Get Options from remote main website
	 * @return array array with Remote ACF Options
	 * @since  1.2.0
	 * @author Sébastien Serre
	 * @package dashboard-wp
	 */
	public static function dbwp_get_options() {
		$options = get_transient( 'remote-settings' );

		if ( empty( $options ) ) {
			$json = wp_remote_post(
				untrailingslashit( MAIN_SITE ) . '/wp-json/dashboard-wp/v1/dashboard-settings',
				[
					'timeout'    => 20,
					'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:70.0) Gecko/20100101 Firefox/70.0',
				]
			);

			if ( 200 === (int) wp_remote_retrieve_response_code( $json ) || 404 === (int)
				wp_remote_retrieve_response_code( $json ) ) {
				$body    = wp_remote_retrieve_body( $json );
				$options = json_decode( $body, true );
				set_transient( 'remote_settings', $options, 86400 );
			}

			if ( 404 === (int) wp_remote_retrieve_response_code( $json ) ) {
				$options = __( $options['message'], 'dashboard-wp' );
			}
		}

		if ( ! is_array( $options ) && false !== $options ) {
			$options = array( $options );
		}

		return $options;
	}

	/**
	 * Get WP Dashboard Options from remote site
	 *
	 * @param string $options Possible Options: 'welcome', 'slogan', 'social', 'posts', 'logo', 'css', 'custom_css'.
	 *
	 * @return string
	 *
	 * @since  1.2.0
	 * @author Sébastien Serre
	 * @package dashboard-wp
	 */
	public static function get_options( $options ) {
		/*if ( empty( self::$options['acf'] ) ) {
			return sprintf( __( 'Please activate Dashboard WordPress on %1$s', 'dashboard-wp' ), MAIN_SITE );
		}*/

		switch ( $options ) {
			case 'welcome':
				$data = self::$options['acf']['dbwp_welcome_message'][0]['dbwp_title'];
				break;
			case 'slogan':
				$data = self::$options['acf']['dbwp_welcome_message'][0]['dbwp_slogan'];
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
			case 'custom_css':
				$data = self::$options['acf']['dbwp_custom_css'];
				break;
			default:
				$data = __( 'Information missing in Main Site Settings', 'dashboard-wp' );
				break;
		}

		return $data;
	}

	/**
	 * Display remote Post.
     * @author Sébastien Serre
     * @package dashboard-wp
     * @since 1.2.0
	 */
	public static function get_remote_posts() {
		$opt  = self::$options['acf'];
		$cpts = $opt['dbwp_posts'];
		if ( ! empty( $cpts ) ) {
			$nb = $opt['dbwp_nb_post'];
			if ( null === $nb ){
			    $nb = 5;
            }
			foreach ( $cpts as $cpt ) {
				$url      = untrailingslashit( MAIN_SITE ) . '/wp-json/wp/v2/' . $cpt . '/?per_page=' . $nb .
				            '&orderby=date&order=desc&lang=fr';
				$response = wp_remote_get( $url );
				if ( ! is_wp_error( $response ) ) {

					$posts = json_decode( wp_remote_retrieve_body( $response ) );
					if ( ! empty( $posts ) ) {
						/**
						 * Filter the CPT name. By default its the Post name
						 *
						 * @author Sébastien Serre
						 * @since  1.2.0
						 */
						?>
                        <h3>
							<?php
							$label = $cpt;
							echo $label = apply_filters( 'custom_remote_post_title', esc_attr( $label ) );
							?>
                        </h3>
                        <ul>
							<?php
							foreach ( $posts as $p ) {
								?>
                                <li><a href="<?php echo $p->link; ?>"><?php echo $p->title->rendered; ?></a></li>
								<?php
							}
							?>
                        </ul>
						<?php
					}
				}
			}
		}
	}

	/**
	 * Load custom CSS
     * @author Sébastien Serre
     * @package dashboard-wp
     * @since 1.2.0
	 */
	public static function load_admin_css() {
		if ( get_current_screen()->base !== 'dashboard' ) {
			return;
		}
		$css = self::get_options( 'css' );
		if ( !empty( $css ) ){
			wp_enqueue_style( 'dashboard_wp', $css );
		} else {
			wp_enqueue_style( 'dashboard_wp', THFO_DASHBOARD_PLUGIN_URL . 'admin/css/dashboard-admin.css' );
		}
	}

}

new Helpers();
