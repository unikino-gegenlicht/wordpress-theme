<?php
namespace GGL\customizer\teamBlock;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'team-block';


function customize_team(\WP_Customize_Manager $wp_customize): void {

	$wp_customize->add_section( section, array(
		'title'    => __( 'Team (Block)', 'gegenlicht' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Configure the display of the team page and the linked block on the frontpage', 'gegenlicht' ),
		'priority'   => 30,
		'active_callback' => 'block_team_enabled',
	) );

	add_team_image_setting($wp_customize);
	add_team_intro_texts($wp_customize);
	add_color_controls($wp_customize);

}


function add_team_image_setting(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('team_image', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$control = new \WP_Customize_Image_Control( $wp_customize, 'team_image', array(
		'label' => __( 'Team Image', 'ggl' ),
		'section' => section,
		'description' => esc_html__('The selected image will be displayed on the block introducing the team', 'gegenlicht'),
	));

	$wp_customize->add_control($control);
}

function add_team_intro_texts(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting( 'team_block_text[de]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'team_block_text[de]', array(
		'label'   => __( 'German Block Text', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));

	$wp_customize->add_setting( 'team_block_text[en]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'team_block_text[en]', array(
		'label'   => __( 'English Block Text', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));
}

function add_color_controls(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting( 'teamBlock_text_color[light]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'teamBlock_text_color[light]', array(
		'label'   => __( 'Text Color (Light Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 7:1 is chosen against the background color', 'gegenlicht'),
	)));

	$wp_customize->add_setting( 'teamBlock_background_color[light]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'teamBlock_background_color[light]', array(
		'label'   => __( 'Background Color (Light Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 1:7 is chosen against the text color', 'gegenlicht'),
	)));

	$wp_customize->add_setting( 'teamBlock_text_color[dark]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'teamBlock_text_color[dark]', array(
		'label'   => __( 'Text Color (Dark Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 7:1 is chosen against the background color', 'gegenlicht'),
	)));

	$wp_customize->add_setting( 'teamBlock_background_color[dark]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'teamBlock_background_color[dark]', array(
		'label'   => __( 'Background Color (Dark Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 1:7 is chosen against the text color', 'gegenlicht'),
	)));
}

function block_team_enabled() {
	return in_array('team', get_theme_mod("displayed_blocks", []));
}