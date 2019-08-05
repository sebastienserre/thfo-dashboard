<?php
/**
 * @since      1.0
 *
 * @package    thivinfodashboard
 * @subpackage thivinfodashboard/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    thivinfodashboard
 * @subpackage thivinfodashboard/admin
 * @author     audrasjb <audrasjb@gmail.com>, Eddy BOELS <eddy@e-labo.biz>
 */

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

                    <p>Bonjour,</p>
                    <p>Vous trouverez ici un détail de vos dernières publications ainsi qu'un lien mail pour
                        me contacter en cas de besoin.
                    </p>
                    <p>Je pourrais etre amené a diffuser également des messages d'information à votre
                        intention dans cette zone.
                    </p>
                    <p>Bonne Journée</p>
                    <p>Sébastien</p>
                </div>
                <div class="dashboard-msg dashboard-alert">
					<?php
					$site    = home_url();
					$decoded = get_transient( 'dashboard-alert' );
					if ( empty( $decoded ) ) {
						$json = wp_remote_get( 'https://thivinfo.com/config-dashboard.json' );
						if ( 200 === (int) wp_remote_retrieve_response_code( $json ) ) {

							$body         = wp_remote_retrieve_body( $json );
							$decoded_body = json_decode( $body, true );
							$decoded      = $decoded_body['message'];
							set_transient( 'dashboard-alert', $decoded, HOUR_IN_SECONDS * 12 );
						}
					}
					$arraykey = array_key_exists( $site, $decoded );
					if ( $arraykey ) {
						echo '<p class="alert-msg">' . $decoded[ $site ] . '</p>';
					}


					?>
                </div>
                <div class="dashboard-news">
                    <h3>Dernières mise à jour</h3>
                    <div class="feature-section images-stagger-right">
						<?php
						$drafts_query = new WP_Query( array(
							'post_type'      => 'any',
							'post_status'    => array( 'publish', 'pending', 'future' ),
							'posts_per_page' => 5,
							'orderby'        => 'modified',
							'order'          => 'DESC'
						) );
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
					$response = wp_remote_get( 'https://thivinfo.com/wp-json/wp/v2/freemius-cpt/?per_page=5&orderby=date&order=desc&lang=fr' );
					if ( ! is_wp_error( $response ) ) {
						$posts = json_decode( wp_remote_retrieve_body( $response ) );
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
					} else {
						//ERROR::remote ressouces unavailable
					}
					?>
                    <h3><a href="https://thivinfo.com/blog/" title="Lien vers le blog Thivinfo" target="_blank">Mes
                            derniers Articles WordPress</a></h3>
					<?php
					$response = wp_remote_get( 'https://thivinfo.com/wp-json/wp/v2/posts/?per_page=2&orderby=date&order=desc&lang=fr' );
					if ( ! is_wp_error( $response ) ) {
						$posts = json_decode( wp_remote_retrieve_body( $response ) );
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
					} else {
						//ERROR::remote ressouces unavailable
					}
					?>
                </div>
            </div>
        </div>

    </div>
    </div>
	<?php
}

/**
 *
 * Admin option page
 *
 */
add_action( 'admin_menu', 'whoadmin_add_admin_menu' );
add_action( 'admin_init', 'whoadmin_settings_init' );


function whoadmin_add_admin_menu() {
	add_options_page( 'Réglages du tableau de bord', 'Thivinfo dashboard', 'manage_options', 'whoadmin_options',
		'whoadmin_options_page' );
}


function whoadmin_settings_init() {

	register_setting( 'whoadminPage', 'whoadmin_options' );

	add_settings_section(
		'whoadmin_options_page_pluginPage_section',
		'Réglages du tableau de bord',
		'whoadmin_options_page_settings_section_callback',
		'whoadminPage'
	);

	add_settings_field(
		'whoadmin_field_guide1_title',
		'Titre guide d’utilisation 1',
		'whoadmin_options_page_field_guide1_title_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);
	add_settings_field(
		'whoadmin_field_guide1_url',
		'URL guide d’utilisation 1',
		'whoadmin_options_page_field_guide1_url_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);

	add_settings_field(
		'whoadmin_field_guide2_title',
		'Titre guide d’utilisation 2',
		'whoadmin_options_page_field_guide2_title_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);
	add_settings_field(
		'whoadmin_field_guide2_url',
		'URL guide d’utilisation 2',
		'whoadmin_options_page_field_guide2_url_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);

	add_settings_field(
		'whoadmin_field_guide3_title',
		'Titre guide d’utilisation 3',
		'whoadmin_options_page_field_guide3_title_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);
	add_settings_field(
		'whoadmin_field_guide3_url',
		'URL guide d’utilisation 3',
		'whoadmin_options_page_field_guide3_url_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);

	add_settings_field(
		'whoadmin_field_guide4_title',
		'Titre guide d’utilisation 4',
		'whoadmin_options_page_field_guide4_title_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);
	add_settings_field(
		'whoadmin_field_guide4_url',
		'URL guide d’utilisation 4',
		'whoadmin_options_page_field_guide4_url_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);

	add_settings_field(
		'whoadmin_field_guide5_title',
		'Titre guide d’utilisation 5',
		'whoadmin_options_page_field_guide5_title_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);
	add_settings_field(
		'whoadmin_field_guide5_url',
		'URL guide d’utilisation 5',
		'whoadmin_options_page_field_guide5_url_render',
		'whoadminPage',
		'whoadmin_options_page_pluginPage_section'
	);
}

// Guide 1
function whoadmin_options_page_field_guide1_title_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['title_guide1'] ) ) {
		$optionTitleGuide1 = $options['title_guide1'];
	} else {
		$optionTitleGuide1 = '';
	}
	?>
    <input type="text" name="whoadmin_options[title_guide1]" size="50" value="<?php echo $optionTitleGuide1; ?>">
	<?php
}

function whoadmin_options_page_field_guide1_url_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['url_guide1'] ) ) {
		$optionUrlGuide1 = $options['url_guide1'];
	} else {
		$optionUrlGuide1 = '';
	}
	?>
    <input type="text" name="whoadmin_options[url_guide1]" size="100" value="<?php echo $optionUrlGuide1; ?>">
	<?php
}

// Guide 2
function whoadmin_options_page_field_guide2_title_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['title_guide2'] ) ) {
		$optionTitleGuide2 = $options['title_guide2'];
	} else {
		$optionTitleGuide2 = '';
	}
	?>
    <input type="text" name="whoadmin_options[title_guide2]" size="50" value="<?php echo $optionTitleGuide2; ?>">
	<?php
}

function whoadmin_options_page_field_guide2_url_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['url_guide2'] ) ) {
		$optionUrlGuide2 = $options['url_guide2'];
	} else {
		$optionUrlGuide2 = '';
	}
	?>
    <input type="text" name="whoadmin_options[url_guide2]" size="100" value="<?php echo $optionUrlGuide2; ?>">
	<?php
}

// Guide 3
function whoadmin_options_page_field_guide3_title_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['title_guide3'] ) ) {
		$optionTitleGuide3 = $options['title_guide3'];
	} else {
		$optionTitleGuide3 = '';
	}
	?>
    <input type="text" name="whoadmin_options[title_guide3]" size="50" value="<?php echo $optionTitleGuide3; ?>">
	<?php
}

function whoadmin_options_page_field_guide3_url_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['url_guide3'] ) ) {
		$optionUrlGuide3 = $options['url_guide3'];
	} else {
		$optionUrlGuide3 = '';
	}
	?>
    <input type="text" name="whoadmin_options[url_guide3]" size="100" value="<?php echo $optionUrlGuide3; ?>">
	<?php
}

// Guide 4
function whoadmin_options_page_field_guide4_title_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['title_guide4'] ) ) {
		$optionTitleGuide4 = $options['title_guide4'];
	} else {
		$optionTitleGuide4 = '';
	}
	?>
    <input type="text" name="whoadmin_options[title_guide4]" size="50" value="<?php echo $optionTitleGuide4; ?>">
	<?php
}

function whoadmin_options_page_field_guide4_url_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['url_guide4'] ) ) {
		$optionUrlGuide4 = $options['url_guide4'];
	} else {
		$optionUrlGuide4 = '';
	}
	?>
    <input type="text" name="whoadmin_options[url_guide4]" size="100" value="<?php echo $optionUrlGuide4; ?>">
	<?php
}

// Guide 5
function whoadmin_options_page_field_guide5_title_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['title_guide5'] ) ) {
		$optionTitleGuide5 = $options['title_guide5'];
	} else {
		$optionTitleGuide5 = '';
	}
	?>
    <input type="text" name="whoadmin_options[title_guide5]" size="50" value="<?php echo $optionTitleGuide5; ?>">
	<?php
}

function whoadmin_options_page_field_guide5_url_render() {
	$options = get_option( 'whoadmin_options' );
	if ( isset( $options['url_guide5'] ) ) {
		$optionUrlGuide5 = $options['url_guide5'];
	} else {
		$optionUrlGuide5 = '';
	}
	?>
    <input type="text" name="whoadmin_options[url_guide5]" size="100" value="<?php echo $optionUrlGuide5; ?>">
	<?php
}

function whoadmin_options_page_settings_section_callback() {
	echo 'Modifier les réglages du tableau de bord e-labo.';
}

function whoadmin_options_page() {
	echo "<form action='options.php' method='post'>";
	settings_fields( 'whoadminPage' );
	do_settings_sections( 'whoadminPage' );
	submit_button();
	echo "</form>";
}