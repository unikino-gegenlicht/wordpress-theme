<?php

namespace GGL\customizer\supporterPage;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'supporter-page';


function customize( \WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( section, array(
		'title'           => __( 'Supporter (Page)', 'gegenlicht' ),
		'capability'      => 'edit_theme_options',
		'description'     => __( 'Configure the display of the supporter page', 'gegenlicht' ),
		'priority'        => 30,
		'active_callback' => 'page_supporter_enabled',
	) );

	add_text_fields( $wp_customize );
	add_association_logo($wp_customize);
	add_association_text($wp_customize);
	add_association_url($wp_customize);

}


function add_text_fields( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'supporter_page_text[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( new \WP_Customize_Code_Editor_Control( $wp_customize, 'supporter_page_text[de]', array(
		'label'       => __( 'German Text', 'gegenlicht' ),
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
		'code_type'   => 'text/html',
		'settings'    => 'supporter_page_text[de]',

	)));

	$wp_customize->add_setting( 'supporter_page_text[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( new \WP_Customize_Code_Editor_Control( $wp_customize, 'supporter_page_text[en]', array(
		'label'       => __( 'English Text', 'gegenlicht' ),
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
		'code_type'   => 'text/html',
		'settings'    => 'supporter_page_text[en]',


	)));
}

function add_association_logo( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'association_logo', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$control = new \WP_Customize_Media_Control( $wp_customize, 'association_logo', array(
		'label' => __( 'Association Logo', 'ggl' ),
		'section' => section,
		'description' => esc_html__('The selected Logo will be displayed above all other supporters together with the text entered below', 'gegenlicht'),
		'mime_type' => 'image/svg+xml',
	));

	$wp_customize->add_control($control);
}

function add_association_text( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'association_text[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( new \WP_Customize_Code_Editor_Control( $wp_customize, 'association_text[de]', array(
		'label'       => __( 'Association Description (German)', 'gegenlicht' ),
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
		'code_type'   => 'text/html',
		'settings'    => 'association_text[de]',

	)));

	$wp_customize->add_setting( 'association_text[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( new \WP_Customize_Code_Editor_Control( $wp_customize, 'association_text[en]', array(
		'label'       => __( 'Association Description (English)', 'gegenlicht' ),
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
		'code_type'   => 'text/html',
		'settings'    => 'association_text[en]',
	)));
}

function add_association_url( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'association_url', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control('association_url', array(
		'label'       => __( 'Association Website Url', 'gegenlicht' ),
		'section'     => section,
	));
}

function page_supporter_enabled() {
	return is_post_type_archive('supporter');
}