<?php
namespace GGL\customizer;
require 'functions.php';

/**
 * This file contains the customizer options related to the pages header.
 *
 * @author Jan Eike Suchard
 */

function title_tagline_section(\WP_Customize_Manager $wp_customize) {
	$wp_customize->remove_control('blogname');
	$wp_customize->remove_control('blogdescription');

	ggl_customizer_header_logo($wp_customize);
	ggl_address($wp_customize);
}


/**
 * Add the option to change the logo used in the page header's navbar to the `title_tagline` section
 *
 * @param \WP_Customize_Manager $wp_customize
 *
 * @author Jan Eike Suchard <jan@gegenlicht.net>
 *
 * @since 1.0.0
 */
function ggl_customizer_header_logo( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_setting('header_logo', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$control = new \WP_Customize_Media_Control( $wp_customize, 'header_logo', array(
		'label' => __( 'Navigation Brand', 'ggl' ),
		'section' => 'title_tagline',
		'description' => esc_html__('The selected Logo will be displayed in the navigation bar brand section (leftmost ara). It will always be displayed, event on mobile devices', 'gegenlicht'),
		'mime_type' => 'image/svg+xml',
	));

	$wp_customize->add_control($control);
}

function ggl_address(\WP_Customize_Manager $wp_customize) {
	$wp_customize->add_setting('postal_address', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control('postal_address', array(
		'type' => 'textarea',
		'label' => __( 'Postal Address', 'ggl' ),
		'section' => 'title_tagline',
	));

	$wp_customize->add_setting('visitor_address', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control('visitor_address', array(
		'type' => 'textarea',
		'label' => __( 'Visitor Address', 'ggl' ),
		'section' => 'title_tagline',
	));
}