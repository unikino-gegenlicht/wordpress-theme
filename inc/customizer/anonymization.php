<?php
/**
 * Anonymization
 *
 * This file sets up the customizer section regarding the anonymization options.
 */


namespace GGL\customizer\anonymization;

const section = 'anonymization';

function customizer( $wp_customize ): void {
	$wp_customize->add_section( section, array(
		'title'    => __( 'Anonymization', 'ggl' ),
		'priority' => 20,
		'description' => __( 'Configure the details regarding the anonymization of screenings without advertising rights', 'ggl' ),
	) );

	add_contact_details($wp_customize);
	add_explainers($wp_customize);
}


function add_contact_details( $wp_customize ): void {
	$wp_customize->add_setting( "anonymous_image", array(
		'default'           => '',
		'type'              => 'theme_mod',
	) );

	$wp_customize->add_control( new \WP_Customize_Media_Control( $wp_customize, 'anonymous_image', array(
		'label'   => __( 'Anonymous Cover Image', 'gegenlicht' ),
		'section' => section,
	)));
}

function add_explainers( $wp_customize ): void {
	$wp_customize->add_setting( "anonymized_movie_explainer[de]", array(
		'default'           => '',
		'type'              => 'theme_mod',
	));

	$wp_customize->add_control('anonymized_movie_explainer[de]', array(
		'label'   => __( 'Anonymous Movie Explainer (German)', 'gegenlicht' ),
		'section' => section,
		'type'    => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
	));

	$wp_customize->add_setting( "anonymized_movie_explainer[en]", array(
		'default'           => '',
		'type'              => 'theme_mod',
	));

	$wp_customize->add_control('anonymized_movie_explainer[en]', array(
		'label'   => __( 'Anonymous Movie Explainer (English)', 'gegenlicht' ),
		'section' => section,
		'type'    => 'textarea',
		'description' => esc_html__( 'To create a new paragraph inside the text, please use a doubled line break. All other linebreaks will be removed', 'gegenlicht' ),
	));
}