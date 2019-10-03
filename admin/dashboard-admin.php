<?php

use Dashboard\Helpers\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

//add_action( 'admin_menu', 'dbwp_disable_default_dashboard_widgets' );
/**
 *
 * Remove useless stuff
 *
 */
function dbwp_disable_default_dashboard_widgets() {
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' );
}

/**
 *
 * Add main widget
 *
 */
add_action( 'admin_footer', 'dbwp_main_dashboard_widget' );
function dbwp_main_dashboard_widget() {
	// Kickout this if not viewing the main dashboard page
	if ( get_current_screen()->base !== 'dashboard' ) {
		return;
	}
	?>
    <div id="dbwp_main_dashboard_widget" class="welcome-panel dbwp-welcome-panel">
        <div class="dbwp-welcome-panel-content dbwp-welcome-panel-header">
            <div class="dbwp-welcome-panel-main">
                <h2><?php $welcome = Helpers::get_options( 'welcome' );
		            echo $welcome ?></h2>
                <p class="about-description"><?php echo Helpers::get_options( 'slogan' ); ?></p>
            </div>
            <div class="dbwp-welcome-panel-support">
				<?php
				$rs = Helpers::get_options( 'social' );
				if ( ! empty( $rs ) ) {
					?>
                    <ul>
						<?php
						foreach ( $rs as $social ) {
							$social_name = strtolower( $social['dbwp_name']['label'] );

							if ( 'Mail' === $social['dbwp_name']['label'] ) {
								$social['dbwp_name']['label'] = $social['dbwp_url'];
								$social['dbwp_url']           = 'mailto:' . $social['dbwp_url'];
								$social_name                  = 'email-alt';
							}

							?>
                            <li>
                                <a href="
                            <?php
								echo $social['dbwp_url'] ?>" class="welcome-icon dashicon dashicons-<?php echo
								$social_name; ?>" target="_blank">
									<?php echo $social['dbwp_name']['label'] ?>
                                </a>
                            </li>
							<?php
						}
						?>
                    </ul>
					<?php
				}
				?>
            </div>
            <div class="dbwp-welcome-panel-aside">
                <a href="<?php echo MAIN_SITE; ?>" target="_blank">
                    <img src="<?php echo Helpers::get_options( 'logo' ); ?>"
                         alt="<?php echo MAIN_SITE; ?>"/>
                </a>
            </div>
        </div>
        <div class="dbwp-welcome-panel-content">
            <div class="dbwp-welcome-panel-main">
                <div class="dashboard-msg dashboard-alert">
					<?php
					echo Helpers::get_tma();
					Helpers::thfo_get_msg();
					?>
                </div>
                <div class="dashboard-news">
                    <h3><?php _e( 'Last Updates', 'dashboard-wp' ); ?></h3>
                    <div class="feature-section images-stagger-right">
						<?php
						$drafts_query = new WP_Query(
							[
								'post_type'      => 'any',
								'post_status'    => array( 'publish', 'pending', 'future' ),
								'posts_per_page' => 5,
								'orderby'        => 'modified',
								'order'          => 'DESC',
							]
						);
						$drafts       =& $drafts_query->posts;
						if ( $drafts && is_array( $drafts ) ) {
							$list = array();
							foreach ( $drafts as $draft ) {
								$url       = get_edit_post_link( $draft->ID );
								$title     = _draft_or_post_title( $draft->ID );
								$last_id   = get_post_meta( $draft->ID, '_edit_last', true );
								$last_user = get_userdata( $last_id );
								$obj       = get_post_type_object( get_post_type( $draft->ID ) );
								$postType  = $obj->labels->singular_name;
								switch ( get_post_status( $draft->ID ) ) {
									case 'draft':
										$post_status = __( 'Draft', 'dashboard-wp' );
										break;
									case 'pending':
										$post_status = __( 'Pending', 'dashboard-wp' );
										break;
									case 'future':
										$post_status = sprintf( __( 'Planned for %1$s', 'dashboard-wp' ), get_the_date
										( get_option( 'date_format' ), $draft->ID ) );
										break;
									case 'auto-draft':
										$post_status = __( 'Auto-Draft', 'dashboard-wp' );
										break;
									case 'publish':
										$post_status = sprintf( __( 'Published on %1$s', 'dashboard-wp' ), get_the_date
										( get_option( 'date_format' ), $draft->ID ) );
										break;

								}

								$last_modified = get_the_modified_date();
								$item          = '<tr>';
								$item          .= '<td><a href="' . $url . '" title="' . sprintf( __( 'Modify', 'dashboard-wp' ),
										esc_attr( $title ) ) . '">' . esc_html( $title ) . '</a></td>';
								$item          .= '<td>' . $post_status . '</td>';
								$item          .= '<td>' . $postType . '</td>';
								if ( $last_user ) {
									$item .= '<td>' . $last_user->display_name . '</td>';
								} else {
									$item .= '<td>Aucun</td>';
								}
								$item   .= '<td>' . sprintf( __( 'On %2$s at %3$s', 'dashboard-wp' ), $last_modified,
										mysql2date(
											get_option( 'date_format' ), $draft->post_modified ), mysql2date( get_option( 'time_format' ), $draft->post_modified ) ) . '</td>';
								$item   .= '</tr>';
								$list[] = $item;
							}
							?>
                            <table class="widefat">
                                <thead>
                                <tr>
                                    <th><?php _e( 'Title / Link', 'dashboard-wp' ); ?></th>
                                    <th><?php _e( 'Status', 'dashboard-wp' ); ?></th>
                                    <th><?php _e( 'Type', 'dashboard-wp' ); ?></th>
                                    <th><?php _e( 'Authors', 'dashboard-wp' ); ?></th>
                                    <th><?php _e( 'Last modification', 'dashboard-wp' ); ?></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php echo join( "\n", $list ); ?>
                                </tbody>
                            </table>
							<?php
						} else {
							_e( 'There\'s no draft for the moment', 'dashboard-wp' );
						}
						?>
                    </div>
                </div>
            </div>
	        <?php
	        if ( ! empty( Helpers::get_options( 'posts' ) ) ) {
		        ?>
                <div class="dbwp-welcome-panel-aside">
                    <div class="dbwp-welcome-panel-news">
	                    <?php
	                    Helpers::get_remote_posts();
	                    ?>
                    </div>
                </div>
		        <?php
	        }
	        ?>
        </div>
    </div>
	<?php
}
