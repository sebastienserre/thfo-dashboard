<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 *
 * Remove useless stuff
 *
 */
function thivinfo_disable_default_dashboard_widgets() {
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );

	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' );
}

add_action( 'admin_menu', 'thivinfo_disable_default_dashboard_widgets' );

function thfo_retrieve_alert() {
	$decoded_body = get_transient( 'dashboard-alerts' );
	if ( empty( $decoded_body ) ) {
		$main_url = stripslashes( MAIN_SITE );
		$json     = wp_remote_get( "$main_url/wp-json/wp/v2/alert?orderby=date&order=desc&lang=fr" );
		if ( 200 === (int) wp_remote_retrieve_response_code( $json ) ) {

			$body         = wp_remote_retrieve_body( $json );
			$decoded_body = json_decode( $body, true );
			set_transient( 'dashboard-alerts', $decoded_body, HOUR_IN_SECONDS * 12 );
		}
	}

	return $decoded_body;
}

function thfo_get_msg( $content = '' ) {

	//delete_transient( 'dashboard-alert' );
	$decoded = get_transient( 'dashboard-alert' );
	if ( empty( $decoded ) ) {

		$decoded_body = thfo_retrieve_alert();

		foreach ( $decoded_body as $alert ) {

			if ( ! empty( $alert ) && sanitize_title( home_url() ) === $alert['slug'] || 'all' === $alert['slug'] || $alert['slug'] === $content ) {
				$decoded[ $alert['slug'] ] = $alert['content']['rendered'];
				set_transient( 'dashboard-alert', $decoded, HOUR_IN_SECONDS * 12 );
			}
		}
	}
	if ( $decoded ) {
		foreach ( $decoded as $current_alert ) {
			echo '<div class="alert-msg">' . $current_alert . '</div>';
		}
	}
}

function thfo_get_general_msg() {
	//delete_transient( 'dashboard-general-msg' );
	$decoded = get_transient( 'dashboard-general-msg' );
	if ( empty( $decoded ) ) {
		$decoded_body = thfo_retrieve_alert();
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

/**
 *
 * Add main widget
 *
 */
add_action( 'admin_footer', 'thivinfo_main_dashboard_widget' );
function thivinfo_main_dashboard_widget() {
	// Kickout this if not viewing the main dashboard page
	if ( get_current_screen()->base !== 'dashboard' ) {
		return;
	}
	?>
    <div id="thivinfo_main_dashboard_widget" class="welcome-panel thivinfo-welcome-panel">
        <div class="thivinfo-welcome-panel-content thivinfo-welcome-panel-header">
            <div class="thivinfo-welcome-panel-main">
                <h2>Bienvenue sur votre tableau de bord</h2>
                <p class="about-description">Conçu et réalisé par Thivinfo, votre Développeur WordPress
                    !</p>
            </div>
            <div class="thivinfo-welcome-panel-support">

                <ul>
                    <li>
                        <h3>Besoin d'aide ?</h3>
                        <a target="_blank" href="mailto:support@thivinfo.com" class="welcome-icon
                                    dashicons-admin-users" title="Envoi un mail au support de Thivinfo">support@thivinfo.com</a>
                    </li>
                    <li>
                        <h3>Suivez moi</h3>
                        <a href="https://twiter.com/sebastienserre" class="welcome-icon dashicon dashicons-twitter"
                           target="_blank">Twitter</a></li>
                </ul>
            </div>
            <div class="thivinfo-welcome-panel-aside">
                <a href="https://thivinfo.com" target="_blank">
                    <img src="https://thivinfo.com/wp-content/themes/thivinfo/assets/images/thivinfo-logo.svg"
                         alt="Thivinfo.com"/>
                </a>
            </div>
        </div>
        <div class="thivinfo-welcome-panel-content">
            <div class="thivinfo-welcome-panel-main">
                <div class="dashboard-msg dashboard-welcome-msg">
					<?php
					thfo_get_general_msg( 'general' );
					?>

                </div>
                <div class="dashboard-msg dashboard-alert">
					<?php
					thfo_get_msg();
					?>
                </div>
                <div class="dashboard-news">
                    <h3>Dernières mise à jour</h3>
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
										$post_status = 'Brouillon';
										break;
									case 'pending':
										$post_status = 'En attente de relecture';
										break;
									case 'future':
										$post_status = 'Planifié pour le ' . get_the_date( get_option( 'date_format' ), $draft->ID );
										break;
									case 'auto-draft':
										$post_status = 'Brouillon automatique';
										break;
									case 'publish':
										$post_status = 'Publié le ' . get_the_date( get_option
											( 'date_format' ), $draft->ID );
										break;

								}
								$last_modified = get_the_modified_date();
								$item          = '<tr>';
								$item          .= '<td><a href="' . $url . '" title="' . sprintf( __( 'Modifier ce contenu' ), esc_attr( $title ) ) . '">' . esc_html( $title ) . '</a></td>';
								$item          .= '<td>' . $post_status . '</td>';
								$item          .= '<td>' . $postType . '</td>';
								if ( $last_user ) {
									$item .= '<td>' . $last_user->display_name . '</td>';
								} else {
									$item .= '<td>Aucun</td>';
								}
								$item   .= '<td>' . sprintf( __( 'Le %2$s à %3$s' ), $last_modified, mysql2date( get_option( 'date_format' ), $draft->post_modified ), mysql2date( get_option( 'time_format' ), $draft->post_modified ) ) . '</td>';
								$item   .= '</tr>';
								$list[] = $item;
							}
							?>
                            <table class="widefat">
                                <thead>
                                <tr>
                                    <th>Titre / lien</th>
                                    <th>Statut</th>
                                    <th>Type</th>
                                    <th>Auteur</th>
                                    <th>Dernière modification</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php echo join( "\n", $list ); ?>
                                </tbody>
                            </table>
							<?php
						} else {
							echo 'Il n\'y a pas de brouillons enregistrés actuellement.';
						}
						?>
                    </div>
                </div>
            </div>
            <div class="thivinfo-welcome-panel-aside">
                <div class="thivinfo-welcome-panel-news">
                    <h3><a href="https://thivinfo.com/boutique/" title="Lien vers la boutique Thivinfo" target="_blank">Mes
                            dernières
                            extensions
                            WordPress</a></h3>
					<?php
					$posts = get_transient( 'dashboard_shop_posts' );
					if ( empty( $posts ) ) {
						$response = wp_remote_get( 'https://thivinfo.com/wp-json/wp/v2/freemius-cpt/?per_page=5&orderby=date&order=desc&lang=fr' );
						if ( ! is_wp_error( $response ) ) {
							$posts = json_decode( wp_remote_retrieve_body( $response ) );
							set_transient( 'dashboard_shop_posts', $posts, HOUR_IN_SECONDS * 12 );
						}
					}
					if ( ! empty( $posts ) ) {
						echo '<ul>';
						foreach ( $posts as $post ) {
							echo '<li><a href="' . $post->link . '">'
							     . $post->title->rendered . '</a></li>';
						}
						echo '</ul>';
					} else {
						//ERROR::remote ressouces has no post
					}
					?>
                    <h3><a href="https://thivinfo.com/blog/" title="Lien vers le blog Thivinfo" target="_blank">Mes
                            derniers Articles WordPress</a></h3>
					<?php
					$posts = get_transient( 'dashboard_post' );
					if ( empty( $posts ) ) {
						$response = wp_remote_get( 'https://thivinfo.com/wp-json/wp/v2/posts/?per_page=2&orderby=date&order=desc&lang=fr' );

						if ( ! is_wp_error( $response ) ) {
							$posts = json_decode( wp_remote_retrieve_body( $response ) );
							set_transient( 'dashboard_post', $posts, HOUR_IN_SECONDS * 12 );
						}
					}
					if ( ! empty( $posts ) ) {
						echo '<ul>';
						foreach ( $posts as $post ) {
							echo '<li><a href="' . $post->link . '">'
							     . $post->title->rendered . '</a></li>';
						}
						echo '</ul>';
					}
					?>
                </div>
            </div>
        </div>
    </div>
    </div>
	<?php
}
