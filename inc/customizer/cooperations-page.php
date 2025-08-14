<?php

namespace GGL\customizer\cooperationsPage;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'cooperations-page';


function customize( \WP_Customize_Manager $wp_customize ): void {

	$wp_customize->add_section( section, array(
		'title'           => __( 'Cooperations (Page)', 'gegenlicht' ),
		'capability'      => 'edit_theme_options',
		'description'     => __( 'Configure the display of the cooperations page', 'gegenlicht' ),
		'priority'        => 30,
		'active_callback' => 'page_cooperations_enabled',
	) );

	add_text_fields( $wp_customize );
	add_rules( $wp_customize );
	add_rules_closer( $wp_customize );
	add_partner_list_text($wp_customize);
	add_contact_link($wp_customize);

}


function add_text_fields( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'coop_page_text[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_page_text[de]', array(
		'label'       => __( 'German Text', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );

	$wp_customize->add_setting( 'coop_page_text[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_page_text[en]', array(
		'label'       => __( 'English Text', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );
}


function add_rules( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'coop_rules[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_rules[de]', array(
		'label'       => __( 'German Rules', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new rule, please use a doubled line break.', 'gegenlicht' ),
		'section'     => section,
	) );

	$wp_customize->add_setting( 'coop_rules[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_rules[en]', array(
		'label'       => __( 'English Rules', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new rule, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );
}

function add_rules_closer( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'coop_rules_closer[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_rules_closer[de]', array(
		'label'       => __( 'Closing Remarks (German)', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );

	$wp_customize->add_setting( 'coop_rules_closer[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_rules_closer[en]', array(
		'label'       => __( 'Closing Remarks (English)', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );
}

function add_partner_list_text( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'coop_partner_list_text[de]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_partner_list_text[de]', array(
		'label'       => __( 'Partner List Text (German)', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );

	$wp_customize->add_setting( 'coop_partner_list_text[en]', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'coop_partner_list_text[en]', array(
		'label'       => __( 'Partner List Text (English)', 'gegenlicht' ),
		'type'        => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
		'section'     => section,
	) );
}

function add_contact_link(\WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting( 'cooperation_contact_page', array(
		'type'       => 'theme_mod',
		'capability' => 'edit_theme_options',
		'default'    => ''
	) );

	$wp_customize->add_control( 'cooperation_contact_page', array(
		'label'       => __( 'Link to Cooperation Request Form', 'gegenlicht' ),
		'type'        => 'url',
		'section'     => section,
	) );
}

function page_cooperations_enabled() {
	return is_post_type_archive('cooperation-partner');
}