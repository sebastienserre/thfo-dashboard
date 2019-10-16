<?php
/**
 * Plugin Name:       WordPress Dashboard
 * Plugin URI:        https://thivinfo.com
 * Description:       Thivinfo Custom Dashboard for WordPress.
 * Version:           1.2.7.1
 * Author:            Thivinfo
 * Author URI:        https://thivinfo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dashboard-wp
 * Domain Path:       /languages
 */


add_filter( 'https_local_ssl_verify', '__return_true' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.
register_activation_hook( __FILE__, 'thfo_add_main_constant');


if ( ! function_exists( 'wd_fs' ) ) {
	// Create a helper function for easy SDK access.
	function wd_fs() {
		global $wd_fs;

		if ( ! isset( $wd_fs ) ) {
			// Activate multisite network integration.
			if ( ! defined( 'WP_FS__PRODUCT_4459_MULTISITE' ) ) {
				define( 'WP_FS__PRODUCT_4459_MULTISITE', true );
			}

			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$wd_fs = fs_dynamic_init( array(
				'id'               => '4459',
				'slug'             => 'dashboard-wp',
				'premium_slug'     => 'wp-dashboard-premium',
				'type'             => 'plugin',
				'public_key'       => 'pk_43ec4a588d1370ca6bf57eccbcf41',
				'is_premium'       => true,
				'is_premium_only'  => true,
				'has_addons'       => false,
				'has_paid_plans'   => true,
				'is_org_compliant' => false,
				'trial'            => array(
					'days'               => 30,
					'is_require_payment' => true,
				),
				'has_affiliation'  => 'selected',
				'menu'             => array(
					'slug'    => 'dasboard-wp',
					'support' => false,
					'network' => true,
					'parent'  => array(
						'slug' => 'options-general.php',
					),
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'       => 'sk_5-Y}E%A;;tGEjkC!MWacUcSl&.26+',
			) );
		}

		return $wd_fs;
	}

	// Init Freemius.
	wd_fs();
	// Signal that SDK was initiated.
	do_action( 'wd_fs_loaded' );
}

//i18n (to come shortly)
load_plugin_textdomain( 'dashboard-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
//Admin
if( is_admin() ){
	require_once plugin_dir_path(__FILE__).'admin/dashboard-admin.php';
}

define( 'THFO_DASHBOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'THFO_DASHBOARD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'THFO_DASHBOARD_PLUGIN_DIR', untrailingslashit( THFO_DASHBOARD_PLUGIN_PATH ) );
define( 'DWP_ACF_PATH', THFO_DASHBOARD_PLUGIN_PATH . '/3rd-party/acf/' );
define( 'DWP_ACF_URL', THFO_DASHBOARD_PLUGIN_URL . '/3rd-party/acf/' );


add_action( 'plugins_loaded', 'thfo_bd_load_cpt' );
function thfo_bd_load_cpt() {
	if ( is_admin() && defined( 'MAIN_SITE' ) && 'ToBeDefined' === MAIN_SITE ){
		add_action( 'admin_notices','dbwp_notices' );
	}
	include_once DWP_ACF_PATH . 'acf.php';
	include_once THFO_DASHBOARD_PLUGIN_PATH . 'inc/acf-fields.php';
	include_once THFO_DASHBOARD_PLUGIN_PATH . 'inc/api-route.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/helpers.php';

	if ( defined( 'MAIN_SITE' ) && MAIN_SITE === home_url() || MAIN_SITE === trailingslashit( home_url() ) ||
	     MAIN_SITE === untrailingslashit( home_url() ) ) {
		require_once plugin_dir_path( __FILE__ ) . 'inc/alert-cpt.php';
		require_once plugin_dir_path( __FILE__ ) . 'inc/website-taxo.php';

		include_once THFO_DASHBOARD_PLUGIN_PATH . 'admin/settings.php';

	}
}

function dbwp_notices(){
	?>
	<div class="notice notice-error">
		<p><?php _e( __( 'Please adapt the constant MAIN_SITE actually called ToBeDefined in your wp-config.php', 'dashboard_wp' ) ); ?></p>
	</div>
	<?php
}

/**
 *
 * Enqueue styles and scripts
 *
 */

add_action( 'admin_enqueue_scripts', 'thivinfo_enqueue_scripts_admin' );
function thivinfo_enqueue_scripts_admin() {
	wp_enqueue_script( 'thivinfodashboard-admin-scripts', THFO_DASHBOARD_PLUGIN_URL . 'admin/js/thivinfodashboard-admin.js', array( 'jquery' ), '', true );
}

function thfo_add_main_constant() {
	if ( file_exists (ABSPATH . "wp-config.php") && is_writable (ABSPATH . "wp-config.php") ) {
		if ( ! defined( 'MAIN_SITE' ) ) {
			$filesystem = thfo_get_filesystem();
			$config     = file_get_contents( ABSPATH . 'wp-config.php' );
			$config     = preg_replace( "/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php\nif ( ! defined( 'MAIN_SITE') ) {\ndefine('MAIN_SITE', 'https://thivinfo.com');\n}\n", $config );
			$filesystem->put_contents( ABSPATH . 'wp-config.php', $config );
		}else {
			return;
		}
	}else {
		return;
	}
}

/**
 * @return WP_Filesystem_Direct
 * @author  Sébastien SERRE
 * @package dashboard-wp
 * @since   1.2.0
 */
function thfo_get_filesystem() {
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

add_filter('acf/settings/url', 'dbwp_acf_settings_url');
function dbwp_acf_settings_url( $url ) {
	return DWP_ACF_URL;
}

add_filter('acf/settings/show_admin', 'dbwp_acf_settings_show_admin');
function dbwp_acf_settings_show_admin( $show_admin ) {
	if ( defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
		return false;
	} else {
		return true;
	}
}

/**
 * @return string $main_url This is the url where the data come from.
 * @author Sébastien Serre
 * @since 1.2
 */
function get_main_url() {
	$main_url = get_field( 'dbwp_main_site', 'dashboard-settings' );

	/**
	 * Filters the main URL
	 * This is the url where the data come from.
	 */
	return apply_filters( 'dbwp_set_main_url', $main_url );
}

add_action( 'admin_menu', 'wpdb_admin_menu' );
function wpdb_admin_menu() {
	add_submenu_page(
		'options-general.php',
		'Dashboard WP',
		'Dashboard WP',
		'manage_options',
		'dasboard-wp',
		'wpdb_admin_menu_render'
	);
}

function wpdb_admin_menu_render() {

}