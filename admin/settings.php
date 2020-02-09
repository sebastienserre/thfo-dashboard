<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

function register_options_pages() {

	// register options page.

	add_submenu_page( 'options-general.php', __( 'Dashboard WP Settings', 'dashboard-wp' ), __( 'Dashboard WP Settings', 'dashboard-wp' ),
		'manage_options', 'dashboard-settings',
		'dbwp_settings' );
}

add_action( 'admin_menu', 'register_options_pages' );

function dbwp_settings() {
	$setting_url = 'options-general.php?page=dashboard-settings&tab=';
	if ( defined( 'MAIN_SITE' ) && MAIN_SITE === home_url() || MAIN_SITE === trailingslashit( home_url() ) ||
	     MAIN_SITE === untrailingslashit( home_url() ) ) {
		$tabs = array(
			'general' => __( 'General', 'dashboard-wp' ),
			'help'    => __( 'Help', 'dashboard-wp' ),
		);
	} else {
		$tabs        = [
			'help' => __( 'Help', 'dashboard-wp' ),
		];
		$_GET['tab'] = 'help';

	}
	$tabs = apply_filters( 'dbwp_settings_tabs', $tabs );

	if ( isset( $_GET['tab'] ) ) {

		$active_tab = $_GET['tab'];

	} else {
		$active_tab = 'general';
	}
	?>
    <div class="wrap">
        <h3><?php echo esc_html( get_admin_page_title() ); ?></h3>
        <h2 class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $tab => $value ) {
				?>
                <a href="<?php echo esc_url( admin_url( $setting_url . $tab ) ); ?>"
                   class="nav-tab <?php echo 'nav-tab-' . $tab;
				   echo $active_tab === $tab ? ' nav-tab-active' : ''; ?>"><?php echo $value ?></a>
			<?php } ?>
        </h2>
        <form method="post" action="options.php">
			<?php $active_tab = apply_filters( 'dbwp_setting_active_tab', $active_tab ); ?>
			<?php
			switch ( $active_tab ) {
				case 'general':
				default:
					settings_fields( 'general-dashboard-wp' );
					do_settings_sections( 'general-dashboard-wp' );
					settings_fields( 'dashboard-wp' );
					do_settings_sections( 'dashboard-wp' );
					submit_button( __( 'Save' ) );
					delete_transient( 'remote-settings' );
					break;
				case 'help':
					settings_fields( 'dashboard-wp-help' );
					do_settings_sections( 'dashboard-wp-help' );
					break;
			}
			?>
        </form>
    </div>
	<?php
}

add_action( 'admin_init', 'dbwp_register_setting' );
function dbwp_register_setting() {
	add_settings_section( 'dashboard-wp-help', __( 'Help Center', 'wp-openagenda' ), 'thfo_openwp_help', 'dashboard-wp-help' );
	add_settings_section( 'dashboard-wp', 'Socials', 'dbwp_socials_network', 'dashboard-wp' );
	add_settings_section( 'general-dashboard-wp', 'Generals', '', 'dashboard-wp' );

	register_setting( 'dashboard-wp', 'dbwp_options ' );
	register_setting( 'dashboard-wp', 'thfo_api_key' );
	add_settings_field( 'openagenda-wp-api', __( 'API Dashboard WP', 'wp-openagenda' ), 'dbwp_api',
		'dashboard-wp', 'general-dashboard-wp' );
}


function dbwp_socials_network() {
	$socials = Dashboard\Helpers\Helpers::dbwp_set_social();
	foreach ( $socials as $social ) {
		add_settings_field( 'dashboard-wp' . $social, $social, function ( $social ) {
			$option       = get_option( 'dbwp_options' );
			$option_value = $option['social'][ $social ];
			if ( ! empty( $option_value ) ) {
				$value = 'value="' . $option_value . '"';
			}
			?>
            <input type="text" name="dbwp_options[social][<?php
			echo $social;
			?>]" <?php echo $value; ?>>
			<?php
		}, 'dashboard-wp', 'dashboard-wp', $social );
	}

	function dbwp_api() {

		if ( '1' === get_option( 'thfo_key_validated' ) ) {
			$text = __( 'Deactivate Key', 'dashboard-wp' );
			$args = [
				'activate' => '2',
				'key'      => get_option( 'thfo_api_key' ),
			];

		} else {
			$text = __( 'Activate Key', 'dashboard-wp' );
			$args = [
				'activate' => '1',
				'key'      => get_option( 'thfo_api_key' ),
			];
		}
		global $wp;
		$activation_url = wp_nonce_url(
			add_query_arg(
				$args,
				admin_url( 'options-general.php?page=dashboard-settings' )
			),
			'validate',
			'_wpnonce'
		);
		?>
        <input type="text" name="thfo_api_key" value="<?php echo esc_html( get_option( 'wp_dashboard_api' ) );
		?>"/>
		<?php $url = esc_url( THFO_WEBSITE_URL ); ?>
		<?php // translators: Add the OpenAGenda URL. ?>
        <p><?php printf( wp_kses( __( 'Find it in your account on <a href="%s" target="_blank">Thivinfo</a>. ', 'dashboard-wp' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) ); ?></p>
        <a href="<?php echo esc_url( $activation_url ); ?>"><?php echo $text; ?></a>
		<?php
	}
}
