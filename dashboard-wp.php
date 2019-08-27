<?php
/**
 * Plugin Name:       WordPress Dashboard
 * Plugin URI:        https://thivinfo.com
 * Description:       Thivinfo Custom Dashboard for WordPress.
 * Version:           1.1
 * Author:            Thivinfo
 * Author URI:        https://thivinfo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-dashboard
 * Domain Path:       /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.



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
			require_once dirname(__FILE__) . '/freemius/start.php';

			$wd_fs = fs_dynamic_init( array(
				'id'                  => '4459',
				'slug'                => 'dashboard-wp',
				'premium_slug'        => 'wp-dashboard-premium',
				'type'                => 'plugin',
				'public_key'          => 'pk_43ec4a588d1370ca6bf57eccbcf41',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'is_org_compliant'    => false,
				'menu'                => array(
					'first-path'     => 'plugins.php',
					'support'        => false,
				),
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
	require_once plugin_dir_path(__FILE__).'admin/thivinfodashboard-admin.php';
}



define( 'THFO_DASHBOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'THFO_DASHBOARD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'THFO_DASHBOARD_PLUGIN_DIR', untrailingslashit( THFO_DASHBOARD_PLUGIN_PATH ) );

add_action( 'plugins_loaded', 'thfo_bd_load_cpt' );
function thfo_bd_load_cpt() {
	if ( defined( 'MAIN_SITE' ) && MAIN_SITE === home_url() ) {
		require_once plugin_dir_path( __FILE__ ) . 'inc/alert-cpt.php';
		require_once plugin_dir_path( __FILE__ ) . 'inc/website-taxo.php';
	}
}

add_action( 'plugins_loaded', 'thfo_db_load_file' );
function thfo_db_load_file(){
	require_once plugin_dir_path( __FILE__ ) . 'inc/helpers.php';

}
/**
 *
 * Enqueue styles and scripts
 *
 */
add_action( 'admin_enqueue_scripts', 'thivinfo_enqueue_styles_admin' );
function thivinfo_enqueue_styles_admin() {
	wp_enqueue_style( 'thivinfodashboard-admin-styles', THFO_DASHBOARD_PLUGIN_URL . 'admin/css/thivinfodashboard-admin.css', array(), '', 'all' );
}

add_action( 'admin_enqueue_scripts', 'thivinfo_enqueue_scripts_admin' );
function thivinfo_enqueue_scripts_admin() {
	wp_enqueue_script( 'thivinfodashboard-admin-scripts', THFO_DASHBOARD_PLUGIN_URL . 'admin/js/thivinfodashboard-admin.js', array( 'jquery' ), '', true );
}

add_action( 'plugins_loaded', 'thfo_add_main_constant' );
function thfo_add_main_constant() {
	if ( file_exists (ABSPATH . "wp-config.php") && is_writable (ABSPATH . "wp-config.php") ) {
		if ( ! defined( 'MAIN_SITE' ) ) {
			$filesystem = Dashboard\Helpers\Helpers::thfo_get_filesystem();
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
