<?php
namespace GGL\customizer\locationPage;

/**
 * This file contains all customizer settings regarding the team page and the team block on the frontpage
 */

const section = 'location-page';


function customize_location_page(\WP_Customize_Manager $wp_customize): void {

	$wp_customize->add_section( section, array(
		'title'    => __( 'Location (Page)', 'gegenlicht' ),
		'capability' => 'edit_theme_options',
		'description' => __( 'Configure the display of location page shown', 'gegenlicht' ),
		'priority'   => 30,
	) );

	add_location_map_setting($wp_customize);

}


function add_location_map_setting(\WP_Customize_Manager $wp_customize): void {
	$wp_customize->add_setting('p_location_map[dark]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$control = new \WP_Customize_Media_Control( $wp_customize, 'p_location_map[dark]', array(
		'label' => __( 'Map (Dark Mode)', 'ggl' ),
		'section' => section,
		'mime_type' => 'image/svg+xml',
	));

	$wp_customize->add_control($control);


	$wp_customize->add_setting('p_location_map[light]', array(
		'type' => 'theme_mod',
		'capability' => 'edit_theme_options',
	));

	$control = new \WP_Customize_Media_Control( $wp_customize, 'p_location_map[light]', array(
		'label' => __( 'Map (Light Mode)', 'ggl' ),
		'section' => section,
		'mime_type' => 'image/svg+xml',
	));

	$wp_customize->add_control($control);
}