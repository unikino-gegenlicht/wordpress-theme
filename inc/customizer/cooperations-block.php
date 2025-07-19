<?php

namespace GGL\customizer\cooperationsBlock;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'cooperations-block';


function customize( \WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( section, array(
		'title'           => __( 'Cooperations (Block)', 'gegenlicht' ),
		'capability'      => 'edit_theme_options',
		'description'     => __( 'Configure the display of the cooperations block', 'gegenlicht' ),
		'priority'        => 30,
		'active_callback' => 'block_cooperations_enabled',
	) );

	add_text_fields( $wp_customize );
	add_color_controls( $wp_customize );

}


function add_text_fields( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'coop_block_text[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_block_text[de]', array(
		'label'       => __( 'German Block Text', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );

	$wp_customize->add_setting( 'coop_block_text[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_block_text[en]', array(
		'label'       => __( 'English Block Text', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );
}

function add_color_controls( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'cooperations_text_color[light]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'cooperations_text_color[light]', array(
		'label'       => __( 'Text Color (Light Mode)', 'gegenlicht' ),
		'section'     => section,
		'description' => esc_html__( 'Please ensure that a contrast ratio of at least 7:1 is chosen against the background color', 'gegenlicht' ),
	) ) );

	$wp_customize->add_setting( 'cooperations_background_color[light]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'cooperations_background_color[light]', array(
		'label'       => __( 'Background Color (Light Mode)', 'gegenlicht' ),
		'section'     => section,
		'description' => esc_html__( 'Please ensure that a contrast ratio of at least 7:1 is chosen against the text color', 'gegenlicht' ),
	) ) );

	$wp_customize->add_setting( 'cooperations_text_color[dark]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'cooperations_text_color[dark]', array(
		'label'       => __( 'Text Color (Dark Mode)', 'gegenlicht' ),
		'section'     => section,
		'description' => esc_html__( 'Please ensure that a contrast ratio of at least 7:1 is chosen against the background color', 'gegenlicht' ),
	) ) );

	$wp_customize->add_setting( 'cooperations_background_color[dark]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'cooperations_background_color[dark]', array(
		'label'       => __( 'Background Color (Dark Mode)', 'gegenlicht' ),
		'section'     => section,
		'description' => esc_html__( 'Please ensure that a contrast ratio of at least 7:1 is chosen against the text color', 'gegenlicht' ),
	) ) );
}

function block_cooperations_enabled() {
	return in_array( 'cooperations', get_theme_mod( "displayed_blocks", [] ) );
}