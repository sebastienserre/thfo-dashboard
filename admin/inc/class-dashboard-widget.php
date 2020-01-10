<?php


namespace DashboardWP\AdminWidget;

use Dashboard\Helpers\Helpers;
use function _e;
use function _wp_get_current_user;
use function home_url;
use function sanitize_text_field;
use function strtolower;
use function submit_button;
use function var_dump;
use function wp_mail;
use function wp_nonce_field;
use function wp_verify_nonce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

class Dashboard_Widget {
	public function __construct() {
		add_action( 'wp_dashboard_setup', [ $this, 'dashboard_widget' ] );
		add_action( 'admin_init', [ $this, 'send_support_mail' ] );
	}

	public function dashboard_widget() {
		add_meta_box( 'custom_help_widget', 'Informations', [ $this, 'custom_dashboard_help' ], 'dashboard',
			'side', 'high' );
	}

	public function custom_dashboard_help() {
		?>
        <div class="dbwp-admin-widget">
            <div class="dbwp-logo">
				<?php
				$img = Helpers::get_options( 'logo' );
				?>
                <img src="<?php echo $img; ?>">
            </div>
            <div class="tma">
				<?php echo Helpers::get_tma(); ?>
            </div>
            <div class="dbwp_messages">
				<?php echo Helpers::thfo_get_msg(); ?>
            </div>
            <div class="dbwp-admin-widget-support">
                <h3><?php _e( 'Need Support? Ask a Question:', 'dashboard-wp' ); ?></h3>
                <form action="#" method="post">
                    <div class="subject-wrap" id="subject-wrap">
						<label for="subject"><?php _e( 'Subject', 'dashboard-wp' ); ?></label>
						<input type="text" name="subject">
                    </div>
                    <label for="message"><?php _e( 'Message', 'dashboard-wp' ); ?></label>
                    <div class="textarea-wrap" id="description-wrap">
                        <textarea name="message" rows="3" cols="15"></textarea>
                    </div>
					<?php
					wp_nonce_field( 'ask_support', 'nonce_support', true, true );
					submit_button( __( 'Send' ) ); ?>
                </form>
            </div>
            <div class="dbwp-social">
				<?php
				$socials = Helpers::get_options( 'social' );
				echo '<p>';
				foreach ( $socials as $name => $social ) {
					if ( ! empty( $social ) ) {
						?>
                        <a href="<?php echo $social; ?>">
                            <span class="dashicons dashicons-<?php echo $name; ?>"></span>
                        </a>
						<?php
					}
				}
				echo '</p>';
				?>
            </div>
        </div>

		<?php
	}

	public function send_support_mail() {
		if ( empty( $_POST['submit'] ) && empty( $_POST['nonce_support'] ) ) {
			return;
		}

		if ( wp_verify_nonce( $_POST['nonce_support'], 'ask_support' ) ) {
			$current_user = _wp_get_current_user();
			$from         = $current_user->user_email;
			$name         = $current_user->user_nicename;
			$options      = Helpers::get_options( 'social' );
			$headers      = [
				'From: ' . $name . '<' . $from . '>',
				'Content-Type: text/html; charset=UTF-8'
			];
			$to           = $options['mail'];
			$message      = sanitize_text_field( $_POST['message'] ) . '<br/>---<br/>' . __( 'Website: ', 'dashboard-wp' ) .
			                home_url();
			if ( ! empty( $from ) && ! empty( $to ) && ! empty( $message ) && ! empty( $headers ) ) {
				wp_mail( $to, sanitize_text_field( $_POST['subject'] ), $message, $headers );
			}
		}
	}
}

new Dashboard_Widget();
