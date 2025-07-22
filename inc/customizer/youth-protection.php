<?php
/**
 * Youth Protection Customizer
 *
 * This file sets up the customizer section regarding the Youth Protection.
 */


namespace GGL\customizer\youthProtection;

const section = 'youth-protection';

function customizer( $wp_customize ): void {
	$wp_customize->add_section( section, array(
		'title'    => __( 'Youth Protection', 'ggl' ),
		'priority' => 20,
		'description' => __( 'Configure the details regarding the youth protection mechanisms used', 'ggl' ),
	) );

	add_contact_details($wp_customize);
}


function add_contact_details( $wp_customize ): void {
	$wp_customize->add_setting( "youth_protection_officer[name]", array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '',
		'type'              => 'theme_mod',
	) );

	$wp_customize->add_setting( "youth_protection_officer[email]", array(
		'sanitize_callback' => 'sanitize_email',
		'default'           => '',
		'type'              => 'theme_mod',
	) );

	$wp_customize->add_control( 'youth_protection_officer[name]', array(
		'label'   => __( 'Youth Protection Officer', 'gegenlicht' ),
		'type'    => 'text',
		'section' => section,
	));
	$wp_customize->add_control( 'youth_protection_officer[email]', array(
		'label'   => __( 'E-Mail Address (Youth Protection Officer)', 'gegenlicht' ),
		'type'    => 'text',
		'section' => section,
	));
}