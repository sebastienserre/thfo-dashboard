<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

add_filter( 'acf/settings/l10n_textdomain', 'dbwp_acf_settings_textdomain' );
function dbwp_acf_settings_textdomain( $domain ) {
	return 'dashboard-wp';
}

add_action( 'init', 'dbwp_load_acf_field' );
function dbwp_load_acf_field() {
	if ( function_exists( 'acf_add_local_field_group' ) ):

		acf_add_local_field_group( array(
			'key'                   => 'group_5d6536941c00f',
			'title'                 => __( 'Dashboard', 'dashboard-wp' ),
			'fields'                => array(
				array(
					'key'               => 'field_5d653ea472bcd',
					'label'             => __( 'Welcome Message', 'dashboard-wp' ),
					'name'              => 'dbwp_welcome_message',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 1,
					'layout'            => 'table',
					'button_label'      => '',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5d653ef57b415',
							'label'             => __( 'Title', 'dashboard-wp' ),
							'name'              => 'dbwp_title',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_5d653f117b416',
							'label'             => __( 'Slogan', 'dashboard-wp' ),
							'name'              => 'dbwp_slogan',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
					),
				),
				array(
					'key'               => 'field_5d653d3e64d83',
					'label'             => __( 'Logo', 'dashboard-wp' ),
					'name'              => 'dbwp_logo',
					'type'              => 'image',
					'instructions'      => __( 'URL to the Logo', 'dashboard-wp' ),
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'return_format'     => 'url',
					'preview_size'      => 'medium',
					'library'           => 'all',
					'min_width'         => '',
					'min_height'        => '',
					'min_size'          => '',
					'max_width'         => '',
					'max_height'        => '',
					'max_size'          => '',
					'mime_types'        => '',
				),
				array(
					'key'               => 'field_5d653daed12e4',
					'label'             => __( 'Social', 'dashboard-wp' ),
					'name'              => 'dbwp_social',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => '',
					'sub_fields'        => array(
						array(
							'key'               => 'field_5d653dc1d12e5',
							'label'             => __( 'Name', 'dashboard-wp' ),
							'name'              => 'dbwp_name',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => dbwp_social_network(),
							'default_value'     => array(),
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 0,
							'return_format'     => 'array',
							'ajax'              => 0,
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5d653e01d12e6',
							'label'             => __( 'URL', 'dashboard-wp' ),
							'name'              => 'dbwp_url',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'maxlength'         => '',
							'prepend'           => '',
							'append'            => '',
						),
					),
				),
				array(
					'key'               => 'field_5d653f3543ca8',
					'label'             => __( 'Posts', 'dashboard-wp' ),
					'name'              => 'dbwp_posts',
					'type'              => 'checkbox',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => dbwp_cpt_list(),
					'allow_custom'      => 0,
					'default_value'     => array(),
					'layout'            => 'horizontal',
					'toggle'            => 0,
					'return_format'     => 'array',
					'save_custom'       => 0,
				),
				array(
					'key'               => 'field_5d68ccfa7c913',
					'label'             => __( 'Number of posts', 'dashboard-wp' ),
					'name'              => 'dbwp_nb_post',
					'type'              => 'number',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => 5,
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'min'               => 0,
					'max'               => '',
					'step'              => 1,
				),
				array(
					'key'               => 'field_5d69039ab2527',
					'label'             => __( 'Have my own CSS', 'dashboard-wp' ),
					'name'              => 'dbwp_custom_css',
					'type'              => 'true_false',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'message'           => '',
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => __( 'Yes', 'dashboard-wp' ),
					'ui_off_text'       => __( 'No', 'dashboard-wp' ),
				),
				array(
					'key'               => 'field_5d6540f349f93',
					'label'             => __( 'CSS', 'dashboard-wp' ),
					'name'              => 'dbwp_css',
					'type'              => 'url',
					'instructions'      => __( 'URL to a CSS file', 'dashboard-wp' ),
					'required'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_5d69039ab2527',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'dashboard-settings',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		) );

	endif;
}

function dbwp_social_network() {
	$rs = [ 'None', 'Twitter', 'Facebook', 'Linkedin', 'WordPress', 'Mail' ];

	/**
	 * Filter list of Social Network
	 */
	return apply_filters( 'dbwp_add_social_network', $rs );
}

function dbwp_cpt_list() {
	$cpts = get_post_types(
		[
			'public' => true,
		],
		'objects'
	);

	foreach ( $cpts as $cpt ) {
		if ( true === $cpt->show_in_rest && ! empty( $cpt->rest_base ) ) {
			$cpt_list[ $cpt->rest_base ] = $cpt->label;
		}
		if ( true === $cpt->show_in_rest && empty( $cpt->rest_base ) ) {
			$cpt_list[ $cpt->name ] = $cpt->label;
		}

	}

	return $cpt_list;
}

add_action( 'init', 'dbwp_acf_alert_field' );
/**
 * @author  Sébastien SERRE
 * @package wp-dashboard
 * @since   1.2.3.1
 */
function dbwp_acf_alert_field() {
	if ( function_exists( 'acf_add_local_field_group' ) ):

		acf_add_local_field_group( array(
			'key'                   => 'group_5d8b98bb469b6',
			'title'                 => __( 'Alerts Options', 'dashboard-wp' ),
			'fields'                => array(
				array(
					'key'               => 'field_5d8b98d41fc17',
					'label'             => __( 'Important', 'dashboard-wp' ),
					'name'              => 'wp_dashboard_important',
					'type'              => 'true_false',
					'instructions'      => __( 'Check yes if your alert is important. It will be stylized differently', 'dashboard-wp' ),
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'message'           => __( 'Important notice ?', 'dashboard-wp' ),
					'default_value'     => 0,
					'ui'                => 1,
					'ui_on_text'        => __( 'Yes', 'dashboard-wp' ),
					'ui_off_text'       => __( 'No', 'dashboard-wp' ),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'alert',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		) );

	endif;
}

if ( function_exists( 'acf_add_local_field_group' ) ):

	acf_add_local_field_group( array(
		'key'                   => 'group_5d9499fe9a2c9',
		'title'                 => __( 'TMA', 'dashboard-wp' ),
		'fields'                => array(
			array(
				'key'               => 'field_5d949a281d276',
				'label'             => __( 'Date', 'dashboard-wp' ),
				'name'              => 'tma_date',
				'type'              => 'date_picker',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'display_format'    => 'd/m/Y',
				'return_format'     => 'd/m/Y',
				'first_day'         => 1,
			),
			array(
				'key'               => 'field_5d949a4f1d277',
				'label'             => __( 'Due Time', 'dashboard-wp' ),
				'name'              => 'tma_due',
				'type'              => 'time_picker',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'display_format'    => 'H:i',
				'return_format'     => 'H:i',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'taxonomy',
					'operator' => '==',
					'value'    => 'websites',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );

endif;
