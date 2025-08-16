<?php
namespace GGL\customizer\locationBlock;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'location-block';


function customize_location(\WP_Customize_Manager $wp_customize): void {

	$wp_customize->add_section( section, array(
		'title'    => __( 'Location (Block)', 'gegenlicht' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Configure the display of location block shown on the fron page', 'gegenlicht' ),
		'priority'   => 30,
		'active_callback' => 'block_location_enabled',
	) );

	add_location_map_setting($wp_customize);
	add_location_detail_page($wp_customize);
	add_location_texts($wp_customize);
	add_color_controls($wp_customize);

}


function add_location_map_setting(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('location_map', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$control = new \WP_Customize_Media_Control( $wp_customize, 'location_map', array(
		'label' => __( 'Map', 'ggl' ),
		'section' => section,
		'mime_type' => 'image/svg+xml',
	));

	$wp_customize->add_control($control);
}

function add_location_detail_page(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('location_detail_page', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control('location_detail_page', array(
		'type' => 'dropdown-pages',
		'section' => section,
		'label' => __( 'Detail Page', 'gegenlicht' ),
		'description' => __('This page contains detailed information about the location'),
	));
}

function add_location_texts(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting( 'location_text[de]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'location_text[de]', array(
		'label'   => __( 'German Block Text', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));

	$wp_customize->add_setting( 'location_text[en]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default' => ''
	));

	$wp_customize->add_control( 'location_text[en]', array(
		'label'   => __( 'English Block Text', 'gegenlicht' ),
		'type'    => 'textarea',
		'description' => esc_html__('To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht'),
		'section' => section,
	));
}

function add_color_controls(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting( 'location_text_color[light]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'location_text_color[light]', array(
		'label'   => __( 'Text Color (Light Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 7:1 is chosen against the background color', 'gegenlicht'),
	)));

	$wp_customize->add_setting( 'location_background_color[light]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'location_background_color[light]', array(
		'label'   => __( 'Background Color (Light Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 1:7 is chosen against the text color', 'gegenlicht'),
	)));

	$wp_customize->add_setting( 'location_text_color[dark]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'location_text_color[dark]', array(
		'label'   => __( 'Text Color (Dark Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 7:1 is chosen against the background color', 'gegenlicht'),
	)));

	$wp_customize->add_setting( 'location_background_color[dark]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'location_background_color[dark]', array(
		'label'   => __( 'Background Color (Dark Mode)', 'gegenlicht' ),
		'section' => section,
		'description' => esc_html__('Please ensure that a contrast ratio of at least 1:7 is chosen against the text color', 'gegenlicht'),
	)));
}

function block_location_enabled() {
	return in_array('location', get_theme_mod("displayed_blocks", []));
}