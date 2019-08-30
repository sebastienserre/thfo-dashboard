<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

add_action( 'init', 'dbwp_load_acf_field');
function dbwp_load_acf_field() {
	if ( function_exists( 'acf_add_local_field_group' ) ):

		acf_add_local_field_group( array(
			'key'                   => 'group_5d6536941c00f',
			'title'                 => 'Dashboard',
			'fields'                => array(
				array(
					'key'               => 'field_5d653ea472bcd',
					'label'             => 'Welcome Message',
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
							'label'             => 'Title',
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
							'label'             => 'Slogan',
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
					'label'             => 'Logo',
					'name'              => 'dbwp_logo',
					'type'              => 'image',
					'instructions'      => 'URL to the Logo',
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
					'label'             => 'Social',
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
							'label'             => 'Name',
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
							'label'             => 'URL',
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
						),
					),
				),
				array(
					'key'               => 'field_5d653f3543ca8',
					'label'             => 'Posts',
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
					'return_format'     => 'value',
					'save_custom'       => 0,
				),
				array(
					'key' => 'field_5d68ccfa7c913',
					'label' => 'Number of posts',
					'name' => 'dbwp_nb_post',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 5,
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => 0,
					'max' => '',
					'step' => 1,
				),
				array(
					'key'               => 'field_5d6540f349f93',
					'label'             => 'CSS',
					'name'              => 'dbwp_css',
					'type'              => 'url',
					'instructions'      => 'URL to a CSS file',
					'required'          => 0,
					'conditional_logic' => 0,
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

	foreach ( $cpts as $cpt){
		if ( true === $cpt->show_in_rest) {
			$cpt_list[ $cpt->rest_base ] = $cpt->rest_base;
		}
		if ( false === $cpt->rest_base ){
			$cpt_list[ $cpt->rest_base ] = $cpt->name;
		}
	}

	return $cpt_list;
}